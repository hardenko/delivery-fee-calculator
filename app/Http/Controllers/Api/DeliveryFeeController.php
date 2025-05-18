<?php

namespace App\Http\Controllers\Api;

use App\DTO\DeliveryFeeCalculationDto;
use App\Http\Requests\CalculateDeliveryFeeRequest;
use App\Services\DeliveryFee\DeliveryFeeCalculatorInterface;
use Illuminate\Http\JsonResponse;

final class DeliveryFeeController extends BaseApiController
{
    public function __construct(
        private readonly DeliveryFeeCalculatorInterface $deliveryFeeCalculator
    )
    {
    }

    public function calculate(CalculateDeliveryFeeRequest $request): JsonResponse
    {
        return $this->response([
            'fee' => $this->deliveryFeeCalculator->calculate(DeliveryFeeCalculationDto::fromArray($request->validated())),
        ]);
    }
}
