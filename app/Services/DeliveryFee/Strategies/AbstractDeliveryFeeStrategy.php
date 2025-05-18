<?php

namespace App\Services\DeliveryFee\Strategies;

use App\Services\DeliveryFee\Discount\LocationDiscountServiceInterface;

abstract class AbstractDeliveryFeeStrategy implements DeliveryFeeStrategyInterface
{
    public function __construct(
        protected LocationDiscountServiceInterface $locationDiscountService
    ) {}

    protected function getWeightThreshold(): string
    {
        return (string) config('fee.weight_threshold');
    }

    protected function getAdditionalFeePerKg(): string
    {
        return (string) config('fee.additional_fee_per_kg');
    }

    abstract protected function getBaseFee(): string;

    public function calculate(string $destination, float $weight): string
    {
        $fee = $this->getBaseFee();
        if (bccomp((string) $weight, $this->getWeightThreshold(), 1) === 1) {
            $excessWeight = bcsub((string) $weight, $this->getWeightThreshold(), 1);
            $additionalFee = bcmul($excessWeight, $this->getAdditionalFeePerKg(), 0);
            $fee = bcadd($fee, $additionalFee, 0);
        }

        return $this->locationDiscountService->apply($destination, $fee);
    }
}
