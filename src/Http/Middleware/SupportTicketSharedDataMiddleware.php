<?php

namespace Zerp\SupportTicket\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Inertia\Inertia;
use App\Models\User;
use App\Classes\Module;
use App\Models\Concerns\TenantScope;

class SupportTicketSharedDataMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (str_starts_with($request->route()?->getName() ?? '', 'support-ticket.')) {
            // The help centre is public and serves ONE company's tickets, FAQ and
            // knowledge base, addressed by the slug in the URL — not by whoever happens
            // to be logged in. Lift the tenant scope for this request so a visitor
            // signed in to another company sees the portal instead of an empty page.
            // Every query here already filters by $userId.
            TenantScope::standDownForThisRequest();

            $userId = $this->getUserIdFromRequest($request);
            
            $user = User::find($userId);
            $userSlug = $request->route('slug');
            $sanitizedUserSlug = $userSlug ? htmlspecialchars($userSlug, ENT_QUOTES, 'UTF-8') : null;
            
            Inertia::share([
                'companyAllSetting' => getCompanyAllSetting($userId),
                'userSlug' => $sanitizedUserSlug,
                'auth' => [
                    'user' => ['activatedPackages' => ActivatedModule($userId ?? null)],
                ],
                'packages' => (new Module())->allModules(),
                'imageUrlPrefix' => $user ? getImageUrlPrefix() : url('/'),
            ]);
        }

        return $next($request);
    }

    private function getUserIdFromRequest(Request $request): int
    {
        $userSlug = $request->route('slug');
        if ($userSlug) {
            try {
                $user = User::where('slug', $userSlug)->first();
                if ($user) {
                    return $user->id;
                }
            } catch (\Exception $e) {
                \Log::error('Database error in SupportTicketSharedDataMiddleware: ' . $e->getMessage());
                abort(500, 'Database error');
            }
        }
        
        abort(404, 'Support ticket page not found');
    }
}