<?php

use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_LOW_ON_BACKUP_CODES_NAG);

if (filter_has_var(INPUT_POST, 'setUpMfa')) {
    // The user pressed the button to create more backup codes.
    Mfa::redirectToMfaSetup($state);
    return;
} elseif (filter_has_var(INPUT_POST, 'continue')) {
    // The user pressed the remind-me-later button.
    SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:low-on-backup-codes.php');
$t->data['numBackupCodesRemaining'] = $state['numBackupCodesRemaining'];
$t->show();

SimpleSAML_Logger::info(sprintf(
    'mfa: Told Employee ID %s they are low on backup codes.',
    $state['employeeId']
));
