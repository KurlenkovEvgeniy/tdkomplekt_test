import './bootstrap';
import {Tooltip} from "bootstrap";

function phoneMask(phone, mask = '(__) ___-__-__') {
    let output = '';
    phone = phone.replace(/[^+\d]/g, '');
    let underScoreCounter = 0;
    mask.split('').forEach(ch => {
        if (ch === '_' && phone[underScoreCounter] !== undefined) {
            ch = phone[underScoreCounter];
            underScoreCounter++;
        }
        output += ch;
    });
    return output;
}

const tooltipTriggerList = document.querySelectorAll('[data-bs-toggle="tooltip"]');
const tooltipList = [...tooltipTriggerList].map(tooltipTriggerEl => new Tooltip(tooltipTriggerEl));

/*const mainFormPhonesGroup = document.querySelector('.main-form-phones-group');
if (mainFormPhonesGroup) {
    mainFormPhonesGroup.querySelector('.main-form-add-phone').addEventListener('click', addPhoneInput);
}*/
// TODO: переделать через Alpine JS
function addPhoneInput(evt) {
    const phonesLength = mainFormPhonesGroup.parentNode.querySelectorAll('.main-form-phones-group').length;
    if (phonesLength >= 5) return;
    const cloned = mainFormPhonesGroup.cloneNode(true);
    const delBtn = cloned.querySelector('.main-form-add-phone');
    delBtn.classList.remove('main-form-add-phone');
    delBtn.classList.replace('btn-outline-secondary', 'btn-outline-danger');
    delBtn.querySelector('i').classList.replace('bi-plus-lg', 'bi-trash3');
    delBtn.setAttribute('x-on:click', "$el.closest('.main-form-phones-group').remove()");

    const input = cloned.querySelector('input[name="phones[]"]');
    input.value = '';
    input.removeAttribute('id');
    cloned.classList.add('mt-2');

    mainFormPhonesGroup.parentNode.append(cloned);
}
