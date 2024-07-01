<?php

namespace App\Billing;

use App\Http\Middleware\CheckUserSubscription;
use Closure;
use Filament\Billing\Providers\Contracts\Provider;
use Illuminate\Http\RedirectResponse;

class SubscriptionBillingProvider implements Provider
{
    public function getRouteAction(): string | Closure | array
    {
        return function(): RedirectResponse{
            return redirect()->route('subscription.checkout');
        };
    }

    public function getSubscribedMiddleware(): string
    {
      return CheckUserSubscription::class;   
    }
}