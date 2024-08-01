import './bootstrap';

import Alpine from 'alpinejs';
import jQuery from 'jquery';

window.Alpine = Alpine;
window.$ = jQuery;

Alpine.start();

$(function(){

    //Cancel button to discard exit
    $("#cancel").type = "button";
    $("#cancel").on("click", function(e) {
        window.location.href = "/testmed/createteststructure";
    });

    //code here
    $("#createtest").on("submit", function(e){
        e.preventDefault();
        let form = $("#createtest");

        $.ajax({
            type: "POST",
            url: "/testmed/createtest",
            data: form.serialize(),
            success: function(data) {
                console.log('ciao');
            },
            error: function(data) {

                console.log(data.responseJSON.errors);
            }
        })

    });

});
