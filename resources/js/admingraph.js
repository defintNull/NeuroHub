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
        document.getElementById("canvascontainer").classList.remove("w-1/2", "h-1/2");
        document.getElementById("canvascontainer").classList.add("w-full", "h-full");
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
                document.getElementById("canvascontainer").classList.remove("w-1/2", "h-1/2");
                document.getElementById("canvascontainer").classList.add("w-full", "h-full");
                lineGraph();
            } else if (type == 'doughnut') {
                document.getElementById("canvascontainer").classList.remove("w-full", "h-full");
                document.getElementById("canvascontainer").classList.add("w-1/2", "h-1/2");
                doughnutGraph();
            } else if (type == 'bar') {
                document.getElementById("canvascontainer").classList.remove("w-1/2", "h-1/2");
                document.getElementById("canvascontainer").classList.add("w-full", "h-full");
                barGhraph();
            }
        }
        if (test == "all") {
            $.get("/admingraph",
                {
                    test: 'all',
                    datemin: document.getElementById('date1').value,
                    datemax: document.getElementById('date2').value,
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
                },
            );
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
            if (!document.getElementById("nodata").classList.contains("hidden")) {
                document.getElementById("nodata").classList.add("hidden");
            }
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
            $('#error').html("Error!");
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
        if (!document.getElementById("nodata").classList.contains("hidden")) {
            document.getElementById("nodata").classList.add("hidden");
        }
        myChart.destroy();
        if (d == "No data") {
            document.getElementById("nodata").classList.remove("hidden");
        } else {
            if (d) {
                if (!document.getElementById("nodata").classList.contains("hidden")) {
                    document.getElementById("nodata").classList.add("hidden");
                }
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
                $('#error').html("Error!");
            }
        }
    });
}


function dateCompare() {
    if (document.getElementById('test') == 'all') {
        $('#error').html("");
        return true;
    }
    let date1 = new Date(document.getElementById('date1').value).getTime();
    let date2 = new Date(document.getElementById('date2').value).getTime();
    if (document.getElementById('test') != 'all' && (isNaN(date1) || isNaN(date2))) {
        $('#error').html("Date required!");
        return false;
    }
    if (date1 <= date2 || (isNaN(date1) && isNaN(date2))) {
        $('#error').html("");
        return true;
    }
    $('#error').html("First date must be before second date");
    return false;
}

function barGhraph() {
    $.get("/admingraph",
        {
            test: document.getElementById('testname').value,
            datemin: document.getElementById('date1').value,
            datemax: document.getElementById('date2').value,
            type: 'bar'
        },
        function (data) {
            if (!document.getElementById("nodata").classList.contains("hidden")) {
                document.getElementById("nodata").classList.add("hidden");
            }
            console.log(data);
            var d = data;
            document.getElementById("canvascontainer").classList.remove("w-1/2", "h-1/2");
            document.getElementById("canvascontainer").classList.add("w-full", "h-full");
            myChart.destroy();
            myChart = new Chart(ctx, {
                type: 'bar',
                data: {
                    labels: d.map(row => row.section),
                    datasets: [{
                        label: '# of Subministrations',
                        data: data.map(row => row.avgscore),
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
