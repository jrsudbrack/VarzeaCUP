<template>
    <div>
        <header class="header">
            <div class="header-container">
                <h1 class="header-title">VarzeaCUP</h1>
                <a class="login-button" href="/login">Login</a>
            </div>
        </header>
        <div class="chart-container">
            <canvas id="classificationChart"></canvas>
        </div>
        <table class="classification-table">
            <thead>
                <tr>
                    <th>Posição</th>
                    <th>Time</th>
                    <th>Pontos</th>
                    <th>Partidas Jogadas</th>
                    <th>Vitorias</th>
                    <th>Empates</th>
                    <th>Derrotas</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="(team, index) in classification" :key="team.id">
                    <td>{{ index + 1 }}</td>
                    <td>{{ team.name }}</td>
                    <td>{{ team.points }}</td>
                    <td>{{ team.matches_played }}</td>
                    <td>{{ team.wins }}</td>
                    <td>{{ team.draws }}</td>
                    <td>{{ team.losses }}</td>
                </tr>
            </tbody>
        </table>
    </div>
</template>

<script>
import axios from 'axios';
import Chart from 'chart.js/auto';

export default {
    data() {
        return {
            classification: []
        };
    },
    created() {
        this.fetchClassification();
    },
    methods: {
        async fetchClassification() {
            try {
                const response = await axios.get('/api/classification');
                this.classification = response.data;
                this.renderChart();
            } catch (error) {
                console.error('Error fetching classification:', error);
            }
        },
        renderChart() {
            const labels = this.classification.map(team => team.name);
            const data = this.classification.map(team => team.points);
            const ctx = document.getElementById('classificationChart').getContext('2d');
            new Chart(ctx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Pontos',
                        data,
                        backgroundColor: 'rgba(75, 192, 192, 0.2)',
                        borderColor: 'rgba(75, 192, 192, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: {
                            position: 'top'
                        },
                        title: {
                            display: true,
                            text: 'Grafico de Pontuação'
                        }
                    }
                }
            });
        },
    }
};
</script>

<style scoped>
@import url('https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap');

body {
    font-family: 'Roboto', sans-serif;
    background-color: #f9f9f9;
    margin: 0;
    padding: 20px;
}
.header {
    background-color: #34495e;
    padding: 10px 20px;
    color: #fff;
    height: 50px;
}
.header-container {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.header-title {
    font-size: 1.5rem;
    margin: 0;
    color: #f39c12;
}
.login-button {
    background-color: #fff;
    color: #007bff;
    text-decoration: none;
    padding: 10px 15px;
    border-radius: 5px;
    font-weight: 700;
    cursor: pointer;
    transition: background-color 0.3s;
}
.login-button:hover {
    background-color: #e6e6e6;
}
.title {
    text-align: center;
    font-size: 2rem;
    color: #333;
    margin-bottom: 20px;
}
.chart-container {
    width: 100%;
    max-width: 800px;
    margin: 0 auto 40px;
}
.classification-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 20px;
    background: #fff;
    box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
    border-radius: 8px;
    overflow: hidden;
}
.classification-table th, .classification-table td {
    padding: 10px;
    text-align: center;
    border: 1px solid #ddd;
}
.classification-table th {
    background-color: #34495e;
    color: #fff;
    font-weight: 700;
}
.classification-table tr:nth-child(even) {
    background-color: #f4f4f4;
}
</style>
