import './bootstrap';

import Chart from 'chart.js/auto'
import Alpine from 'alpinejs';
import jQuery from 'jquery';

window.Alpine = Alpine;

var myChart;

const ctx = document.getElementById('myChart');


$.get("/admingraph", function (data) {
    var d = data;
    if (d) {
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
    } else {
        $('p').html("Error!");
    }
});


$('#testname').on('change', function () {
    $.get("/admingraph", {
        test: document.getElementById("testname").value
    }, function (data) {
        var d = data;
        myChart.destroy();
        if (d) {
            if (document.getElementById("testname").value == "all") {
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
            } else {
                myChart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: d.map(row => row.data),
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
            }
        } else {
            $('p').html("Error!");
        }
    });
});
