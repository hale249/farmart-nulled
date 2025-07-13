<?php

namespace Botble\AffiliatePro\Listeners;

use Botble\AffiliatePro\Enums\CommissionStatusEnum;
use Botble\AffiliatePro\Facades\AffiliateHelper;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Models\Commission;
use Botble\Ecommerce\Events\OrderPlacedEvent;
use Botble\Ecommerce\Models\OrderProduct;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Log;

class OrderPlacedListener implements ShouldQueue
{
    public function handle(OrderPlacedEvent $event): void
    {
        $order = $event->order;

        // Log the order ID for debugging
        Log::info('OrderPlacedListener: Processing order #' . $order->id);

        // Check if a commission already exists for this order
        if (Commission::query()->where('order_id', $order->id)->exists()) {
            Log::info('OrderPlacedListener: Commission already exists for order #' . $order->id);

            return;
        }

        // Check if the order has an affiliate cookie
        $affiliateCode = Cookie::get('affiliate_code');

        if (! $affiliateCode) {
            Log::info('OrderPlacedListener: No affiliate code found for order #' . $order->id);

            return;
        }

        // Find the affiliate
        $affiliate = Affiliate::query()->where('affiliate_code', $affiliateCode)->first();

        if (! $affiliate) {
            Log::info('OrderPlacedListener: Affiliate not found for code: ' . $affiliateCode);

            return;
        }

        // Calculate commission for each product in the order
        $totalCommission = 0;

        foreach ($order->products as $orderProduct) {
            $productCommission = $this->calculateProductCommission($orderProduct);
            $totalCommission += $productCommission;
        }

        if ($totalCommission <= 0) {
            Log::info('OrderPlacedListener: No commission calculated for order #' . $order->id);

            return;
        }

        // Log for debugging
        Log::info('OrderPlacedListener: Creating pending commission for order #' . $order->id . ' with amount: ' . $totalCommission);

        // Create pending commission record
        Commission::query()->create([
            'affiliate_id' => $affiliate->id,
            'order_id' => $order->id,
            'amount' => $totalCommission,
            'description' => 'Pending commission for order ' . $order->code,
            'status' => CommissionStatusEnum::PENDING,
        ]);

        Log::info('OrderPlacedListener: Pending commission created for order #' . $order->id);
    }

    protected function calculateProductCommission(OrderProduct $orderProduct): float
    {
        $productId = $orderProduct->product_id;
        $productPrice = $orderProduct->price;
        $quantity = $orderProduct->qty;

        $commissionPercentage = AffiliateHelper::getCommissionPercentage($productId);

        return $productPrice * $quantity * ($commissionPercentage / 100);
    }
}
