const input = document.querySelector("#input");
const enterBtn = document.querySelector('#enter');

const MDCRipple = mdc.ripple.MDCRipple;
const MDCTextField = mdc.textField.MDCTextField;
const MDCTextFieldIcon = mdc.textField.MDCTextFieldIcon;
const MDCSnackbar = mdc.snackbar.MDCSnackbar;


const ripples = [].map.call(document.querySelectorAll('.mdc-ripple'), function(el) {
  return new MDCRipple(el);
});

const textfields = [].map.call(document.querySelectorAll('.mdc-text-field'), function(el) {
  return new MDCTextField(el);
});

const textfieldIcons = [].map.call(document.querySelectorAll('.mdc-text-field-icon'), function(el) {
  return new MDCTextFieldIcon(el);
});

const snackbar = new MDCSnackbar(document.querySelector('.mdc-snackbar'));
