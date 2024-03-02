<?php

namespace App\Rules;

use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Contracts\Validation\ValidatorAwareRule;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Validator;

// Check attribute's array item is required if any other array items not filled and any/all specified attributes not filled
class RequiredOneOfArrayWithout implements DataAwareRule, ValidatorAwareRule, ValidationRule
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

            $result = $this->validator->validateRequired($requiredAttribute, $this->data[$requiredAttribute]);

            $fullResult = $fullResult || $result;

            // we don't need to check all the provided attributes
            if ($this->isAll !== 'all' && !$result) {
                break;
            }
        }

        if (!$fullResult) {
            $clearAttr = preg_replace('/\.[0-9]+$/', '', $attribute);
            if (!is_array($this->data[$clearAttr])) {
                throw new \UnexpectedValueException("Attribute '$clearAttr' is not an array");
            }

            foreach ($this->data[$clearAttr] as $keyData => $datum) {
                if ($this->validator->validateRequired("$clearAttr.$keyData", $datum)) {
                    return;
                }
            }
            if ($this->isAll === 'all') {
                $fail('validation.required_one_of_array_without_all')->translate(['values' => implode(',', $translatedAttrs)]);
            }
            else {
                $fail('validation.required_one_of_array_without')->translate(['values' => implode(',', $translatedAttrs)]);
            }
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
