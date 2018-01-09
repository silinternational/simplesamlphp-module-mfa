<?php
namespace Sil\SspMfa;

use InvalidArgumentException;
use Psr\Log\AbstractLogger;
use Psr\Log\LoggerInterface;
use Psr\Log\NullLogger;
use Sil\SspMfa\Assert;

class AuthProcLogger extends AbstractLogger
{
    /** @var LoggerInterface */
    protected $internalLogger;
    
    /**
     * Constructor.
     *
     * @param string $loggerClass
     * @throws InvalidArgumentException
     */
    public function __construct($loggerClass)
    {
        Assert::classExists($loggerClass);
        
        $this->internalLogger = new $loggerClass();
        
        Assert::isInstanceOf($this->internalLogger, LoggerInterface::class);
    }
    
    /**
     * Create a new instance of this class based on the config data in the
     * given state array. If no loggerClass config is provided, a NullLogger
     * will be used as the internal logger.
     *
     * @param array $state The simpleSAMLphp state provided to an auth. proc.
     * @return \Sil\SspMfa\AuthProcLogger
     */
    public static function fromState($state)
    {
        return new AuthProcLogger($state['loggerClass'] ?? NullLogger::class);
    }
    
    /**
     * {@inheritdoc}
     */
    public function log($level, $message, array $context = [])
    {
        $this->internalLogger->log($level, $message, $context);
    }
}
