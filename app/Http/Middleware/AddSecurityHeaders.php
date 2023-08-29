<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AddSecurityHeaders
{
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Response $response */
        $response = $next($request);

        // add security headers
        // to the response object

        $response->headers->add([
            // Deny iframe usage if
            // it's not the same origin
            'X-Frame-Options' => 'SAMEORIGIN',

            // Block mimetype sniffing
            'X-Content-Type-Options' => 'nosniff',

            // Content blocking based on the origin for:
            // - javascript
            // - styles
            // - images
            // - etc...
            'Content-Security-Policy' => implode('; ', [
                "default-src 'none'",
            ]),

            // Use strictly HTTPS to access the API
            //
            // note: if we have always called the API through
            // HTTPS (localhost), the header will be ignored
            'Strict-Transport-Security' => 'max-age=31536000; includeSubDomains',

            // Disabled all web APIs
            'Permissions-Policy' => implode(', ', [
                'accelerometer=()',
                'ambient-light-sensor=()',
                'autoplay=()',
                'battery=()',
                'camera=()',
                'cross-origin-isolated=()',
                'display-capture=()',
                'document-domain=()',
                'encrypted-media=()',
                'execution-while-not-rendered=()',
                'execution-while-out-of-viewport=()',
                'fullscreen=()',
                'geolocation=()',
                'gyroscope=()',
                'keyboard-map=()',
                'magnetometer=()',
                'microphone=()',
                'midi=()',
                'navigation-override=()',
                'payment=()',
                'picture-in-picture=()',
                'publickey-credentials-get=()',
                'screen-wake-lock=()',
                'sync-xhr=()',
                'usb=()',
                'web-share=()',
                'xr-spatial-tracking=()',
                'clipboard-read=()',
                'clipboard-write=()',
                'gamepad=()',
                'speaker-selection=()',
                'conversion-measurement=()',
                'focus-without-user-activation=()',
                'hid=()',
                'idle-detection=()',
                'interest-cohort=()',
                'serial=()',
                'sync-script=()',
                'trust-token-redemption=()',
                'unload=()',
                'window-placement=()',
                'vertical-scroll=()',
            ]),

            // Send the origin, path, and querystring when performing a same-origin request.
            // For cross-origin requests send the origin (only) when the protocol security
            // level stays same (HTTPS -> HTTPS). Don't send the Referer header to less secure
            // destinations (HTTPS → HTTP).
            'Referrer-Policy' => 'strict-origin-when-cross-origin',

            // Block site rendering when it contains malicious code
            'X-XSS-Protection' => '1; mode=block',
        ]);

        return $response;
    }
}
