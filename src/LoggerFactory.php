<?php
namespace Sil\SspMfa;

use InvalidArgumentException;
use Psr\Log\LoggerInterface;
use Psr\Log\Psr3SamlLogger;
use Sil\SspMfa\Assert;

class LoggerFactory
{
    /**
     * Get a logger of the specified class.
     *
     * @param string $loggerClass The kind of logger to get (as a class path).
     * @return LoggerInterface
     *
     * @throws InvalidArgumentException
     */
    public static function get($loggerClass)
    {
        Assert::classExists($loggerClass);
        $logger = new $loggerClass();
        Assert::isInstanceOf($logger, LoggerInterface::class);
        return $logger;
    }
    
    /**
     * Get a logger of the type specified in the state's loggerClass config. If
     * no loggerClass is present, a logger will be returned that works within
     * simpleSAMLphp.
     *
     * @param array $state The simpleSAMLphp state provided to an auth. proc.
     * @return LoggerInterface
     *
     * @throws InvalidArgumentException
     */
    public static function getAccordingToState($state)
    {
        return self::get($state['loggerClass'] ?? Psr3SamlLogger::class);
    }
}
