<?php

namespace App\Http\Middleware;

use App\Models\Package;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

use function App\Support\package_name;

class EnsureUserHasLicense
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var \App\Models\Token $token */
        $token = $request->user();
        $vendor = $request->route('vendor');
        $package = $request->route('package');
        [$name] = package_name("{$vendor}/{$package}");

        if (! Package::query()->where('name', $name)->exists()) {
            abort(404);
        }

        if (! $token->packages()->where('name', $name)->exists()) {
            abort(403);
        }

        return $next($request);
    }
}
