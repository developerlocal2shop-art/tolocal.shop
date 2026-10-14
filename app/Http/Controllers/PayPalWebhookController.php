<?php

namespace App\Http\Controllers;

use App\Models\PlanFeatures;
use App\Models\PlanSubscription;
use App\Models\PlanSubscriptionUsage;
use App\Models\Plans;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Srmklive\PayPal\Services\PayPal as PayPalClient;

class PayPalWebhookController extends Controller
{
    private const FREE_PLAN_ID = 1;

    public function handle(Request $request)
    {
        $webhookId = config('paypal.webhook_id');
        if (!$webhookId) {
            Log::error('PayPal webhook rejected because the webhook ID is not configured.');

            return response()->json(['message' => 'PayPal webhook is not configured.'], 503);
        }

        $provider = new PayPalClient();
        $provider->setApiCredentials(config('paypal'));
        $provider->getAccessToken();
        $provider->setWebHookID($webhookId);

        $verification = $provider->verifyIPN($request);
        if (!is_array($verification) || ($verification['verification_status'] ?? null) !== 'SUCCESS') {
            Log::warning('PayPal webhook signature verification failed.', [
                'event_id' => $request->input('id'),
            ]);

            return response()->json(['message' => 'Invalid PayPal webhook signature.'], 401);
        }

        $payload = $request->json()->all();
        if (($payload['event_type'] ?? null) !== 'BILLING.SUBSCRIPTION.CANCELLED') {
            return response()->json(['status' => true]);
        }

        $subscriptionId = data_get($payload, 'resource.id');
        if (!is_string($subscriptionId) || $subscriptionId === '') {
            return response()->json(['message' => 'PayPal subscription ID is missing.'], 400);
        }

        DB::transaction(function () use ($subscriptionId, $payload) {
            $subscription = PlanSubscription::where('paypal_subscription_id', $subscriptionId)
                ->lockForUpdate()
                ->first();

            if (!$subscription) {
                Log::info('PayPal cancellation received for an unknown subscription.', [
                    'event_id' => $payload['id'] ?? null,
                    'subscription_id' => $subscriptionId,
                ]);

                return;
            }

            $subscriberId = $subscription->subscriber_id;
            $this->assignFreePlan($subscriberId);

            Log::info('Company package downgraded after PayPal subscription cancellation.', [
                'event_id' => $payload['id'] ?? null,
                'subscription_id' => $subscriptionId,
                'subscriber_id' => $subscriberId,
            ]);
        });

        return response()->json(['status' => true]);
    }

    private function assignFreePlan($subscriberId): void
    {
        $plan = Plans::findOrFail(self::FREE_PLAN_ID);
        $subscriptions = PlanSubscription::where('subscriber_id', $subscriberId)
            ->lockForUpdate()
            ->get();

        PlanSubscriptionUsage::whereIn('subscription_id', $subscriptions->pluck('id'))->delete();
        PlanSubscription::whereIn('id', $subscriptions->pluck('id'))->delete();

        $subscription = new PlanSubscription();
        $subscription->subscriber_type = 'App\Models\User';
        $subscription->subscriber_id = $subscriberId;
        $subscription->plan_id = $plan->id;
        $subscription->slug = $plan->slug;
        $subscription->name = json_encode($plan->name);
        $subscription->starts_at = now();
        $subscription->statuss = 'active';
        $subscription->type = 'free';
        $subscription->save();

        $features = PlanFeatures::where('plan_id', $plan->id)->get();
        foreach ($features as $feature) {
            $usage = new PlanSubscriptionUsage();
            $usage->subscription_id = $subscription->id;
            $usage->feature_id = $feature->id;
            $usage->name = $feature->slug;
            $usage->used = $feature->value ?? 0;
            $usage->save();
        }
    }
}
