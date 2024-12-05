
import Chart from 'chart.js/auto';

const year = new Date().getFullYear();

// Grafico delle statistiche mensili
var ctxMonthly = document.getElementById('monthlyTotalChart').getContext('2d');
window.monthlyTotalChart = new Chart(ctxMonthly, {
    type: 'line',
    data: {
        labels: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
        datasets: [{
            label: 'Totale',
            data: [0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0, 0],
            backgroundColor: '#2470e2a9',
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
var ctxAnnual = document.getElementById('yearlyTotalChart').getContext('2d');
window.yearlyTotalChart = new Chart(ctxAnnual, {
    type: 'line',
    data: {
        labels: [''+(year-4), ''+(year-3), ''+(year-2), ''+(year-1), ''+(year)],
        datasets: [{
            label: 'Totale',
            data: [0, 0, 0, 0, 0],
            backgroundColor: '#2470e2a9',
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
