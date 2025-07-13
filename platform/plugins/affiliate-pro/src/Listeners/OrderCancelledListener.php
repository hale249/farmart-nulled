<?php

namespace Botble\AffiliatePro\Listeners;

use Botble\AffiliatePro\Enums\CommissionStatusEnum;
use Botble\AffiliatePro\Models\Affiliate;
use Botble\AffiliatePro\Models\Commission;
use Botble\Ecommerce\Events\OrderCancelledEvent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class OrderCancelledListener implements ShouldQueue
{
    public function handle(OrderCancelledEvent $event): void
    {
        $order = $event->order;

        // Log for debugging
        Log::info('OrderCancelledListener: Processing cancelled order #' . $order->id);

        // Find any commissions for this order
        $commission = Commission::query()->where('order_id', $order->id)->first();

        if (! $commission) {
            Log::info('OrderCancelledListener: No commission found for order #' . $order->id);

            return;
        }

        // Handle based on commission status
        if ($commission->status === CommissionStatusEnum::PENDING) {
            // For pending commissions, just mark as rejected
            $commission->update([
                'status' => CommissionStatusEnum::REJECTED,
                'description' => 'Commission rejected for cancelled order ' . $order->code,
            ]);

            Log::info('OrderCancelledListener: Pending commission rejected for order #' . $order->id);
        } elseif ($commission->status === CommissionStatusEnum::APPROVED) {
            // For approved commissions, reverse the balance and mark as rejected
            $affiliate = Affiliate::query()->find($commission->affiliate_id);

            if ($affiliate) {
                // Subtract the commission amount from the affiliate's balance
                $affiliate->balance -= $commission->amount;
                $affiliate->total_commission -= $commission->amount;
                $affiliate->save();

                // Create a transaction record for the reversal
                $affiliate->transactions()->create([
                    'amount' => -$commission->amount,
                    'description' => 'Commission reversed for cancelled order ' . $order->code,
                    'type' => 'reversal',
                    'reference_id' => $commission->id,
                    'reference_type' => Commission::class,
                ]);

                Log::info('OrderCancelledListener: Reversed commission for order #' . $order->id);
            }

            // Update commission status to rejected
            $commission->update([
                'status' => CommissionStatusEnum::REJECTED,
                'description' => 'Commission reversed for cancelled order ' . $order->code,
            ]);
        }

        Log::info('OrderCancelledListener: Commission processed for cancelled order #' . $order->id);
    }
}
