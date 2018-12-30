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
        $message = sprintf(
            'Unable to resolve parameter %s$%s (%s)',
            $parameter->isPassedByReference() ? '&' : '',
            $parameter->getName(),
            $this->formatClassInfo($parameter)
        );
        parent::__construct($message, 0, $previous);
    }

    private function formatClassInfo(\ReflectionParameter $parameter): string
    {
        return $parameter->getType();
    }
}
