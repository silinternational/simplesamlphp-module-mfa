<?php

/**
 * This "controller" (per MVC) must be called with the following query string
 * parameters:
 * - StateId
 */

use Sil\SspMfa\LoggerFactory;
use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_GET, 'StateId');
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_PROMPT);

$logger = LoggerFactory::getAccordingToState($state);

if (filter_has_var(INPUT_POST, 'send')) {
    Mfa::sendManagerCode($state, $logger);
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:send-manager-mfa.php');
$t->data['errorMessage'] = $errorMessage ?? null;
$t->data['mfaOption'] = $mfaOption;
$t->data['mfaOptions'] = $mfaOptions;
$t->data['stateId'] = $stateId;
$t->data['managerEmail'] = $state['managerEmail'];
$t->show();

$logger->info(json_encode([
    'event' => 'Send manager code',
    'employeeId' => $state['employeeId'],
    'mfaType' => $mfaOption['type'],
]));
