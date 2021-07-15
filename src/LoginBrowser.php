<?php
namespace Sil\SspMfa;

use Sinergi\BrowserDetector\Browser;
use Sinergi\BrowserDetector\Os;

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
    
    public static function supportsU2f($userAgent)
    {
        $operatingSystem = new Os($userAgent);
        if ($operatingSystem->isMobile()) {
            return false;
        }
        
        $browser = new Browser($userAgent);
        $browserName = $browser->getName();
        
        // For now, simply set these to match the criteria in
        // https://github.com/silinternational/idp-profile-ui/blob/master/src/2sv/key/u2f-api.js
        return ($browserName !== Browser::SAFARI);
    }
}
