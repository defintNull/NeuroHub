import './bootstrap';

import Alpine from 'alpinejs';
import jQuery, { timers } from 'jquery';
import { split } from 'postcss/lib/list';
import 'jquery-ui/dist/jquery-ui';

window.Alpine = Alpine;
window.$ = jQuery;

Alpine.start();

// The function recoursively open the sections json,
// create the section tree and append it to the ul element of the testnode
function sectionNode(testnode, sections, deletemodifybutton) {

    //Recoursive code
    let count = Object.keys(sections).length;
    for(let i=0; i<count; i++) {
        let section = sections["section" + (i+1)];

        //Creation container for positioning
        let positioner = document.createElement("div");
        positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6");

        // Creation html section object
        let sectionnode = document.createElement("li");
        if(testnode.classList.contains('test')) {
            sectionnode.classList.add('section', "sortable-section-item");
        } else if(testnode.classList.contains('section')) {
            sectionnode.classList.add('section', "sortable-subsection-item");
        }
        sectionnode.id = "section-" + section.id;
        let detail = document.createElement("details");
        detail.open = true;
        let summary = document.createElement("summary");
        detail.appendChild(summary);

        //Section Name
        let summarytitle = document.createElement("p");
        summarytitle.innerHTML = section.name;
        summarytitle.classList.add("truncate");
        positioner.appendChild(summarytitle);

        //Modify Delete button
        let moddelbutton = document.createElement("div");
        moddelbutton = deletemodifybutton.cloneNode(true);
        moddelbutton.childNodes[0].childNodes[1].childNodes[3].value = "section";
        moddelbutton.childNodes[0].childNodes[1].childNodes[5].value = section.id;
        moddelbutton.childNodes[0].childNodes[3].childNodes[3].value = "section";
        moddelbutton.childNodes[0].childNodes[3].childNodes[5].value = section.id;
        positioner.appendChild(moddelbutton);

        //List
        let sortable = document.createElement("ul");
        detail.appendChild(sortable);

        summary.appendChild(positioner);
        sectionnode.appendChild(detail);

        if('sections' in section) {
            sortable.classList.add("sortable-section");
            //Ricoursive function call
            sectionNode(sectionnode, section.sections, deletemodifybutton);

            //Add section button
            sectionnode.childNodes[0].childNodes[1].append(document.getElementsByClassName("sectionbutton")[0].cloneNode(true));
        } else {
            if('questions' in section) {
                sortable.classList.add("sortable-question");
                let questioncount = Object.keys(section.questions).length;
                for(let i=0; i<questioncount; i++) {
                    //Creation container for positioning
                    let positioner = document.createElement("div");
                    positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6");

                    //Creation html question node
                    let questionnode = document.createElement("li");
                    questionnode.classList.add('question', "sortable-question-item");
                    questionnode.id = "question-" + section.questions["question"+ (i+1)].id;
                    sectionnode.childNodes[0].childNodes[1].appendChild(questionnode)

                    //Questiontitle
                    let questiontitle = document.createElement("div");
                    positioner.appendChild(questiontitle);
                    questiontitle.outerHTML = "<div class=\"question-title\">" + section.questions["question"+ (i+1)].title + "</div>";

                    //Modify Delete button
                    moddelbutton = document.createElement("div");
                    moddelbutton = deletemodifybutton.cloneNode(true);
                    moddelbutton.childNodes[0].childNodes[1].childNodes[3].value = "question";
                    moddelbutton.childNodes[0].childNodes[1].childNodes[5].value = section.questions["question"+ (i+1)].id;
                    moddelbutton.childNodes[0].childNodes[3].childNodes[3].value = "question";
                    moddelbutton.childNodes[0].childNodes[3].childNodes[5].value = section.questions["question"+ (i+1)].id;
                    positioner.appendChild(moddelbutton);
                    sectionnode.childNodes[0].childNodes[1].childNodes[i].appendChild(positioner);
                }
                //Add question button
                sectionnode.childNodes[0].childNodes[1].append(document.getElementsByClassName("questionbutton")[0].cloneNode(true));

            } else {
                //Aqq question and section button
                sectionnode.childNodes[0].childNodes[1].append(document.getElementsByClassName("sectionbutton")[0].cloneNode(true));
                sectionnode.childNodes[0].childNodes[1].append(document.getElementsByClassName("questionbutton")[0].cloneNode(true));
            }
        }
        testnode.childNodes[0].childNodes[1].appendChild(sectionnode);
    }
}

async function deletemodifyButton() {
    return new Promise((resolve, reject) => {
        //Retrieve add-section-button
        $.ajax({
            type: "GET",
            url: "/testmed/createteststructure/ajax/createdeletemodifybutton",
            success: function(data) {
                let deletebutton = document.createElement("div");
                let i1 = data.indexOf("<delete>");
                let i2 = data.indexOf("</delete>");
                let bodyHTML = data.substring(i1 + "<delete>".length, i2);
                deletebutton.innerHTML = bodyHTML;

                let deletemodifybutton = document.createElement("div");
                let container = document.createElement("div");
                container.classList.add("w-20", "h-6", "inline-flex","items-center");
                deletemodifybutton.classList.add('deletemodifybutton', "flex", "flex-row", "items-center");
                i1 = data.indexOf("<modify>");
                i2 = data.indexOf("</modify>");
                bodyHTML = data.substring(i1 + "<modify>".length, i2);
                deletemodifybutton.innerHTML = bodyHTML;
                deletemodifybutton.appendChild(deletebutton.childNodes[1])
                container.appendChild(deletemodifybutton)
                resolve(container);
            },
            error: function(err) {
                reject(err);
            }
        });
    });
}

async function treesetting() {
    //Getting buttons
    let deletemodifybutton = await deletemodifyButton();

    return new Promise((resolve, reject) => {
        //Retreiving test tree
        $.ajax({
            type: "GET",
            url: "/testmed/createteststructure/ajax/createtree",
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

                //Modify and Delete button
                let moddelbutton = document.createElement("div");
                moddelbutton = deletemodifybutton.cloneNode(true);
                moddelbutton.childNodes[0].childNodes[1].childNodes[3].value = "test";
                moddelbutton.childNodes[0].childNodes[1].childNodes[5].value = test.id.split("-")[1];
                moddelbutton.childNodes[0].childNodes[3].childNodes[3].value = "test";
                moddelbutton.childNodes[0].childNodes[3].childNodes[5].value = test.id.split("-")[1];
                positioner.appendChild(moddelbutton);

                if("sections" in data.test) {
                    let sortable = document.createElement("ul");
                    sortable.classList.add("sortable-test");
                    detail.appendChild(sortable);
                    summary.appendChild(positioner);
                    test.appendChild(detail);
                    sectionNode(test, data.test.sections, deletemodifybutton);
                } else {
                    let sortable = document.createElement("ul");
                    sortable.classList.add("sortable-test");
                    detail.appendChild(sortable);
                    summary.appendChild(positioner);
                    test.appendChild(detail);
                }

                test.childNodes[0].childNodes[1].appendChild(document.getElementsByClassName("sectionbutton")[0].cloneNode(true));
                resolve(test);
            },
            error: function(err) {
            }
        });
    });
}

const test = await treesetting();

function reload() {
    const startpage = document.getElementsByClassName("constructor")[0].innerHTML;
    $(".addsectionbutton").off("click").on("click", function(e) {
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
                    document.getElementById("new-section").replaceWith(document.getElementsByClassName("sectionbutton")[0].cloneNode(true));
                    document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                    reload();
                });
                $("#storesection").on("click", function(e) {
                    e.preventDefault();
                    $.ajax({
                        type: "POST",
                        url: "/testmed/createteststructure/ajax/addsection",
                        data: $("#sectionform").serialize(),
                        success: function(data) {

                            if(data.status == 200) {
                                //Creation container for positioning
                                let positioner = document.createElement("div");
                                positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6");

                                // Creation html section object
                                let sectionnode = document.createElement("li");
                                if(data.parent == "test") {
                                    sectionnode.classList.add('section', "sortable-section-item");
                                } else if(data.parent == "section") {
                                    sectionnode.classList.add('section', "sortable-subsection-item");
                                }
                                sectionnode.id = "section-" + data.id;
                                let detail = document.createElement("details");
                                detail.open = true;
                                let summary = document.createElement("summary");
                                detail.appendChild(summary);

                                //Section Name
                                let summarytitle = document.createElement("p");
                                summarytitle.innerHTML = data.name;
                                summarytitle.classList.add("truncate");
                                positioner.appendChild(summarytitle);

                                //Modify Delete button
                                let moddelbutton = document.createElement("div");
                                moddelbutton = document.getElementsByClassName("deletemodifybutton")[0].parentElement.cloneNode(true);
                                moddelbutton.childNodes[0].childNodes[1].childNodes[3].value = "section";
                                moddelbutton.childNodes[0].childNodes[1].childNodes[5].value = data.id;
                                moddelbutton.childNodes[0].childNodes[3].childNodes[3].value = "section";
                                moddelbutton.childNodes[0].childNodes[3].childNodes[5].value = data.id;
                                positioner.appendChild(moddelbutton);

                                //List
                                let sortable = document.createElement("ul");
                                sortable.classList.add("sortable-section");
                                detail.appendChild(sortable);

                                summary.appendChild(positioner);
                                sectionnode.appendChild(detail);

                                //Aqq question and section button
                                let newsection = document.getElementById("new-section");
                                if(newsection.parentElement.querySelector(":scope > .questionbutton")) {
                                    newsection.parentElement.querySelector(":scope > .questionbutton").remove();
                                }
                                sectionnode.childNodes[0].childNodes[1].append(document.getElementsByClassName("sectionbutton")[0].cloneNode(true));
                                sectionnode.childNodes[0].childNodes[1].append(document.getElementsByClassName("questionbutton")[0].cloneNode(true));

                                newsection.parentElement.appendChild(document.getElementsByClassName("sectionbutton")[0].cloneNode(true));
                                newsection.replaceWith(sectionnode);
                                document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                                reload();
                            }
                        },
                        error: function(err) {

                            if(err.status == 422) {
                                let arr = err.responseJSON.errors.sectionname;
                                let errorfield = document.getElementById("sectionname-error");
                                errorfield.innerHTML = "";
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

    $(".addquestionbutton").off("click").on("click", function(e) {
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
                    document.getElementById("new-question").replaceWith(document.getElementsByClassName("questionbutton")[0].cloneNode(true));
                    document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                    reload();
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
                                            document.getElementById("new-question").replaceWith(document.getElementsByClassName("questionbutton")[0].cloneNode(true));
                                            document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                                            reload();
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
                                            radiolist.insertBefore(radio, radiolist.childNodes[radiolist.childNodes.length-4]);
                                            radio.outerHTML = bodyHTML;
                                            radio = document.getElementById("radio-input-");
                                            radio.id = radio.id + radiolenght.value;
                                            radio.name = radio.name + radiolenght.value;
                                            document.getElementById("radio-input-error-").id = document.getElementById("radio-input-error-").id + radiolenght.value;

                                            radiolenght.value = radiolist.childElementCount - 2;

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
                                            $(".cancelitem").off("click").on("click", function(e) {
                                                let id = this.previousSibling.previousSibling.id.split("-")[2]
                                                let cicle = document.getElementById("radiolenght").value - 1 - id;
                                                for(let i=0; i<cicle; i++) {
                                                    let element = document.getElementById("radio-input-"+ (+id +i +1));
                                                    element.id = "radio-input-" + (element.id.split("-")[2] - 1);
                                                    element.name = "radioinput" + element.id.split("-")[2];
                                                    element.parentElement.nextSibling.nextSibling.id = "radio-input-error-" + element.id.split("-")[2];
                                                }
                                                this.parentElement.parentElement.remove();
                                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
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

                                            $(check).off("click").on("click", function(e) {
                                                if(this.checked) {
                                                    //Enabling text field
                                                    this.nextSibling.nextSibling.disabled = false;

                                                    //Find new id
                                                    let id = 1;
                                                    let looper = this.parentElement.previousSibling.previousSibling;
                                                    while(looper.nodeName != "LI") {
                                                        if(looper.childNodes[1].id.split("-")[4] == "disabled") {
                                                            looper = looper.previousSibling.previousSibling.previousSibling.previousSibling;
                                                        } else {
                                                            id = +looper.childNodes[1].id.split("-")[4] +1;
                                                            break;
                                                        }
                                                    }

                                                    //Shifting ids
                                                    let cicle = document.getElementById("radiolenght").value - id +1;
                                                    for(let i=0; i<cicle; i++) {
                                                        let element = document.getElementById("checkbox-personal-text-"+ (+id +cicle -i -1));
                                                        element.previousSibling.previousSibling.id = "checkbox-personal-" + (+element.id.split("-")[3] +1);
                                                        element.id = "checkbox-personal-text-" + (+element.id.split("-")[3] +1);
                                                        element.name = "checkboxpersonal" + element.id.split("-")[3];
                                                        element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                                    }

                                                    //Re-enabling checkbox
                                                    this.nextSibling.nextSibling.id = this.nextSibling.nextSibling.id.replace("disabled", id);
                                                    this.nextSibling.nextSibling.name = this.nextSibling.nextSibling.name.replace("disabled", id);
                                                    this.id = this.id.replace("disabled", id);
                                                    this.classList.remove("checkboxdisabled");
                                                    this.parentElement.nextSibling.nextSibling.childNodes[1].id = this.parentElement.nextSibling.nextSibling.childNodes[1].id.replace("disabled", id);

                                                    //Adjust checkbox lenght
                                                    document.getElementById("radiolenght").value = +document.getElementById("radiolenght").value +1;
                                                } else {
                                                    let id = this.id.split("-")[2]

                                                    //Disabling uncheck field
                                                    this.nextSibling.nextSibling.disabled = true;
                                                    this.nextSibling.nextSibling.id = "checkbox-personal-text-disabled";
                                                    this.nextSibling.nextSibling.name = "checkboxpersonaldisabled";
                                                    this.id = "checkbox-personal-disabled";
                                                    this.classList.add("checkboxdisabled");
                                                    this.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-disabled";

                                                    //Reducing by one ids of active checkbox greather than unchecked
                                                    let cicle = document.getElementById("radiolenght").value - id;
                                                    for(let i=0; i<cicle; i++) {
                                                        let element = document.getElementById("checkbox-personal-text-"+ (+id +i +1));
                                                        element.previousSibling.previousSibling.id = "checkbox-personal-" + (element.id.split("-")[3] - 1);
                                                        element.id = "checkbox-personal-text-" + (element.id.split("-")[3] - 1);
                                                        element.name = "checkboxpersonal" + element.id.split("-")[3];
                                                        element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                                    }

                                                    //Fixing checkbox lenght
                                                    document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;

                                                }
                                            });

                                            $(checkinput).on("input", function(e) {
                                                if (this.value !== '' && !isNaN(this.value) && Number(this.value) > 100) {
                                                    document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.add("hidden");
                                                } else {
                                                    document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.remove("hidden");
                                                }
                                            });

                                            //Delete button interaction
                                            $(".valuelistitem").on("mouseover", function(e) {
                                                this.childNodes[5].childNodes[1].classList.remove("hidden");
                                            });
                                            $(".valuelistitem").on("mouseout", function(e) {
                                                this.childNodes[5].childNodes[1].classList.add("hidden");
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
                                            $(".cancelitem").off("click").on("click", function(e) {
                                                let id = this.previousSibling.previousSibling.id.split("-")[3]
                                                let cicle = document.getElementById("radiolenght").value - id;
                                                this.parentElement.nextSibling.nextSibling.remove();
                                                this.parentElement.remove();

                                                for(let i=0; i<cicle; i++) {
                                                    let element = document.getElementById("checkbox-personal-text-"+ (+id +i +1));
                                                    element.previousSibling.previousSibling.id = "checkbox-personal-" + (element.id.split("-")[3] - 1);
                                                    element.id = "checkbox-personal-text-" + (element.id.split("-")[3] - 1);
                                                    element.name = "checkboxpersonal" + element.id.split("-")[3];
                                                    element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                                }
                                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                            });

                                        });
                                    }
                                });
                            } else if(type == "open") {

                            } else if(type == "multipleselection") {
                                let radiolenght = document.getElementById("radiolenght");
                                radiolenght.value = 0;
                                $.ajax({
                                    type: "GET",
                                    url: "/testmed/createteststructure/ajax/multipleselectionquestionitem",
                                    success: function(data) {
                                        const i1 = data.indexOf("<body>");
                                        const i2 = data.indexOf("</body>");
                                        const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                        $("#addchoice").on("click", function(e) {
                                            let checklist = document.getElementById("valueslist");
                                            let check = document.createElement("div");
                                            checklist.insertBefore(check, checklist.childNodes[checklist.childNodes.length-2]);
                                            check.outerHTML = bodyHTML;
                                            check = document.getElementById("checkbox-");
                                            check.id = check.id + (+radiolenght.value + 1);
                                            let checkinput = document.getElementById("checkbox-text-");
                                            checkinput.id = checkinput.id + (+radiolenght.value + 1);
                                            checkinput.name = checkinput.name + (+radiolenght.value + 1);
                                            document.getElementById("checkbox-text-error-").id = document.getElementById("checkbox-text-error-").id + (+radiolenght.value + 1);

                                            radiolenght.value = +radiolenght.value + 1;

                                            //Delete button interaction
                                            $(".valuelistitem").on("mouseover", function(e) {
                                                this.childNodes[5].childNodes[1].classList.remove("hidden");
                                            });
                                            $(".valuelistitem").on("mouseout", function(e) {
                                                this.childNodes[5].childNodes[1].classList.add("hidden");
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
                                            $(".cancelitem").off("click").on("click", function(e) {
                                                let id = this.previousSibling.previousSibling.id.split("-")[2]
                                                let cicle = document.getElementById("radiolenght").value - id;
                                                this.parentElement.nextSibling.nextSibling.remove();
                                                this.parentElement.remove();

                                                for(let i=0; i<cicle; i++) {
                                                    let element = document.getElementById("checkbox-text-"+ (+id +i +1));
                                                    element.previousSibling.previousSibling.id = "checkbox-" + (element.id.split("-")[2] - 1);
                                                    element.id = "checkbox-text-" + (element.id.split("-")[2] - 1);
                                                    element.name = "checkbox" + element.id.split("-")[2];
                                                    element.parentElement.nextSibling.nextSibling.id = "checkbox-text-error-" + element.id.split("-")[2];
                                                }
                                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                            });

                                        });
                                    }
                                });
                            } else if(type == "image") {
                                let radiolenght = document.getElementById("radiolenght");
                                radiolenght.value = 0;
                                $.ajax({
                                    type: "GET",
                                    url: "/testmed/createteststructure/ajax/imagequestionitem",
                                    success: function(data) {
                                        const i1 = data.indexOf("<body>");
                                        const i2 = data.indexOf("</body>");
                                        const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                        $("#addchoice").on("click", function(e) {
                                            let radiolist = document.getElementById("radiolist");
                                            let radio = document.createElement("div");
                                            radiolist.insertBefore(radio, radiolist.childNodes[radiolist.childNodes.length-2]);
                                            radio.outerHTML = bodyHTML;
                                            radio = document.getElementById("image-input-");
                                            radio.id = radio.id + radiolenght.value;
                                            radio.name = radio.name + radiolenght.value;
                                            let label = document.getElementById("image-input-label-");
                                            label.id = label.id + radiolenght.value;
                                            label.setAttribute("for", label.getAttribute("for") + radiolenght.value);
                                            let span = document.getElementById("file-name-");
                                            span.id = span.id + radiolenght.value;
                                            let imagepreview = document.getElementById("image-preview-");
                                            imagepreview.id = imagepreview.id + radiolenght.value;
                                            document.getElementById("image-input-error-").id = document.getElementById("image-input-error-").id + radiolenght.value;

                                            radiolenght.value = radiolist.childElementCount - 1;

                                            //Delete button interaction
                                            $(".imagelistitem").on("mouseover", function(e) {
                                                this.childNodes[1].childNodes[7].childNodes[1].classList.remove("hidden");
                                            });
                                            $(".imagelistitem").on("mouseout", function(e) {
                                                this.childNodes[1].childNodes[7].childNodes[1].classList.add("hidden");
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
                                            $(".cancelitem").off("click").on("click", function(e) {
                                                let id = this.previousSibling.previousSibling.previousSibling.previousSibling.childNodes[3].id.split("-")[2]
                                                let cicle = document.getElementById("radiolenght").value - 1 - id;
                                                for(let i=0; i<cicle; i++) {
                                                    let element = document.getElementById("image-input-" + (+id +i +1));
                                                    element.id = "image-input-" + (element.id.split("-")[2] - 1);
                                                    element.name = "imageinput" + element.id.split("-")[2];
                                                    let label = document.getElementById("image-input-label-" + (+id +i +1));
                                                    label.id = "image-input-label-" + element.id.split("-")[2];
                                                    label.setAttribute("for", "image-input-" + element.id.split("-")[2]);
                                                    let span = document.getElementById("file-name-" + (+id +i +1));
                                                    span.id = "file-name-" + element.id.split("-")[2];
                                                    let imagepreview = document.getElementById("image-preview-" + (+id +i +1));
                                                    imagepreview.id = "image-preview-" + element.id.split("-")[2];
                                                    document.getElementById("image-input-error-" + (+id +i +1)).id = "image-input-error-" + element.id.split("-")[2];
                                                }
                                                this.parentElement.parentElement.remove();
                                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                            });

                                            //Preview Image
                                            $(".imageinput").off("change").on("change", function(e) {
                                                var file = e.target.files[0];
                                                let id = this.id.split("-")[2]

                                                if (file) {
                                                    document.getElementById("file-name-" + id).classList.add("hidden");
                                                    var reader = new FileReader();

                                                    reader.onload = function(e) {
                                                        // Display the preview image
                                                        var preview = document.getElementById('image-preview-' + id);
                                                        preview.src = e.target.result;
                                                        preview.classList.remove('hidden');

                                                        // Display the file name
                                                        document.getElementById('file-name-' + id).textContent = file.name;
                                                    }

                                                    reader.readAsDataURL(file); // Convert the file to a data URL
                                                }
                                            });

                                        });

                                    }
                                });
                            }

                            $("#storechoosequestion").on("click", function(e) {
                                e.preventDefault();
                                let formData = new FormData(document.getElementById("choosequestionform"));
                                $.ajax({
                                    type: "POST",
                                    url: "/testmed/createteststructure/ajax/add"+type+"question",
                                    data: formData,
                                    contentType: false,
                                    processData: false,
                                    success: function(data) {

                                        if(data.status == 200) {
                                            //Creation container for positioning
                                            let positioner = document.createElement("div");
                                            positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6");

                                            //Creation html question node
                                            let questionnode = document.createElement("li");
                                            questionnode.classList.add('question', "sortable-question-item");
                                            questionnode.id = "question-" + data.id;

                                            //Questiontitle
                                            let questiontitle = document.createElement("div");
                                            positioner.appendChild(questiontitle);
                                            questiontitle.outerHTML = "<div class=\"question-title\">" + data.title + "</div>";

                                            //Modify Delete button
                                            let moddelbutton = document.getElementsByClassName("deletemodifybutton")[0].parentElement.cloneNode(true);
                                            moddelbutton.childNodes[0].childNodes[1].childNodes[3].value = "question";
                                            moddelbutton.childNodes[0].childNodes[1].childNodes[5].value = data.id;
                                            moddelbutton.childNodes[0].childNodes[3].childNodes[3].value = "question";
                                            moddelbutton.childNodes[0].childNodes[3].childNodes[5].value = data.id;
                                            positioner.appendChild(moddelbutton);

                                            //Append
                                            questionnode.appendChild(positioner);

                                            //Aqq question button
                                            let newquestion = document.getElementById("new-question");
                                            if(newquestion.parentElement.querySelector(".sectionbutton")) {
                                                newquestion.parentElement.querySelector(".sectionbutton").remove();
                                            }
                                            newquestion.parentElement.append(document.getElementsByClassName("questionbutton")[0].cloneNode(true));

                                            if(newquestion.parentElement.classList.contains("sortable-section")) {
                                                newquestion.parentElement.classList.replace("sortable-section", "sortable-question");
                                            }
                                            newquestion.replaceWith(questionnode);
                                            document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                                            reload();
                                        }

                                    },
                                    error: function(err) {
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
                                                let errortext = document.getElementById("questiontext-error");
                                                errortext.innerHTML = "";
                                                if(err.responseJSON.errors.questiontext) {
                                                    let arr = err.responseJSON.errors.questiontext;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("questiontext", "question text");
                                                        errortext.append(li);
                                                    }
                                                }
                                                let errorradiosection = document.getElementById("radio-section-error");
                                                errorradiosection.innerHTML = "";
                                                if(err.responseJSON.errors.radiosection) {
                                                    let arr = err.responseJSON.errors.radiosection;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("radiosection", "radio section");
                                                        errorradiosection.append(li);
                                                    }
                                                }
                                            } else if(type == "value") {
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
                                                let errortext = document.getElementById("questiontext-error");
                                                errortext.innerHTML = "";
                                                if(err.responseJSON.errors.questiontext) {
                                                    let arr = err.responseJSON.errors.questiontext;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("questiontext", "question text");
                                                        errortext.append(li);
                                                    }
                                                }
                                            } else if(type == "open") {
                                                let errortext = document.getElementById("questiontext-error");
                                                errortext.innerHTML = "";
                                                if(err.responseJSON.errors.questiontext) {
                                                    let arr = err.responseJSON.errors.questiontext;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("questiontext", "question text");
                                                        errortext.append(li);
                                                    }
                                                }
                                            } else if(type == "multipleselection") {
                                                for(let i=1; i<=radiolenght.value; i++) {
                                                    let errorfield = document.getElementById("checkbox-text-error-"+i);
                                                    errorfield.innerHTML = "";
                                                    if(err.responseJSON.errors["checkbox"+i]) {
                                                        let arr = err.responseJSON.errors["checkbox"+i];
                                                        for(let m=0; m<arr.length; m++) {
                                                            let li = document.createElement("li");
                                                            li.innerHTML = arr[m].replace("checkbox"+i, "");
                                                            errorfield.append(li);
                                                        }
                                                    }
                                                }
                                                let errortext = document.getElementById("questiontext-error");
                                                errortext.innerHTML = "";
                                                if(err.responseJSON.errors.questiontext) {
                                                    let arr = err.responseJSON.errors.questiontext;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("questiontext", "question text");
                                                        errortext.append(li);
                                                    }
                                                }
                                                let errorfield = document.getElementById("values-input-error");
                                                errorfield.innerHTML = "";
                                                if(err.responseJSON.errors.checkboxsection) {
                                                    let arr = err.responseJSON.errors.checkboxsection;
                                                    for(let m=0; m<arr.length; m++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[m].replace("checkboxsection", "checkbox section");
                                                        errorfield.append(li);
                                                    }
                                                }
                                            } else if(type == "image") {
                                                let errortext = document.getElementById("questiontext-error");
                                                errortext.innerHTML = "";
                                                if(err.responseJSON.errors.questiontext) {
                                                    let arr = err.responseJSON.errors.questiontext;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("questiontext", "question text");
                                                        errortext.append(li);
                                                    }
                                                }
                                                for(let i=0; i<radiolenght.value; i++) {
                                                    let errorfield = document.getElementById("image-input-error-"+i);
                                                    errorfield.innerHTML = "";
                                                    if(err.responseJSON.errors["imageinput"+i]) {
                                                        let arr = err.responseJSON.errors["imageinput"+i];
                                                        for(let m=0; m<arr.length; m++) {
                                                            let li = document.createElement("li");
                                                            li.innerHTML = arr[m].replace("imageinput"+i, "");
                                                            errorfield.append(li);
                                                        }
                                                    }
                                                }
                                                let errorrimagefield = document.getElementById("image-field-error");
                                                errorrimagefield.innerHTML = "";
                                                if(err.responseJSON.errors.imagefield) {
                                                    let arr = err.responseJSON.errors.imagefield;
                                                    for(let i=0; i<arr.length; i++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[i].replace("imagefield", "image field");
                                                        errorrimagefield.append(li);
                                                    }
                                                }
                                            }

                                            let errorfield = document.getElementById("questiontitle-error");
                                            errorfield.innerHTML = "";
                                            if(err.responseJSON.errors.questiontitle) {
                                                let arr = err.responseJSON.errors.questiontitle;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("questiontitle", "question title");
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
    $("summary").on("click", function(e) {
        e.preventDefault();
    });

    $("summary").on("mouseover", function(e) {
        this.childNodes[0].childNodes[1].childNodes[0].style.visibility = "visible";

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
        this.childNodes[0].childNodes[1].childNodes[0].style.visibility = "hidden";
    });

    //Hidden modify an delete code for question
    $(".question-title").off("mouseover").on("mouseover", function(e) {
        this.nextSibling.childNodes[0].style.visibility = "visible";

        $(".deletemodifybutton").off("mouseover").on("mouseover", function(e) {
            this.style.visibility = "visible";

            //Hover delete button
            $(this.childNodes[3]).off("mouseover").on("mouseover", function(e) {
                this.classList.add("rounded-md");
                this.style.backgroundColor = "red"
            });

            $(this.childNodes[3]).off("mouseout").on("mouseout", function(e) {
                this.style.backgroundColor = "white"
            });

            //Hover modify button
            $(this.childNodes[1]).off("mouseover").on("mouseover", function(e) {
                this.classList.add("rounded-md");
                this.style.backgroundColor = "blue"
            });

            $(this.childNodes[1]).off("mouseout").on("mouseout", function(e) {
                this.style.backgroundColor = "white"
            });

        });

        $(".deletemodifybutton").off("mouseout").on("mouseout", function(e) {
            this.style.visibility = "hidden";
        });
    });

    $(".question-title").off("mouseout").on("mouseout", function(e) {
        this.nextSibling.childNodes[0].style.visibility = "hidden";
    });

    //Sortability
    $(".sortable-question").each(function() {
        if ($(this).hasClass("ui-sortable")) {
            $(this).sortable("destroy");
        }
    });
    $(".sortable-question").sortable({
        items: ".sortable-question-item",
        cancel: ".questionbutton",
        cursor: "grabbing",
        update: function(event, ui) {
            let draggitem = ui.item;
            let previtem = $(ui.item).prev();

            let endid;
            if(previtem.length) {
                endid = previtem[0].id.split("-")[1];
            } else {
                endid = "start";
            }
            //Ajax to change the question progressive
            $.ajax({
                method: "POST",
                url: "/testmed/createteststructure/ajax/updatequestionprogressive",
                data: {
                    _token: document.querySelector('input[name="_token"]').value,
                    start: draggitem[0].id.split("-")[1],
                    end: endid,
                },
            });

        }

    });

    $(".sortable-test").sortable({
        items: ".sortable-section-item",
        handle: "> details > summary",
        cursor: "grabbing",
        cancel: ".sectionbutton",

        update: function(event, ui) {
            let draggitem = ui.item;
            let previtem = $(ui.item).prev();

            let endid;
            if(previtem.length) {
                console.log(draggitem[0]);
                endid = previtem[0].id.split("-")[1];
            } else {
                endid = "start";
            }
            //Ajax to change the question progressive
            $.ajax({
                method: "POST",
                url: "/testmed/createteststructure/ajax/updatetestprogressive",
                data: {
                    _token: document.querySelector('input[name="_token"]').value,
                    start: draggitem[0].id.split("-")[1],
                    end: endid,
                },
                success: function(data) {
                    console.log(data);
                    if(data.status == 400) {
                        //window.location.pathname = "testmed/createteststructure";
                    }
                },
                error: function(err) {
                    console.log(err);
                    //window.location.pathname = "testmed/createteststructure";
                }
            });

        }
    });

    $(".sortable-section").each(function() {
        if ($(this).hasClass("ui-sortable")) {
            $(this).sortable("destroy");
        }
    });
    $(".sortable-section").sortable({
        items: ".sortable-subsection-item",
        handle: "> details > summary",
        cancel: ".sectionbutton",
        update: function(event, ui) {
            let draggitem = ui.item;
            let previtem = $(ui.item).prev();

            let endid;
            if(previtem.length) {
                console.log(draggitem[0]);
                endid = previtem[0].id.split("-")[1];
            } else {
                endid = "start";
            }
            //Ajax to change the question progressive
            $.ajax({
                method: "POST",
                url: "/testmed/createteststructure/ajax/updatesectionprogressive",
                data: {
                    _token: document.querySelector('input[name="_token"]').value,
                    start: draggitem[0].id.split("-")[1],
                    end: endid,
                },
                success: function(data) {
                    console.log(data);
                    if(data.status == 400) {
                        //window.location.pathname = "testmed/createteststructure";
                    }
                },
                error: function(err) {
                    console.log(err);
                    //window.location.pathname = "testmed/createteststructure";
                }
            });
        }
    });

    //Click Delete button
    $(".formdeletebutton").on("click", function(e) {
        let button = this;
        $.ajax({
            type: "POST",
            url: "/testmed/createteststructure/ajax/deleteelement",
            data: $(this).serialize(),
            success: function(data) {
                if(data.status == 200) {
                    if(data.redirect) {
                        window.location.href = "/testmed/createteststructure?status=exit-status";
                    } else {
                        //Loop to find first parent list item
                        let parent = button.parentElement;
                        while(parent.nodeName != "LI") {
                            parent = parent.parentElement;
                        }
                        let ul = parent.parentElement;
                        let type;
                        if(parent.classList.contains("question")) {
                            type = "question";
                        } else {
                            type = "section";
                        }
                        parent.remove();

                        //Check if is needed to add a section or question button and reenabling action listeners
                        if(ul.children.length == 1) {
                            if(type == "question") {
                                ul.insertBefore(document.getElementsByClassName("sectionbutton")[0].cloneNode(true), ul.children[0]);
                            } else {
                                if(ul.parentElement.parentElement.classList.contains("section")) {
                                    ul.appendChild(document.getElementsByClassName("questionbutton")[0].cloneNode(true));
                                }
                            }
                            reload();
                        }
                    }
                }
            }
        });
    });

    //Click Modify button
    $(".formmodifybutton").on("click", function(e) {
        let modifybutton = this;

        $.ajax({
            type: "POST",
            url: "/testmed/createteststructure/ajax/createelementmodify",
            data: $(this).serialize(),
            success: function(data) {
                if(data.status != 400) {
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
                        document.getElementsByClassName("constructor")[0].innerHTML = startpage;
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
                                        document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                                    }
                                },
                                error: function(err) {
                                    if(err.status == 422) {
                                        let arr = err.responseJSON.errors.testname;
                                        let errorfield = document.getElementById("testname-error");
                                        errorfield.innerHTML = "";
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
                                        document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                                        modifybutton.parentElement.parentElement.previousSibling.innerHTML = data.name;
                                    }
                                },
                                error: function(err) {
                                    if(err.status == 422) {
                                        let arr = err.responseJSON.errors.sectionname;
                                        let errorfield = document.getElementById("sectionname-error");
                                        errorfield.innerHTML = "";
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
                            $(".cancelitem").off("click").on("click", function(e) {
                                let id = this.previousSibling.previousSibling.id.split("-")[2]
                                let cicle = document.getElementById("radiolenght").value - 1 - id;
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
                            radiolenght.value = radiolist.childElementCount - 2;
                            $.ajax({
                                type: "GET",
                                url: "/testmed/createteststructure/ajax/multiplequestionitem",
                                success: function(data) {
                                    const i1 = data.indexOf("<body>");
                                    const i2 = data.indexOf("</body>");
                                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                    $("#addchoice").on("click", function(e) {
                                        let radio = document.createElement("div");
                                        radiolist.insertBefore(radio, radiolist.childNodes[radiolist.childNodes.length-4]);
                                        radio.outerHTML = bodyHTML;
                                        radio = document.getElementById("radio-input-");
                                        radio.id = radio.id + radiolenght.value;
                                        radio.name = radio.name + radiolenght.value;
                                        document.getElementById("radio-input-error-").id = document.getElementById("radio-input-error-").id + radiolenght.value;

                                        radiolenght.value = radiolist.childElementCount - 2;

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
                                        $(".cancelitem").off("click").on("click", function(e) {
                                            let id = this.previousSibling.previousSibling.id.split("-")[2]
                                            let cicle = document.getElementById("radiolenght").value - 1 - id;
                                            for(let i=0; i<cicle; i++) {
                                                let element = document.getElementById("radio-input-"+ (+id +i +1));
                                                element.id = "radio-input-" + (element.id.split("-")[2] - 1);
                                                element.name = "radioinput" + element.id.split("-")[2];
                                                element.parentElement.nextSibling.nextSibling.id = "radio-input-error-" + element.id.split("-")[2];
                                            }
                                            this.parentElement.parentElement.remove();
                                            document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                        });

                                    });
                                }
                            });
                        } else if(type == "value") {

                            if(document.getElementsByClassName("checkboxpersonal")) {
                                let check = document.getElementsByClassName("checkboxpersonal");
                                let checkinput = document.getElementsByClassName("checkboxpersonaltext");
                                $(check).off("click").on("click", function(e) {
                                    if(this.checked) {
                                        //Enabling text field
                                        this.nextSibling.nextSibling.disabled = false;

                                        //Find new id
                                        let id = 1;
                                        let looper = this.parentElement.previousSibling.previousSibling;
                                        while(looper.nodeName != "LI") {
                                            if(looper.childNodes[1].id.split("-")[4] == "disabled") {
                                                looper = looper.previousSibling.previousSibling.previousSibling.previousSibling;
                                            } else {
                                                id = +looper.childNodes[1].id.split("-")[4] +1;
                                                break;
                                            }
                                        }

                                        //Shifting ids
                                        let cicle = document.getElementById("radiolenght").value - id +1;
                                        for(let i=0; i<cicle; i++) {
                                            let element = document.getElementById("checkbox-personal-text-"+ (+id +cicle -i -1));
                                            element.previousSibling.previousSibling.id = "checkbox-personal-" + (+element.id.split("-")[3] +1);
                                            element.id = "checkbox-personal-text-" + (+element.id.split("-")[3] +1);
                                            element.name = "checkboxpersonal" + element.id.split("-")[3];
                                            element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                        }

                                        //Re-enabling checkbox
                                        this.nextSibling.nextSibling.id = this.nextSibling.nextSibling.id.replace("disabled", id);
                                        this.nextSibling.nextSibling.name = this.nextSibling.nextSibling.name.replace("disabled", id);
                                        this.id = this.id.replace("disabled", id);
                                        this.classList.remove("checkboxdisabled");
                                        this.parentElement.nextSibling.nextSibling.childNodes[1].id = this.parentElement.nextSibling.nextSibling.childNodes[1].id.replace("disabled", id);

                                        //Adjust checkbox lenght
                                        document.getElementById("radiolenght").value = +document.getElementById("radiolenght").value +1;
                                    } else {
                                        let id = this.id.split("-")[2]

                                        //Disabling uncheck field
                                        this.nextSibling.nextSibling.disabled = true;
                                        this.nextSibling.nextSibling.id = "checkbox-personal-text-disabled";
                                        this.nextSibling.nextSibling.name = "checkboxpersonaldisabled";
                                        this.id = "checkbox-personal-disabled";
                                        this.classList.add("checkboxdisabled");
                                        this.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-disabled";

                                        //Reducing by one ids of active checkbox greather than unchecked
                                        let cicle = document.getElementById("radiolenght").value - id;
                                        for(let i=0; i<cicle; i++) {
                                            let element = document.getElementById("checkbox-personal-text-"+ (+id +i +1));
                                            element.previousSibling.previousSibling.id = "checkbox-personal-" + (element.id.split("-")[3] - 1);
                                            element.id = "checkbox-personal-text-" + (element.id.split("-")[3] - 1);
                                            element.name = "checkboxpersonal" + element.id.split("-")[3];
                                            element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                        }

                                        //Fixing checkbox lenght
                                        document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;

                                    }
                                });

                                $(checkinput).on("input", function(e) {
                                    if (this.value !== '' && !isNaN(this.value) && Number(this.value) > 100) {
                                        document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.add("hidden");
                                    } else {
                                        document.getElementById("checkbox-personal-text-error-"+split(this.id,"-",true)[3]).classList.remove("hidden");
                                    }
                                });
                            }

                            //Delete button interaction
                            $(".valuelistitem").on("mouseover", function(e) {
                                this.childNodes[5].childNodes[1].classList.remove("hidden");
                            });
                            $(".valuelistitem").on("mouseout", function(e) {
                                this.childNodes[5].childNodes[1].classList.add("hidden");
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
                            $(".cancelitem").off("click").on("click", function(e) {
                                let id = this.previousSibling.previousSibling.id.split("-")[3]
                                let cicle = document.getElementById("radiolenght").value - id;
                                this.parentElement.nextSibling.nextSibling.remove();
                                this.parentElement.remove();
                                for(let i=0; i<cicle; i++) {
                                    let element = document.getElementById("checkbox-personal-text-"+ (+id +i));
                                    element.previousSibling.previousSibling.id = "checkbox-personal-" + (element.id.split("-")[3] - 1);
                                    element.id = "checkbox-personal-text-" + (element.id.split("-")[3] - 1);
                                    element.name = "checkboxpersonal" + element.id.split("-")[3];
                                    element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                }
                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                            });

                            //Esclusivity of the list items
                            $(".rapidcheck").on("click", function(e) {
                                for(let i=0; i<101; i++) {
                                    let prova = document.getElementById("checkbox-single-" + i);
                                    prova.checked = false;
                                }
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

                                        //Delete button interaction
                                        $(".valuelistitem").on("mouseover", function(e) {
                                            this.childNodes[5].childNodes[1].classList.remove("hidden");
                                        });
                                        $(".valuelistitem").on("mouseout", function(e) {
                                            this.childNodes[5].childNodes[1].classList.add("hidden");
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
                                        $(".cancelitem").off("click").on("click", function(e) {
                                            let id = this.previousSibling.previousSibling.id.split("-")[3]
                                            let cicle = document.getElementById("radiolenght").value - id;
                                            this.parentElement.nextSibling.nextSibling.remove();
                                            this.parentElement.remove();
                                            for(let i=0; i<cicle; i++) {
                                                let element = document.getElementById("checkbox-personal-text-"+ (+id +i +1));
                                                element.previousSibling.previousSibling.id = "checkbox-personal-" + (element.id.split("-")[3] - 1);
                                                element.id = "checkbox-personal-text-" + (element.id.split("-")[3] - 1);
                                                element.name = "checkboxpersonal" + element.id.split("-")[3];
                                                element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                            }
                                            document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                        });

                                        $(check).off("click").on("click", function(e) {
                                            if(this.checked) {
                                                //Enabling text field
                                                this.nextSibling.nextSibling.disabled = false;

                                                //Find new id
                                                let id = 1;
                                                let looper = this.parentElement.previousSibling.previousSibling;
                                                while(looper.nodeName != "LI") {
                                                    if(looper.childNodes[1].id.split("-")[4] == "disabled") {
                                                        looper = looper.previousSibling.previousSibling.previousSibling.previousSibling;
                                                    } else {
                                                        id = +looper.childNodes[1].id.split("-")[4] +1;
                                                        break;
                                                    }
                                                }

                                                //Shifting ids
                                                let cicle = document.getElementById("radiolenght").value - id +1;
                                                for(let i=0; i<cicle; i++) {
                                                    let element = document.getElementById("checkbox-personal-text-"+ (+id +cicle -i -1));
                                                    element.previousSibling.previousSibling.id = "checkbox-personal-" + (+element.id.split("-")[3] +1);
                                                    element.id = "checkbox-personal-text-" + (+element.id.split("-")[3] +1);
                                                    element.name = "checkboxpersonal" + element.id.split("-")[3];
                                                    element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                                }

                                                //Re-enabling checkbox
                                                this.nextSibling.nextSibling.id = this.nextSibling.nextSibling.id.replace("disabled", id);
                                                this.nextSibling.nextSibling.name = this.nextSibling.nextSibling.name.replace("disabled", id);
                                                this.id = this.id.replace("disabled", id);
                                                this.classList.remove("checkboxdisabled");
                                                this.parentElement.nextSibling.nextSibling.childNodes[1].id = this.parentElement.nextSibling.nextSibling.childNodes[1].id.replace("disabled", id);

                                                //Adjust checkbox lenght
                                                document.getElementById("radiolenght").value = +document.getElementById("radiolenght").value +1;
                                            } else {
                                                let id = this.id.split("-")[2]

                                                //Disabling uncheck field
                                                this.nextSibling.nextSibling.disabled = true;
                                                this.nextSibling.nextSibling.id = "checkbox-personal-text-disabled";
                                                this.nextSibling.nextSibling.name = "checkboxpersonaldisabled";
                                                this.id = "checkbox-personal-disabled";
                                                this.classList.add("checkboxdisabled");
                                                this.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-disabled";

                                                //Reducing by one ids of active checkbox greather than unchecked
                                                let cicle = document.getElementById("radiolenght").value - id;
                                                for(let i=0; i<cicle; i++) {
                                                    let element = document.getElementById("checkbox-personal-text-"+ (+id +i +1));
                                                    element.previousSibling.previousSibling.id = "checkbox-personal-" + (element.id.split("-")[3] - 1);
                                                    element.id = "checkbox-personal-text-" + (element.id.split("-")[3] - 1);
                                                    element.name = "checkboxpersonal" + element.id.split("-")[3];
                                                    element.parentElement.nextSibling.nextSibling.childNodes[1].id = "checkbox-personal-text-error-" + element.id.split("-")[3];
                                                }

                                                //Fixing checkbox lenght
                                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;

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
                        } else if(type == "open") {

                        } else if(type == "multipleselection") {
                            let radiolenght = document.getElementById("radiolenght");

                            //Delete button interaction
                            $(".valuelistitem").on("mouseover", function(e) {
                                this.childNodes[5].childNodes[1].classList.remove("hidden");
                            });
                            $(".valuelistitem").on("mouseout", function(e) {
                                this.childNodes[5].childNodes[1].classList.add("hidden");
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
                            $(".cancelitem").off("click").on("click", function(e) {
                                let id = this.previousSibling.previousSibling.id.split("-")[2]
                                let cicle = document.getElementById("radiolenght").value - id;
                                this.parentElement.nextSibling.nextSibling.remove();
                                this.parentElement.remove();

                                for(let i=0; i<cicle; i++) {
                                    let element = document.getElementById("checkbox-text-"+ (+id +i +1));
                                    element.previousSibling.previousSibling.id = "checkbox-" + (element.id.split("-")[2] - 1);
                                    element.id = "checkbox-text-" + (element.id.split("-")[2] - 1);
                                    element.name = "checkbox" + element.id.split("-")[2];
                                    element.parentElement.nextSibling.nextSibling.id = "checkbox-text-error-" + element.id.split("-")[2];
                                }
                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                            });

                            $.ajax({
                                type: "GET",
                                url: "/testmed/createteststructure/ajax/multipleselectionquestionitem",
                                success: function(data) {
                                    const i1 = data.indexOf("<body>");
                                    const i2 = data.indexOf("</body>");
                                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                    $("#addchoice").on("click", function(e) {
                                        let checklist = document.getElementById("valueslist");
                                        let check = document.createElement("div");
                                        checklist.insertBefore(check, checklist.childNodes[checklist.childNodes.length-2]);
                                        check.outerHTML = bodyHTML;
                                        check = document.getElementById("checkbox-");
                                        check.id = check.id + (+radiolenght.value + 1);
                                        let checkinput = document.getElementById("checkbox-text-");
                                        checkinput.id = checkinput.id + (+radiolenght.value + 1);
                                        checkinput.name = checkinput.name + (+radiolenght.value + 1);
                                        document.getElementById("checkbox-text-error-").id = document.getElementById("checkbox-text-error-").id + (+radiolenght.value + 1);

                                        radiolenght.value = +radiolenght.value + 1;

                                        //Delete button interaction
                                        $(".valuelistitem").on("mouseover", function(e) {
                                            this.childNodes[5].childNodes[1].classList.remove("hidden");
                                        });
                                        $(".valuelistitem").on("mouseout", function(e) {
                                            this.childNodes[5].childNodes[1].classList.add("hidden");
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
                                        $(".cancelitem").off("click").on("click", function(e) {
                                            let id = this.previousSibling.previousSibling.id.split("-")[2]
                                            let cicle = document.getElementById("radiolenght").value - id;
                                            this.parentElement.nextSibling.nextSibling.remove();
                                            this.parentElement.remove();

                                            for(let i=0; i<cicle; i++) {
                                                let element = document.getElementById("checkbox-text-"+ (+id +i +1));
                                                element.previousSibling.previousSibling.id = "checkbox-" + (element.id.split("-")[2] - 1);
                                                element.id = "checkbox-text-" + (element.id.split("-")[2] - 1);
                                                element.name = "checkbox" + element.id.split("-")[2];
                                                element.parentElement.nextSibling.nextSibling.id = "checkbox-text-error-" + element.id.split("-")[2];
                                            }
                                            document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                        });

                                    });
                                }
                            });
                        } else if(type == "image") {
                            let radiolenght = document.getElementById("radiolenght");

                            //Delete button interaction
                            $(".imagelistitem").on("mouseover", function(e) {
                                this.childNodes[1].childNodes[7].childNodes[1].classList.remove("hidden");
                            });
                            $(".imagelistitem").on("mouseout", function(e) {
                                this.childNodes[1].childNodes[7].childNodes[1].classList.add("hidden");
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

                            //Preview Image
                            $(".imageinput").off("change").on("change", function(e) {
                                var file = e.target.files[0];
                                let id = this.id.split("-")[2]

                                //Removing hidden input
                                let oldimage = document.getElementById("old-image-" + id);
                                if(oldimage != null) {
                                    oldimage.remove();
                                }

                                if (file) {
                                    document.getElementById("file-name-" + id).classList.add("hidden");
                                    var reader = new FileReader();

                                    reader.onload = function(e) {
                                        // Display the preview image
                                        var preview = document.getElementById('image-preview-' + id);
                                        preview.src = e.target.result;
                                        preview.classList.remove('hidden');

                                        // Display the file name
                                        document.getElementById('file-name-' + id).textContent = file.name;
                                    }

                                    reader.readAsDataURL(file); // Convert the file to a data URL
                                }
                            });

                            $(".cancelitem").off("click").on("click", function(e) {
                                let id = this.previousSibling.previousSibling.previousSibling.previousSibling.childNodes[3].id.split("-")[2]
                                let cicle = document.getElementById("radiolenght").value - 1 - id;
                                for(let i=0; i<cicle; i++) {
                                    let element = document.getElementById("image-input-" + (+id +i +1));
                                    element.id = "image-input-" + (element.id.split("-")[2] - 1);
                                    element.name = "imageinput" + element.id.split("-")[2];
                                    let label = document.getElementById("image-input-label-" + (+id +i +1));
                                    label.id = "image-input-label-" + element.id.split("-")[2];
                                    label.setAttribute("for", "image-input-" + element.id.split("-")[2]);
                                    let span = document.getElementById("file-name-" + (+id +i +1));
                                    span.id = "file-name-" + element.id.split("-")[2];
                                    let imagepreview = document.getElementById("image-preview-" + (+id +i +1));
                                    imagepreview.id = "image-preview-" + element.id.split("-")[2];
                                    document.getElementById("image-input-error-" + (+id +i +1)).id = "image-input-error-" + element.id.split("-")[2];

                                    let oldimage = document.getElementById("old-image-" + (+id +i +1));
                                    if(oldimage != null) {
                                        oldimage.id = "old-image-" + element.id.split("-")[2];
                                        oldimage.name = "imageinput" + element.id.split("-")[2];
                                    }
                                }
                                this.parentElement.parentElement.remove();
                                document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                            });

                            document.getElementById("radiolenght").value = radiolist.childElementCount - 1;
                            $.ajax({
                                type: "GET",
                                url: "/testmed/createteststructure/ajax/imagequestionitem",
                                success: function(data) {
                                    const i1 = data.indexOf("<body>");
                                    const i2 = data.indexOf("</body>");
                                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                                    $("#addchoice").on("click", function(e) {
                                        let radiolist = document.getElementById("radiolist");
                                        let radio = document.createElement("div");
                                        radiolist.insertBefore(radio, radiolist.childNodes[radiolist.childNodes.length-2]);
                                        radio.outerHTML = bodyHTML;
                                        radio = document.getElementById("image-input-");
                                        radio.id = radio.id + radiolenght.value;
                                        radio.name = radio.name + radiolenght.value;
                                        let label = document.getElementById("image-input-label-");
                                        label.id = label.id + radiolenght.value;
                                        label.setAttribute("for", label.getAttribute("for") + radiolenght.value);
                                        let span = document.getElementById("file-name-");
                                        span.id = span.id + radiolenght.value;
                                        let imagepreview = document.getElementById("image-preview-");
                                        imagepreview.id = imagepreview.id + radiolenght.value;
                                        document.getElementById("image-input-error-").id = document.getElementById("image-input-error-").id + radiolenght.value;

                                        radiolenght.value = radiolist.childElementCount - 1;

                                        //Delete button interaction
                                        $(".imagelistitem").on("mouseover", function(e) {
                                            this.childNodes[1].childNodes[7].childNodes[1].classList.remove("hidden");
                                        });
                                        $(".imagelistitem").on("mouseout", function(e) {
                                            this.childNodes[1].childNodes[7].childNodes[1].classList.add("hidden");
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

                                        //Preview Image
                                        $(".imageinput").off("change").on("change", function(e) {
                                            var file = e.target.files[0];
                                            let id = this.id.split("-")[2]

                                            //Removing hidden input
                                            let oldimage = document.getElementById("old-image-" + id);
                                            if(oldimage != null) {
                                                oldimage.remove();
                                            }

                                            if (file) {
                                                document.getElementById("file-name-" + id).classList.add("hidden");
                                                var reader = new FileReader();

                                                reader.onload = function(e) {
                                                    // Display the preview image
                                                    var preview = document.getElementById('image-preview-' + id);
                                                    preview.src = e.target.result;
                                                    preview.classList.remove('hidden');

                                                    // Display the file name
                                                    document.getElementById('file-name-' + id).textContent = file.name;
                                                }

                                                reader.readAsDataURL(file); // Convert the file to a data URL
                                            }
                                        });

                                        $(".cancelitem").off("click").on("click", function(e) {
                                            let id = this.previousSibling.previousSibling.previousSibling.previousSibling.childNodes[3].id.split("-")[2]
                                            let cicle = document.getElementById("radiolenght").value - 1 - id;
                                            for(let i=0; i<cicle; i++) {
                                                let element = document.getElementById("image-input-" + (+id +i +1));
                                                element.id = "image-input-" + (element.id.split("-")[2] - 1);
                                                element.name = "imageinput" + element.id.split("-")[2];
                                                let label = document.getElementById("image-input-label-" + (+id +i +1));
                                                label.id = "image-input-label-" + element.id.split("-")[2];
                                                label.setAttribute("for", "image-input-" + element.id.split("-")[2]);
                                                let span = document.getElementById("file-name-" + (+id +i +1));
                                                span.id = "file-name-" + element.id.split("-")[2];
                                                let imagepreview = document.getElementById("image-preview-" + (+id +i +1));
                                                imagepreview.id = "image-preview-" + element.id.split("-")[2];
                                                document.getElementById("image-input-error-" + (+id +i +1)).id = "image-input-error-" + element.id.split("-")[2];

                                                let oldimage = document.getElementById("old-image-" + (+id +i +1));
                                                if(oldimage != null) {
                                                    oldimage.id = "old-image-" + element.id.split("-")[2];
                                                    oldimage.name = "imageinput" + element.id.split("-")[2];
                                                }
                                            }
                                            this.parentElement.parentElement.remove();
                                            document.getElementById("radiolenght").value = document.getElementById("radiolenght").value - 1;
                                        });

                                    });

                                }
                            });
                        }


                        $("#updatechoosequestion").on("click", function(e) {
                            e.preventDefault();
                            let formData = new FormData(document.getElementById("choosequestionform"));
                            $.ajax({
                                method: "POST",
                                url: "/testmed/createteststructure/ajax/update"+type+"question",
                                data: formData,
                                contentType: false,
                                processData: false,
                                success: function(data) {

                                    if(data.status == 200) {
                                        document.getElementsByClassName("constructor")[0].innerHTML = startpage;
                                        modifybutton.parentElement.parentElement.previousSibling.innerHTML = data.title;
                                    }
                                },
                                error: function(err) {

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
                                            let errortext = document.getElementById("questiontext-error");
                                            errortext.innerHTML = "";
                                            if(err.responseJSON.errors.questiontext) {
                                                let arr = err.responseJSON.errors.questiontext;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("questiontext", "question text");
                                                    errortext.append(li);
                                                }
                                            }
                                            let errorradiosection = document.getElementById("radio-section-error");
                                            errorradiosection.innerHTML = "";
                                            if(err.responseJSON.errors.radiosection) {
                                                let arr = err.responseJSON.errors.radiosection;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("radiosection", "radio section");
                                                    errorradiosection.append(li);
                                                }
                                            }
                                        } else if(type == "value") {
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
                                            let errortext = document.getElementById("questiontext-error");
                                            errortext.innerHTML = "";
                                            if(err.responseJSON.errors.questiontext) {
                                                let arr = err.responseJSON.errors.questiontext;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("questiontext", "question text");
                                                    errortext.append(li);
                                                }
                                            }
                                        } else if(type == "open") {
                                            let errortext = document.getElementById("questiontext-error");
                                            errortext.innerHTML = "";
                                            if(err.responseJSON.errors.questiontext) {
                                                let arr = err.responseJSON.errors.questiontext;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("questiontext", "question text");
                                                    errortext.append(li);
                                                }
                                            }
                                        } else if(type == "multipleselection") {
                                            for(let i=1; i<=radiolenght.value; i++) {
                                                let errorfield = document.getElementById("checkbox-text-error-"+i);
                                                errorfield.innerHTML = "";
                                                if(err.responseJSON.errors["checkbox"+i]) {
                                                    let arr = err.responseJSON.errors["checkbox"+i];
                                                    for(let m=0; m<arr.length; m++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[m].replace("checkbox"+i, "");
                                                        errorfield.append(li);
                                                    }
                                                }
                                            }
                                            let errortext = document.getElementById("questiontext-error");
                                            errortext.innerHTML = "";
                                            if(err.responseJSON.errors.questiontext) {
                                                let arr = err.responseJSON.errors.questiontext;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("questiontext", "question text");
                                                    errortext.append(li);
                                                }
                                            }
                                            let errorfield = document.getElementById("values-input-error");
                                            errorfield.innerHTML = "";
                                            if(err.responseJSON.errors.checkboxsection) {
                                                let arr = err.responseJSON.errors.checkboxsection;
                                                for(let m=0; m<arr.length; m++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[m].replace("checkboxsection", "checkbox section");
                                                    errorfield.append(li);
                                                }
                                            }
                                        } else if(type == "image") {
                                            let errortext = document.getElementById("questiontext-error");
                                            errortext.innerHTML = "";
                                            if(err.responseJSON.errors.questiontext) {
                                                let arr = err.responseJSON.errors.questiontext;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("questiontext", "question text");
                                                    errortext.append(li);
                                                }
                                            }
                                            for(let i=0; i<radiolenght.value; i++) {
                                                let errorfield = document.getElementById("image-input-error-"+i);
                                                errorfield.innerHTML = "";
                                                if(err.responseJSON.errors["imageinput"+i]) {
                                                    let arr = err.responseJSON.errors["imageinput"+i];
                                                    for(let m=0; m<arr.length; m++) {
                                                        let li = document.createElement("li");
                                                        li.innerHTML = arr[m].replace("imageinput"+i, "");
                                                        errorfield.append(li);
                                                    }
                                                }
                                            }
                                            let errorrimagefield = document.getElementById("image-field-error");
                                            errorrimagefield.innerHTML = "";
                                            if(err.responseJSON.errors.imagefield) {
                                                let arr = err.responseJSON.errors.imagefield;
                                                for(let i=0; i<arr.length; i++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[i].replace("imagefield", "image field");
                                                    errorrimagefield.append(li);
                                                }
                                            }
                                        }

                                        let errorfield = document.getElementById("questiontitle-error");
                                        errorfield.innerHTML = "";
                                        if(err.responseJSON.errors.questiontitle) {
                                            let arr = err.responseJSON.errors.questiontitle;
                                            for(let i=0; i<arr.length; i++) {
                                                let li = document.createElement("li");
                                                li.innerHTML = arr[i].replace("questiontitle", "question title");
                                                errorfield.append(li);
                                            }
                                        }
                                    }
                                }
                            });
                        });

                    }
                }
            },
            error: function(err) {
            }
        });
    });
}

$(function(){

    //Cancel button to discard exit
    $("#cancel").type = "button";
    $("#cancel").on("click", function(e) {
        window.location.href = "/testmed/createteststructure";
    });

    //Append tree
    document.getElementById("tree").appendChild(test);

    reload();

});
