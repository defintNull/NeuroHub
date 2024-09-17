import './bootstrap';

import Chart from 'chart.js/auto'
import Alpine from 'alpinejs';
import jQuery from 'jquery';

window.Alpine = Alpine;

var myChart;

const ctx = document.getElementById('myChart');


$.get("/medgraph", function (data) {
    var d = data;
    console.log(d);
    if (d) {
        myChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: d.map(row => row.date),
                datasets: [{
                    label: '# of Subministrations',
                    data: data.map(row => row.visitcount),
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


document.getElementById("submitbtn").addEventListener("click", function (e) {
    e.preventDefault();
    if (dateCompare())
        lineGraph();
});


function lineGraph() {
    $.get("/medgraph", {
        date1: document.getElementById('date1').value,
        date2: document.getElementById('date2').value,
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
                        data: data.map(row => row.visitcount),
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

function dateCompare() {
    let date1 = new Date(document.getElementById('date1').value).getTime();
    let date2 = new Date(document.getElementById('date2').value).getTime();
    if (date1 <= date2 || (isNaN(date1) && isNaN(date2))) {
        $('p').html("");
        return true;
    }
    $('p').html("First date must be before second date");
    return false;
}
