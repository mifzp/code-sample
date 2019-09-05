<?php
namespace sample2\exceptions;

/**
 * InvalidCallException represents an exception caused by calling a method in a wrong way.
 *
 * Class InvalidCallException
 * @package sample2\exceptions
 */
class InvalidCallException extends \yii\base\InvalidCallException
{
    /**
     * @return string the user-friendly name of this exception
     */
    public function getName()
    {
        return 'Invalid method call';
    }
}
