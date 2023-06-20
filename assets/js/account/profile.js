function updateProfile() {
    var firstname = document.getElementById("firstname");
    var lastname = document.getElementById("lastname");
    var email = document.getElementById("email");
    var password = document.getElementById("password");
    var password2 = document.getElementById("password2");

    if (firstname.value.length == 0) {
        showAlert('Please provide a valid firstname.')
        return;
    }

    if (lastname.value.length == 0) {
        showAlert('Please provide a valid lastname.')
        return;
    }

    if (email.value.length == 0) {
        showAlert('Please provide a valid email address.')
        return;
    }

    if (password.value.length == 0) {
        showAlert('Please provide a valid password.')
        return;
    }

    if (password2.value.length == 0) {
        showAlert('Please provide a valid password retype.')
        return;
    }

    if (password.value != password2.value) {
        showAlert('Passwords do not match.')
        return;
    }

    AjaxRequest('doprofile', profileForm, profileCallback, arcloader)
}

function profileCallback(data) {
    if (data.error != null) {
        showAlert(data.error);
    }
    //console.log(data);
    showAlert(data.message, true);
}

function showAlert(message, success = false) {
    var alert = document.getElementById("alert");
    alert.classList.remove("d-none");
    if (success == true) {
        alert.classList.add("alert-success");
        alert.classList.remove("alert-danger");
    } else {
        alert.classList.add("alert-danger");
        alert.classList.remove("alert-success");
    }
    alert.innerHTML = message;
}

// Enter key for update
window.addEventListener(
    "keydown",
    (event) => {
      if (event.code == "Enter") {
        register();
      }
    },
    true
);