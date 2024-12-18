<?php

namespace App\Http\Middleware;

use App\Models\ApiKey;
use App\Models\Merchant;
use App\Traits\ApiReturnFormatTrait;
use Closure;

class ControlPanelCheckApiKey
{
    use ApiReturnFormatTrait;

    public function handle($request, Closure $next)
    {
        if ($request->hasHeader('apikey')):

            $requestApiKey = $request->header('apikey');
            $envApiKey = env('API_KEY');

            if ($requestApiKey === $envApiKey):

                return $next($request);
            else:
                return $this->responseWithError('Invalid API key');
            endif;
        else:
            return $this->responseWithError('API key missing');
        endif;
    }
}
