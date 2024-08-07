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

    // The function recoursively open the sections json,
    // create the section tree and append it to the ul element of the testnode
    function sectionNode(testnode, sections, sectionbutton, questionbutton) {

        //Recoursive code
        let count = Object.keys(sections).length;
        for(let i=0; i<count; i++) {
            let section = sections["section" + (i+1)];
            console.log(section.name);
            // Creation html section object
            let sectionnode = document.createElement("li");
            sectionnode.classList.add('section');
            sectionnode.id = "section-" + section.id;
            let detail = document.createElement("details");
            let summary = document.createElement("summary");
            summary.innerHTML = section.name;
            detail.appendChild(summary);
            detail.appendChild(document.createElement("ul"));
            sectionnode.appendChild(detail);

            if('sections' in section) {
                //Ricoursive function call
                sectionNode(sectionnode, section.sections, sectionbutton, questionbutton);

                //Add section button
                let button = document.createElement("div");
                sectionnode.childNodes[0].childNodes[1].append(button);
                button.outerHTML = sectionbutton.outerHTML;
            } else {
                if('questions' in section) {

                    let questioncount = Object.keys(section.questions).length;
                    for(let i=0; i<questioncount; i++) {
                        //Creation html question node
                        let questionnode = document.createElement("li");
                        questionnode.classList.add('question');
                        questionnode.id = "question-" + section.questions["question"+ (i+1)].id;
                        questionnode.innerHTML = section.questions["question"+ (i+1)].title;
                        sectionnode.childNodes[0].childNodes[1].appendChild(questionnode);
                    }
                    //Add question button
                    let button = document.createElement("div");
                    sectionnode.childNodes[0].childNodes[1].append(button);
                    button.outerHTML = questionbutton.outerHTML;

                } else {
                    //Aqq question and section button
                    let sbutton = document.createElement("div");
                    sectionnode.childNodes[0].childNodes[1].append(sbutton);
                    sbutton.outerHTML = questionbutton.outerHTML;
                    let qbutton = document.createElement("div");
                    sectionnode.childNodes[0].childNodes[1].append(qbutton);
                    qbutton.outerHTML = sectionbutton.outerHTML;
                }
            }
            testnode.childNodes[0].childNodes[1].appendChild(sectionnode);
        }
    }

    async function sectionButton() {
        return new Promise((resolve, reject) => {
            //Retrieve add-section-button
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/addsectionbutton",
                success: function(data) {
                    let addsectionbutton = document.createElement("li");
                    addsectionbutton.classList.add('sectionbutton');
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);
                    addsectionbutton.innerHTML = bodyHTML;
                    resolve(addsectionbutton);
                },
                error: function(err) {
                    reject(err);
                }
            });
        });
    }

    async function questionButton() {
        return new Promise((resolve, reject) => {
            //Retrieve add-section-button
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/addquestionbutton",
                success: function(data) {
                    let addquestionbutton = document.createElement("li");
                    addquestionbutton.classList.add('questionbutton');
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);
                    addquestionbutton.innerHTML = bodyHTML;
                    resolve(addquestionbutton);
                },
                error: function(err) {
                    reject(err);
                }
            });
        });
    }

    async function treesetting() {
        //Getting buttons
        let [sectionbutton, questionbutton] = await Promise.all([sectionButton(), questionButton()]);

        return new Promise((resolve, reject) => {
            //Retreiving test tree
            $.ajax({
                type: "GET",
                url: "/testmed/createteststructure/ajax/createtree",
                success: function(data) {
                    //Test Node
                    let test = document.createElement("li");
                    test.classList.add('test');
                    test.id = data.test.id;
                    let detail = document.createElement("details");
                    let summary = document.createElement("summary");
                    summary.innerHTML = data.test.name;
                    detail.appendChild(summary);

                    if("sections" in data.test) {
                        detail.appendChild(document.createElement("ul"));
                        test.appendChild(detail);
                        sectionNode(test, data.test.sections, sectionbutton, questionbutton);
                    } else {
                        test.appendChild(detail);
                    }

                    test.childNodes[0].childNodes[1].appendChild(sectionbutton);
                    $("#tree").append(test);
                    resolve();
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
                    let type = document.getElementById("new-section").parentElement.parentElement.parentElement.classList[0];
                    let id = document.getElementById("new-section").parentElement.parentElement.parentElement.id.split("-")[1];
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
                    let id = document.getElementById("new-question").parentElement.parentElement.parentElement.id.split("-")[1];
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

                                let type = document.getElementById('type').getAttribute('value');
                                $("#storechoosequestion").on("click", function(e) {
                                    e.preventDefault();
                                    $.ajax({
                                        type: "POST",
                                        url: "/testmed/createteststructure/ajax/add"+type+"question",
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
