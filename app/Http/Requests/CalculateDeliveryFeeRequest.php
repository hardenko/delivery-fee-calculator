<?php

namespace App\Http\Requests;

use App\DTO\DeliveryFeeCalculationDto;
use App\Enums\DeliveryTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

final class CalculateDeliveryFeeRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'destination' => [
                'bail',
                'required',
                'string',
                'max:255',
            ],
            'weight' => [
                'bail',
                'required',
                'numeric',
                'min:0',
            ],
            'delivery_type' => [
                'bail',
                'required',
                Rule::enum(DeliveryTypeEnum::class),
            ],
        ];
    }
}
