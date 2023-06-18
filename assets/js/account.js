loginForm.addEventListener("submit", (e) => {
    e.preventDefault();

    var email = document.getElementById("email");
    var password = document.getElementById("password");

    if (email.value.length == 0) {
        showAlert('Please provide a valid email address.')
        return;
    }

    if (password.value.length == 0) {
        showAlert('Please provide a valid password.')
        return;
    }

    AjaxRequest('dologin', loginForm, loginCallback, arcloader)
});

function loginCallback(data) {
    if (data.error != null) {
        showAlert(data.error);
    }
}

function showAlert(message) {
    var alert = document.getElementById("alert");
    alert.classList.remove("d-none");
    alert.innerHTML = message;
}