<?php

namespace App\Services\DeliveryFee;

use App\DTO\DeliveryFeeCalculationDto;

interface DeliveryFeeCalculatorInterface
{
    public function calculate(DeliveryFeeCalculationDto $dto): string;
}
