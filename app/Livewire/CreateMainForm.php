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
        //$this->validate();

        return $this->redirect('/');
    }
    public function render()
    {
        return view('livewire.create-main-form')->with([
            'maritalStatuses' => UserMaritalStatus::getLocalized(),
        ]);
    }

    public function addPhone()
    {
        if (count($this->phoneInputCounter) >= 5) {
            $this->skipRender();
            return;
        }

        $this->currentPhoneCounter++;
        $this->phoneInputCounter[$this->currentPhoneCounter] = $this->currentPhoneCounter;
    }

    public function removePhone($idx)
    {
        unset($this->phoneInputCounter[$idx]);
    }
}
