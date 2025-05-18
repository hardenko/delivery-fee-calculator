<?php

namespace App\DTO;

final class DeliveryFeeCalculationDto
{
    public function __construct(
        public string $destination,
        public float $weight,
        public string $deliveryType,
    ) {}

    public static function fromArray(array $data): self
    {
        return new self(
            destination: $data['destination'],
            weight: $data['weight'],
            deliveryType: $data['delivery_type'],
        );
    }
}
