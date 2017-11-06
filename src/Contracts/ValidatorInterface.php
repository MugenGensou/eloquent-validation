<?php

namespace Mugen\EloquentValidation\Contracts;

/**
 * Interface ValidatorInterface
 * @package Mugen\EloquentValidation\Contracts
 */
interface ValidatorInterface
{
    /**
     * @return bool
     */
    public function validate(): bool;
}