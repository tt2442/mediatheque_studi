import "@icon/dripicons/dripicons.css";

const $ = require("jquery");
global.$ = global.jQuery = $;
import "bootstrap";

import "bootstrap/dist/css/bootstrap.css";

import "./styles/app.css";


// ------------------------------------- ALERT
function alertClose() {
    $(".alert").delay(3000).slideUp(200, function() {
        $(this).alert('close');
    });
    document.alertClose = alertClose
}
$('.alert').on('click', function() {
    $(this).alert('close');
})

$(function() {
    alertClose()
});