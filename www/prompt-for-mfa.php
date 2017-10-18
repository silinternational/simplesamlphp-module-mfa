<?php

use sspmod_mfa_Auth_Process_Mfa as Mfa;

$stateId = filter_input(INPUT_POST, 'StateId') ?? null;
$stateId = $stateId ?? filter_input(INPUT_GET, 'StateId');
if (empty($stateId)) {
    throw new SimpleSAML_Error_BadRequest('Missing required StateId query parameter.');
}

$state = SimpleSAML_Auth_State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_PROMPT);
$mfaOptions = $state['mfaOptions'] ?? [];

// If the user specified an MFA id, try to get that MFA option.
$mfaId = filter_input(INPUT_POST, 'mfaId');
$mfaId = $mfaId ?? filter_input(INPUT_GET, 'mfaId');

if (empty($mfaId)) {
    $mfaOption = Mfa::getMfaOptionToUse($mfaOptions);
    $mfaId = $mfaOption['id'];
} else {
    $mfaOption = Mfa::getMfaOptionById($mfaOptions, $mfaId);
}

// If the user has submitted their MFA value...
if (filter_has_var(INPUT_POST, 'submitMfa')) {
    $mfaSubmission = filter_input(INPUT_POST, 'mfaSubmission');
    if (substr($mfaSubmission, 0, 1) == '{') {
        $mfaSubmission = json_decode($mfaSubmission, true);
    }
    
    // NOTE: This will only return if validation fails.
    $errorMessage = Mfa::validateMfaSubmission(
        $mfaId,
        $state['employeeId'],
        $mfaSubmission,
        $state
    );
}

$globalConfig = SimpleSAML_Configuration::getInstance();

$mfaTemplateToUse = Mfa::getTemplateFor($mfaOption['type']);

$t = new SimpleSAML_XHTML_Template($globalConfig, $mfaTemplateToUse);
$t->data['formTarget'] = SimpleSAML_Module::getModuleURL('mfa/prompt-for-mfa.php');
$t->data['formData'] = ['StateId' => $stateId, 'mfaId' => $mfaId];
$t->data['errorMessage'] = $errorMessage ?? null;
$t->data['mfaOption'] = $mfaOption;
$t->data['mfaOptions'] = $mfaOptions;
$t->data['stateId'] = $stateId;
$t->show();

SimpleSAML_Logger::info(sprintf(
    'mfa: Prompted Employee ID %s for MFA.',
    $state['employeeId']
));
