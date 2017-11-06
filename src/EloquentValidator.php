<?php

namespace Mugen\EloquentValidation;

use Illuminate\Validation\Factory as ValidatorFactory;
use Illuminate\Validation\Validator;
use Mugen\EloquentValidation\Contracts\ValidatorInterface;
use Mugen\EloquentValidation\Exceptions\EloquentValidationException;

/**
 * Class EloquentValidator
 * @package Mugen\EloquentValidation
 */
class EloquentValidator implements ValidatorInterface
{
    /**
     * @var array
     */
    protected $rules = [];

    /**
     * @var array
     */
    protected $attributes = [];

    /**
     * @var Validator
     */
    protected $validator;

    /**
     * EloquentValidator constructor.
     * @param array $attributes
     * @param array $rules
     */
    public function __construct(array $attributes = [], array $rules = [])
    {
        $this->attributes = $attributes;

        $this->rules = $rules;
    }

    /**
     * @return Validator
     */
    protected function makeValidator(): Validator
    {
        return app(ValidatorFactory::class)->make($this->attributes, $this->rules);
    }

    /**
     * @return Validator
     */
    protected function validator(): Validator
    {
        return $this->validator ?? $this->validator = $this->makeValidator();
    }

    /**
     * @return bool
     * @throws EloquentValidationException
     */
    public function validate(): bool
    {
        if ($this->validator()->fails())
            throw new EloquentValidationException($this->validator()->errors()->all());

        return true;
    }

    /**
     * @param array $rules
     * @return EloquentValidator
     */
    public function setRules(array $rules): self
    {
        $this->rules = $rules;

        return $this;
    }

    /**
     * @param string|null $attribute
     * @return array
     */
    public function getRules(string $attribute = null): array
    {
        if (!empty($attribute) && isset($this->rules[$attribute]))
            return $this->rules[$attribute];

        return $this->rules;
    }

    /**
     * @param array $attributes
     * @return EloquentValidator
     */
    public function setAttributes(array $attributes): self
    {
        $this->attributes = $attributes;

        return $this;
    }

    /**
     * @param array $attributes
     * @return EloquentValidator
     */
    public function with(array $attributes): self
    {
        return $this->setAttributes($attributes);
    }
}
