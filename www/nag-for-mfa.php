<?php

use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_NAG);

// If the user has pressed the set-up-MFA button...
if (filter_has_var(INPUT_POST, 'setUpMfa')) {
    $mfaSetupUrl = $state['mfaSetupUrl'];

    // Tell the MFA-setup URL where the user is ultimately trying to go (if known).
    if (array_key_exists('saml:RelayState', $state)) {
        $returnTo = sspmod_expirychecker_Utilities::getUrlFromRelayState(
            $state['saml:RelayState']
        );
        if ( ! empty($returnTo)) {
            $mfaSetupUrl .= '?returnTo=' . $returnTo;
        }
    }

    SimpleSAML_Utilities::redirect($mfaSetupUrl);
    return;
} elseif (filter_has_var(INPUT_POST, 'continue')) {
    // The user has pressed the continue button.
    //unset($state['Attributes']['mfa']);
    SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:nag-for-mfa.php');
$t->data['learnMoreUrl'] = $state['mfaLearnMoreUrl'];
$t->show();

SimpleSAML_Logger::info(sprintf(
    'mfa: Encouraged Employee ID %s to set up MFA.',
    $state['employeeId']
));
