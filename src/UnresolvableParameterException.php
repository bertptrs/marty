<?php

namespace marty;

/**
 * Thrown when parameter resolution fails and a parameter cannot be resolved.
 *
 * The message will provide information more information on what went wrong.
 */
class UnresolvableParameterException extends \InvalidArgumentException
{
    public function __construct(\ReflectionParameter $parameter, \Throwable $previous = null)
    {
        $message = 'Unable to resolve parameter ';
        if ($parameter->isPassedByReference()) {
            $message .= '&';
        }
        $message .= '$' . $parameter->getName();

        if ($parameter->getClass() != null) {
            $message .= sprintf(' (%s)', $parameter->getClass()->getName());
        }

        parent::__construct($message, 0, $previous);
    }
}
