<?php

namespace Tests\Unit;

use App\Services\DeliveryFee\Discount\LocationDiscountServiceInterface;
use App\Services\DeliveryFee\Strategies\AbstractDeliveryFeeStrategy;
use Mockery;
use Tests\TestCase;

final class DeliveryFeeStrategyTest extends TestCase
{
    private AbstractDeliveryFeeStrategy $strategy;

    protected function setUp(): void
    {
        parent::setUp();

        $locationDiscount = Mockery::mock(LocationDiscountServiceInterface::class);
        $locationDiscount->shouldReceive('apply')
            ->andReturnUsing(function ($city, $fee) {
                if (strtolower($city) === 'kyiv') {
                    return bcsub($fee, bcmul($fee, '0.15', 0), 0);
                }
                return $fee;
            });

        $this->strategy = Mockery::mock(AbstractDeliveryFeeStrategy::class, [$locationDiscount])
            ->shouldAllowMockingProtectedMethods()
            ->makePartial();

        $this->strategy->shouldReceive('getBaseFee')
            ->andReturn('100');

        config(['fee.weight_threshold' => '10']);
        config(['fee.additional_fee_per_kg' => '20']);
    }
    protected function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function testCalculateBaseFeeWithoutWeightExcessOrDiscount(): void
    {
        $fee = $this->strategy->calculate('Lviv', 5.0);

        $this->assertEquals('100', $fee);
    }

    public function testCalculateWithWeightExcess(): void
    {
        $fee = $this->strategy->calculate('Lviv', 15.5);

        $this->assertEquals('210', $fee);
    }

    public function testCalculateWithKyivDiscount(): void
    {
        $fee = $this->strategy->calculate('Kyiv', 5.0);

        $this->assertEquals('85', $fee);
    }

    public function testCalculateWithWeightExcessAndKyivDiscount(): void
    {
        $fee = $this->strategy->calculate('kyiv', 12.5);

        $this->assertEquals('128', $fee);
    }

    public function testWeightThresholdEdgeCase(): void
    {
        $feeAtThreshold = $this->strategy->calculate('Lviv', 10.0);
        $this->assertEquals('100', $feeAtThreshold);

        $feeJustAboveThreshold = $this->strategy->calculate('Lviv', 10.1);
        $this->assertEquals('102', $feeJustAboveThreshold);
    }

    public function testCaseInsensitiveKyivComparison(): void
    {
        $feeWithLowercase = $this->strategy->calculate('kyiv', 5.0);
        $feeWithUppercase = $this->strategy->calculate('KYIV', 5.0);
        $feeWithMixedCase = $this->strategy->calculate('KyIv', 5.0);

        $this->assertEquals('85', $feeWithLowercase);
        $this->assertEquals('85', $feeWithUppercase);
        $this->assertEquals('85', $feeWithMixedCase);
    }
}
