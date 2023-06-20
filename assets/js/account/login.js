function login() {
  var email = document.getElementById("email");
  var password = document.getElementById("password");

  if (email.value.length == 0) {
    showAlert("Please provide a valid email address.");
    return;
  }

  if (password.value.length == 0) {
    showAlert("Please provide a valid password.");
    return;
  }

  AjaxRequest("dologin", loginForm, loginCallback, arcloader);
}

function loginCallback(data) {
  if (data.error != null) {
    showAlert(data.error);
    return;
  }
  showAlert(data.message, true);
  window.location.href = "profile";
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

// Enter key for login
window.addEventListener(
  "keydown",
  (event) => {
    if (event.code == "Enter") {
      login();
    }
  },
  true
);