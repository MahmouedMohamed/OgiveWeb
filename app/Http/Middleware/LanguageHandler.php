<?php

namespace App\Http\Middleware;

use App\Traits\ApiResponse;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Foundation\Application;

class LanguageHandler
{
    use ApiResponse;

    private $app;

    /**
     * Localization constructor.
     *
     * @param \Illuminate\Foundation\Application $app
     */
    public function __construct(Application $app)
    {
        $this->app = $app;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        // read the language from the request header
        $language = strtolower($request->header('Content-Language'));

        // if the header is missed
        if (!$language) {
            // take the default local language
            $language = $this->app->config->get('app.locale');
        }

        // check the languages defined is supported
        if (!array_key_exists($language, $this->app->config->get('app.supported_languages'))) {
            // respond with error
            return $this->sendForbidden('Language not supported.');
        }

        // set the local language
        $this->app->setLocale($language);
        return $next($request);
    }
}
