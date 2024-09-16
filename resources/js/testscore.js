import './bootstrap';

import Alpine from 'alpinejs';
import jQuery from 'jquery';
import 'jquery-ui/dist/jquery-ui';

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
        positioner.classList.add("selectform");
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

        //Csrf token
        let token = document.getElementById("csrf").childNodes[1].cloneNode();
        positioner.appendChild(token);

        //Section Name
        let summarytitle = document.createElement("p");
        summarytitle.innerHTML = section.name;
        summarytitle.classList.add("section-title");
        positioner.appendChild(summarytitle);

        //Icon
        let checkicon = document.createElement("div");
        checkicon.classList.add("w-6", "ml-1")
        if(section.status == 0) {
            let checkmark = document.getElementById("checkmark").cloneNode(true);
            checkicon.appendChild(checkmark);
            checkmark.id = checkmark.id + "-section-" + section.id;
            checkmark.style = "color: green";
            checkmark.classList.remove("hidden");
        } else if(section.status == 1) {
            let hourglass = document.getElementById("hourglass").cloneNode(true);
            checkicon.appendChild(hourglass);
            hourglass.id = hourglass.id + "-section-" + section.id;
            hourglass.classList.remove("hidden");
        } else {
            let alticon = document.getElementById("alt").cloneNode(true);
            checkicon.appendChild(alticon);
            alticon.id = alticon.id + "-section-" + section.id;
            alticon.style = "color: red";
            alticon.classList.remove("hidden");
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
                    positioner.classList.add("selectform");
                    positioner.method = "POST";
                    positioner.classList.add("flex", "flex-row", "inline-flex", "max-h-6", "modifyform");

                    //Creation html question node
                    let questionnode = document.createElement("li");
                    questionnode.classList.add('question', 'mb-2');
                    questionnode.id = "question-" + section.questions["question"+ (i+1)].id;
                    sectionnode.childNodes[0].childNodes[1].appendChild(questionnode)

                    //Csrf token
                    let token = document.getElementById("csrf").childNodes[1].cloneNode();
                    positioner.appendChild(token);

                    //Questiontitle
                    let questiontitle = document.createElement("div");
                    positioner.appendChild(questiontitle);
                    questiontitle.outerHTML = "<div class=\"question-title\">" + section.questions["question"+ (i+1)].title + "</div>";

                    //Icon
                    let checkicon = document.createElement("div");
                    checkicon.classList.add("w-6", "ml-1")
                    if(section.questions["question"+ (i+1)].status == 0) {
                        let checkmark = document.getElementById("checkmark").cloneNode(true);
                        checkicon.appendChild(checkmark);
                        checkmark.id = checkmark.id + "-question-" + questionnode.id.split("-")[1];
                        checkmark.style = "color: green";
                        checkmark.classList.remove("hidden");
                    } else if(section.questions["question"+ (i+1)].status == 1) {
                        let hourglass = document.getElementById("hourglass").cloneNode(true);
                        checkicon.appendChild(hourglass);
                        hourglass.id = hourglass.id + "-question-" + questionnode.id.split("-")[1];
                        hourglass.classList.remove("hidden");
                    } else {
                        let alticon = document.getElementById("alt").cloneNode(true);
                        checkicon.appendChild(alticon);
                        alticon.id = alticon.id + "-question-" + questionnode.id.split("-")[1];
                        alticon.style = "color: red";
                        alticon.classList.remove("hidden");
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
            url: "/testmed/createteststructure/testscore/ajax/createtree",
            success: function(data) {
                console.log(data);
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
                console.log(err);
            }
        });
    });
}

const test = await treesetting();

function scoreoperations() {
    let scorecontainer = document.getElementById("scorecontainer");
    $("#score-enabler").on("click", function(e) {
        if ($(this).is(":checked")) {
            if(scorecontainer) {
                scorecontainer.classList.remove("opacity-50");
                document.getElementById("scoreoperations").disabled = false;
                document.getElementById("score").classList.remove("hidden");
            }
            let selectvalues = document.getElementsByClassName("selectvalue");
            if(selectvalues.length != 0) {
                for(let i=0; i<selectvalues.length; i++) {
                    selectvalues[i].classList.remove("hidden");
                }
            }
        } else {
            if(scorecontainer) {
                scorecontainer.classList.add("opacity-50");
                document.getElementById("scoreoperations").disabled = true;
                document.getElementById("score").classList.add("hidden");
            }
            let selectvalues = document.getElementsByClassName("selectvalue");
            if(selectvalues.length != 0) {
                for(let i=0; i<selectvalues.length; i++) {
                    selectvalues[i].classList.add("hidden");
                }
            }
        }
    });

    if(scorecontainer) {
        $("#scoreoperations").on("change", function(e) {
            var selectedValue = $(this).val();
            $.ajax({
                method: "POST",
                url: "/testmed/createteststructure/testscore/ajax/createscoreitem",
                data: {
                    _token: document.querySelector('input[name="_token"]').value,
                    type: selectedValue
                },
                success: function(data) {
                    //Reading and pasting button
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                    let elementdetail = document.createElement("div");
                    document.getElementById("score").innerHTML = "";
                    $("#score").append(elementdetail);
                    elementdetail.outerHTML = bodyHTML;

                    let addconversion = document.getElementById("addconversion");

                    if(addconversion) {

                        $(addconversion).on("click", function(e) {
                            e.preventDefault();
                            let conversionitemleft = document.getElementById("conversionitemleft").cloneNode(true);
                            let conversionitemright = document.getElementById("conversionitemright").cloneNode(true);
                            conversionitemleft.childNodes[1].value = "";
                            conversionitemright.childNodes[1].value = "";
                            conversionitemleft.id = "";
                            conversionitemright.id = "";
                            conversionitemleft.classList.remove('unremovable');
                            conversionitemright.classList.remove('unremovable');
                            let lenght = document.getElementById("lenght");
                            conversionitemleft.childNodes[1].name = "value-" + (+lenght.value +1);
                            conversionitemright.childNodes[1].name = "converted-" + (+lenght.value +1);
                            conversionitemleft.childNodes[3].id = "conversion-value-error-" + (+lenght.value +1);
                            conversionitemright.childNodes[3].id = "conversion-converted-error-" + (+lenght.value +1);
                            conversionitemleft.childNodes[3].innerHTML = "";
                            conversionitemright.childNodes[3].innerHTML = "";
                            lenght.value = +lenght.value +1;
                            let conversiongrid = document.getElementById("conversiongrid");
                            conversiongrid.insertBefore(conversionitemleft, this.parentElement);
                            conversiongrid.insertBefore(conversionitemright, this.parentElement);
                        });

                        $("#removeconversion").on("click", function(e) {
                            e.preventDefault();
                            let cancelitem = this.parentElement.previousElementSibling.previousElementSibling;
                            if(!cancelitem.classList.contains('unremovable')) {
                                cancelitem.remove();
                                cancelitem = this.parentElement.previousElementSibling.previousElementSibling;
                                cancelitem.remove();
                                let lenght = document.getElementById("lenght");
                                lenght.value = +lenght.value - 1;
                            }


                        });
                    }
                },
                error: function(err) {

                }
            });
        });
    }
}

function scorepage() {
    $.ajax({
        method: "GET",
        url: "/testmed/createteststructure/testscore/ajax/createnodescore",
        success: function(data) {
            //Reading and pasting button
            const i1 = data.indexOf("<body>");
            const i2 = data.indexOf("</body>");
            const bodyHTML = data.substring(i1 + "<body>".length, i2);

            let elementdetail = document.createElement("div");
            document.getElementsByClassName("constructor")[0].innerHTML = "";
            $(".constructor").append(elementdetail);
            elementdetail.outerHTML = bodyHTML;

            scoreoperations();

            $("#scoreform").on("submit", function(e) {
                e.preventDefault();
                let formData = new FormData(this);

                $.ajax({
                    method: "POST",
                    url: "/testmed/createteststructure/testscore/ajax/storescore",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(data) {
                        console.log(data);
                        if(data.status == 200) {
                            let identifier = document.getElementById("identifier");
                            let hourglass = document.getElementById("hourglass-" + identifier.getAttribute("value"));
                            let parent = hourglass.parentElement;
                            hourglass.remove();
                            let checkmark = document.getElementById("checkmark").cloneNode(true);
                            parent.appendChild(checkmark);
                            checkmark.id = checkmark.id + identifier.getAttribute("value");
                            checkmark.style = "color: green";
                            checkmark.classList.remove("hidden");
                            scorepage();
                        } else if(data.status == 300) {
                            window.location.href = '/testmed/createtest?status=1'
                        }
                    },
                    error: function(err) {
                        console.log(err);
                        if(err.status = 422) {
                            let valuelenght = document.getElementById("lenght");
                            if(valuelenght) {
                                for(let i=1; i<=valuelenght.value; i++) {
                                    let errorfieldvalue = document.getElementById("conversion-value-error-"+i);
                                    let errorfieldconverted = document.getElementById("conversion-converted-error-"+i);
                                    errorfieldvalue.innerHTML = "";
                                    errorfieldconverted.innerHTML = "";
                                    if(err.responseJSON.errors["converted-"+i]) {
                                        let arr = err.responseJSON.errors["converted-"+i];
                                        for(let m=0; m<arr.length; m++) {
                                            let li = document.createElement("li");
                                            li.innerHTML = arr[m].replace("converted-"+i, "");
                                            errorfieldconverted.append(li);
                                        }
                                    }
                                    if(err.responseJSON.errors["value-"+i]) {
                                        let arr = err.responseJSON.errors["value-"+i];
                                        for(let m=0; m<arr.length; m++) {
                                            let li = document.createElement("li");
                                            li.innerHTML = arr[m].replace("value-"+i, "");
                                            errorfieldvalue.append(li);
                                        }
                                    }
                                }
                            }
                            let formulaerror = document.getElementById('formula-error');
                            if(formulaerror) {
                                formulaerror.innerHTML = "";
                                if(err.responseJSON.errors["formula"]) {
                                    let arr = err.responseJSON.errors["formula"];
                                    for(let m=0; m<arr.length; m++) {
                                        let li = document.createElement("li");
                                        li.innerHTML = arr[m]//.replace("formula", "");
                                        formulaerror.append(li);
                                    }
                                }
                            }

                        }
                    }
                });
            });
        },
        error: function(err) {
            console.log(err);
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
        window.location.href = "/testmed/createteststructure/testscore";
    });

    scorepage();

    $(".question-title, .section-title").on("mouseover", function(e) {
        if(this.nextElementSibling.childNodes[0].id.split("-")[0] != "alt") {
            this.classList.add("px-2");
            this.classList.add("rounded-lg");
            this.classList.add("bg-blue-100");
        }
    });

    $(".question-title, .section-title").on("mouseout", function(e) {
        if(this.nextElementSibling.childNodes[0].id.split("-")[0] != "alt") {
            this.classList.remove("px-2");
            this.classList.remove("rounded-lg");
            this.classList.remove("bg-blue-100");
        }
    });

    $(".selectform").on("click", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            method: "POST",
            url: "/testmed/createteststructure/testscore/ajax/createupdatescore",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                if(data.status != 400) {
                    //Reading and pasting button
                    const i1 = data.indexOf("<body>");
                    const i2 = data.indexOf("</body>");
                    const bodyHTML = data.substring(i1 + "<body>".length, i2);

                    let elementdetail = document.createElement("div");
                    document.getElementsByClassName("constructor")[0].innerHTML = "";
                    $(".constructor").append(elementdetail);
                    elementdetail.outerHTML = bodyHTML;

                    scoreoperations();

                    //Operation of loading data
                    let update = document.getElementById("data");
                    if(update) {
                        let type = document.getElementById("data-type").innerHTML;

                        if(type.includes('section')) {
                            //Section page
                            let scoretype = document.getElementById("scoretype").innerHTML;
                            let scoreenabler = document.getElementById("score-enabler");
                            scoreenabler.checked = true;

                            // Manually dispatch a change event
                            let event = new Event('click', { bubbles: true });
                            scoreenabler.dispatchEvent(event);

                            if(scoretype.includes('formula') && !scoretype.includes('conversion')) {
                                let scoreoperation = document.getElementById("scoreoperations");
                                scoreoperation.children[0].selected = false;
                                scoreoperation.children[1].selected = true;

                                // Manually dispatch a change event
                                let event = new Event('change', { bubbles: true });
                                scoreoperation.dispatchEvent(event);

                                function checkAndSetValue() {
                                    // Get the elements
                                    var formulaElement = document.getElementById("formula");

                                    if (formulaElement) {

                                        formulaElement.value = document.getElementById("given-formula").innerHTML;

                                        // Clear the interval once the operation is done
                                        clearInterval(intervalId);
                                    }
                                }
                                var intervalId = setInterval(checkAndSetValue, 250);

                            } else if(scoretype.includes('conversion') && !scoretype.includes('formula')) {
                                let scoreoperation = document.getElementById("scoreoperations");
                                scoreoperation.children[0].selected = false;
                                scoreoperation.children[2].selected = true;

                                // Manually dispatch a change event
                                let event = new Event('change', { bubbles: true });
                                scoreoperation.dispatchEvent(event);

                                function checkAndSetValue() {
                                    // Get the elements
                                    var conversionElement = document.getElementById("conversiongrid");

                                    if (conversionElement) {

                                        let json = JSON.parse(document.getElementById("given-conversion").innerHTML);;

                                        let counter = 0;
                                        for(let key in json) {
                                            if(counter != 0) {
                                                let conversionitemleft = document.getElementById("conversionitemleft").cloneNode(true);
                                                let conversionitemright = document.getElementById("conversionitemright").cloneNode(true);
                                                conversionitemleft.childNodes[1].value = "";
                                                conversionitemright.childNodes[1].value = "";
                                                conversionitemleft.id = "";
                                                conversionitemright.id = "";
                                                conversionitemleft.classList.remove('unremovable');
                                                conversionitemright.classList.remove('unremovable');
                                                let lenght = document.getElementById("lenght");
                                                conversionitemleft.childNodes[1].name = "value-" + (+lenght.value +1);
                                                conversionitemright.childNodes[1].value = key;
                                                conversionitemleft.childNodes[1].value = json[key];
                                                conversionitemright.childNodes[1].name = "converted-" + (+lenght.value +1);
                                                conversionitemleft.childNodes[3].id = "conversion-value-error-" + (+lenght.value +1);
                                                conversionitemright.childNodes[3].id = "conversion-converted-error-" + (+lenght.value +1);
                                                conversionitemleft.childNodes[3].innerHTML = "";
                                                conversionitemright.childNodes[3].innerHTML = "";
                                                lenght.value = +lenght.value +1;
                                                conversionElement.insertBefore(conversionitemleft, document.getElementById("addconversion").parentElement);
                                                conversionElement.insertBefore(conversionitemright, document.getElementById("addconversion").parentElement);
                                            } else {
                                                document.getElementsByName("value-1")[0].value = key;
                                                document.getElementsByName("converted-1")[0].value = json[key];
                                            }
                                            counter++;
                                        }

                                        // Clear the interval once the operation is done
                                        clearInterval(intervalId);
                                    }
                                }
                                var intervalId = setInterval(checkAndSetValue, 250);

                            } else if(scoretype.includes('formula') && scoretype.includes('formula')) {
                                let scoreoperation = document.getElementById("scoreoperations");
                                scoreoperation.children[0].selected = false;
                                scoreoperation.children[3].selected = true;

                                // Manually dispatch a change event
                                let event = new Event('change', { bubbles: true });
                                scoreoperation.dispatchEvent(event);

                                function checkAndSetValue() {
                                    // Get the elements
                                    var formulaElement = document.getElementById("formula");
                                    var conversionElement = document.getElementById("conversiongrid");

                                    if (formulaElement && conversionElement) {

                                        formulaElement.value = document.getElementById("given-formula").innerHTML;

                                        let json = JSON.parse(document.getElementById("given-conversion").innerHTML);

                                        let counter = 0;
                                        for(let key in json) {
                                            if(counter != 0) {
                                                let conversionitemleft = document.getElementById("conversionitemleft").cloneNode(true);
                                                let conversionitemright = document.getElementById("conversionitemright").cloneNode(true);
                                                conversionitemleft.childNodes[1].value = "";
                                                conversionitemright.childNodes[1].value = "";
                                                conversionitemleft.id = "";
                                                conversionitemright.id = "";
                                                conversionitemleft.classList.remove('unremovable');
                                                conversionitemright.classList.remove('unremovable');
                                                let lenght = document.getElementById("lenght");
                                                conversionitemleft.childNodes[1].name = "value-" + (+lenght.value +1);
                                                conversionitemright.childNodes[1].value = key;
                                                conversionitemleft.childNodes[1].value = json[key];
                                                conversionitemright.childNodes[1].name = "converted-" + (+lenght.value +1);
                                                conversionitemleft.childNodes[3].id = "conversion-value-error-" + (+lenght.value +1);
                                                conversionitemright.childNodes[3].id = "conversion-converted-error-" + (+lenght.value +1);
                                                conversionitemleft.childNodes[3].innerHTML = "";
                                                conversionitemright.childNodes[3].innerHTML = "";
                                                lenght.value = +lenght.value +1;
                                                conversionElement.insertBefore(conversionitemleft, document.getElementById("addconversion").parentElement);
                                                conversionElement.insertBefore(conversionitemright, document.getElementById("addconversion").parentElement);
                                            } else {
                                                document.getElementsByName("value-1")[0].value = key;
                                                document.getElementsByName("converted-1")[0].value = json[key];
                                            }
                                            counter++;
                                        }

                                        // Clear the interval once the operation is done
                                        clearInterval(intervalId);
                                    }
                                }
                                var intervalId = setInterval(checkAndSetValue, 250);
                            }

                        } else if(type.includes('question')) {
                            let scoreenabler = document.getElementById("score-enabler");

                            if(document.getElementById("data")) {
                                scoreenabler.checked = true;
                            }

                            // Manually dispatch a change event
                            let event = new Event('click', { bubbles: true });
                            scoreenabler.dispatchEvent(event);

                            if(!type.includes('value')) {
                                if(document.getElementById("data")) {
                                    let json = JSON.parse(document.getElementById("scores").innerHTML);
                                    for(let i=0; i<Object.keys(json).length; i++) {
                                        let select = document.getElementById("select-value-"+i);
                                        select.children[0].selected = false;
                                        select.children[json[i]].selected = true;
                                    }
                                }
                            }
                        }
                    }

                    $("#back").on("click", function(e) {
                        e.preventDefault();
                        scorepage();
                    });

                    $("#updateform").on("submit", function(e) {
                        e.preventDefault();
                        let formData = new FormData(this);
                        $.ajax({
                            method: "POST",
                            url: "/testmed/createteststructure/testscore/ajax/updatescore",
                            data: formData,
                            contentType: false,
                            processData: false,
                            success: function(data) {
                                console.log(data);
                                if(data.status == 200) {
                                    scorepage();
                                }
                            },
                            error: function(err) {
                                console.log(err);
                                if(err.status = 422) {
                                    let valuelenght = document.getElementById("lenght");
                                    if(valuelenght) {
                                        for(let i=1; i<=valuelenght.value; i++) {
                                            let errorfieldvalue = document.getElementById("conversion-value-error-"+i);
                                            let errorfieldconverted = document.getElementById("conversion-converted-error-"+i);
                                            errorfieldvalue.innerHTML = "";
                                            errorfieldconverted.innerHTML = "";
                                            if(err.responseJSON.errors["converted-"+i]) {
                                                let arr = err.responseJSON.errors["converted-"+i];
                                                for(let m=0; m<arr.length; m++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[m].replace("converted-"+i, "");
                                                    errorfieldconverted.append(li);
                                                }
                                            }
                                            if(err.responseJSON.errors["value-"+i]) {
                                                let arr = err.responseJSON.errors["value-"+i];
                                                for(let m=0; m<arr.length; m++) {
                                                    let li = document.createElement("li");
                                                    li.innerHTML = arr[m].replace("value-"+i, "");
                                                    errorfieldvalue.append(li);
                                                }
                                            }
                                        }
                                    }
                                    let formulaerror = document.getElementById('formula-error');
                                    if(formulaerror) {
                                        formulaerror.innerHTML = "";
                                        if(err.responseJSON.errors["formula"]) {
                                            let arr = err.responseJSON.errors["formula"];
                                            for(let m=0; m<arr.length; m++) {
                                                let li = document.createElement("li");
                                                li.innerHTML = arr[m]//.replace("formula", "");
                                                formulaerror.append(li);
                                            }
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

            },

        });
    });
});
