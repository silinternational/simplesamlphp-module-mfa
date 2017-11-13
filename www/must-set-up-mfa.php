<?php

use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_NEEDED_MESSAGE);

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
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:must-set-up-mfa.php');
$t->show();

SimpleSAML_Logger::info(sprintf(
    'mfa: Told Employee ID %s they they must set up MFA.',
    $state['employeeId']
));
