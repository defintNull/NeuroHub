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
        // Creation html section object
        let sectionnode = document.createElement("li");
        sectionnode.classList.add('section');
        sectionnode.id = "section-" + section.id;
        let detail = document.createElement("details");
        detail.open = true;

        let summary = document.createElement("summary");
        detail.appendChild(summary);

        //Form for retrieve question
        let sectionform = document.createElement("form");
        sectionform.method = "POST";
        sectionform.classList.add("selectionform");
        summary.appendChild(sectionform);

        //Csrf token
        let token = document.getElementById("csrf").childNodes[1].cloneNode();
        sectionform.appendChild(token);

        //hidden input with id
        let idinput = document.createElement("input");
        idinput.type = "hidden";
        idinput.name = "sectionid";
        idinput.value = sectionnode.id.split("-")[1];
        sectionform.appendChild(idinput);

        let summarytitle = document.createElement("p");
        summarytitle.innerHTML = section.name;
        sectionform.appendChild(summarytitle);

        //List
        detail.appendChild(document.createElement("ul"));
        sectionnode.appendChild(detail);

        if('sections' in section) {
            //Ricoursive function call
            sectionNode(sectionnode, section.sections);

        } else {
            if('questions' in section) {

                let questioncount = Object.keys(section.questions).length;
                for(let i=0; i<questioncount; i++) {

                    //Creation html question node
                    let questionnode = document.createElement("li");
                    questionnode.classList.add('question', "mb-2");
                    questionnode.id = "question-" + section.questions["question"+ (i+1)].id;

                    //Form for retrieve question
                    let questionform = document.createElement("form");
                    questionform.method = "POST";
                    questionform.classList.add("selectionform");
                    questionnode.appendChild(questionform);

                    //Csrf token
                    let token = document.getElementById("csrf").childNodes[1].cloneNode();
                    questionform.appendChild(token);

                    //hidden input with id
                    let idinput = document.createElement("input");
                    idinput.type = "hidden";
                    idinput.name = "questionid";
                    idinput.value = questionnode.id.split("-")[1];
                    questionform.appendChild(idinput);

                    //Questiontitle
                    let questiontitle = document.createElement("div");
                    questiontitle.classList.add("question-title");
                    questionform.appendChild(questiontitle);
                    questiontitle.innerHTML = section.questions["question"+ (i+1)].title;

                    sectionnode.childNodes[0].childNodes[1].appendChild(questionnode)

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
            url: window.location.pathname + "/ajax/createtree",
            success: function(data) {
                //Test Node
                let test = document.createElement("li");
                test.classList.add('test');
                test.id = "test-"+data.test.id;
                let detail = document.createElement("details");
                detail.open = true;
                let summary = document.createElement("summary");
                //summary.innerHTML = data.test.name;
                detail.appendChild(summary);

                //Form for retrieve question
                let testform = document.createElement("form");
                testform.method = "POST";
                testform.classList.add("selectionform");
                summary.appendChild(testform);

                //Csrf token
                let token = document.getElementById("csrf").childNodes[1].cloneNode();
                testform.appendChild(token);

                //hidden input with id
                let idinput = document.createElement("input");
                idinput.type = "hidden";
                idinput.name = "testid";
                idinput.value = test.id.split("-")[1];
                testform.appendChild(idinput);

                let summarytitle = document.createElement("p");
                summarytitle.innerHTML = data.test.name;
                testform.appendChild(summarytitle);

                if("sections" in data.test) {
                    detail.appendChild(document.createElement("ul"));
                    test.appendChild(detail);
                    sectionNode(test, data.test.sections);
                } else {
                    detail.appendChild(document.createElement("ul"));
                    test.appendChild(detail);
                }

                resolve(test);
            },
            error: function(err) {
            }
        });
    });
}

const test = await treesetting();

$(function(){
    //Append tree
    document.getElementById("tree").appendChild(test);

    //Blocking summaries onclick
    $("summary").on("click", function(e) {
        e.preventDefault();
    });

    //On form click behaviour
    $(".selectionform").on("click", function(e) {
        e.preventDefault();
        let formData = new FormData(this);
        $.ajax({
            type: "POST",
            url: window.location.pathname + "/ajax/elementdetail",
            data: formData,
            contentType: false,
            processData: false,
            success: function(data) {
                //Reading and pasting button
                const i1 = data.indexOf("<body>");
                const i2 = data.indexOf("</body>");
                const bodyHTML = data.substring(i1 + "<body>".length, i2);

                let elementdetail = document.createElement("div");
                document.getElementsByClassName("constructor")[0].innerHTML = "";
                $(".constructor").append(elementdetail);
                elementdetail.outerHTML = bodyHTML;
            },
            error: function(err) {
            }
        });
    });

});
