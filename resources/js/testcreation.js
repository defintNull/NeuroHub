import './bootstrap';

import Alpine from 'alpinejs';
import { formToJSON } from 'axios';
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

    async function treesetting() {
        return new Promise((resolve, reject) => {

            //Retrieve add-section-button
            let addsectionbutton = document.createElement("li");
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/addsectionbutton",
                success: function(data) {
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                    $(".test").append(addsectionbutton);
                    addsectionbutton.innerHTML = bodyHTML;
                    resolve();
                },
                error: function(err) {
                    reject(err);
                }
            });
        });
    }

    //add-section button
    async function buttonsubmit() {
        await treesetting();
        $(".addsectionbutton").on("click", function(e) {
            e.preventDefault();
            let button = this;
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/addsection",
                success: function(data) {
                    //Reading and pasting button
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                    let addsection = document.createElement("div");
                    document.getElementsByClassName("constructor")[0].innerHTML = "";
                    $(".constructor").append(addsection);
                    addsection.outerHTML = bodyHTML;

                    //changing button estetic
                    button.parentElement.parentElement.outerHTML = "<li id=\"new-section\" class=\"text-red-500\"> New Section </li>";

                    //Setting hidden fields
                    let type = document.getElementById("new-section").parentElement.classList[0].replace("node", "");
                    let id = document.getElementById("new-section").parentElement.id.split("-")[1];
                    document.getElementById("parent-type").setAttribute("value", type);
                    document.getElementById("parent-id").setAttribute("value", id);

                    //Add button event for submit section form
                    $("#storesection").on("click", function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: "POST",
                            url: "/testmed/createteststructure/ajax/addsection",
                            data: $("#sectionform").serialize(),
                            success: function(data) {
                                window.location.href = "/testmed/createteststructure";
                            },
                            error: function(err) {
                                console.log(err);
                                if(err.status == 422) {
                                    let arr = err.responseJSON.errors.sectionname;
                                    let errorfield = document.getElementById("sectionname-error");
                                    for(let i=0; i<arr.length; i++) {
                                        let li = document.createElement("li");
                                        li.innerHTML = arr[i];
                                        errorfield.append(li);
                                    }
                                }
                            }
                        });
                    });
                }
            });
        });
    }

    buttonsubmit();

});
