document.addEventListener('DOMContentLoaded', function() {
    function decryptCheck() {
        var chars = "0123456789abcdefghijklmnopqrstuvwxyz";
        var string_length = 8;
        var num_chars = chars.length;  
        var result = '';
        while (string_length--) { 
            result += chars[Math.floor(Math.random() * num_chars)];  
        }
        var password = document.getElementById('password').value;
        var token = result + document.querySelector("[name='csrf_token']").value;

        var hiddenInput = document.createElement('input');
        hiddenInput.type = 'hidden';
        hiddenInput.name = 'str';
        hiddenInput.value = result;
        document.getElementById('form').appendChild(hiddenInput);

        var encryptPass = CryptoJS.AES.encrypt(JSON.stringify(password), token, { format: CryptoJSAesJson }).toString();
        document.getElementById('password').value = encryptPass;
    }

    function validateForm() {
        var email = document.getElementById('username').value;
        var password = document.getElementById('password').value;
        var errorContainer = document.getElementById('error-container');
        errorContainer.innerHTML = '';
        if (!email) {
            errorContainer.innerHTML += '<div class="alert alert-danger">Email is required.</div>';
        } else if (!validateEmail(email)) {
            errorContainer.innerHTML += '<div class="alert alert-danger">Invalid email format.</div>';
        }

        if (!password) {
            errorContainer.innerHTML += '<div class="alert alert-danger">Password is required.</div>';
        }

        if (errorContainer.innerHTML === '') {
            return true;
        }

        return false;
    }

    function validateEmail(email) {
        var re = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        return re.test(String(email).toLowerCase());
    }

    document.getElementById('form').addEventListener('submit', function(event) {
        event.preventDefault();
        if (validateForm()) {
            document.querySelector('.btn-primary').disabled = true;
            decryptCheck();
            event.target.submit();
        }
    });
});