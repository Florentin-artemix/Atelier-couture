<?php

namespace App\Exceptions;

class InsufficientMeasurementsException extends BusinessException
{
    public function __construct(string $message = 'Les mesures obligatoires ne sont pas toutes renseignees.')
    {
        parent::__construct($message, 422);
    }
}
