<?php

namespace Tests\Feature;

use App\Enums\DeliveryTypeEnum;
use App\Http\Controllers\Api\DeliveryFeeController;
use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Tests\TestCase;

#[CoversClass(DeliveryFeeController::class)]
final class DeliveryFeeControllerTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        config([
            'fee.weight_threshold' => 5,
            'fee.additional_fee_per_kg' => 20,
            'fee.standard_base_fee' => 100,
            'fee.express_base_fee' => 200,
            'fee.location_discounts.kyiv' => 20,
        ]);
    }

    #[DataProvider('provideCalculateDeliveryFee')]
    public function testCalculateDeliveryFee($payload, int $expectedStatus, array $expectedResponse): void
    {
        $response = $this->postJson('/api/calculate-delivery-fee', $payload);

        $response->assertStatus($expectedStatus)
            ->assertJson($expectedResponse);
    }

    public static function provideCalculateDeliveryFee(): array
    {
        return [
            'success - calculate_delivery_fee for express delivery with discount' => [
                'payload' => [
                    'destination' => 'kyiv',
                    'weight' => 6,
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'fee' => 176,
                ],
            ],
            'success - calculate_delivery_fee for express delivery without discount' => [
                'payload' => [
                    'destination' => 'lviv',
                    'weight' => 6,
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'fee' => 220,
                ],
            ],
            'success - calculate_delivery_fee for standard delivery with discount' => [
                'payload' => [
                    'destination' => 'kyiv',
                    'weight' => 50,
                    'delivery_type' => DeliveryTypeEnum::STANDARD->value,
                ],
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'fee' => 800,
                ],
            ],
            'success - calculate_delivery_fee for standard delivery without discount' => [
                'payload' => [
                    'destination' => 'lviv',
                    'weight' => 50,
                    'delivery_type' => DeliveryTypeEnum::STANDARD->value,
                ],
                'expectedStatus' => 200,
                'expectedResponse' => [
                    'fee' => 1000,
                ],
            ],
            'failed - missing destination' => [
                'payload' => [
                    'weight' => 3.5,
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The destination field is required.',
                    'errors' => [
                        'destination' => [
                            'The destination field is required.',
                        ]
                    ]
                ]
            ],
            'failed - destination too long' => [
                'payload' => [
                    'destination' => str_repeat('a', 256), // 256 characters (exceeds max:255)
                    'weight' => 3.5,
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The destination field must not be greater than 255 characters.',
                    'errors' => [
                        'destination' => [
                            'The destination field must not be greater than 255 characters.',
                        ]
                    ]
                ]
            ],
            'failed - missing weight' => [
                'payload' => [
                    'destination' => 'kyiv',
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The weight field is required.',
                    'errors' => [
                        'weight' => [
                            'The weight field is required.',
                        ]
                    ]
                ]
            ],
            'failed - weight not numeric' => [
                'payload' => [
                    'destination' => 'kyiv',
                    'weight' => 'heavy',
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The weight field must be a number.',
                    'errors' => [
                        'weight' => [
                            'The weight field must be a number.',
                        ]
                    ]
                ]
            ],
            'failed - negative weight' => [
                'payload' => [
                    'destination' => 'kyiv',
                    'weight' => -1,
                    'delivery_type' => DeliveryTypeEnum::EXPRESS->value,
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The weight field must be at least 0.',
                    'errors' => [
                        'weight' => [
                            'The weight field must be at least 0.',
                        ]
                    ]
                ]
            ],
            'failed - invalid delivery type' => [
                'payload' => [
                    'destination' => 'kyiv',
                    'weight' => 3.5,
                    'delivery_type' => 'overnight', // Not in enum
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The selected delivery type is invalid.',
                    'errors' => [
                        'delivery_type' => [
                            'The selected delivery type is invalid.',
                        ]
                    ]
                ]
            ],
            'failed - multiple validation errors' => [
                'payload' => [
                    'destination' => '',
                    'weight' => -5,
                    'delivery_type' => 'invalid',
                ],
                'expectedStatus' => 422,
                'expectedResponse' => [
                    'message' => 'The destination field is required. (and 2 more errors)',
                    'errors' => [
                        'destination' => [
                            'The destination field is required.',
                        ],
                        'weight' => [
                            'The weight field must be at least 0.',
                        ],
                        'delivery_type' => [
                            'The selected delivery type is invalid.',
                        ]
                    ]
                ]
            ]
        ];
    }
}

