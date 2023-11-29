<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class EnsureShopifyProxySignature
{
    /**
     * Checks if the shop in the query arguments is currently installed.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        return $next($request);

        //@todo - Update when we know how to generate correct HMAC...
        $appSecret = env('APP_SECRET', false);
        $query = $request->query();
        $signature = '';
        $params = [];

        foreach ($query as $k => $v) {
            if ($k === 'signature') {
                $signature = $v;
                continue;
            }
            if (!in_array($k, ['_fd', 'pb'])) {
                $params[$k] = (string) $v;
            }
        }
        ksort($params);
        $hmacData = str_replace('&', '', urldecode(http_build_query($params)));
        $test = hash_hmac('sha256', $hmacData, $appSecret);
        var_dump($query);
        var_dump($signature);
        var_dump($params);
        var_dump($hmacData);
        var_dump($test);
        die();
    }
}
