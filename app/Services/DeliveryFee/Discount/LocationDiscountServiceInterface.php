<?php

namespace App\Services\DeliveryFee\Discount;

interface LocationDiscountServiceInterface
{
    public function apply(string $city, string $fee): string;
}
