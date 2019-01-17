<?php
namespace Sil\SspMfa\Behat\fakes;

use InvalidArgumentException;
use Sil\Idp\IdBroker\Client\exceptions\MfaRateLimitException;

/**
 * FAKE IdP ID Broker API client, used for testing.
 */
class FakeIdBrokerClient
{
    const CORRECT_VALUE = '111111';
    const INCORRECT_VALUE = '999999';
    
    const RATE_LIMITED_MFA_ID = '987';
    
    /**
     * Constructor.
     *
     * @param string $baseUri - The base of the API's URL.
     *     Example: 'https://api.example.com/'.
     * @param string $accessToken - Your authorization access (bearer) token.
     * @param array $config - Any other configuration settings.
     */
    public function __construct(
        string $baseUri,
        string $accessToken,
        array $config = []
    ) {
        // No-op
    }

    /**
     * Verify an MFA value
     * @param int $id
     * @param string $value
     * @return bool
     */
    public function mfaVerify($id, $employeeId, $value)
    {
        if ($id === self::RATE_LIMITED_MFA_ID) {
            throw new MfaRateLimitException('Too many recent failures for this MFA');
        }
        return ($value === self::CORRECT_VALUE);
    }
    
    /**
     * Create a new MFA configuration
     * @param string $employee_id
     * @param string $type
     * @param string $label
     * @return array|null
     * @throws Exception
     */
    public function mfaCreate($employee_id, $type, $label = null)
    {
        if (empty($employee_id)) {
            throw new InvalidArgumentException('employee_id is required');
        }
        
        if ($type === 'backupcode') {
            return [
                "id" => 1234,
                "data" => [
                    "00000000",
                    "11111111",
                    "22222222",
                    "33333333",
                    "44444444",
                    "55555555",
                    "66666666",
                    "77777777",
                    "88888888",
                    "99999999"
                ],
            ];
        }

        if ($type === 'manager') {
            return [
                "id" => 5678,
                "data" => [],
            ];
        }

        throw new InvalidArgumentException(sprintf(
            'This Fake ID Broker class does not support creating %s MFA records.',
            $type
        ));
    }
}
