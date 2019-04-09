<?php
namespace Sil\SspMfa\Behat\fakes;

use InvalidArgumentException;
use Sil\Idp\IdBroker\Client\ServiceException;

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
     * @return array
     * @throws ServiceException
     */
    public function mfaVerify($id, $employeeId, $value)
    {
        if ($id === self::RATE_LIMITED_MFA_ID) {
            throw new ServiceException('Too many recent failures for this MFA', 0, 429);
        }

        if ($value !== self::CORRECT_VALUE) {
            throw new ServiceException('Incorrect code', 0, 400);
        }

        return [
            'id' => $id,
            'type' => 'backupcode',
            'label' => 'Printable Codes',
            'created_utc' => '2019-01-02T03:04:05Z',
            'data' => [
                'count' => 4,
            ],
        ];
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

    /**
     * Get a list of MFA configurations for given user
     * @param string $employee_id
     * @return array
     * @throws ServiceException
     */
    public function mfaList($employee_id)
    {
        return [
            [
                'id' => 1,
                'type' => 'backupcode',
                'label' => 'Printable Codes',
                'created_utc' => '2019-04-02T16:02:14Z',
                'last_used_utc' => '2019-04-01T00:00:00Z',
                'data' => [
                    'count' => 10
                ],
            ],
            [
                'id' => 2,
                'type' => 'totp',
                'label' => 'Smartphone App',
                'created_utc' => '2019-04-02T16:02:14Z',
                'last_used_utc' => '2019-04-01T00:00:00Z',
                'data' => [
                ],
            ],
        ];
    }
}
