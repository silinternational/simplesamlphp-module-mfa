<?php

use Psr\Log\LoggerInterface;
use Sil\Psr3Adapters\Psr3SamlLogger;

/**
 * Filter which prompts the user for MFA credentials.
 *
 * See README.md for sample (and explanation of) expected configuration.
 */
class sspmod_mfa_Auth_Process_Mfa extends SimpleSAML_Auth_ProcessingFilter
{
    const SESSION_TYPE = 'mfa';
    const STAGE_SENT_TO_MFA_CHANGE_URL = 'mfa:sent_to_mfa_change_url';
    const STAGE_SENT_TO_MFA_NEEDED_MESSAGE = 'mfa:sent_to_mfa_needed_message';
    const STAGE_SENT_TO_MFA_PROMPT = 'mfa:sent_to_mfa_prompt';
    
    private $accountNameAttr = null;
    private $mfaSetupUrl = null;
    
    /** @var LoggerInterface */
    protected $logger;
    
    /**
     * Initialize this filter.
     *
     * @param array $config  Configuration information about this filter.
     * @param mixed $reserved  For future use.
     */
    public function __construct($config, $reserved)
    {
        parent::__construct($config, $reserved);
        $this->initComposerAutoloader();
        assert('is_array($config)');
        $this->initLogger($config);
        
        $this->loadValuesFromConfig($config, [
            'mfaSetupUrl',
            'accountNameAttr',
        ]);
    }
    
    protected function loadValuesFromConfig($config, $attributes)
    {
        foreach ($attributes as $attribute) {
            $this->$attribute = $config[$attribute] ?? null;
            
            self::validateConfigValue(
                $attribute,
                $this->$attribute,
                $this->logger
            );
        }
    }
    
    /**
     * Validate the given config value
     *
     * @param string $attribute The name of the attribute.
     * @param mixed $value The value to check.
     * @param LoggerInterface $logger The logger.
     * @throws Exception
     */
    public static function validateConfigValue($attribute, $value, $logger)
    {
        if (empty($value) || !is_string($value)) {
            $exception = new Exception(sprintf(
                'The value we have for %s (%s) is empty or is not a string',
                $attribute,
                var_export($value, true)
            ), 1507146042);

            $logger->critical($exception->getMessage());
            throw $exception;
        }
    }
    
    /**
     * Get the specified attribute from the given state data.
     *
     * NOTE: If the attribute's data is an array, the first value will be
     *       returned. Otherwise, the attribute's data will simply be returned
     *       as-is.
     *
     * @param string $attributeName The name of the attribute.
     * @param array $state The state data.
     * @return mixed The attribute value, or null if not found.
     */
    protected function getAttribute($attributeName, $state)
    {
        $attributeData = $state['Attributes'][$attributeName] ?? null;
        
        if (is_array($attributeData)) {
            return $attributeData[0] ?? null;
        }
        
        return $attributeData;
    }
    
    /**
     * Get all of the values for the specified attribute from the given state
     * data.
     *
     * NOTE: If the attribute's data is an array, it will be returned as-is.
     *       Otherwise, it will be returned as a single-entry array of the data.
     *
     * @param string $attributeName The name of the attribute.
     * @param array $state The state data.
     * @return array|null The attribute's value(s), or null if the attribute was
     *     not found.
     */
    protected function getAttributeAllValues($attributeName, $state)
    {
        $attributeData = $state['Attributes'][$attributeName] ?? null;
        
        return is_null($attributeData) ? null : (array)$attributeData;
    }
    
    /**
     * Extract the actual data from the array of JSON strings of MFA options.
     *
     * @param string[] $arrayOfJson An array of JSON strings.
     * @param string $accountName The name of the user account (for logging any
     *     errors).
     * @param LoggerInterface $logger The logger.
     * @return array[]
     */
    protected function getMfaOptionsFromJson($arrayOfJson, $accountName, $logger)
    {
        $mfaOptions = [];
        foreach ($arrayOfJson as $json) {
            $mfaOption = \json_decode($json, true);
            if ($mfaOption === null) {
                $exception = new \InvalidArgumentException(sprintf(
                    'Invalid JSON in mfaOptionsJson entry for %s: %s',
                    $accountName,
                    var_export($json, true)
                ));
                $logger->error($exception->getMessage());
                throw $exception;
            }
            $mfaOptions[] = $mfaOption;
        }
        return $mfaOptions;
    }
    
    protected function initComposerAutoloader()
    {
        $path = __DIR__ . '/../../../vendor/autoload.php';
        if (file_exists($path)) {
            require_once $path;
        }
    }
    
    protected function initLogger($config)
    {
        $loggerClass = $config['loggerClass'] ?? Psr3SamlLogger::class;
        $this->logger = new $loggerClass();
        if ( ! $this->logger instanceof LoggerInterface) {
            throw new Exception(sprintf(
                'The specified loggerClass (%s) does not implement '
                . '\\Psr\\Log\\LoggerInterface.',
                var_export($loggerClass, true)
            ), 1507139915);
        }
    }
    
    /**
     * Redirect the user to set up MFA.
     *
     * @param array $state
     * @param string $accountName
     * @param string $mfaSetupUrl
     * @param string $mfaSetupSession
     * @param int $expiryTimestamp The timestamp when the password will expire.
     */
    public function redirectToMfaSetup(
        &$state,
        $accountName,
        $mfaSetupUrl
    ) {
        /* Save state and redirect. */
        $state['accountName'] = $accountName;
        
        /* If state already has the MFA-setup URL, go straight there to avoid
         * an eternal loop between that and the IdP. Otherwise add the original
         * destination URL as a parameter.  */
        if (array_key_exists('saml:RelayState', $state)) {
            $relayState = $state['saml:RelayState'];
            
            /**
             * @TODO Make sure this doesn't match when the MFA setup URL is
             *       simply included as a query string parameter/value. In other
             *       words, make sure the user is really just going to the MFA
             *       setup website.
             */
            if (strpos($relayState, $mfaSetupUrl) !== false) {             
                // NOTE: This function call will never return.
                SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
                return;
            } else {
                $returnTo = sspmod_mfa_Utilities::getUrlFromRelayState($relayState);
                if ( ! empty($returnTo)) {                                 
                    $mfaSetupUrl .= '?returnTo=' . $returnTo;
                }
            }
        }
        
        $this->logger->warning(sprintf(
            'mfa: Sending user (%s) to set up MFA at %s',
            var_export($accountName, true),
            var_export($mfaSetupUrl, true)
        ));
        
        SimpleSAML_Utilities::redirect($mfaSetupUrl);
    }
    
    /**
     * Apply this AuthProc Filter.
     *
     * @param array &$state The current state.
     */
    public function process(&$state)
    {
        // Get the necessary info from the state data.
        $accountName = $this->getAttribute($this->accountNameAttr, $state);
        $promptForMfa = $this->getAttribute('promptForMfa', $state);
        
        if (strtolower($promptForMfa) !== 'no') {
            $mfaOptionsJson = $this->getAttributeAllValues('mfaOptionsJson', $state);
            if (empty($mfaOptionsJson)) {
                $this->redirectToMfaNeededMessage($state, $accountName, $this->mfaSetupUrl);
            } else {
                $this->redirectToMfaPrompt($state, $accountName, $mfaOptionsJson);
            }
        }
    }
    
    /**
     * Redirect the user to a page telling them they must set up MFA.
     *
     * @param array $state The state data.
     * @param string $accountName The name of the user account.
     * @param string[] $mfaOptionsJson The list of MFA options, each
     *     individually encoded as a JSON string.
     */
    protected function redirectToMfaNeededMessage(&$state, $accountName, $mfaSetupUrl)
    {
        assert('is_array($state)');
        
        $this->logger->info(sprintf(
            'mfa: Redirecting %s to must-set-up-MFA message.',
            var_export($accountName, true)
        ));
        
        /* Save state and redirect. */
        $state['accountName'] = $accountName;
        $state['mfaSetupUrl'] = $mfaSetupUrl;
        
        $stateId = SimpleSAML_Auth_State::saveState($state, self::STAGE_SENT_TO_MFA_NEEDED_MESSAGE);
        $url = SimpleSAML_Module::getModuleURL('mfa/must-set-up-mfa.php');
        
        SimpleSAML_Utilities::redirect($url, array('StateId' => $stateId));
    }
    
    /**
     * Redirect the user to the appropriate MFA-prompt page.
     *
     * @param array $state The state data.
     * @param string $accountName The name of the user account.
     * @param string[] $mfaOptionsJson The list of MFA options, each
     *     individually encoded as a JSON string.
     */
    protected function redirectToMfaPrompt(&$state, $accountName, $mfaOptionsJson)
    {
        assert('is_array($state)');
        
        $mfaOptions = $this->getMfaOptionsFromJson($mfaOptionsJson);
        $state['mfaOptions'] = $mfaOptions;
        
        $this->logger->info(sprintf(
            'mfa: Redirecting %s to MFA prompt.',
            var_export($accountName, true)
        ));
        
        /* Save state and redirect. */
        $state['accountName'] = $accountName;
        
        $id = SimpleSAML_Auth_State::saveState($state, self::STAGE_SENT_TO_MFA_PROMPT);
        $url = SimpleSAML_Module::getModuleURL('mfa/prompt-for-mfa.php');
        
        SimpleSAML_Utilities::redirect($url, array('StateId' => $id));
    }
}
