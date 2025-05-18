<?php

namespace App\Services\DeliveryFee\Discount;

class LocationDiscountService implements LocationDiscountServiceInterface
{
    private array $discounts;

    public function __construct()
    {
        $this->discounts = config('fee.location_discounts', []);
    }

    public function apply(string $city, string $fee): string
    {
        $cityKey = strtolower($city);

        if (!array_key_exists($cityKey, $this->discounts)) {
            return $fee;
        }

        $discountPercent = (string) $this->discounts[$cityKey];
        $discount = bcmul($fee, bcdiv($discountPercent, '100', 2), 0);

        return bcsub($fee, $discount, 0);
    }
}
