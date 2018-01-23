<?php

/**
 * This "controller" (per MVC) expects the state to contain, among other things,
 * the following:
 * - mfaSetupUrl
 * - newBackupCodes
 */

use Sil\SspMfa\LoggerFactory;
use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId') ?? null;
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_NEW_BACKUP_CODES_PAGE);
$logger = LoggerFactory::getAccordingToState($state);

// If the user pressed the continue button...
if (filter_has_var(INPUT_POST, 'continue')) {
    SimpleSAML_Auth_ProcessingChain::resumeProcessing($state);
    return;
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:new-backup-codes.php');
$t->data['mfaSetupUrl'] = $state['mfaSetupUrl'];
$t->data['newBackupCodes'] = $state['newBackupCodes'] ?? [];
$t->show();
