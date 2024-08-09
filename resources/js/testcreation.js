import './bootstrap';

import Alpine from 'alpinejs';
import jQuery, { timers } from 'jquery';

window.Alpine = Alpine;
window.$ = jQuery;

Alpine.start();

const ciao = 'ciao';

// The function recoursively open the sections json,
// create the section tree and append it to the ul element of the testnode
function sectionNode(testnode, sections, sectionbutton, questionbutton, deletemodifybutton) {

    //Recoursive code
    let count = Object.keys(sections).length;
    for(let i=0; i<count; i++) {
        let section = sections["section" + (i+1)];
        // Creation html section object
        let sectionnode = document.createElement("li");
        sectionnode.classList.add('section');
        sectionnode.id = "section-" + section.id;
        let detail = document.createElement("details");
        detail.open = true;
        let summary = document.createElement("summary");
        summary.innerHTML = section.name;
        detail.appendChild(summary);

        //Modify Delete button
        let moddelbutton = document.createElement("div");
        moddelbutton = deletemodifybutton.cloneNode(true);
        moddelbutton.childNodes[1].childNodes[3].value = "section";
        moddelbutton.childNodes[1].childNodes[5].value = section.id;
        moddelbutton.childNodes[3].childNodes[3].value = "section";
        moddelbutton.childNodes[3].childNodes[5].value = section.id;
        detail.appendChild(moddelbutton);

        //List
        detail.appendChild(document.createElement("ul"));
        sectionnode.appendChild(detail);

        if('sections' in section) {
            //Ricoursive function call
            sectionNode(sectionnode, section.sections, sectionbutton, questionbutton, deletemodifybutton);

            //Add section button
            let button = document.createElement("div");
            sectionnode.childNodes[0].childNodes[2].append(button);
            button.outerHTML = sectionbutton.outerHTML;
        } else {
            if('questions' in section) {

                let questioncount = Object.keys(section.questions).length;
                for(let i=0; i<questioncount; i++) {
                    //Creation html question node
                    let questionnode = document.createElement("li");
                    questionnode.classList.add('question');
                    questionnode.id = "question-" + section.questions["question"+ (i+1)].id;
                    questionnode.innerHTML = "<div class=\"question-title\">" + section.questions["question"+ (i+1)].title + "</div>";
                    sectionnode.childNodes[0].childNodes[2].appendChild(questionnode);

                    //Modify Delete button
                    moddelbutton = document.createElement("div");
                    moddelbutton = deletemodifybutton.cloneNode(true);
                    moddelbutton.childNodes[1].childNodes[3].value = "question";
                    moddelbutton.childNodes[1].childNodes[5].value = section.questions["question"+ (i+1)].id;
                    moddelbutton.childNodes[3].childNodes[3].value = "question";
                    moddelbutton.childNodes[3].childNodes[5].value = section.questions["question"+ (i+1)].id;
                    sectionnode.childNodes[0].childNodes[2].childNodes[i].appendChild(moddelbutton);
                }
                //Add question button
                let button = document.createElement("div");
                sectionnode.childNodes[0].childNodes[2].append(button);
                button.outerHTML = questionbutton.outerHTML;

            } else {
                //Aqq question and section button
                let sbutton = document.createElement("div");
                sectionnode.childNodes[0].childNodes[2].append(sbutton);
                sbutton.outerHTML = questionbutton.outerHTML;
                let qbutton = document.createElement("div");
                sectionnode.childNodes[0].childNodes[2].append(qbutton);
                qbutton.outerHTML = sectionbutton.outerHTML;
            }
        }
        testnode.childNodes[0].childNodes[2].appendChild(sectionnode);
    }
}

async function sectionquestionButton() {
    return new Promise((resolve, reject) => {
        //Retrieve add-section-button
        $.ajax({
            type: "GET",
            url: "/testmed/createteststructure/ajax/addsectionquestionbutton",
            success: function(data) {
                let addsectionbutton = document.createElement("li");
                addsectionbutton.classList.add('sectionbutton');
                let i1 = data.indexOf("<section>");
                let i2 = data.indexOf("</section>");
                let bodyHTML = data.substring(i1 + "<section>".length, i2);
                addsectionbutton.innerHTML = bodyHTML;
                let addquestionbutton = document.createElement("li");
                addquestionbutton.classList.add('questionbutton');
                i1 = data.indexOf("<question>");
                i2 = data.indexOf("</question>");
                bodyHTML = data.substring(i1 + "<question>".length, i2);
                addquestionbutton.innerHTML = bodyHTML;

                resolve([addsectionbutton,addquestionbutton]);
            },
            error: function(err) {
                reject(err);
            }
        });
    });
}

async function deletemodifyButton() {
    return new Promise((resolve, reject) => {
        //Retrieve add-section-button
        $.ajax({
            type: "GET",
            url: "/testmed/createteststructure/ajax/createdeletemodifybutton",
            success: function(data) {
                let deletebutton = document.createElement("div");
                deletebutton.classList.add('deletemodifybutton');
                let i1 = data.indexOf("<delete>");
                let i2 = data.indexOf("</delete>");
                let bodyHTML = data.substring(i1 + "<delete>".length, i2);
                deletebutton.innerHTML = bodyHTML;

                let deletemodifybutton = document.createElement("div");
                deletemodifybutton.classList.add('deletemodifybutton');
                i1 = data.indexOf("<modify>");
                i2 = data.indexOf("</modify>");
                bodyHTML = data.substring(i1 + "<modify>".length, i2);
                deletemodifybutton.innerHTML = bodyHTML;
                deletemodifybutton.appendChild(deletebutton.childNodes[1])
                resolve(deletemodifybutton);
            },
            error: function(err) {
                reject(err);
            }
        });
    });
}

async function treesetting() {
    //Getting buttons
    let [[sectionbutton, questionbutton], deletemodifybutton] = await Promise.all([sectionquestionButton(), deletemodifyButton()]);

    return new Promise((resolve, reject) => {
        //Retreiving test tree
        $.ajax({
            type: "GET",
            url: "/testmed/createteststructure/ajax/createtree",
            success: function(data) {
                //Test Node
                let test = document.createElement("li");
                test.classList.add('test');
                test.id = "test-"+data.test.id;
                let detail = document.createElement("details");
                detail.open = true;
                let summary = document.createElement("summary");
                summary.innerHTML = data.test.name;
                detail.appendChild(summary);

                //Modify and Delete button
                let moddelbutton = document.createElement("div");
                moddelbutton = deletemodifybutton.cloneNode(true);
                moddelbutton.childNodes[1].childNodes[3].value = "test";
                moddelbutton.childNodes[1].childNodes[5].value = test.id.split("-")[1];
                moddelbutton.childNodes[3].childNodes[3].value = "test";
                moddelbutton.childNodes[3].childNodes[5].value = test.id.split("-")[1];
                detail.appendChild(moddelbutton);

                if("sections" in data.test) {
                    detail.appendChild(document.createElement("ul"));
                    test.appendChild(detail);
                    sectionNode(test, data.test.sections, sectionbutton, questionbutton, deletemodifybutton);
                } else {
                    detail.appendChild(document.createElement("ul"));
                    test.appendChild(detail);
                }

                test.childNodes[0].childNodes[2].appendChild(sectionbutton);
                resolve(test);
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
}

const test = await treesetting();

$(function(){

    //Cancel button to discard exit
    $("#cancel").type = "button";
    $("#cancel").on("click", function(e) {
        window.location.href = "/testmed/createteststructure";
    });

    //Append tree
    document.getElementById("tree").appendChild(test);

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
                document.getElementById("test-id").setAttribute("value", test.id.split("-")[1]);

                //Block other click event
                $(".formmodifybutton").off("click");
                $(".formdeletebutton").off("click");
                $(".addquestionbutton").off("click");
                $(".addquestionbutton").on("click", function(e) {
                    e.preventDefault();
                });
                $(".addsectionbutton").off("click");
                $(".addsectionbutton").on("click", function(e) {
                    e.preventDefault();
                });

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
                document.getElementById("test-id").setAttribute("value", test.id.split("-")[1]);

                //Block other click event
                $(".formmodifybutton").off("click");
                $(".formdeletebutton").off("click");
                $(".addquestionbutton").off("click");
                $(".addquestionbutton").on("click", function(e) {
                    e.preventDefault();
                });
                $(".addsectionbutton").off("click");
                $(".addsectionbutton").on("click", function(e) {
                    e.preventDefault();
                });

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
                            document.getElementById("test-id").setAttribute("value", test.id.split("-")[1]);
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
                                        if(err.status == 422) {
                                            let arr = err.responseJSON.errors.questiontitle;
                                            let errorfield = document.getElementById("questiontitle-error");
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
        });
    });

    //Hidden modify an delete code for summary
    $("summary").on("mouseover", function(e) {
        this.nextSibling.style.visibility = "visible";

        $(".deletemodifybutton").on("mouseover", function(e) {
            this.style.visibility = "visible";

            //Hover delete button
            $(this.childNodes[3]).on("mouseover", function(e) {
                this.classList.add("rounded-md");
                this.style.backgroundColor = "red"
            });

            $(this.childNodes[3]).on("mouseout", function(e) {
                this.style.backgroundColor = "white"
            });

            //Hover modify button
            $(this.childNodes[1]).on("mouseover", function(e) {
                this.classList.add("rounded-md");
                this.style.backgroundColor = "blue"
            });

            $(this.childNodes[1]).on("mouseout", function(e) {
                this.style.backgroundColor = "white"
            });
        });

        $(".deletemodifybutton").on("mouseout", function(e) {
            this.style.visibility = "hidden";
        });
    });

    $("summary").on("mouseout", function(e) {
        this.nextSibling.style.visibility = "hidden";
    });

    //Hidden modify an delete code for question
    $(".question-title").on("mouseover", function(e) {
        this.nextSibling.style.visibility = "visible";

        $(".deletemodifybutton").on("mouseover", function(e) {
            this.style.visibility = "visible";

            //Hover delete button
            $(this.childNodes[3]).on("mouseover", function(e) {
                this.classList.add("rounded-md");
                this.style.backgroundColor = "red"
            });

            $(this.childNodes[3]).on("mouseout", function(e) {
                this.style.backgroundColor = "white"
            });

            //Hover modify button
            $(this.childNodes[1]).on("mouseover", function(e) {
                this.classList.add("rounded-md");
                this.style.backgroundColor = "blue"
            });

            $(this.childNodes[1]).on("mouseout", function(e) {
                this.style.backgroundColor = "white"
            });

        });

        $(".deletemodifybutton").on("mouseout", function(e) {
            this.style.visibility = "hidden";
        });
    });

    $(".question-title").on("mouseout", function(e) {
        this.nextSibling.style.visibility = "hidden";
    });

    //Click Delete button
    $(".formdeletebutton").on("click", function(e) {
        $.ajax({
            type: "POST",
            url: "/testmed/createteststructure/ajax/deleteelement",
            data: $(this).serialize(),
            success: function(data) {
                if(data.status == 200) {
                    if(data.redirect) {
                        window.location.href = "/testmed/createteststructure?status=exit-status";
                    } else {
                        window.location.href = "/testmed/createteststructure";
                    }
                }
            }
        });
    });

    //Click Modify button
    $(".formmodifybutton").on("click", function(e) {

        $.ajax({
            type: "POST",
            url: "/testmed/createteststructure/ajax/createelementmodify",
            data: $(this).serialize(),
            success: function(data) {
                //Reading and pasting selector
                const i1 = data.indexOf("<body>");
                const i2 = data.indexOf("</body>");
                const bodyHTML = data.substring(i1 + "<body>".length, i2);

                let elementmodify = document.createElement("div");
                document.getElementsByClassName("constructor")[0].innerHTML = "";
                $(".constructor").append(elementmodify);
                elementmodify.outerHTML = bodyHTML;

                //Add button event for cancel button
                $(".cancel").on("click", function(e) {
                    e.preventDefault();
                    window.location.href = "/testmed/createteststructure";
                });

                let type = this.data.split("&")[1].split("=")[1]
                if(type == "test") {
                    $("#updatetest").on("click", function(e) {
                        e.preventDefault();
                        $.ajax({
                            method: "POST",
                            url: "/testmed/createteststructure/ajax/updatetest",
                            data: $("#testform").serialize(),
                            success: function(data) {

                                if(data.status == 200) {
                                    window.location.href = "/testmed/createteststructure";
                                }
                            },
                            error: function(err) {
                                if(err.status == 422) {
                                    let arr = err.responseJSON.errors.testname;
                                    let errorfield = document.getElementById("testname-error");
                                    for(let i=0; i<arr.length; i++) {
                                        let li = document.createElement("li");
                                        li.innerHTML = arr[i];
                                        errorfield.append(li);
                                    }
                                }
                            }
                        });
                    });
                } else if(type == "section") {
                    $("#updatesection").on("click", function(e) {
                        e.preventDefault();
                        $.ajax({
                            method: "POST",
                            url: "/testmed/createteststructure/ajax/updatesection",
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

                } else if(type == "question") {
                    $("#updatechoosequestion").on("click", function(e) {
                        e.preventDefault();
                        let type = document.getElementById("type").value;
                        $.ajax({
                            method: "POST",
                            url: "/testmed/createteststructure/ajax/update"+type+"question",
                            data: $("#choosequestionform").serialize(),
                            success: function(data) {

                                if(data.status == 200) {
                                    window.location.href = "/testmed/createteststructure";
                                }
                            },
                            error: function(err) {
                                if(err.status == 422) {
                                    if(type == "value") {
                                        let arr = err.responseJSON.errors.questiontitle;
                                        let errorfield = document.getElementById("questiontitle-error");
                                        for(let i=0; i<arr.length; i++) {
                                            let li = document.createElement("li");
                                            li.innerHTML = arr[i];
                                            errorfield.append(li);
                                        }
                                    } else if(type == "multiple") {
                                        let arr = err.responseJSON.errors.questiontitle;
                                        let errorfield = document.getElementById("questiontitle-error");
                                        for(let i=0; i<arr.length; i++) {
                                            let li = document.createElement("li");
                                            li.innerHTML = arr[i];
                                            errorfield.append(li);
                                        }
                                    }

                                }
                            }
                        });
                    });

                }
            }
        });
    });
});
