<div class="container d-flex flex-column flex-grow-1 justify-content-center my-3">
    <div class="row">

        @if ($isSuccess)
            <div class="col d-flex align-items-center flex-column">
                <h2>Успешно добавлено!</h2>
                <a href="/" class="btn btn-primary text-white">Обновить страницу</a>
            </div>
        @else
            <form class="row g-3 main-form shadow rounded-3 p-3 col-lg-6 mx-auto" wire:submit="save" novalidate onkeydown="return event.key != 'Enter';">
            @csrf
            <div class="col-sm-4">
                <label for="main-form-surname" class="form-label required">{{ __('Фамилия') }}</label>
                <input id="main-form-surname" wire:model="form.surname" class="form-control @error('form.surname') is-invalid @enderror" type="text" name="surname" placeholder="{{ __('Иванов') }}" value="">
                @error('form.surname') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-sm-4">
                <label for="main-form-name" class="form-label required">{{ __('Имя') }}</label>
                <input id="main-form-name" wire:model="form.name" class="form-control @error('form.name') is-invalid @enderror" type="text" name="name" placeholder="{{ __('Иван') }}" value="">
                @error('form.name') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-sm-4">
                <label for="main-form-middle-name" class="form-label">{{ __('Отчество') }}</label>
                <input id="main-form-middle-name" wire:model="form.middle_name" class="form-control" type="text" name="middle_name" placeholder="{{ __('Иванович') }}" value="">
            </div>
            <div>
                <label for="main-form-email" class="form-label">{{ __('Email') }}</label>
                <input id="main-form-email" wire:model="form.email" class="form-control @error('form.email') is-invalid @enderror" type="email" name="email" placeholder="{{ __('example@site.by') }}" value="">
                @error('form.email') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="">
                <label for="main-form-phone-1" class="form-label">{{ __('Телефоны') }} <span class="text-muted">(макс. 5)</span></label>
                @foreach($form->userPhones as $keyPhone => $phone)
                <div class="input-group main-form-phones-group {{ $keyPhone!=0?'mt-2':'' }} @error("form.userPhones.$keyPhone.number") has-validation @enderror"
                     wire:key="{{ $keyPhone }}"
                     x-data="initPhoneRow"
                >
                    <div class="input-group-text">
                        <select @change="onPhoneCountryChangeHandler" x-model="currentPhoneCodeId" wire:model="form.userPhones.{{ $keyPhone }}.phone_country_id" class="form-select border-0 py-0" aria-label="Выбор страны">
                            @foreach($countryPhoneCodes as $countryPhoneCode)
                            <option @selected($phone['phone_country_id']==$countryPhoneCode['id']) value="<?=$countryPhoneCode['id']?>"><?=$countryPhoneCode['code']?></option>
                            @endforeach
                        </select>
                    </div>
                    @php // TODO: bug - fill phone with '+375' mask, then switch to '+7' and add another phone input - previous mask resets to '+375' @endphp
                    <input id="main-form-phone-{{$keyPhone}}" x-mask:dynamic="currentMask" class="form-control @error("form.userPhones.$keyPhone.number") is-invalid @enderror" type="tel" value="" wire:model="form.userPhones.{{ $keyPhone }}.number">
                    @php
                        $_clickAction = '';
                        if ($keyPhone==0 && count($form->userPhones) < 5) $_clickAction = 'addUserPhone';
                        elseif ($keyPhone!=0) $_clickAction = "removeUserPhone($keyPhone)";
                    @endphp
                    <button
                        {{ $_clickAction ? 'wire:click='.$_clickAction : '' }}
                        data-bs-toggle="tooltip" data-bs-title="{{ $keyPhone==0 ? __('Добавить телефон') : __('Удалить телефон') }}"
                        class="btn btn-outline-{{$keyPhone==0?'secondary':'danger'}}"
                        type="button"
                        wire:loading.delay.attr="disabled"
                        wire:target="{{ $_clickAction }}"
                    >
                        @if($keyPhone == 0)
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
                    @error("form.userPhones.$keyPhone.number") <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
                @endforeach
                @error('form.userPhones') <span class="d-block invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div class="col-sm-6">
                <label for="main-form-marital-status" class="form-label">{{ __('Семейное положение') }}</label>
                <select name="marital_status" id="main-form-marital-status" class="form-select" wire:model="form.marital_status">
                    @foreach($maritalStatuses as $statusKey => $maritalStatus)
                        <option value="{{ $statusKey }}">{{ $maritalStatus }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-sm-6">
                <label for="main-form-email" class="form-label required">{{ __('Дата рождения') }}</label>
                <input id="main-form-email" wire:model="form.birthday" class="form-control @error('form.birthday') is-invalid @enderror" type="date" name="birthday" value="">
                @error('form.birthday') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="main-form-about">{{ __('О себе') }}</label>
                <textarea class="form-control @error('form.about') is-invalid @enderror" wire:model="form.about"></textarea>
                @error('form.about') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div>
                <label for="main-form-files">{{ __('Файлы') }} <span class="text-muted">(jpg, png, pdf; не более 5 файлов, не более 5Мб каждый)</span></label>
                <input type="file" wire:model="form.files" id="main-form-files" class="form-control @if($errors->hasAny(['form.files', 'form.files.*'])) is-invalid @endif" multiple accept="image/jpeg,image/png,application/pdf">
                @error('form.files') <span class="invalid-feedback">{{ $message }}</span> @enderror
                @error('form.files.*') <span class="invalid-feedback">{{ $message }}</span> @enderror
            </div>
            <div>
                <div class="form-check">
                    <input id="main-form-accept-rules" type="checkbox" class="form-check-input @error('form.rulesAccepted') is-invalid @enderror" wire:model="form.rulesAccepted">
                    <label for="main-form-accept-rules">{{ __('Я ознакомился с правилами') }}</label>
                    @error('form.rulesAccepted') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            <div class="d-flex justify-content-center">
                <button class="btn btn-success" type="submit">Отправить</button>
            </div>
            <script>
                function initPhoneRow() {
                    return {
                        countryPhoneCodes: {{ Js::from($countryPhoneCodes) }},
                        currentMask: '{{ $countryPhoneCodes->first()['mask'] ?? '' }}',
                        currentPhoneCodeId: {{ $countryPhoneCodes->first()['id'] ?? 0 }},
                        onPhoneCountryChangeHandler(e) {
                            this.currentMask = this.countryPhoneCodes[this.currentPhoneCodeId]['mask'];
                        }
                    }
                }
            </script>
        </form>
        @endif
    </div>
</div>
