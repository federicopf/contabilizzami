
import Chart from 'chart.js/auto';

// Grafico delle statistiche mensili
var ctxMonthly = document.getElementById('monthlyStatsChart').getContext('2d');
var monthlyStatsChart = new Chart(ctxMonthly, {
    type: 'bar',
    data: {
        labels: ['Gen', 'Feb', 'Mar', 'Apr', 'Mag', 'Giu', 'Lug', 'Ago', 'Set', 'Ott', 'Nov', 'Dic'],
        datasets: [{
            label: 'Entrate',
            data: [1200, 1500, 1700, 1300, 1900, 2200, 2100, 1800, 2000, 1900, 2400, 2300],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
        }, {
            label: 'Uscite',
            data: [900, 1000, 800, 1200, 1300, 1400, 1100, 950, 1200, 1300, 1000, 1050],
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
var ctxAnnual = document.getElementById('annualStatsChart').getContext('2d');
var annualStatsChart = new Chart(ctxAnnual, {
    type: 'bar',
    data: {
        labels: ['2020', '2021', '2022', '2023', '2024'],
        datasets: [{
            label: 'Entrate',
            data: [15000, 18000, 17000, 19000, 22000],
            backgroundColor: 'rgba(75, 192, 192, 0.6)',
        }, {
            label: 'Uscite',
            data: [12000, 14000, 13000, 15000, 16000],
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
