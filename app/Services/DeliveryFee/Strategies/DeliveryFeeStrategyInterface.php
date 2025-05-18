<?php

namespace App\Services\DeliveryFee\Strategies;

interface DeliveryFeeStrategyInterface
{
    public function calculate(string $destination, float $weight): string;
}
