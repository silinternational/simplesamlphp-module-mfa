<?php
namespace Sil\SspMfa\Behat\context;

use Behat\Behat\Tester\Exception\PendingException;
use Behat\Behat\Context\Context;
use Behat\Mink\Driver\GoutteDriver;
use Behat\Mink\Element\DocumentElement;
use Behat\Mink\Element\NodeElement;
use Behat\Mink\Session;
use PHPUnit\Framework\Assert;
use Sil\SspMfa\Behat\fakes\FakeIdBrokerClient;

/**
 * Defines application features from the specific context.
 */
class MfaContext implements Context
{
    protected $username = null;
    protected $password = null;
    
    /**
     * The browser session, used for interacting with the website.
     *
     * @var Session 
     */
    protected $session;
    
    /**
     * Initializes context.
     *
     * Every scenario gets its own context instance.
     * You can also pass arbitrary arguments to the
     * context constructor through behat.yml.
     */
    public function __construct()
    {
        $driver = new GoutteDriver();
        $this->session = new Session($driver);
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
        $submitMfaButton = $page->findById('submitMfa');
        Assert::assertNotNull($submitMfaButton, 'Failed to find the submit-MFA button');
        return $submitMfaButton;
    }
    
    /**
     * @When I login
     */
    public function iLogin()
    {
        $this->session->visit(
            'http://mfasp/module.php/core/authenticate.php?as=mfa-idp-no-port'
        );
        $page = $this->session->getPage();
        $page->fillField('username', $this->username);
        $page->fillField('password', $this->password);
        $this->submitLoginForm($page);
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
        $loginButton = $this->getSubmitMfaButton($page);
        $loginButton->click();
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
        $body = $page->find('css', 'body');
        if ($body instanceof NodeElement) {
            $onload = $body->getAttribute('onload');
            if ($onload === "document.getElementsByTagName('input')[0].click();") {
                $body->pressButton('Submit');
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
        Assert::assertContains('need to', $page->getHtml());
        Assert::assertContains('set up 2-step verification', $page->getHtml());
    }
    
    /**
     * @Then there should be a way to go set up MFA now
     */
    public function thereShouldBeAWayToGoSetUpMfaNow()
    {
        $page = $this->session->getPage();
        $this->assertFormContains('id="setUpMfa"', $page);
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
        Assert::assertContains(
            'Please enter one of your backup codes',
            $page->getHtml()
        );
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
     * @Then I should see a prompt for a TOTP code
     */
    public function iShouldSeeAPromptForATotpCode()
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'Please enter the 6-digit number from your app',
            $page->getHtml()
        );
    }

    /**
     * @Given I provide credentials that need MFA and have U2F available
     */
    public function iProvideCredentialsThatNeedMfaAndHaveUfAvailable()
    {
        // See `development/idp-local/config/authsources.php` for options.
        $this->username = 'has_u2f';
        $this->password = 'a';
    }

    /**
     * @Then I should see a prompt for a U2F security key
     */
    public function iShouldSeeAPromptForAUfSecurityKey()
    {
        $page = $this->session->getPage();
        Assert::assertContains(
            'Please insert your security key',
            $page->getHtml()
        );
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
        $this->submitMfaValue(FakeIdBrokerClient::CORRECT_VALUE);
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
        $this->username = 'has_rate_limited_mfa';
        $this->password = 'a';
    }
}
