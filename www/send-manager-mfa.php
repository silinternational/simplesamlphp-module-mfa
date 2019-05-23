<?php

/**
 * This "controller" (per MVC) must be called with the following query string
 * parameters:
 * - StateId
 */

use Sil\SspMfa\LoggerFactory;
use SimpleSAML\Auth\State;
use SimpleSAML\Configuration;
use SimpleSAML\Error\BadRequest;
use SimpleSAML\Utils\HTTP;
use SimpleSAML\XHTML\Template;
use SimpleSAML\Module\mfa\Auth\Process\Mfa;

$stateId = filter_input(INPUT_GET, 'StateId');
if (empty($stateId)) {
    throw new BadRequest('Missing required StateId query parameter.');
}

$state = State::loadState($stateId, Mfa::STAGE_SENT_TO_MFA_PROMPT);

$logger = LoggerFactory::getAccordingToState($state);

if (filter_has_var(INPUT_POST, 'send')) {
    Mfa::sendManagerCode($state, $logger);
} elseif (filter_has_var(INPUT_POST, 'cancel')) {
    $moduleUrl = SimpleSAML\Module::getModuleURL('mfa/prompt-for-mfa.php', [
        'StateId' => $stateId,
    ]);
    HTTP::redirectTrustedURL($moduleUrl);
}

$globalConfig = Configuration::getInstance();

$t = new Template($globalConfig, 'mfa:send-manager-mfa.php');
$t->data['stateId'] = $stateId;
$t->data['managerEmail'] = $state['managerEmail'];
$t->show();

$logger->info(json_encode([
    'event' => 'offer to send manager code',
    'employeeId' => $state['employeeId'],
]));
