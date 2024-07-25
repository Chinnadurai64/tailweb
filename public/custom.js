function restrictTextOnlyInput(event) {
    if (event.ctrlKey || event.altKey) {
        event.preventDefault();
    } else {
        const key = event.keyCode;
        if (!((key === 8) || (key === 9) || (key === 32) || (key === 46) || 
              (key >= 35 && key <= 40) || (key >= 65 && key <= 90))) {
            event.preventDefault();
        }
    }
}

function restrictAndValidateInput(event) {
    const input = event.target;
    const key = event.which || event.keyCode;
    const allowedKeys = [46, 45, 8, 9, 37, 39, 38, 40];

    if (allowedKeys.includes(key) || (key >= 48 && key <= 57)) {
        return; 
    }
    event.preventDefault();
    let value = input.value + String.fromCharCode(key); 
    const sanitizedValue = value.replace(/[^0-9.]/g, ''); 
    const numberValue = parseFloat(sanitizedValue);

    if (numberValue < 0 || numberValue > 100 || isNaN(numberValue)) {
        input.value = sanitizedValue.slice(0, -1);
    } else {
        input.value = sanitizedValue;
    }
}
function restrictAndValidateContent(event) {
    const input = event.target;
    const key = event.which || event.keyCode;
    const allowedKeys = [46, 45, 8, 9, 37, 39, 38, 40]; 
    if (allowedKeys.includes(key) || (key >= 48 && key <= 57)) {
        return; 
    }
    event.preventDefault(); 
    let value = input.textContent + String.fromCharCode(key); 
    const sanitizedValue = value.replace(/[^0-9.]/g, '');
    const numberValue = parseFloat(sanitizedValue);
    if (numberValue < 0 || numberValue > 100 || isNaN(numberValue)) {
        input.textContent = sanitizedValue.slice(0, -1);
    } else {
        input.textContent = sanitizedValue;
    }
}

document.querySelectorAll('.number-only').forEach(element => {
    element.addEventListener('keydown', restrictAndValidateInput);
    element.addEventListener('input', restrictAndValidateInput);
});
document.querySelectorAll('.number-only-contant').forEach(element => {
    element.addEventListener('keydown', restrictAndValidateContent);
    element.addEventListener('input', restrictAndValidateContent);
});

document.querySelectorAll('.text-only').forEach(element => {
    element.addEventListener('keydown', restrictTextOnlyInput);
});
