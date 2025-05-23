
import Chart from 'chart.js/auto';

const year = new Date().getFullYear();

// Grafico delle statistiche mensili
var ctxMonthly = document.getElementById('monthlyStatsChart').getContext('2d');
window.monthlyStatsChart = new Chart(ctxMonthly, {
    type: 'bar',
    data: {
        labels: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
        datasets: [{
            label: 'Entrate',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
        }, {
            label: 'Uscite',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
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


// Grafico delle statistiche annuali
var ctxAnnual = document.getElementById('yearlyStatsChart').getContext('2d');
window.yearlyStatsChart = new Chart(ctxAnnual, {
    type: 'bar',
    data: {
        labels: [''+(year-4), ''+(year-3), ''+(year-2), ''+(year-1), ''+(year)],
        datasets: [{
            label: 'Entrate',
            data: [0, 0, 0, 0, 0],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
        }, {
            label: 'Uscite',
            data: [0, 0, 0, 0, 0],
            backgroundColor: 'rgba(255, 99, 132, 0.6)',
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
