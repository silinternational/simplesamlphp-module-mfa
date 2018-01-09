<?php

use Sil\SspMfa\AuthProcLogger;
use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_NEEDED_MESSAGE);
$logger = AuthProcLogger::fromState($state);

// If the user has pressed the set-up-MFA button...
if (filter_has_var(INPUT_POST, 'setUpMfa')) {
    Mfa::redirectToMfaSetup($state);
    return;
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:must-set-up-mfa.php');
$t->data['learnMoreUrl'] = $state['mfaLearnMoreUrl'];
$t->show();

$logger->info(sprintf(
    'mfa: Told Employee ID %s they they must set up MFA.',
    $state['employeeId']
));
