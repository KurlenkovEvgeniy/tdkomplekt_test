<?php

namespace App\Livewire;

use App\Enums\UserMaritalStatus;
use App\Livewire\Forms\MainForm;
use Livewire\Component;

class CreateMainForm extends Component
{

    public MainForm $form;

    public $phoneInputCounter = [1];
    public $currentPhoneCounter = 1;

    public function save()
    {
        $this->form->store();

        return $this->redirect('/');
    }
    public function render()
    {
        return view('livewire.create-main-form')->with([
            'maritalStatuses' => UserMaritalStatus::getLocalized(),
            'countryPhoneCodes' => collect(MainForm::$countryPhoneCodes),
        ]);
    }

    public function addPhone()
    {
        if (count($this->phoneInputCounter) >= 5) {
            $this->skipRender();
            return;
        }

        // TODO: use $this->form->phones instead of $this->phoneInputCounter
        $this->currentPhoneCounter++;
        $this->phoneInputCounter[$this->currentPhoneCounter] = $this->currentPhoneCounter;
        $this->form->phonesCountry[$this->currentPhoneCounter] = '(99) 999-99-99';
    }

    public function removePhone($idx)
    {
        unset($this->phoneInputCounter[$idx]);
        unset($this->form->phones[$idx]);
        unset($this->form->phonesCountry[$idx]);
    }
}
