<?php

namespace App\Rules;

use App\Livewire\Forms\MainForm;
use Closure;
use Illuminate\Contracts\Validation\DataAwareRule;
use Illuminate\Contracts\Validation\ValidationRule;

class PhoneCountryPattern implements DataAwareRule, ValidationRule
{
    protected array $data = [];

    protected string $phoneCountryField;

    public function __construct($phoneCountryField)
    {
        $this->phoneCountryField = $phoneCountryField;
    }

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        $attr = $nestedKey = false;
        if (preg_match('/(.+)\.([0-9])+\.(.+)/', $attribute, $matches)) {
            $attr = $matches[1] ?? false;
            $nestedKey = $matches[2] ?? false;
        }

        if (!isset($this->data[$attr][$nestedKey][$this->phoneCountryField])) {
            throw new \OutOfBoundsException("Attribute '$this->phoneCountryField' doesn't present in $attr");
        }

        $countryId = $this->data[$attr][$nestedKey][$this->phoneCountryField];

        $patternDigitsNumber = substr_count(MainForm::$countryPhoneCodes[$countryId]['mask'], '9');

        if (strlen($value) != $patternDigitsNumber) {
            $fail('validation.phone_country_pattern')->translate();
        }
    }

    public function setData(array $data)
    {
        $this->data = $data;
    }
}
