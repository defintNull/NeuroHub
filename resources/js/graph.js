import './bootstrap';

import Chart from 'chart.js/auto'
import Alpine from 'alpinejs';
import jQuery from 'jquery';

window.Alpine = Alpine;

var myChart;

const ctx = document.getElementById('myChart');

$.get("/admingraph",
    {
        test: 'all',
    },
    function (data) {
        console.log(data);
        var d = data;
        document.getElementById("canvascontainer").classList.remove("w-1/2","h-1/2");
        document.getElementById("canvascontainer").classList.add("w-full","h-full");
        myChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: d.map(row => row.test),
                datasets: [{
                    label: '# of Subministrations',
                    data: data.map(row => row.subministration),
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    });




$('#testname').on('change', function () {
    if (document.getElementById('testname').value == "all") {
        document.getElementById("graphlabel").hidden = true;
        document.getElementById("graph").hidden = true;
    } else {
        document.getElementById("graphlabel").hidden = false;
        document.getElementById("graph").hidden = false;
    }
});



document.getElementById("submitbtn").addEventListener("click", function (e) {
    e.preventDefault();
    if (dateCompare()) {
        let test = document.getElementById('testname').value
        if (test != "all") {
            /*             document.getElementById("graphlabel").hidden = false;
                        document.getElementById("graph").hidden = false; */
            var type = document.getElementById('graph').value
            if (type == 'line') {
                document.getElementById("canvascontainer").classList.remove("w-1/2","h-1/2");
                document.getElementById("canvascontainer").classList.add("w-full","h-full");
                lineGraph();
            } else if (type == 'doughnut') {
                document.getElementById("canvascontainer").classList.remove("w-full","h-full");
                document.getElementById("canvascontainer").classList.add("w-1/2","h-1/2");
                doughnutGraph();
            }
        }
        if (test == "all") {
            if (isNaN(date1) && isNaN(date2)) {
                $.get("/admingraph",
                    {
                        test: 'all',
                    },
                    function (data) {
                        myChart.destroy();
                        var d = data;
                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: d.map(row => row.test),
                                datasets: [{
                                    label: '# of Subministrations',
                                    data: data.map(row => row.subministration),
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
            } else {
                $.get("/admingraph",
                    {
                        test: 'all',
                        datemin: document.getElementById('date1').value,
                        datemax: document.getElementById('date2').value,
                    },
                    function (data) {
                        var d = data;
                        myChart.destroy();
                        myChart = new Chart(ctx, {
                            type: 'bar',
                            data: {
                                labels: d.map(row => row.test),
                                datasets: [{
                                    label: '# of Subministrations',
                                    data: data.map(row => row.subministration),
                                    borderWidth: 1
                                }]
                            },
                            options: {
                                scales: {
                                    y: {
                                        beginAtZero: true
                                    }
                                }
                            }
                        });
                    });
            }
        }
    }
});

function lineGraph() {
    $.get("/admingraph", {
        test: document.getElementById('testname').value,
        datemin: document.getElementById('date1').value,
        datemax: document.getElementById('date2').value,
        type: 'line'
    }, function (data) {
        var d = data;
        console.log(d);
        myChart.destroy();
        if (d) {
            myChart = new Chart(ctx, {
                type: 'line',
                data: {
                    labels: d.map(row => row.date),
                    datasets: [{
                        label: '# of Subministrations',
                        data: data.map(row => row.subministration),
                        borderWidth: 1,
                        borderColor: 'rgb(75, 192, 192)',
                        tension: 0.1
                    }]
                },
                options: {
                    scales: {
                        y: {
                            beginAtZero: true
                        }
                    }
                }
            });
        } else {
            $('p').html("Error!");
        }
    });
}


function doughnutGraph() {
    $.get("/admingraph", {
        test: document.getElementById('testname').value,
        datemin: document.getElementById('date1').value,
        datemax: document.getElementById('date2').value,
        type: 'doughnut'
    }, function (data) {
        var d = data;
        myChart.destroy();
        if (d) {
            myChart = new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: data.map(row => row.score),
                    datasets: [{
                        label: '# of results',
                        data: data.map(row => row.scorecount),
                        overOffset: 4,
                    }]
                },
            });
        } else {
            $('p').html("Error!");
        }
    });
}


function dateCompare() {
    let date1 = new Date(document.getElementById('date1').value).getTime();
    let date2 = new Date(document.getElementById('date2').value).getTime();
    if (document.getElementById('test') != 'all' && (isNaN(date1) && isNaN(date2))){
        $('p').html("Date required!");
        return false;
    }
    if (date1 <= date2 || (isNaN(date1) && isNaN(date2))) {
        $('p').html("");
        return true;
    }
    $('p').html("First date must be before second date");
    return false;
}
