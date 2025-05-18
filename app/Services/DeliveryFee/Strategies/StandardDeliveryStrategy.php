<?php

namespace App\Services\DeliveryFee\Strategies;

final class StandardDeliveryStrategy extends AbstractDeliveryFeeStrategy
{
    protected function getBaseFee(): string
    {
        return (string) config('fee.standard_base_fee');
    }
}
