<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class MainForm extends Form
{
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

    public function rules()
    {
        return [
            'surname' => [
                'required',
                'max:255',
                'min:255',
            ],
            'name' => [
                'required',
                'max:255',
            ],
            'middle_name' => [
                'max:255',
            ],
            'email' => [
                'email:rfc',
                'required_without:phones',
                'sometimes'
            ],
            'phones' => [
                'required_without:email',
                'array',
            ],
            'phones.*' => [
                'min:255',
            ],
        ];
    }


}
