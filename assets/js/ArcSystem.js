
/**
 * Send an Ajax request to the url.
 * 
 * @param {string} url The url to send the request to. 
 * @param {mixed} data The form or data to send. 
 * @param {function} responseCallback The function to call when the request is complete and will be pass the repsonse JSON. 
 */
function AjaxRequest(url, data, responseCallback) {
    fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
    .then(response => {
        if (typeof responseCallback === "function") {
            responseCallback(response.json());
        }
    })
    .catch(err => {
        console.error(err);
    });
}