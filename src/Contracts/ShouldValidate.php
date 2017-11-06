<?php

namespace Mugen\EloquentValidation\Contracts;

/**
 * Interface ShouldValidate
 * @package Mugen\EloquentValidation\Contracts
 */
interface ShouldValidate
{
    /**
     * @param string|null $event
     * @return ValidatorInterface
     */
    public function validator(string $event = null): ValidatorInterface;
}
