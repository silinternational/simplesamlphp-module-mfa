<?php
namespace Sil\SspMfa\Behat\context;

use Behat\Behat\Context\Context;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Exception\ElementNotFoundException;
use Behat\Mink\Session;
use PHPUnit\Framework\Assert;
use Sil\PhpEnv\Env;
use Sil\SspMfa\Behat\fakes\FakeIdBrokerClient;
use Sil\SspMfa\LoginBrowser;

/**
 * Defines application features from the specific context.
 */
class MfaContext implements Context
{
    protected $nonPwManagerUrl = 'http://mfasp/module.php/core/authenticate.php?as=mfa-idp-no-port';
    
    protected $username = null;
    protected $password = null;
    
    const USER_AGENT_WITHOUT_WEBAUTHN_SUPPORT = 'Mozilla/5.0 (Windows NT 10.0; Trident/7.0; rv:11.0) like Gecko';
    const USER_AGENT_WITH_WEBAUTHN_SUPPORT = 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_14_6) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/96.0.4664.55 Safari/537.36';
    
    /**
     * The browser session, used for interacting with the website.
     *
     * @var Session
     */
    protected $session;
    
    /**
     * The driver for our browser-based testing.
     *
     * @var GoutteDriver
     */
    protected $driver;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $this->driver = new GoutteDriver();
        $this->session = new Session($this->driver);
        $this->session->start();
    }
    
    /**
     * Assert that the given page has a form that contains the given text.
     *
     * @param string $text The text (or HTML) to search for.
     * @param DocumentElement $page The page to search in.
     * @return void
     */
    protected function assertFormContains($text, $page)
    {
        $forms = $page->findAll('css', 'form');
        foreach ($forms as $form) {
            if (strpos($form->getHtml(), $text) !== false) {
                return;
            }
        }
        Assert::fail(sprintf(
            "No form found containing %s in this HTML:\n%s",
            var_export($text, true),
            $page->getHtml()
        ));
    }
    
    /**
     * Get the "continue" button.
     *
     * @param DocumentElement $page The page.
     * @return NodeElement
     */
    protected function getContinueButton($page)
    {
        $continueButton = $page->find('css', '[name=continue]');
        return $continueButton;
    }
    
    /**
     * Get the login button from the given page.
     *
     * @param DocumentElement $page The page.
     * @return NodeElement
     */
    protected function getLoginButton($page)
    {
        $buttons = $page->findAll('css', 'button');
        $loginButton = null;
        foreach ($buttons as $button) {
            $lcButtonText = strtolower($button->getText());
            if (strpos($lcButtonText, 'login') !== false) {
                $loginButton = $button;
                break;
            }
        }
        Assert::assertNotNull($loginButton, 'Failed to find the login button');
        return $loginButton;
    }
    
    /**
     * Get the button for submitting the MFA form.
     *
     * @param DocumentElement $page The page.
     * @return NodeElement
     */
    protected function getSubmitMfaButton($page)
    {
        $submitMfaButton = $page->find('css', '[name=submitMfa]');
        Assert::assertNotNull($submitMfaButton, 'Failed to find the submit-MFA button');
        return $submitMfaButton;
    }
    
    /**
     * @When I login
     */
    public function iLogin()
    {
        $this->session->visit($this->nonPwManagerUrl);
        $page = $this->session->getPage();
        try {
            $page->fillField('username', $this->username);
            $page->fillField('password', $this->password);
            $this->submitLoginForm($page);
        } catch (ElementNotFoundException $e) {
            Assert::fail(sprintf(
                "Did not find that element in the page.\nError: %s\nPage content: %s",
                $e->getMessage(),
                $page->getContent()
            ));
        }
    }
    
    /**
     * @Then I should end up at my intended destination
     */
    public function iShouldEndUpAtMyIntendedDestination()
    {
        $page = $this->session->getPage();
        Assert::assertContains('Your attributes', $page->getHtml());
    }
    
    /**
     * Submit the current form, including the secondary page's form (if
     * simpleSAMLphp shows another page because JavaScript isn't supported) by
     * clicking the specified button.
     *
     * @param string $buttonName The value of the desired button's `name`
     *     attribute.
     */
    protected function submitFormByClickingButtonNamed($buttonName)
    {
        $page = $this->session->getPage();
        $button = $page->find('css', sprintf(
            '[name=%s]',
            $buttonName
        ));
        Assert::assertNotNull($button, 'Failed to find button named ' . $buttonName);
        $button->click();
        $this->submitSecondarySspFormIfPresent($page);
    }
    
    /**
     * Submit the login form, including the secondary page's form (if
     * simpleSAMLphp shows another page because JavaScript isn't supported).
     *
     * @param DocumentElement $page The page.
     */
    protected function submitLoginForm($page)
    {
        $loginButton = $this->getLoginButton($page);
        $loginButton->click();
        $this->submitSecondarySspFormIfPresent($page);
    }
    
    
    /**
     * Submit the MFA form, including the secondary page's form (if
     * simpleSAMLphp shows another page because JavaScript isn't supported).
     *
     * @param DocumentElement $page The page.
     */
    protected function submitMfaForm($page)
    {
        $submitMfaButton = $this->getSubmitMfaButton($page);
        $submitMfaButton->click();
        $this->submitSecondarySspFormIfPresent($page);
    }
    
    
    /**
     * Submit the secondary page's form (if simpleSAMLphp shows another page
     * because JavaScript isn't supported).
     *
     * @param DocumentElement $page The page.
     */
    protected function submitSecondarySspFormIfPresent($page)
    {
        // SimpleSAMLphp 1.15 markup for secondary page:
        $postLoginSubmitButton = $page->findButton('postLoginSubmitButton');
        if ($postLoginSubmitButton instanceof NodeElement) {
            $postLoginSubmitButton->click();
        } else {
            
            // SimpleSAMLphp 1.14 markup for secondary page:
            $body = $page->find('css', 'body');
            if ($body instanceof NodeElement) {
                $onload = $body->getAttribute('onload');
                if ($onload === "document.getElementsByTagName('input')[0].click();") {
                    $body->pressButton('Submit');
                }
            }
        }
    }
    
    /**
     * @Given I provide credentials that do not need MFA
     */
    public function iProvideCredentialsThatDoNotNeedMfa()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'no_mfa_needed';
        $this->password = 'a';
    }
    
    /**
     * @Given I provide credentials that need MFA but have no MFA options available
     */
    public function iProvideCredentialsThatNeedMfaButHaveNoMfaOptionsAvailable()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'must_set_up_mfa';
        $this->password = 'a';
    }
    
    /**
     * @Then I should see a message that I have to set up MFA
     */
    public function iShouldSeeAMessageThatIHaveToSetUpMfa()
    {
        $page = $this->session->getPage();
        Assert::assertContains('must set up 2-', $page->getHtml());
    }
    
    /**
     * @Then there should be a way to go set up MFA now
     */
    public function thereShouldBeAWayToGoSetUpMfaNow()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('name="setUpMfa"', $page);
    }
    
    /**
     * @Given I provide credentials that need MFA and have backup codes available
     */
    public function iProvideCredentialsThatNeedMfaAndHaveBackupCodesAvailable()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_backupcode';
        $this->password = 'a';
    }
    
    /**
     * @Then I should see a prompt for a backup code
     */
    public function iShouldSeeAPromptForABackupCode()
    {
        $page = $this->session->getPage();
        $pageHtml = $page->getHtml();
        Assert::assertContains('<h2>Printable Backup Code</h2>', $pageHtml);
        Assert::assertContains('Enter code', $pageHtml);
    }
    
    /**
     * @Given I provide credentials that need MFA and have TOTP available
     */
    public function iProvideCredentialsThatNeedMfaAndHaveTotpAvailable()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_totp';
        $this->password = 'a';
    }
    
    /**
     * @Then I should see a prompt for a TOTP (code)
     */
    public function iShouldSeeAPromptForATotpCode()
    {
        $page = $this->session->getPage();
        $pageHtml = $page->getHtml();
        Assert::assertContains('<h2>Smartphone App</h2>', $pageHtml);
        Assert::assertContains('Enter 6-digit code', $pageHtml);
    }

    /**
     * @Given I provide credentials that need MFA and have WebAuthn available
     */
    public function iProvideCredentialsThatNeedMfaAndHaveUfAvailable()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_webauthn';
        $this->password = 'a';
    }

    /**
     * @Then I should see a prompt for a WebAuthn (security key)
     */
    public function iShouldSeeAPromptForAWebAuthn()
    {
        $page = $this->session->getPage();
        Assert::assertContains('<h2>USB Security Key</h2>', $page->getHtml());
    }

    /**
     * @Given I have logged in (again)
     */
    public function iHaveLoggedIn()
    {
        $this->iLogin();
    }

    protected function submitMfaValue($mfaValue)
    {
        $page = $this->session->getPage();
        $page->fillField('mfaSubmission', $mfaValue);
        $this->submitMfaForm($page);
        return $page->getHtml();
    }

    /**
     * @When I submit a correct backup code
     */
    public function iSubmitACorrectBackupCode()
    {
        if (! $this->pageContainsElementWithText('h2', 'Printable Backup Code')) {
            $this->clickLink('backupcode');
        }
        $this->submitMfaValue(FakeIdBrokerClient::CORRECT_VALUE);
    }
    
    protected function pageContainsElementWithText($cssSelector, $text)
    {
        $page = $this->session->getPage();
        $elements = $page->findAll('css', $cssSelector);
        foreach ($elements as $element) {
            if (strpos($element->getText(), $text) !== false) {
                return true;
            }
        }
        return false;
    }
    
    protected function clickLink($text)
    {
        $this->session->getPage()->clickLink($text);
    }

    /**
     * @When I submit an incorrect backup code
     */
    public function iSubmitAnIncorrectBackupCode()
    {
        $this->submitMfaValue(FakeIdBrokerClient::INCORRECT_VALUE);
    }

    /**
     * @Then I should see a message that I have to wait before trying again
     */
    public function iShouldSeeAMessageThatIHaveToWaitBeforeTryingAgain()
    {
        $page = $this->session->getPage();
        $pageHtml = $page->getHtml();
        Assert::assertContains(' wait ', $pageHtml);
        Assert::assertContains('try again', $pageHtml);
    }

    /**
     * @Then I should see a message that it was incorrect
     */
    public function iShouldSeeAMessageThatItWasIncorrect()
    {
        $page = $this->session->getPage();
        $pageHtml = $page->getHtml();
        Assert::assertContains('Incorrect 2-step verification code', $pageHtml);
    }

    /**
     * @Given I provide credentials that have a rate-limited MFA
     */
    public function iProvideCredentialsThatHaveARateLimitedMfa()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_rate_limited_mfa';
        $this->password = 'a';
    }

    /**
     * @Then there should be a way to continue to my intended destination
     */
    public function thereShouldBeAWayToContinueToMyIntendedDestination()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('name="continue"', $page);
    }

    /**
     * @When I click the remind-me-later button
     */
    public function iClickTheRemindMeLaterButton()
    {
        $this->submitFormByClickingButtonNamed('continue');
    }

    /**
     * @When I click the set-up-MFA button
     */
    public function iClickTheSetUpMfaButton()
    {
        $this->submitFormByClickingButtonNamed('setUpMfa');
    }

    /**
     * @Then I should end up at the mfa-setup URL
     */
    public function iShouldEndUpAtTheMfaSetupUrl()
    {
        $mfaSetupUrl = Env::get('MFA_SETUP_URL_FOR_TESTS');
        Assert::assertNotEmpty($mfaSetupUrl, 'No MFA_SETUP_URL_FOR_TESTS provided');
        $currentUrl = $this->session->getCurrentUrl();
        Assert::assertStringStartsWith(
            $mfaSetupUrl,
            $currentUrl,
            'Did NOT end up at the MFA-setup URL'
        );
    }

    /**
     * @Then there should NOT be a way to continue to my intended destination
     */
    public function thereShouldNotBeAWayToContinueToMyIntendedDestination()
    {
        $page = $this->session->getPage();
        $continueButton = $this->getContinueButton($page);
        Assert::assertNull($continueButton, 'Should not have found a continue button');
    }

    /**
     * @Then I should NOT be able to get to my intended destination
     */
    public function iShouldNotBeAbleToGetToMyIntendedDestination()
    {
        $this->session->visit($this->nonPwManagerUrl);
        Assert::assertStringStartsNotWith(
            $this->nonPwManagerUrl,
            $this->session->getCurrentUrl(),
            'Failed to prevent me from getting to SPs other than the MFA setup URL'
        );
    }

    /**
     * @Given I provide credentials that need MFA and have 4 backup codes available
     */
    public function iProvideCredentialsThatNeedMfaAndHave4BackupCodesAvailable()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_4_backupcodes';
        $this->password = 'a';
    }

    /**
     * @Then I should see a message that I am running low on backup codes
     */
    public function iShouldSeeAMessageThatIAmRunningLowOnBackupCodes()
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'You are almost out of Printable Backup Codes',
            $page->getHtml()
        );
    }

    /**
     * @Then there should be a way to get more backup codes now
     */
    public function thereShouldBeAWayToGetMoreBackupCodesNow()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('name="getMore"', $page);
    }

    /**
     * @Given I provide credentials that need MFA and have 1 backup code available and no other MFA
     */
    public function iProvideCredentialsThatNeedMfaAndHave1BackupCodeAvailableAndNoOtherMfa()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_1_backupcode_only';
        $this->password = 'a';
    }

    /**
     * @Then I should see a message that I have used up my backup codes
     */
    public function iShouldSeeAMessageThatIHaveUsedUpMyBackupCodes()
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'You just used your last Printable Backup Code',
            $page->getHtml()
        );
    }

    /**
     * @Given I provide credentials that need MFA and have 1 backup code available plus some other MFA
     */
    public function iProvideCredentialsThatNeedMfaAndHave1BackupCodeAvailablePlusSomeOtherMfa()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_1_backupcode_plus';
        $this->password = 'a';
    }

    /**
     * @When I click the get-more-backup-codes button
     */
    public function iClickTheGetMoreBackupCodesButton()
    {
        $this->submitFormByClickingButtonNamed('getMore');
    }

    /**
     * @Then I should be told I only have :numRemaining backup codes left
     */
    public function iShouldBeToldIOnlyHaveBackupCodesLeft($numRemaining)
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'You only have ' . $numRemaining . ' remaining',
            $page->getHtml()
        );
    }

    /**
     * @Then I should be given more backup codes
     */
    public function iShouldBeGivenMoreBackupCodes()
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'Here are your new Printable Backup Codes',
            $page->getContent()
        );
    }

    /**
     * @Given I provide credentials that have WebAuthn
     */
    public function iProvideCredentialsThatHaveUf()
    {
        $this->iProvideCredentialsThatNeedMfaAndHaveUfAvailable();
    }

    /**
     * @Given the user's browser supports WebAuthn
     */
    public function theUsersBrowserSupportsUf()
    {
        $userAgentWithWebAuthn = self::USER_AGENT_WITH_WEBAUTHN_SUPPORT;
        Assert::assertTrue(
            LoginBrowser::supportsWebAuthn($userAgentWithWebAuthn),
            'Update USER_AGENT_WITH_WEBAUTHN_SUPPORT to a User Agent with WebAuthn support'
        );
        
        $this->driver->getClient()->setServerParameter('HTTP_USER_AGENT', $userAgentWithWebAuthn);
    }

    /**
     * @Given I provide credentials that have WebAuthn, TOTP
     */
    public function iProvideCredentialsThatHaveUfTotp()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_webauthn_totp';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have WebAuthn, backup codes
     */
    public function iProvideCredentialsThatHaveUfBackupCodes()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_webauthn_backupcodes';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have WebAuthn, TOTP, backup codes
     */
    public function iProvideCredentialsThatHaveUfTotpBackupCodes()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_webauthn_totp_backupcodes';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have TOTP
     */
    public function iProvideCredentialsThatHaveTotp()
    {
        $this->iProvideCredentialsThatNeedMfaAndHaveTotpAvailable();
    }

    /**
     * @Given I provide credentials that have TOTP, backup codes
     */
    public function iProvideCredentialsThatHaveTotpBackupCodes()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_totp_backupcodes';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have backup codes
     */
    public function iProvideCredentialsThatHaveBackupCodes()
    {
        $this->iProvideCredentialsThatNeedMfaAndHaveBackupCodesAvailable();
    }

    /**
     * @Given I provide credentials that have a manager code, a WebAuthn and a more recently used TOTP
     */
    public function IProvideCredentialsThatHaveManagerCodeWebauthnAndMoreRecentlyUsedTotp()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_mgr_code_webauthn_and_more_recently_used_totp';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have a used WebAuthn
     */
    public function IProvideCredentialsThatHaveUsedWebAuthn()
    {
        $this->username = 'has_webauthn_';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have a used TOTP
     */
    public function IProvideCredentialsThatHaveUsedTotp()
    {
        $this->username = 'has_totp_';
        $this->password = 'a';
    }

    /**
     * @Given I provide credentials that have a used backup code
     */
    public function IProvideCredentialsThatHaveUsedBackupCode()
    {
        $this->username = 'has_backup_code_';
        $this->password = 'a';
    }

    /**
     * @Given and I have a more recently used TOTP
     */
    public function IHaveMoreRecentlyUsedTotp()
    {
        $this->username .= 'and_more_recently_used_totp';
        $this->password = 'a';
    }

    /**
     * @Given and I have a more recently used Webauthn
     */
    public function IHaveMoreRecentlyUsedWebauthn()
    {
        $this->username .= 'and_more_recently_used_webauthn';
        $this->password = 'a';
    }

    /**
     * @Given and I have a more recently used backup code
     */
    public function IHaveMoreRecentlyUsedBackupCode()
    {
        $this->username .= 'and_more_recently_used_backup_code';
        $this->password = 'a';
    }

    /**
     * @Given the user's browser does not support WebAuthn
     */
    public function theUsersBrowserDoesNotSupportUf()
    {
        $userAgentWithoutWebAuthn = self::USER_AGENT_WITHOUT_WEBAUTHN_SUPPORT;
        Assert::assertFalse(
            LoginBrowser::supportsWebAuthn($userAgentWithoutWebAuthn),
            'Update USER_AGENT_WITHOUT_WEBAUTHN_SUPPORT to a User Agent without WebAuthn support'
        );
        
        $this->driver->getClient()->setServerParameter('HTTP_USER_AGENT', $userAgentWithoutWebAuthn);
    }

    /**
     * @Then I should not see an error message about WebAuthn being unsupported
     */
    public function iShouldNotSeeAnErrorMessageAboutUfBeingUnsupported()
    {
        $page = $this->session->getPage();
        Assert::assertNotContains('USB Security Keys are not supported', $page->getContent());
    }

    /**
     * @Then I should see an error message about WebAuthn being unsupported
     */
    public function iShouldSeeAnErrorMessageAboutUfBeingUnsupported()
    {
        $page = $this->session->getPage();
        Assert::assertContains('USB Security Keys are not supported', $page->getContent());
    }

    /**
     * @Given the user has a manager email
     */
    public function theUserHasAManagerEmail()
    {
        $this->username .= '_and_mgr';
    }

    /**
     * @Then I should see a link to send a code to the user's manager
     */
    public function iShouldSeeALinkToSendACodeToTheUsersManager()
    {
        $page = $this->session->getPage();
        Assert::assertContains('Can\'t use any of your 2-Step Verification options', $page->getContent());
    }

    /**
     * @Given the user does not have a manager email
     */
    public function theUserDoesntHaveAManagerEmail()
    {
        /*
         * No change to username needed.
         */
    }

    /**
     * @Then I should not see a link to send a code to the user's manager
     */
    public function iShouldNotSeeALinkToSendACodeToTheUsersManager()
    {
        $page = $this->session->getPage();
        Assert::assertNotContains('Send a code</a> to your manager', $page->getContent());
    }

    /**
     * @When I click the Request Assistance link
     */
    public function iClickTheRequestAssistanceLink()
    {
        $this->clickLink('Click here');
    }

    /**
     * @When I click the Send a code link
     */
    public function iClickTheRequestACodeLink()
    {
        $this->submitFormByClickingButtonNamed('send');
    }

    /**
     * @Then I should see a prompt for a manager rescue code
     */
    public function iShouldSeeAPromptForAManagerRescueCode()
    {
        $page = $this->session->getPage();
        $pageHtml = $page->getHtml();
        Assert::assertContains('<h2>Manager Rescue Code</h2>', $pageHtml);
        Assert::assertContains('Enter code', $pageHtml);
    }

    /**
     * @When I submit the correct manager code
     */
    public function iSubmitTheCorrectManagerCode()
    {
        $this->submitMfaValue(FakeIdBrokerClient::CORRECT_VALUE);
    }

    /**
     * @When I submit an incorrect manager code
     */
    public function iSubmitAnIncorrectManagerCode()
    {
        $this->submitMfaValue(FakeIdBrokerClient::INCORRECT_VALUE);
    }

    /**
     * @Given I provide credentials that have a manager code
     */
    public function iProvideCredentialsThatHaveAManagerCode()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_mgr_code';
        $this->password = 'a';
    }

    /**
     * @Then there should be a way to request a manager code
     */
    public function thereShouldBeAWayToRequestAManagerCode()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('name="send"', $page);
    }

    /**
     * @When I click the Cancel button
     */
    public function iClickTheCancelButton()
    {
        $this->submitFormByClickingButtonNamed('cancel');
    }
}
