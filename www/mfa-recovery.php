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

$mfaOptions = $state['mfaOptions'] ?? [];
$mfaOption = Mfa::getManagerMfa($mfaOptions);

// If the user has a manager MFA option and has submitted the code in the form ...
if ($mfaOption !== null && filter_has_var(INPUT_POST, 'submitMfa')) {
    $mfaId = $mfaOption['id'];
    $mfaSubmission = filter_input(INPUT_POST, 'mfaSubmission');
    if (substr($mfaSubmission, 0, 1) == '{') {
        $mfaSubmission = json_decode($mfaSubmission, true);
    }

    $rememberMe = false;

    // NOTE: This will only return if validation fails.
    $errorMessage = Mfa::validateMfaSubmission(
        $mfaId,
        $state['employeeId'],
        $mfaSubmission,
        $state,
        $rememberMe,
        $logger,
        $mfaOption['type']
    );

    $logger->warning(json_encode([
        'event' => 'MFA validation result: failed',
        'employeeId' => $state['employeeId'],
        'mfaType' => $mfaOption['type'],
        'error' => $errorMessage,
    ]));
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$t = new SimpleSAML_XHTML_Template($globalConfig, 'mfa:prompt-for-mfa-manager.php');
$t->data['stateId'] = $stateId;
$t->data['mfaOptions'] = $mfaOptions;
$t->show();

$logger->info(json_encode([
    'event' => 'Presented user with mfa recovery options',
    'employeeId' => $state['employeeId'],
]));
