<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\App;

class Language
{
    /**
     * Update the language
     *
     * @param $request
     * @param Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $locale = $request->header('X-localization');
        $locale = $locale && in_array($locale, config('app.locales'))
            ? $locale
            : config('app.fallback_locale');

        App::setLocale($locale);

        return $next($request);
    }
}
