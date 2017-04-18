<?php

namespace AppBundle\Exception\User;

class PasswordNotMatchesException extends \Exception
{
    const CODE = 100;
    const MESSAGE = 'Password not matches';

    public function __construct(\Exception $previous = null)
    {
        parent::__construct(self::MESSAGE, self::CODE, $previous);
    }
}