<?php

namespace App\Livewire;

use App\Enums\UserMaritalStatus;
use App\Livewire\Forms\MainForm;
use Livewire\Component;
use Livewire\WithFileUploads;

class CreateMainForm extends Component
{
    use WithFileUploads;

    public MainForm $form;

    public $isSuccess = false;

    public function save()
    {
        $this->form->store();

        $this->isSuccess = true;
    }
    public function render()
    {
        return view('livewire.create-main-form')->with([
            'maritalStatuses' => UserMaritalStatus::getLocalized(),
            'countryPhoneCodes' => collect(MainForm::$countryPhoneCodes),
        ]);
    }

    public function mount()
    {
        $this->form->userPhones[] = [
            'number' => '',
            'phone_country_id' => 1 // TODO: real default value
        ];
    }

    public function addUserPhone()
    {
        if (count($this->form->userPhones) >= 5) {
            $this->skipRender();
            return;
        }

        $this->form->userPhones[] = [
            'number' => '',
            'phone_country_id' => 1 // TODO: real default value
        ];
    }

    public function removeUserPhone($idx)
    {
        unset($this->form->userPhones[$idx]);
    }
}
