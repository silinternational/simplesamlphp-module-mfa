<?php
namespace Sil\SspMfa;

use Sinergi\BrowserDetector\Browser;

/**
 * Simple class for figuring out some things about the user's browser.
 */
class LoginBrowser
{
    /**
     * Get the User-Agent sent by the user's browser (or null if not present).
     *
     * @return string|null
     */
    public static function getUserAgent()
    {
        return filter_input(INPUT_SERVER, 'HTTP_USER_AGENT') ?: null;
    }
    
    // TODO: Replace this with client-side feature detection.
    public static function supportsWebAuthn($userAgent)
    {
        $browser = new Browser($userAgent);
        $browserName = $browser->getName();
        
        // For now, simply set these to approximate the results shown on caniuse:
        // https://caniuse.com/?search=webauthn
        return in_array(
            $browserName,
            [
                Browser::CHROME,
                Browser::SAFARI,
                Browser::EDGE,
                Browser::FIREFOX,
                Browser::OPERA,
            ],
            true
        );
    }
}
