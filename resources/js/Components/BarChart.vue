<script setup>
import { ref, watchEffect, onMounted, defineProps } from 'vue';
import { Bar } from 'vue-chartjs';
import { Chart as ChartJS, Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale } from 'chart.js';
import axios from 'axios';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

const props = defineProps(['successPOCount', 'failedPOCount', 'successInvoiceCount', 'failedInvoiceCount']);
const chartData = ref({
  labels: ['Successful PO', 'Failed PO', 'Successful Invoice', 'Failed Invoice'],
  datasets: [{
    data: [0, 0, 0, 0], 
    backgroundColor: [
      'green',
      'red',
      'blue',
      'brown',
    ],
  }],
});

const chartOptions = ref({
  responsive: true,
  plugins: {
    legend: {
      display: false,
      position: 'bottom',
      labels: {
        usePointStyle: true,
        title: {
          display: true,
          text: 'Rizwan',
        },
      },
    },
  },
});


let forceRerender = ref(0);

onMounted(async () => {
  try {
    const response = await axios.get('/dashboard/get');
    chartData.value.datasets[0].data = [
      response.data.successPOCount,
      response.data.failedPOCount,
      response.data.successInvoiceCount,
      response.data.failedInvoiceCount,
    ];

    // Incrementing the reactive variable to force re-rendering
    forceRerender.value++;
  } catch (error) {
    console.error('Error fetching data from /dashboard/get', error);
  }
});

watchEffect(() => {
  // The reactive variable is used as a key to force re-rendering
  const key = forceRerender.value;
  forceRerender.value = key + 1;
});
</script>

<template>
  <Bar :key="forceRerender" :options="chartOptions" :data="chartData" />
</template>
