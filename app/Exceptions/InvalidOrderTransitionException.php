<?php

namespace App\Exceptions;

class InvalidOrderTransitionException extends BusinessException
{
    public function __construct(string $message = 'Transition de statut non autorisee.')
    {
        parent::__construct($message, 422);
    }
}
