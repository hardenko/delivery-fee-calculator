<?php

namespace App\Services\DeliveryFee\Strategies;

final class ExpressDeliveryStrategy extends AbstractDeliveryFeeStrategy
{
    protected function getBaseFee(): string
    {
        return (string) config('fee.express_base_fee');
    }
}
