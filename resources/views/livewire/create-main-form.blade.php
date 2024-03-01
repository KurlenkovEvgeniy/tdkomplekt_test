<div class="container d-flex flex-column flex-grow-1 justify-content-center my-3">
    <div class="row">
        <form class="row g-3 main-form shadow rounded-3 p-3 col-lg-6 mx-auto" wire:submit="save">
            @csrf
            <div class="col-sm-4">
                <label for="main-form-surname" class="form-label required">{{ __('Фамилия') }}</label>
                <input id="main-form-surname" wire:model.blur="form.surname" class="form-control" type="text" name="surname" placeholder="{{ __('Иванов') }}" value="">
                @error('form.surname') <span class="error">{{ $message }}</span> @enderror
            </div>
            <div class="col-sm-4">
                <label for="main-form-name" class="form-label required">{{ __('Имя') }}</label>
                <input id="main-form-name" wire:model="form.name" class="form-control" type="text" name="name" placeholder="{{ __('Иван') }}" value="">
            </div>
            <div class="col-sm-4">
                <label for="main-form-middle-name" class="form-label">{{ __('Отчество') }}</label>
                <input id="main-form-middle-name" wire:model="form.middle_name" class="form-control" type="text" name="middle_name" placeholder="{{ __('Иванович') }}" value="">
            </div>
            <div>
                <label for="main-form-email" class="form-label">{{ __('Email') }}</label>
                <input id="main-form-email" wire:model.blur="form.email" class="form-control" type="email" name="email" placeholder="{{ __('example@site.by') }}" value="">
                <div>
                    @error('form.email') <span class="error">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="">
                <label for="main-form-phone" class="form-label">{{ __('Телефоны') }} <span class="text-muted">(макс. 5)</span></label>
                @foreach($phoneInputCounter as $phoneInput)
                <div class="input-group main-form-phones-group {{ $phoneInput!=1?'mt-2':'' }}"
                     x-data="{
                        currentMask: '(99) 999-99-99',
                        {{--phoneCountryMasks: [
                            { id: 1, name: 'Беларусь', code: '+375', mask: '(99) 999-99-99' },
                            { id: 2, name: 'Россия', code: '+7', mask: '(999) 999-99-99' }
                        ]--}}
                     }"
                >
                    <div class="input-group-text">
                        <select x-model="currentMask" name="phones_country[]" class="form-select border-0 py-0" aria-label="Выбор страны">
                            <option value="(99) 999-99-99">+375</option>
                            <option value="(999) 999-99-99">+7</option>
                            {{--<template x-for="phoneCountryMask in phoneCountryMasks">
                                <option :value="phoneCountryMask.mask" x-text="phoneCountryMask.code"></option>
                            </template>--}}
                        </select>
                    </div>
                    <input id="main-form-phone" x-mask:dynamic="currentMask" class="form-control" type="tel" name="phones[]" value="" wire:model.blur="form.phones.{{ $phoneInput }}">
                    @php
                        $_clickAction = '';
                        if ($phoneInput==1 && count($phoneInputCounter) < 5) $_clickAction = 'addPhone';
                        elseif ($phoneInput!=1) $_clickAction = "removePhone($phoneInput)";
                    @endphp
                    <button
                        wire:click="{{ $_clickAction }}"
                        data-bs-toggle="tooltip" data-bs-title="{{ $phoneInput==1 ? __('Добавить телефон') : __('Удалить телефон') }}"
                        class="btn btn-outline-{{$phoneInput==1?'secondary':'danger'}}"
                        type="button"
                        wire:loading.delay.attr="disabled"
                        wire:target="{{ $_clickAction }}"
                    >
                        @if($phoneInput == 1)
                            <i class="bi bi-plus-lg"
                               wire:loading.remove.delay
                               wire:target="{{ $_clickAction }}">
                            </i>
                        @else
                            <i class="bi bi-trash3"
                               wire:loading.remove.delay
                               wire:target="{{ $_clickAction }}">
                            </i>
                        @endif
                            <i class="spinner-border spinner-border-sm" wire:loading.delay wire:target="{{ $_clickAction }}"></i>
                    </button>
                </div>
                @error('form.phones.'.$phoneInput) <span class="error">{{ $message }}</span> @enderror
                @endforeach
            </div>
            <div class="col-sm-6">
                <label for="main-form-marital-status" class="form-label">{{ __('Семейное положение') }}</label>
                <select name="marital_status" id="main-form-marital-status" class="form-select">
                    @foreach($maritalStatuses as $statusKey => $maritalStatus)
                        <option value="{{ $statusKey }}">{{ $maritalStatus }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="main-form-email" class="form-label">{{ __('Дата рождения') }}</label>
                <input id="main-form-email" class="form-control" type="date" name="birthday" value="">
            </div>
            <div>
                <label for="main-form-about">{{ __('О себе') }}</label>
                <textarea class="form-control"></textarea>
            </div>
            <div>
                <label for="main-form-files">{{ __('Файлы') }} <span class="text-muted">(jpg, png, pdf; не более 5 файлов, не более 5Мб каждый)</span></label>
                <input type="file" name="files" id="main-form-files" class="form-control" multiple accept="image/jpeg,image/png,application/pdf" maxlength="5">
            </div>
            <div>
                <div class="form-check">
                    <input id="main-form-accept-rules" type="checkbox" class="form-check-input">
                    <label for="main-form-accept-rules">{{ __('Я ознакомился с правилами') }}</label>
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-success" type="submit">Отправить</button>
            </div>
        </form>
    </div>
</div>
