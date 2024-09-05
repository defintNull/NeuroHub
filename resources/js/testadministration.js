import './bootstrap';

import Alpine from 'alpinejs';
import jQuery from 'jquery';

window.Alpine = Alpine;
window.$ = jQuery;

Alpine.start();

$(function() {
    $("#cancel").type = "button";
    $("#cancel").on("click", function(e) {
        window.location.href = "/med/visitadministration/controlpanel";
    });

    $("#deletevisit").on("click", function(e) {
        e.preventDefault();
        document.getElementById("deletevisitform").submit();
    });
});
