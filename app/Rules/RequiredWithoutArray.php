<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

// Check attribute is required if any/all of specified array attributes has not at least one filled item
class RequiredWithoutArray implements DataAwareRule, ValidatorAwareRule, ValidationRule
{
    protected array $data = [];

    protected Validator $validator;

    protected array $attributes;

    protected string $isAll;

    /**
     * Indicates whether the rule should be implicit.
     *
     * @var bool
     */
    public $implicit = true;

    public function __construct($attributes, $isAll = '')
    {
        if (is_string($attributes)) $attributes = [$attributes];
        $this->attributes = $attributes;
        $this->isAll = $isAll;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $translatedAttrs = [];
        foreach ($this->attributes as $attr) {
            $translatedAttrs[] = Lang::get("validation.attributes.$attr");
        }

        $fullResult = false;
        foreach ($this->attributes as $requiredAttribute) {
            if (!isset($this->data[$requiredAttribute])) {
                throw new \OutOfBoundsException("Attribute '$requiredAttribute' doesn't present in data");
            }
            if (!is_array($this->data[$requiredAttribute])) {
                throw new \UnexpectedValueException("Attribute '$requiredAttribute' is not an array");
            }

            $result = false;
            foreach ($this->data[$requiredAttribute] as $keyData => $datum) {
                $result = $this->validator->validateRequired("$requiredAttribute.$keyData", $datum);
                // Check only the first encountered required array item, the rest validation is rest upon attribute's rules
                if ($result) break;
            }

            // we don't need to check all the provided attributes
            if ($this->isAll !== 'all' && !$result && !$this->validator->validateRequired($attribute, $value)) {
                $fail('validation.required_without_array')->translate(['values' => implode(',', $translatedAttrs)]);
            }

            $fullResult = $fullResult || $result;
        }

        if ($this->isAll === 'all' && !$fullResult && !$this->validator->validateRequired($attribute, $value)) {
            $fail('validation.required_without_array_all')->translate(['values' => implode(',', $translatedAttrs)]);
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }

    public function setValidator(Validator $validator)
    {
        $this->validator = $validator;
    }
}
