<?php

namespace Mugen\EloquentValidation\Traits;

use Mugen\EloquentValidation\Contracts\ValidatorInterface;
use Mugen\EloquentValidation\EloquentValidator;

/**
 * Trait Validatable
 * @package Mugen\EloquentValidation\Traits
 */
trait Validatable
{
    /**
     * Trans model casts to rules
     * @var array
     */
    protected $castRules = [
        'int'        => 'integer',
        'integer'    => 'integer',
        'real'       => 'numeric',
        'float'      => 'numeric',
        'double'     => 'numeric',
        'string'     => 'string',
        'bool'       => 'boolean',
        'boolean'    => 'boolean',
        'object'     => 'array',
        'array'      => 'array',
        'json'       => 'array',
        'collection' => 'array',
        'date'       => 'date',
        'datetime'   => 'date',
        'timestamp'  => [
            'integer',
            'between:1,10',
        ],
    ];

    /**
     * Type validate rules
     * @var array
     */
    protected $typeRules = [];

    /**
     * Logic validate rules
     * @var array
     */
    protected $logicRules = [];

    /**
     * @var array
     */
    protected $rules;

    /**
     * @param string|null $event
     * @return ValidatorInterface
     */
    public function validator(string $event = null): ValidatorInterface
    {
        return new EloquentValidator($this->getAttributes(), $this->rules($event));
    }

    protected function rules(string $event = null): array
    {
        if (empty($this->rules)) {
            $casts = $this->getCasts();

            if ($event === 'creating')
                unset($casts[$this->getKeyName()]);

            foreach ($casts as &$cast)
                if (isset($this->castRules[$cast]))
                    $cast = (array)$this->castRules[$cast];

            $this->mergeRules($casts);

            $this->mergeRules($this->typeRules);

            $this->mergeRules($this->logicRules);
        }

        return $this->rules;
    }

    protected function mergeRules(array $mergeRules = []): void
    {
        foreach ($mergeRules as $key => $rules) {
            if (is_string($rules))
                $rules = explode('|', $rules);

            $this->rules[$key] = array_merge($this->rules[$key] ?? [], $rules);
        }
    }
}
