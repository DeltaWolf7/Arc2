/**
 * Send an Ajax request to the url.
 *
 * @param {string} url The url to send the request to.
 * @param {mixed} form The form data to send.
 * @param {function} responseCallback The function to call when the request is complete and will be pass the repsonse JSON.
 * @param {function} loader The function to call to show and hide the loader.
 */
function AjaxRequest(url, form, responseCallback, loader) {
  if (typeof loader === "function") {
    // show loader if defined.
    loader(true);
  }

  var text = "";

  fetch(url, {
    method: "POST",
    body: new FormData(form),
  })
    .then((response) => text = response.text()) // Parse the response as text
    .then((text) => {
      try {
        const data = JSON.parse(text); // Try to parse the response as JSON
        if (typeof responseCallback === "function") {
          responseCallback(data);
        }
      } catch (e) {
        console.warn(text);
      }
      if (typeof loader === "function") {
        // hide loader if defined.
        loader(false);
      }
    });
}
