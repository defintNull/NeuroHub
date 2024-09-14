import Chart from 'chart.js/auto'

const ctx = document.getElementById('myChart');

const data = [
    { year: 2010, count: 10 },
    { year: 2011, count: 20 },
    { year: 2012, count: 15 },
    { year: 2013, count: 25 },
    { year: 2014, count: 22 },
    { year: 2015, count: 30 },
    { year: 2016, count: 28 },
  ];


new Chart(ctx, {
    type: 'bar',
    data: {
        labels: Object.keys(d),
        datasets: [{
            label: '# of Subministrations',
            data: Object.values(d),
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
