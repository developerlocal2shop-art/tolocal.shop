<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\PaymentTrasaction;
use App\Models\PlanSubscription;
use App\Services\PayPalService;
use Illuminate\Support\Facades\Log;

class PackagePrice extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'package:price';

    /**
     * The console command description.
     */
    protected $description = 'Cancel PayPal subscriptions before a changed package price renews';

    public function handle()
    {
        $paypal = null;
        $hasErrors = false;

        $users = User::whereNotNull('directory_id')->get();

        foreach ($users as $user) {
            $payment = PaymentTrasaction::where('directory_id', $user->directory_id)
                ->whereIn('type', ['auto_payment', 'recurring'])
                ->latest('id')
                ->first();
            $subscription = PlanSubscription::with('plan')
                ->where('slug', '!=', 'free')
                ->where('subscriber_id', $user->directory_id)
                ->latest('id')
                ->first();

            if (!$subscription || !$subscription->plan || !$subscription->plan->signup_fee || !$payment) {
                continue;
            }

            $expectedAmount = round(
                (float) $subscription->plan->signup_fee * (int) $subscription->plan->invoice_period,
                2
            );
            if ($expectedAmount === round((float) $payment->total_amount, 2)) {
                continue;
            }

            $endsAt = $payment->ends_at ?: $subscription->ends_at;
            if (!$endsAt) {
                $this->warn("No renewal date is available for directory {$user->directory_id}.");
                $hasErrors = true;
                continue;
            }

            if (!\Illuminate\Support\Carbon::parse($endsAt)->subDay()->isToday()) {
                continue;
            }

            if (!$subscription->paypal_subscription_id) {
                $this->error("No PayPal subscription ID is stored for directory {$user->directory_id}.");
                Log::warning('Cannot cancel PayPal subscription after package price change.', [
                    'directory_id' => $user->directory_id,
                    'plan_subscription_id' => $subscription->id,
                ]);
                $hasErrors = true;
                continue;
            }

            try {
                $paypal ??= app(PayPalService::class)->provider()->withExceptions();
                $paypal->cancelSubscription(
                    $subscription->paypal_subscription_id,
                    'Package price changed before renewal.'
                );

                $subscription->statuss = 'cancelled';
                $subscription->save();
                $this->info("Cancelled PayPal subscription for directory {$user->directory_id}.");
            } catch (\Throwable $exception) {
                $this->error("Unable to cancel PayPal subscription for directory {$user->directory_id}.");
                Log::error('PayPal subscription cancellation failed after package price change.', [
                    'directory_id' => $user->directory_id,
                    'plan_subscription_id' => $subscription->id,
                    'paypal_subscription_id' => $subscription->paypal_subscription_id,
                    'exception' => $exception->getMessage(),
                ]);
                $hasErrors = true;
            }
        }

        return $hasErrors ? self::FAILURE : self::SUCCESS;
    }

}
