import './bootstrap';

import Alpine from 'alpinejs';
import jQuery, { timers } from 'jquery';
import { split } from 'postcss/lib/list';

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

                            //Settings hidden fields
                            let type = document.getElementById('type').getAttribute('value');
                            document.getElementById("test-id").setAttribute("value", test.id.split("-")[1]);

                            if(type == "multiple") {
                                let radiolenght = document.getElementById("radiolenght");
                                radiolenght.value = 0;
                                $.ajax({
                                    type: "GET",
                                    url: "/testmed/createteststructure/ajax/multiplequestionitem",
                                    success: function(data) {
                                        const i1 = data.indexOf("<body>");
                                        const i2 = data.indexOf("</body>");
                                        const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                        $("#addchoice").on("click", function(e) {
                                            let radiolist = document.getElementById("radiolist");
                                            let radio = document.createElement("div");
                                            radiolist.insertBefore(radio, radiolist.childNodes[radiolist.childNodes.length-2]);
                                            radio.outerHTML = bodyHTML;
                                            radio = document.getElementById("radio-input-");
                                            radio.id = radio.id + radiolenght.value;
                                            radio.name = radio.name + radiolenght.value;
                                            document.getElementById("radio-input-error-").id = document.getElementById("radio-input-error-").id + radiolenght.value;

                                            radiolenght.value = radiolist.childElementCount - 1;

                                        });
                                    }
                                });

                            } else if(type == "value") {
                                //Esclusivity of the list items
                                $(".rapidcheck").on("click", function(e) {
                                    for(let i=0; i<101; i++) {
                                        let prova = document.getElementById("checkbox-single-" + i);
                                        prova.checked = false;
                                    }
                                    console.log(this.value);
                                    if(this.value == 10) {
                                        for(let i=0; i<11; i++) {
                                            let singlecheck = document.getElementById("checkbox-single-" + i);
                                            singlecheck.checked = true;
                                        }
                                    } else if(this.value == 20) {
                                        for(let i=0; i<21; i++) {
                                            let singlecheck = document.getElementById("checkbox-single-" + i);
                                            singlecheck.checked = true;
                                        }
                                    } else if(this.value == 50) {
                                        for(let i=0; i<51; i++) {
                                            let singlecheck = document.getElementById("checkbox-single-" + i);
                                            singlecheck.checked = true;
                                        }
                                    } else if(this.value == 100) {
                                        for(let i=0; i<101; i++) {
                                            let singlecheck = document.getElementById("checkbox-single-" + i);
                                            singlecheck.checked = true;
                                        }
                                    }

                                });
                                $(".singlecheck").on("click", function(e) {

                                    let rapidcheck = document.getElementsByClassName("rapidcheck");
                                    for(let i=0; i<rapidcheck.length; i++) {
                                        rapidcheck[i].checked = false;
                                    }
                                });

                                let radiolenght = document.getElementById("radiolenght");
                                radiolenght.value = 0;
                                $.ajax({
                                    type: "GET",
                                    url: "/testmed/createteststructure/ajax/valuequestionitem",
                                    success: function(data) {
                                        const i1 = data.indexOf("<body>");
                                        const i2 = data.indexOf("</body>");
                                        const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                        $("#addchoice").on("click", function(e) {
                                            let checklist = document.getElementById("valueslist");
                                            let check = document.createElement("div");
                                            checklist.insertBefore(check, checklist.childNodes[checklist.childNodes.length-2]);
                                            check.outerHTML = bodyHTML;
                                            check = document.getElementById("checkbox-personal-");
                                            check.id = check.id + (+radiolenght.value + 1);
                                            check.checked = true;
                                            let checkinput = document.getElementById("checkbox-personal-text-");
                                            checkinput.id = checkinput.id + (+radiolenght.value + 1);
                                            checkinput.name = checkinput.name + (+radiolenght.value + 1);
                                            document.getElementById("checkbox-personal-text-error-").id = document.getElementById("checkbox-personal-text-error-").id + (+radiolenght.value + 1);

                                            radiolenght.value = +radiolenght.value + 1;

                                            $(check).on("click", function(e) {
                                                if(this.checked) {
                                                    checkinput.disabled = false;
                                                } else {
                                                    checkinput.disabled = true;
                                                }
                                            });

                                            $(checkinput).on("input", function(e) {
                                                if (this.value !== '' && !isNaN(this.value) && Number(this.value) > 100) {
                                                    document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.add("hidden");
                                                } else {
                                                    document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.remove("hidden");
                                                }
                                            });

                                        });
                                    }
                                });
                            }

                            $("#storechoosequestion").on("click", function(e) {
                                e.preventDefault();
                                $.ajax({
                                    type: "POST",
                                    url: "/testmed/createteststructure/ajax/add"+type+"question",
                                    data: $("#choosequestionform").serialize(),
                                    success: function(data) {
                                        console.log(data);
                                        if(data.status == 200) {
                                            window.location.href = "/testmed/createteststructure";
                                        }

                                    },
                                    error: function(err) {
                                        console.log(err);
                                        if(err.status == 422) {
                                            if(type == "multiple") {
                                                for(let i=0; i<radiolenght.value; i++) {
                                                    let errorfield = document.getElementById("radio-input-error-"+i);
                                                    errorfield.innerHTML = "";
                                                    if(err.responseJSON.errors["radioinput"+i]) {
                                                        let arr = err.responseJSON.errors["radioinput"+i];
                                                        for(let m=0; m<arr.length; m++) {
                                                            let li = document.createElement("li");
                                                            li.innerHTML = arr[m].replace("radioinput"+i, "");
                                                            errorfield.append(li);
                                                        }
                                                    }
                                                }
                                            }

                                            if(type == "value") {
                                                let errorfield = document.getElementById("values-input-error");
                                                errorfield.innerHTML = "";
                                                if(err.responseJSON.errors.values) {
                                                    let arr = err.responseJSON.errors.values;
                                                    for(let m=0; m<arr.length; m++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[m];
                                                        errorfield.append(li);
                                                    }
                                                }
                                            }

                                            let errorfield = document.getElementById("questiontitle-error");
                                            errorfield.innerHTML = "";
                                            if(err.responseJSON.errors.questiontitle) {
                                                let arr = err.responseJSON.errors.questiontitle;
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
                    let type = document.getElementById("type").value;

                    if( type == "multiple") {
                        //Delete button interaction
                        $(".multiplelistitem").on("mouseover", function(e) {
                            this.childNodes[1].childNodes[5].childNodes[1].classList.remove("hidden");
                        });
                        $(".multiplelistitem").on("mouseout", function(e) {
                            this.childNodes[1].childNodes[5].childNodes[1].classList.add("hidden");
                        });
                        $(".cancelitem").on("mouseover", function(e) {
                            this.childNodes[1].classList.add("rounded-md");
                            this.childNodes[1].style.backgroundColor = "red"
                            this.childNodes[1].classList.remove("hidden");
                        });
                        $(".cancelitem").on("mouseout", function(e) {
                            this.childNodes[1].classList.add("hidden");
                            this.childNodes[1].classList.remove("rounded-md");
                            this.childNodes[1].style.backgroundColor = null;
                        });
                        $(".cancelitem").on("click", function(e) {
                            let id = this.previousSibling.previousSibling.id.split("-")[2]
                            let cicle = document.getElementById("radiolenght").value - 1 - id;
                            console.log(id);
                            for(let i=0; i<cicle; i++) {
                                let element = document.getElementById("radio-input-"+ (+id +i +1));
                                element.id = "radio-input-" + (element.id.split("-")[2] - 1);
                                element.name = "radioinput" + element.id.split("-")[2];
                                element.parentElement.nextSibling.nextSibling.id = "radio-input-error-" + element.id.split("-")[2];
                            }
                            this.parentElement.parentElement.remove();
                            document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                        });

                        let radiolenght = document.getElementById("radiolenght");
                        let radiolist = document.getElementById("radiolist");
                        radiolenght.value = radiolist.childElementCount - 1;
                        $.ajax({
                            type: "GET",
                            url: "/testmed/createteststructure/ajax/multiplequestionitem",
                            success: function(data) {
                                const i1 = data.indexOf("<body>");
                                const i2 = data.indexOf("</body>");
                                const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                $("#addchoice").on("click", function(e) {
                                    let radio = document.createElement("div");
                                    radiolist.insertBefore(radio, radiolist.childNodes[radiolist.childNodes.length-2]);
                                    radio.outerHTML = bodyHTML;
                                    radio = document.getElementById("radio-input-");
                                    radio.id = radio.id + radiolenght.value;
                                    radio.name = radio.name + radiolenght.value;
                                    document.getElementById("radio-input-error-").id = document.getElementById("radio-input-error-").id + radiolenght.value;

                                    radiolenght.value = radiolist.childElementCount - 1;

                                    //Delete button interaction
                                    $(".multiplelistitem").on("mouseover", function(e) {
                                        this.childNodes[1].childNodes[5].childNodes[1].classList.remove("hidden");
                                    });
                                    $(".multiplelistitem").on("mouseout", function(e) {
                                        this.childNodes[1].childNodes[5].childNodes[1].classList.add("hidden");
                                    });
                                    $(".cancelitem").on("mouseover", function(e) {
                                        this.childNodes[1].classList.add("rounded-md");
                                        this.childNodes[1].style.backgroundColor = "red"
                                        this.childNodes[1].classList.remove("hidden");
                                    });
                                    $(".cancelitem").on("mouseout", function(e) {
                                        this.childNodes[1].classList.add("hidden");
                                        this.childNodes[1].classList.remove("rounded-md");
                                        this.childNodes[1].style.backgroundColor = null;
                                    });

                                });
                            }
                        });
                    } else if(type == "value") {
                        //Esclusivity of the list items
                        $(".rapidcheck").on("click", function(e) {
                            for(let i=0; i<101; i++) {
                                let prova = document.getElementById("checkbox-single-" + i);
                                prova.checked = false;
                            }
                            console.log(this.value);
                            if(this.value == 10) {
                                for(let i=0; i<11; i++) {
                                    let singlecheck = document.getElementById("checkbox-single-" + i);
                                    singlecheck.checked = true;
                                }
                            } else if(this.value == 20) {
                                for(let i=0; i<21; i++) {
                                    let singlecheck = document.getElementById("checkbox-single-" + i);
                                    singlecheck.checked = true;
                                }
                            } else if(this.value == 50) {
                                for(let i=0; i<51; i++) {
                                    let singlecheck = document.getElementById("checkbox-single-" + i);
                                    singlecheck.checked = true;
                                }
                            } else if(this.value == 100) {
                                for(let i=0; i<101; i++) {
                                    let singlecheck = document.getElementById("checkbox-single-" + i);
                                    singlecheck.checked = true;
                                }
                            }

                        });
                        $(".singlecheck").on("click", function(e) {

                            let rapidcheck = document.getElementsByClassName("rapidcheck");
                            for(let i=0; i<rapidcheck.length; i++) {
                                rapidcheck[i].checked = false;
                            }
                        });

                        let radiolenght = document.getElementById("radiolenght");
                        $.ajax({
                            type: "GET",
                            url: "/testmed/createteststructure/ajax/valuequestionitem",
                            success: function(data) {
                                const i1 = data.indexOf("<body>");
                                const i2 = data.indexOf("</body>");
                                const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                $("#addchoice").on("click", function(e) {
                                    let checklist = document.getElementById("valueslist");
                                    let check = document.createElement("div");
                                    checklist.insertBefore(check, checklist.childNodes[checklist.childNodes.length-2]);
                                    check.outerHTML = bodyHTML;
                                    check = document.getElementById("checkbox-personal-");
                                    check.id = check.id + (+radiolenght.value + 1);
                                    check.checked = true;
                                    let checkinput = document.getElementById("checkbox-personal-text-");
                                    checkinput.id = checkinput.id + (+radiolenght.value + 1);
                                    checkinput.name = checkinput.name + (+radiolenght.value + 1);
                                    document.getElementById("checkbox-personal-text-error-").id = document.getElementById("checkbox-personal-text-error-").id + (+radiolenght.value + 1);

                                    radiolenght.value = +radiolenght.value + 1;

                                    $(check).on("click", function(e) {
                                        if(this.checked) {
                                            checkinput.disabled = false;
                                        } else {
                                            checkinput.disabled = true;
                                        }
                                    });

                                    $(checkinput).on("input", function(e) {
                                        if (this.value !== '' && !isNaN(this.value) && Number(this.value) > 100) {
                                            document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.add("hidden");
                                        } else {
                                            document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.remove("hidden");
                                        }
                                    });

                                });
                            }
                        });
                    }


                    $("#updatechoosequestion").on("click", function(e) {
                        e.preventDefault();
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
                                    console.log(err);
                                    if(type == "multiple") {
                                        for(let i=0; i<radiolenght.value; i++) {
                                            let errorfield = document.getElementById("radio-input-error-"+i);
                                            errorfield.innerHTML = "";
                                            if(err.responseJSON.errors["radioinput"+i]) {
                                                let arr = err.responseJSON.errors["radioinput"+i];
                                                for(let m=0; m<arr.length; m++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[m].replace("radioinput"+i, "");
                                                    errorfield.append(li);
                                                }
                                            }
                                        }
                                    }

                                    if(type == "value") {
                                        let errorfield = document.getElementById("values-input-error");
                                        errorfield.innerHTML = "";
                                        if(err.responseJSON.errors.values) {
                                            let arr = err.responseJSON.errors.values;
                                            for(let m=0; m<arr.length; m++) {
                                                let li = document.createElement("li");
                                                li.innerHTML = arr[m];
                                                errorfield.append(li);
                                            }
                                        }
                                    }

                                    let errorfield = document.getElementById("questiontitle-error");
                                    errorfield.innerHTML = "";
                                    if(err.responseJSON.errors.questiontitle) {
                                        let arr = err.responseJSON.errors.questiontitle;
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
            },
            error: function(err) {
                console.log(err);
            }
        });
    });
});
