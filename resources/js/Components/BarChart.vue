<template>
    <Bar
      id="my-chart-id"
      :options="chartOptions"
      :data="chartData"
    />
  </template>
  
  <script>
  import { Bar } from 'vue-chartjs'
  import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js'
  
  ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale)
  
  export default {
    name: 'BarChart',
    components: { Bar },
    data() {
      return {
        chartData: {
          labels: [ 'January', 'February', 'March' ],
          datasets: [ { data: [40, 20, 12] ,
          backgroundColor: [
              'rgba(255, 99, 132, 0.6)', // January color (red)
              'rgba(54, 162, 235, 0.6)', // February color (blue)
              'rgba(255, 206, 86, 0.6)'  // March color (yellow)
            ]
         } ]
        },
        chartOptions: {
            responsive: true,
        plugins: {
          legend: {
            display: true,
            position: 'top', // You can adjust the legend position (top, bottom, left, right)
            labels: {
              usePointStyle: true, // This option will use a small point-style for each legend item
                    }
             }
            }
        }
      }
    }
  }
  </script>