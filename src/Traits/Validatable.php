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
    protected $castMapping = [
        'int'        => 'integer',
        'integer'    => 'integer',
        'real'       => 'numeric',
        'float'      => 'numeric',
        'double'     => 'numeric',
        'string'     => 'string',
        'bool'       => 'boolean',
        'boolean'    => 'boolean',
        'object'     => 'string',
        'array'      => 'string',
        'json'       => 'string',
        'collection' => 'string',
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
    // protected $typeRules;

    /**
     * Logic validate rules
     * @var array
     */
    // protected $logicRules;

    /**
     * @var array
     */
    // protected $nullable;

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
        return $this->rules[$event] ?? $this->rules[$event] = $this->mergeRules(
                $this->castRules($event === 'creating'),
                $this->nullable(),
                $this->typeRules($event),
                $this->logicRules($event)
            );
    }

    /**
     * @param bool $withoutPrimary
     * @return array
     */
    protected function castRules(bool $withoutPrimary): array
    {
        $casts = $this->getCasts();

        if ($withoutPrimary)
            unset($casts[$this->getKeyName()]);

        foreach ($casts as &$cast)
            if (isset($this->castMapping[$cast]))
                $cast = (array)$this->castMapping[$cast];

        return $casts;
    }

    /**
     * @return array
     */
    protected function nullable(): array
    {
        if (!property_exists($this, 'nullable'))
            return [];

        return array_combine(
            $this->nullable,
            array_fill(0, count($this->nullable), 'nullable')
        );
    }

    /**
     * @param string $event
     * @return array
     */
    protected function typeRules(string $event): array
    {
        if (property_exists($this, 'typeRules') && is_array($this->typeRules))
            return $this->typeRules;

        return [];
    }

    /**
     * @param string $event
     * @return array
     */
    protected function logicRules(string $event): array
    {
        if (property_exists($this, 'logicRules') && is_array($this->logicRules))
            return $this->logicRules;

        return [];
    }

    /**
     * @param array ...$mergeRules
     * @return array
     */
    protected function mergeRules(...$mergeRules): array
    {
        $finalRules = [];

        foreach ($mergeRules as $mergeRule)
            if (is_array($mergeRule) && count($mergeRule))
                foreach ($mergeRule as $key => $rules) {
                    if (empty($rules))
                        continue;

                    if (is_string($rules))
                        $rules = explode('|', $rules);

                    $finalRules[$key] = array_merge($finalRules[$key] ?? [], $rules);
                }

        return $finalRules;
    }
}
