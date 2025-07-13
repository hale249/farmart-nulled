<?php

namespace Botble\AffiliatePro\Http\Middleware;

use Botble\AffiliatePro\Services\AffiliateTrackingService;
use Closure;
use Illuminate\Http\Request;

class AffiliateTrackingMiddleware
{
    protected AffiliateTrackingService $trackingService;

    public function __construct(AffiliateTrackingService $trackingService)
    {
        $this->trackingService = $trackingService;
    }

    public function handle(Request $request, Closure $next)
    {
        // Check if there's an affiliate code in the request
        $affiliateCode = $request->query('aff');

        if ($affiliateCode) {
            $this->trackingService->handleAffiliateTracking($request, $affiliateCode);
        }

        return $next($request);
    }
}
