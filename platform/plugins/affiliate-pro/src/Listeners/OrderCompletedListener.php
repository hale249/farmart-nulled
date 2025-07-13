<?php

namespace Botble\AffiliatePro\Listeners;

use Botble\AffiliatePro\Enums\CommissionStatusEnum;
use Botble\AffiliatePro\Events\CommissionEarnedEvent;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Models\Commission;
use Botble\Ecommerce\Events\OrderCompletedEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OrderCompletedListener implements ShouldQueue
{
    public function handle(OrderCompletedEvent $event): void
    {
        $order = $event->order;

        // Log for debugging
        Log::info('OrderCompletedListener: Processing order #' . $order->id);

        // Find pending commission for this order
        $commission = Commission::query()
            ->where('order_id', $order->id)
            ->where('status', CommissionStatusEnum::PENDING)
            ->first();

        if (! $commission) {
            Log::info('OrderCompletedListener: No pending commission found for order #' . $order->id);

            return;
        }

        // Find the affiliate
        $affiliate = Affiliate::query()->find($commission->affiliate_id);

        if (! $affiliate) {
            Log::info('OrderCompletedListener: Affiliate not found for commission #' . $commission->id);

            return;
        }

        // Update commission status to approved
        $commission->update([
            'status' => CommissionStatusEnum::APPROVED,
            'description' => 'Commission approved for order ' . $order->code,
        ]);

        // Update affiliate balance and total commission
        $affiliate->balance += $commission->amount;
        $affiliate->total_commission += $commission->amount;
        $affiliate->save();

        // Create transaction record
        $affiliate->transactions()->create([
            'amount' => $commission->amount,
            'description' => 'Commission approved for order ' . $order->code,
            'type' => 'commission',
            'reference_id' => $commission->id,
            'reference_type' => Commission::class,
        ]);

        // Fire commission earned event for email notification
        event(new CommissionEarnedEvent($commission));

        Log::info('OrderCompletedListener: Commission approved for order #' . $order->id . ' with amount: ' . $commission->amount);
    }
}
