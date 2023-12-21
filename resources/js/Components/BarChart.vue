<script setup>
import { ref, watchEffect, defineProps } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps(['successPOCount', 'failedPOCount', 'successInvoiceCount', 'failedInvoiceCount']);
const chartRef = ref(null);

const chartData = ref({
  labels: ['Successful PO', 'Failed PO', 'Successful Invoice', 'Failed Invoice'],
  datasets: [{
    data: [0, 0, 0, 0], // Initialize with zeros
    backgroundColor: [
      'rgba(255, 99, 132, 0.6)',
      'rgba(255, 99, 132, 0.6)',
      'rgba(54, 162, 235, 0.6)',
      'rgba(255, 206, 86, 0.6)',
    ],
  }],
});

const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      display: true,
      position: 'bottom',
      title: 'Rizwan',
      labels: {
        usePointStyle: true,
      },
    },
  },
});

// Watch for changes in props and update chartData accordingly
watchEffect(() => {
  chartData.value.datasets[0].data = [
    props.successPOCount,
    props.failedPOCount,
    props.successInvoiceCount,
    props.failedInvoiceCount,
  ];

  if (chartRef.value) {
    //chartRef.value.update(); // Manually update the chart
  }
});
</script>

<template>
  <Bar ref="chartRef" :options="chartOptions" :data="chartData" />
</template>
