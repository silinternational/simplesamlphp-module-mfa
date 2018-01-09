<?php

use Sil\SspMfa\AuthProcLogger;
use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_OUT_OF_BACKUP_CODES_MESSAGE);
$logger = AuthProcLogger::fromState($state);
$hasOtherMfaOptions = Mfa::hasMfaOptionsOtherThan('backupcode', $state);

if (filter_has_var(INPUT_POST, 'setUpMfa')) {
    // The user pressed the button to create more backup codes.
    Mfa::redirectToMfaSetup($state);
    return;
} elseif (filter_has_var(INPUT_POST, 'continue') && $hasOtherMfaOptions) {
    // The user pressed the remind-me-later button.
    SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:out-of-backup-codes.php');
$t->data['hasOtherMfaOptions'] = $hasOtherMfaOptions;
$t->show();

$logger->info(sprintf(
    'mfa: Told Employee ID %s they are out of backup codes%s.',
    $state['employeeId'],
    $hasOtherMfaOptions ? '' : ' and must set up more'
));
