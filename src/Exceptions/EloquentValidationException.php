<?php

namespace Mugen\EloquentValidation\Exceptions;

use Exception;

/**
 * Class EloquentValidationException
 * @package Mugen\EloquentValidation\Exceptions
 */
class EloquentValidationException extends Exception
{
    /**
     * @var array
     */
    protected $errors;

    /**
     * EloquentValidationException constructor.
     * @param array $errors
     */
    public function __construct(array $errors)
    {
        $this->errors = $errors;

        parent::__construct(trans('The data type written to the database is incorrect.'));
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }
}
