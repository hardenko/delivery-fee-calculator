<?php

namespace App\Providers;

use App\Services\DeliveryFee\DeliveryFeeCalculatorInterface;
use App\Services\DeliveryFee\DeliveryFeeCalculatorService;
use App\Services\DeliveryFee\Discount\LocationDiscountService;
use App\Services\DeliveryFee\Discount\LocationDiscountServiceInterface;
use App\Services\DeliveryFee\Strategies\ExpressDeliveryStrategy;
use App\Services\DeliveryFee\Strategies\StandardDeliveryStrategy;
use Illuminate\Support\ServiceProvider;

class DeliveryServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->bind(DeliveryFeeCalculatorService::class, function ($app) {
            return new DeliveryFeeCalculatorService(
                $app->make(StandardDeliveryStrategy::class),
                $app->make(ExpressDeliveryStrategy::class)
            );
        });

        $this->app->bind(DeliveryFeeCalculatorInterface::class, DeliveryFeeCalculatorService::class);

        $this->app->bind(LocationDiscountServiceInterface::class, LocationDiscountService::class);
    }
}
