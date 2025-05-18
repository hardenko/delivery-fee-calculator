<?php

namespace App\Services\DeliveryFee;

use App\DTO\DeliveryFeeCalculationDto;
use App\Enums\DeliveryTypeEnum;
use App\Services\DeliveryFee\Strategies\ExpressDeliveryStrategy;
use App\Services\DeliveryFee\Strategies\StandardDeliveryStrategy;

final readonly class DeliveryFeeCalculatorService implements DeliveryFeeCalculatorInterface
{
    private array $strategies;

    public function __construct(
        private StandardDeliveryStrategy $standardStrategy,
        private ExpressDeliveryStrategy  $expressStrategy
    )
    {
        $this->strategies = [
            DeliveryTypeEnum::STANDARD->value => $standardStrategy,
            DeliveryTypeEnum::EXPRESS->value => $expressStrategy
        ];
    }

    public function calculate(DeliveryFeeCalculationDto $dto): string
    {
        $strategy = $this->strategies[$dto->deliveryType];
        return round($strategy->calculate($dto->destination, $dto->weight));
    }
}
