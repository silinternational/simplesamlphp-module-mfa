<?php
namespace Sil\SspMfa;

use InvalidArgumentException;

/**
 * Simple assertion class intended for use in production code, not merely tests.
 * Inspired in part by PHPUnit's assertions and Webmozart\Assert.
 */
class Assert
{
    /**
     * Assert that the named class exists.
     *
     * @param string $className The name of the class in question.
     * @throws InvalidArgumentException
     */
    public static function classExists(string $className)
    {
        if (! class_exists($className)) {
            throw new InvalidArgumentException(sprintf(
                'The specified class (%s) does not exist.',
                self::describe($className)
            ));
        }
    }
    
    /**
     * Describe the given value. If it's an object, return the class name,
     * otherwise just get a string representation of the value.
     *
     * @param mixed $value The value in question.
     * @return string
     */
    protected static function describe($value)
    {
        return is_object($value) ? get_class($value) : var_export($value, true);
    }
    
    /**
     * Assert that the given object is an instance of the specified class.
     *
     * @param mixed $object The object in question.
     * @param string $className The name/classpath of the class in question.
     * @throws InvalidArgumentException
     */
    public static function isInstanceOf($object, string $className)
    {
        if (! ($object instanceof $className)) {
            throw new InvalidArgumentException(sprintf(
                'The given object (%s) is not an instance of %s.',
                self::describe($object),
                self::describe($className)
            ));
        }
    }
}
