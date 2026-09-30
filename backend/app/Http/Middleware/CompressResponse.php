<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CompressResponse
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (
            !$request->header('Accept-Encoding') ||
            strpos($request->header('Accept-Encoding'), 'gzip') === false
        ) {
            return $response;
        }

        if (!$this->shouldCompress($response)) {
            return $response;
        }

        $response->header('Content-Encoding', 'gzip');

        if ($response->headers->has('Content-Length')) {
            $response->headers->remove('Content-Length');
        }

        return $response;
    }

    private function shouldCompress($response)
    {
        $minSize = 1024;
        $contentLength = $response->headers->get('Content-Length');

        if ($contentLength && $contentLength < $minSize) {
            return false;
        }

        $contentType = $response->headers->get('Content-Type');
        if (!$contentType) {
            return false;
        }

        return strpos($contentType, 'application/json') !== false ||
               strpos($contentType, 'text/') !== false;
    }
}
