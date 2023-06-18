function arcloader(data) {

    if (data == true) {
        var body = document.body;
        body.innerHTML = '<div id="tmpSpinner" class="spinner-border text-primary" role="status" style="position: fixed; top: 50%; left: 50%; width: 5rem; height: 5rem;">'
            + '</div>' + body.innerHTML;
    } else {
        var spinner = document.getElementById("tmpSpinner");
        spinner.remove();
    }
}