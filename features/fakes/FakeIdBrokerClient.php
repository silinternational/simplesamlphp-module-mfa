<?php
namespace Sil\SspMfa\Behat\fakes;

/**
 * FAKE IdP ID Broker API client, used for testing.
 */
class FakeIdBrokerClient
{
    /**
     * The list of (FAKE) acceptable MFA submissions, indexed by the
     * MFA option's ID.
     *
     * @var array<int,string>
     */
    private $fakeMfaAnswers = [
        1 => '111111',
        2 => '222222',
        7 => '777777',
    ];
    
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
        $fakeMfaAnswer = $this->fakeMfaAnswers[$id] ?? null;
        
        if ($fakeMfaAnswer === null) {
            return false;
        }
        
        return ($fakeMfaAnswer === $value);
    }
}
