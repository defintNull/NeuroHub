import './bootstrap';

import Alpine from 'alpinejs';
import jQuery, { timers } from 'jquery';

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
                    //resolve();
                },
                error: function(err) {
                    reject(err);
                }
            });

            let addquestionbutton = document.createElement("li");
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/addquestionbutton",
                success: function(data) {
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                    $(".test").append(addquestionbutton);
                    addquestionbutton.innerHTML = bodyHTML;
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

                    //Add button event for submit and cancel section form
                    $(".cancel").on("click", function(e) {
                        e.preventDefault();
                        window.location.href = "/testmed/createteststructure";
                    });
                    $("#storesection").on("click", function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: "POST",
                            url: "/testmed/createteststructure/ajax/addsection",
                            data: $("#sectionform").serialize(),
                            success: function(data) {

                                if(data.status == 200) {
                                    window.location.href = "/testmed/createteststructure";
                                }
                            },
                            error: function(err) {

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

        $(".addquestionbutton").on("click", function(e) {
            e.preventDefault();
            let button = this;
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/addquestion",
                success: function(data) {

                    //Reading and pasting selector
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                    let addquestion = document.createElement("div");
                    document.getElementsByClassName("constructor")[0].innerHTML = "";
                    $(".constructor").append(addquestion);
                    addquestion.outerHTML = bodyHTML;

                    //changing button estetic
                    button.parentElement.parentElement.outerHTML = "<li id=\"new-question\" class=\"text-red-500\"> New Question </li>";

                    //Setting hidden field
                    let id = document.getElementById("new-question").parentElement.id.split("-")[1];
                    document.getElementById("parent-id").setAttribute("value", id);

                    //Add button event for submit and cancel question form
                    $(".cancel").on("click", function(e) {
                        e.preventDefault();
                        window.location.href = "/testmed/createteststructure";
                    });
                    $("#storequestion").on("click", function(e) {
                        e.preventDefault();
                        $.ajax({
                            type: "POST",
                            url: "/testmed/createteststructure/ajax/addquestion",
                            data: $("#questionform").serialize(),
                            success: function(data) {

                                //Reading and pasting form
                                const i1 = data.indexOf("<body>");
                                const i2 = data.indexOf("</body>");
                                const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                let questionform = document.createElement("div");
                                document.getElementsByClassName("constructor")[0].innerHTML = "";
                                $(".constructor").append(questionform);
                                questionform.outerHTML = bodyHTML;

                                let $type = document.getElementById('type').getAttribute('value');
                                $(".cancel").on("click", function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: "POST",
                                        url: "/testmed/createteststructure/ajax/cancelquestion",
                                        data: $("#choosequestionform").serialize(),
                                        success: function(data) {
                                            if(data.status == 200) {
                                                window.location.href = "/testmed/createteststructure";
                                            }
                                        }
                                    });

                                });

                                $("#storechoosequestion").on("click", function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: "POST",
                                        url: "/testmed/createteststructure/ajax/add"+$type+"question",
                                        data: $("#choosequestionform").serialize(),
                                        success: function(data) {

                                            if(data.status == 200) {
                                                window.location.href = "/testmed/createteststructure";
                                            }

                                        },
                                        error: function(err) {
                                            console.log(err);
                                        }
                                    });
                                });

                            }
                        });
                    });
                }
            });
        });

    }

    buttonsubmit();

});
