<?php

namespace AppBundle\Exception\User;

class PasswordNotValidException extends \Exception
{
    public function __construct(\Exception $previous = null)
    {
        parent::__construct('Password not valid', 280, $previous);
    }
}