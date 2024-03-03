<?php

namespace App\Livewire\Forms;

use App\Enums\UserMaritalStatus;
use App\Models\User;
use App\Rules\PhoneCountryPattern;
use App\Rules\RequiredOneOfArrayWithout;
use App\Rules\RequiredWithoutArray;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\File;
use Livewire\Attributes\Validate;
use Livewire\Form;

class MainForm extends Form
{
    // stub for country codes
    public static array $countryPhoneCodes = [
        1 => [ 'id' => 1, 'name' => 'Беларусь', 'code' => '+375', 'mask' => '(99) 999-99-99' ],
        2 => [ 'id' => 2, 'name' => 'Россия', 'code' => '+7', 'mask' => '(999) 999-99-99' ],
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
    public $userPhones = [];
    #[Validate]
    public $about = '';
    #[Validate]
    public $files = [];
    #[Validate]
    public string $marital_status = 'none';
    #[Validate]
    public string $birthday;
    #[Validate]
    public $rulesAccepted;

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
                'nullable',
                'max:255',
            ],
            'email' => [
                new RequiredWithoutArray('userPhones.*.number'),
                'nullable',
                'email:rfc',
            ],
            'userPhones' => [
                'array',
                'min:1',
                'max:5',
            ],
            'userPhones.*.number' => [
                new RequiredOneOfArrayWithout('email'),
                new PhoneCountryPattern('phone_country_id'),
                'nullable',
                'numeric',
                'distinct',
            ],
            'userPhones.*.phone_country_id' => [
                'required',
                Rule::in(array_keys(MainForm::$countryPhoneCodes)),
            ],
            'birthday' => [
                'required',
                'date_format:Y-m-d',
            ],
            'about' => [
                'nullable',
                'max:1000',
            ],
            'marital_status' => [
                'nullable',
                Rule::in(UserMaritalStatus::getArrayValues()),
            ],
            'files' => [
                'array',
                'max:5',
            ],
            'files.*' => [
                'file',
                File::types(['jpeg', 'png', 'pdf'])
                    ->max('5mb'),
            ],
            'rulesAccepted' => [
                'accepted',
            ],
        ];
    }

    protected function prepareForValidation($attributes)
    {
        foreach ($attributes['userPhones'] as $keyPhone => $phone) {
            $attributes['userPhones'][$keyPhone]['number'] = preg_replace('/[^0-9]/', '', $phone['number']);
        }
        return $attributes;
    }

    public function validationAttributes()
    {
        return [
            'userPhones.*.number' => Lang::get("validation.attributes.phone"),
            'files.*' => Lang::get("validation.attributes.files"),
        ];
    }

    public function store()
    {
        $res = $this->validate();

        $newUser = User::create($res);

        if (!empty($res['userPhones']))
            $newUser->phones()->createMany($res['userPhones']);

        if (!empty($res['files'])) {
            $filesToCreate = [];
            foreach ($this->files as $file) {
                $path = $file->store('user-files');
                if ($path) {
                    $filesToCreate[] = [
                        'original_name' => $file->getClientOriginalName(),
                        'path' => $path,
                        'mime_type' => $file->getMimeType(),
                        'size' => $file->getSize(),
                        'collection_name' => 'user_files',
                    ];
                }

            }
            $newUser->files()->createMany($filesToCreate);
        }

    }


}
