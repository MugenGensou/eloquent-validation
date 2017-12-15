<?php

namespace Mugen\EloquentValidation\Exceptions;

use Exception;
use Illuminate\Contracts\Support\Responsable;
use Response;

/**
 * Class EloquentValidationException
 * @package Mugen\EloquentValidation\Exceptions
 */
class EloquentValidationException extends Exception implements Responsable
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

        parent::__construct(__('The data type written to the database is incorrect.'));
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function toResponse($request)
    {
        return Response::json([
            'message' => $this->getMessage(),
            'errors'  => $this->getErrors(),
        ], 500);
    }
}
