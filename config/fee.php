<?php

return [
    'weight_threshold' => env('DELIVERY_WEIGHT_THRESHOLD', 2),
    'additional_fee_per_kg' => env('DELIVERY_ADDITIONAL_FEE_PER_KG', 10),
    'standard_base_fee' => env('DELIVERY_STANDARD_BASE_FEE', 50),
    'express_base_fee' => env('DELIVERY_EXPRESS_BASE_FEE', 100),
    'location_discounts' => [
        'kyiv' => 10,
    ],
];
