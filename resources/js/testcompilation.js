import './bootstrap';

import Alpine from 'alpinejs';
import jQuery from 'jquery';

window.Alpine = Alpine;
window.$ = jQuery;

Alpine.start();

// The function recoursively open the sections json,
// create the section tree and append it to the ul element of the testnode
function sectionNode(testnode, sections) {

    //Recoursive code
    let count = Object.keys(sections).length;
    for(let i=0; i<count; i++) {
        let section = sections["section" + (i+1)];

        //Creation container for positioning
        let positioner = document.createElement("form");
        positioner.method = "POST";
        positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6", "modifyform");

        // Creation html section object
        let sectionnode = document.createElement("li");
        sectionnode.classList.add('section');
        sectionnode.id = "section-" + section.id;
        let detail = document.createElement("details");
        detail.open = true;
        let summary = document.createElement("summary");
        detail.appendChild(summary);

        //Section Name
        let summarytitle = document.createElement("p");
        summarytitle.innerHTML = section.name;
        positioner.appendChild(summarytitle);

        //checkicon
        let checkicon = document.createElement("div");
        checkicon.classList.add("w-6", "ml-1")
        let checkmark = document.getElementById("checkmark").cloneNode(true);
        checkicon.appendChild(checkmark);
        checkmark.id = checkmark.id + "-section-" + section.id;
        checkmark.style = "color: green";
        if(section.status == 1) {
            checkmark.classList.remove("hidden");
        }
        positioner.appendChild(checkicon);

        //Hidden field for modify
        let hidden = document.createElement("input");
        hidden.type = "hidden";
        hidden.name = "update";
        hidden.value = "section-" + section.id;
        positioner.appendChild(hidden);

        //List
        detail.appendChild(document.createElement("ul"));

        summary.appendChild(positioner);
        sectionnode.appendChild(detail);

        if('sections' in section) {
            //Ricoursive function call
            sectionNode(sectionnode, section.sections);
        } else {
            if('questions' in section) {

                let questioncount = Object.keys(section.questions).length;
                for(let i=0; i<questioncount; i++) {
                    //Creation container for positioning
                    let positioner = document.createElement("form");
                    positioner.method = "POST";
                    positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6", "modifyform");

                    //Creation html question node
                    let questionnode = document.createElement("li");
                    questionnode.classList.add('question', 'mb-2');
                    questionnode.id = "question-" + section.questions["question"+ (i+1)].id;
                    sectionnode.childNodes[0].childNodes[1].appendChild(questionnode)

                    //Questiontitle
                    let questiontitle = document.createElement("div");
                    positioner.appendChild(questiontitle);
                    questiontitle.outerHTML = "<div class=\"question-title\">" + section.questions["question"+ (i+1)].title + "</div>";

                    //Checkicon
                    let checkicon = document.createElement("div");
                    checkicon.classList.add("w-6", "ml-1")
                    let checkmark = document.getElementById("checkmark").cloneNode(true);
                    checkicon.appendChild(checkmark);
                    checkmark.id = checkmark.id + "-question-" + questionnode.id.split("-")[1];
                    checkmark.style = "color: green";
                    if(section.questions["question"+ (i+1)].status == 1) {
                        checkmark.classList.remove("hidden");
                    }
                    positioner.appendChild(checkicon);

                    //Hidden field for modify
                    let hidden = document.createElement("input");
                    hidden.type = "hidden";
                    hidden.name = "update";
                    hidden.value = "question-" + section.questions["question"+ (i+1)].id;
                    positioner.appendChild(hidden);

                    sectionnode.childNodes[0].childNodes[1].childNodes[i].appendChild(positioner);
                }

            }
        }
        testnode.childNodes[0].childNodes[1].appendChild(sectionnode);
    }
}

async function treesetting() {

    return new Promise((resolve, reject) => {
        //Retreiving test tree
        $.ajax({
            type: "GET",
            url: "/med/visitadministration/ajax/createtree",
            success: function(data) {

                //Creation container for positioning
                let positioner = document.createElement("div");
                positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6");

                //Test Node
                let test = document.createElement("li");
                test.classList.add('test');
                test.id = "test-"+data.test.id;
                let detail = document.createElement("details");
                detail.open = true;
                let summary = document.createElement("summary");
                detail.appendChild(summary);

                //Test Name
                let summarytitle = document.createElement("p");
                summarytitle.innerHTML = data.test.name;
                positioner.appendChild(summarytitle);

                if("sections" in data.test) {
                    detail.appendChild(document.createElement("ul"));
                    summary.appendChild(positioner);
                    test.appendChild(detail);
                    sectionNode(test, data.test.sections);
                } else {
                    detail.appendChild(document.createElement("ul"));
                    summary.appendChild(positioner);
                    test.appendChild(detail);
                }

                resolve(test);
            },
            error: function(err) {
            }
        });
    });
}

function errorAnalysis(responseJSON) {
    let type = document.getElementById("type").value;
    if(type == "multiple") {
        let errorfield = document.getElementById("multiple-question-error");
        errorfield.innerHTML = "";
        if(responseJSON.errors["radioinput"]) {
            let arr = responseJSON.errors["radioinput"];
            for(let m=0; m<arr.length; m++) {
                let li = document.createElement("li");
                li.innerHTML = arr[m].replace("radioinput", "");
                errorfield.append(li);
            }
        }
    } else if(type == "value") {
        let errorfield = document.getElementById("value-question-error");
        errorfield.innerHTML = "";
        if(responseJSON.errors["valueinput"]) {
            let arr = responseJSON.errors["valueinput"];
            for(let m=0; m<arr.length; m++) {
                let li = document.createElement("li");
                li.innerHTML = arr[m].replace("valueinput", "");
                errorfield.append(li);
            }
        }
    } else if(type == "open") {
        let errorfield = document.getElementById("open-question-error");
        errorfield.innerHTML = "";
        if(responseJSON.errors["openinput"]) {
            let arr = responseJSON.errors["openinput"];
            for(let m=0; m<arr.length; m++) {
                let li = document.createElement("li");
                li.innerHTML = arr[m].replace("openinput", "answer");
                errorfield.append(li);
            }
        }
    } else if(type == "multipleselection") {
        let errorfield = document.getElementById("multiple-selection-question-error");
        errorfield.innerHTML = "";
        if(responseJSON.errors["checkbox"]) {
            let arr = responseJSON.errors["checkbox"];
            for(let m=0; m<arr.length; m++) {
                let li = document.createElement("li");
                li.innerHTML = arr[m].replace("checkbox", "");
                errorfield.append(li);
            }
        }
    } else if(type == "image") {
        let errorfield = document.getElementById("image-question-error");
        errorfield.innerHTML = "";
        if(responseJSON.errors["imageradio"]) {
            let arr = responseJSON.errors["imageradio"];
            for(let m=0; m<arr.length; m++) {
                let li = document.createElement("li");
                li.innerHTML = arr[m].replace("imageradio", "image");
                errorfield.append(li);
            }
        }
    }
}

const test = await treesetting();

function nodeCompilation() {
    $.ajax({
        type: "GET",
        url: "/med/visitadministration/ajax/createnodeinput",
        success: function(data) {
            //Reading and pasting element
            const i1 = data.indexOf("<body>");
            const i2 = data.indexOf("</body>");
            const bodyHTML = data.substring(i1 + "<body>".length, i2);

            let elementdetail = document.createElement("div");
            document.getElementsByClassName("constructor")[0].innerHTML = "";
            let constructor = $(".constructor");
            constructor.append(elementdetail);
            constructor.scrollTop(0);
            elementdetail.outerHTML = bodyHTML;

            $("#nextform").on("submit", function(e) {
                e.preventDefault();

                $.ajax({
                    type: "POST",
                    url: "/med/visitadministration/ajax/storenode",
                    data: $("#nextform").serialize(),
                    success: function(data) {

                        if(data.status == 200) {
                            $("#nextform").off("submit").on("submit", function(m) {
                                m.preventDefault();
                            });
                            document.getElementById("checkmark-" + data.id).classList.remove("hidden");
                            nodeCompilation();
                        } else if(data.status == 422) {
                            errorAnalysis(data.responseJSON);
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        if(err.status == 422) {
                            errorAnalysis(err.responseJSON);
                        }
                    }
                });
            });

            $("#testform").on("submit", function(e) {
                e.preventDefault();
                $.ajax({
                    type: "POST",
                    url: "/med/visitadministration/ajax/storenode",
                    data: $("#testform").serialize(),
                    success: function(data) {
                        console.log(data);
                        if(data.status == 200) {
                            window.location.href = "/med/visitadministration/endinterview";
                        }
                    },
                    error: function(err) {

                    }
                });
            });

            $(".modifyform").on("mouseover", function(e) {
                this.classList.add("px-2");
                this.classList.add("rounded-lg");
                this.classList.add("bg-blue-100");
            });

            $(".modifyform").on("mouseout", function(e) {
                this.classList.remove("px-2");
                this.classList.remove("rounded-lg");
                this.classList.remove("bg-blue-100");
            });

            $(".modifyform").on("click", function(e) {
                e.preventDefault();

                let form = this;
                $.ajax({
                    type: "GET",
                    url: "/med/visitadministration/ajax/updatenode",
                    data: $(form).serialize(),
                    success: function(data) {
                        $(".modifyform").off("submit").on("submit", function(m) {
                            m.preventDefault();
                        });
                        //Reading and pasting element

                        const i1 = data.indexOf("<body>");
                        const i2 = data.indexOf("</body>");
                        const bodyHTML = data.substring(i1 + "<body>".length, i2);

                        let elementdetail = document.createElement("div");
                        document.getElementsByClassName("constructor")[0].innerHTML = "";
                        let updatefield = $(".constructor");
                        updatefield.append(elementdetail);
                        updatefield.scrollTop(0);
                        elementdetail.outerHTML = bodyHTML;

                        //Cancel button to discard exit
                        $(".cancel").type = "button";
                        $(".cancel").off("click").on("click", function(e) {
                            e.preventDefault();
                            window.location.href = "/med/visitadministration/testcompilation";
                        });

                        $("#updateform").on("submit", function(e) {
                            e.preventDefault();

                            $.ajax({
                                type: "POST",
                                url: "/med/visitadministration/ajax/updatenode",
                                data: $("#updateform").serialize(),
                                success: function(data) {
                                    if(data.status == 200) {
                                        $("#updateform").off("submit").on("submit", function(m) {
                                            m.preventDefault();
                                        });
                                        nodeCompilation();
                                    } else if(data.status == 422) {
                                        errorAnalysis(data.responseJSON);
                                    }
                                },
                                error: function(err) {
                                    if(err.status == 422) {
                                        errorAnalysis(err.responseJSON);
                                    }
                                }
                            });
                        });
                    },
                    error: function(err) {

                    }
                });
            });
        },
        error: function(err) {

        }
    });
}

$(function(){
    //Append tree
    document.getElementById("tree").appendChild(test);

    //Blocking summaries onclick
    $("summary").on("click", function(e) {
        e.preventDefault();
    });

    //Cancel button to discard exit
    $("#cancel").type = "button";
    $("#cancel").on("click", function(e) {
        window.location.href = "/med/visitadministration/testcompilation";
    });

    nodeCompilation();

})
