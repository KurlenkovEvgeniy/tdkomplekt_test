<?php

namespace App\Livewire\Forms;

use App\Rules\RequiredOneOfArrayWithout;
use App\Rules\RequiredWithoutArray;
use Illuminate\Support\Facades\Lang;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MainForm extends Form
{
    // stub for country codes
    public static array $countryPhoneCodes = [
        [ 'id' => 1, 'name' => 'Беларусь', 'code' => '+375', 'mask' => '(99) 999-99-99' ],
        [ 'id' => 2, 'name' => 'Россия', 'code' => '+7', 'mask' => '(999) 999-99-99' ],
    ];
    #[Validate]
    public $surname = '';
    #[Validate]
    public $name = '';
    #[Validate]
    public $middle_name = '';
    #[Validate]
    public $email = '';
    #[Validate]
    public $phones = [];

    // TODO: default code from $countryPhoneCodes
    #[Validate]
    public $phonesCountry = [1 => '(99) 999-99-99'];

    public function rules()
    {
        return [
            'surname' => [
                'required',
                'max:255',
            ],
            'name' => [
                'required',
                'max:255',
            ],
            'middle_name' => [
                'max:255',
            ],
            'email' => [
                new RequiredWithoutArray('phones'),
                'nullable',
                'email:rfc',
            ],
            'phones' => [
                'array',
                'min:1',
                'max:5',
            ],
            'phones.*' => [
                // TODO: create rule to check phone length against country phone code pattern
                new RequiredOneOfArrayWithout('email'),
                'nullable',
                'numeric',
                'distinct',
            ],
        ];
    }

    protected function prepareForValidation($attributes)
    {
        foreach ($attributes['phones'] ?? [] as $keyPhone => $phone) {
            $attributes['phones'][$keyPhone] = preg_replace('/[^0-9]/', '', $phone);
        }
        return $attributes;
    }

    public function validationAttributes()
    {
        return [
            'phones.*' => Lang::get("validation.attributes.phone"),
        ];
    }

    public function store()
    {
        $this->validate();

        //Post::create($this->only(['title', 'content']));
    }


}
