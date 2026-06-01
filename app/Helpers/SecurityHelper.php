<?php

if (! function_exists('csp_nonce')) {
    /**
     * Get the CSP nonce for the current request.
     *
     * @return string
     */
    function csp_nonce(): string
    {
        if (app()->bound('csp_nonce')) {
            return app('csp_nonce');
        }

        // Generate a fallback nonce if not bound (e.g. in artisan command or before middleware)
        $nonce = base64_encode(random_bytes(16));
        app()->instance('csp_nonce', $nonce);
        return $nonce;
    }
}
