<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';

  const vendorCount = ref(0);
  const successfulTransactions = ref(0);
  const purchaseOrderCount = ref(0);
  const invoiceCount = ref(0);
  const successPOCount = ref(0);
  const failedPOCount = ref(0);
  const successInvoiceCount = ref(0);
  const failedInvoiceCount = ref(0);
  const failedTransactions = ref(0);
  const totalTransactions = ref(0);
  const progressValue = ref(50); // Set the initial progress value (change as needed)
  const maxValue = ref(1000);


  onMounted(async () => {
    try {
      const response = await axios.get('/dashboard/get');
      vendorCount.value = response.data.vendorCount;
      successfulTransactions.value = response.data.successfulTransactions;
      purchaseOrderCount.value = response.data.purchaseOrderCount;
      invoiceCount.value = response.data.invoiceCount;
      successPOCount.value = response.data.successPOCount;
      failedPOCount.value = response.data.failedPOCount;
      successInvoiceCount.value = response.data.successInvoiceCount;
      failedInvoiceCount.value = response.data.failedInvoiceCount;
      failedTransactions.value = response.data.failedTransactions;
      totalTransactions.value = response.data.totalTransactions;


    } catch (error) {
      console.error('Error fetching dashboard data', error);
    }
  });

</script>

<script>
import BarChart from '@/Components/BarChart.vue';

export default {
  name: 'App',
  components: { BarChart }
}
</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <div class="flex justify-between items-end">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
              Dashboard
            </h2>
            <p class="text-sm text-gray-500">Last 30 Days</p>
          </div>
        </template>
        <!--First Row-->
        <div class="overflow-hidden sm:rounded-lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-blue">
                    <div class="flex items-center justify-center blue-text font-semibold text-xl text-gray-800 leading-tight">
                        TOTAL VENDORS 
                    </div>
                    <div class="flex items-center justify-center blue-text font-semibold text-xl text-gray-800 leading-tight">
                         ADDED
                    </div>
                    <div class="flex items-center justify-center font-semibold text-xl text-gray-800 leading-tight">
                        {{ vendorCount }}
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-green">
                    <div class="flex items-center justify-center green-text font-semibold text-xl text-gray-800 leading-tight">
                        SUCCESSFUL 
                    </div>
                    <div class="flex items-center justify-center green-text font-semibold text-xl text-gray-800 leading-tight">
                        TRANSACTIONS
                    </div>
                    <div class="flex items-center justify-center font-semibold text-xl text-gray-800 leading-tight">
                        {{ successfulTransactions }}
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-lightblue">
                    <div class="flex items-center justify-center lightblue-text font-semibold text-xl text-gray-800 leading-tight">
                        TOTAL NUMBER OF 
                    </div>
                    <div class="flex items-center justify-center lightblue-text font-semibold text-xl text-gray-800 leading-tight">
                        PO RECEIVED
                    </div>
                    <div class="flex items-center justify-center font-semibold text-xl text-gray-800 leading-tight">
                        {{ purchaseOrderCount }}
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-orange">
                    <div class="flex items-center justify-center orange-text font-semibold text-xl text-gray-800 leading-tight ">
                        TOTAL NUMBER
                    </div>
                    <div class="flex items-center justify-center orange-text font-semibold text-xl text-gray-800 leading-tight">
                        OF INVOICE
                    </div>
                    <div class="flex items-center justify-center font-semibold text-xl text-gray-800 leading-tight">
                        {{ invoiceCount }}
                    </div>
                </div>
            </div>
            <!--Second Row-->
            <!-- <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div> -->
            <!--Third Row-->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-72 bg-white">
                  <!-- <BarChart
                  :successPOCount="successPOCount"
                  :failedPOCount="failedPOCount"
                  :successInvoiceCount="successInvoiceCount"
                  :failedInvoiceCount="failedInvoiceCount"
                  />                -->
                  <BarChart/>
                  </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-72 bg-white">
                  <div class="flex items-center justify-center font-semibold text-xl text-gray-800 leading-tight">
                        Summary
                    </div>
                    <br/>
                  <span class="font-semibold text-xl text-gray-800 leading-tight" style="margin-left: 12px;">Total Transactions: {{ totalTransactions }} </span>
                  <br/>
                  <progress class="progress-width" :value="totalTransactions" :max="maxValue"></progress>
                  <div class="text-center mt-2">
                  </div>
                  <span class="font-semibold text-xl text-gray-800 leading-tight" style="margin-left: 12px;">Successful Transactions: {{ successfulTransactions }} </span>
                  <br/>
                  <progress class="progress-width2" :value="successfulTransactions" :max="maxValue"></progress>
                  <div class="text-center mt-2">
                  </div>
                  <span class="font-semibold text-xl text-gray-800 leading-tight" style="margin-left: 12px;">Failed Transactions: {{ failedTransactions }} </span>
                  <br/>
                  <progress class="progress-width3" :value="failedTransactions" :max="maxValue"></progress>
                  <div class="text-center mt-2">
                  </div>
                  <span class="font-semibold text-xl text-gray-800 leading-tight" style="margin-left: 12px;">Total Vendors Added: {{ vendorCount }} </span>
                  <br/>
                  <progress class="progress-width4" :value="vendorCount" :max="maxValue"></progress>
                  <div class="text-center mt-2">
                  </div>

                </div>
                <!-- <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div> -->
            </div>
            <!--Forth Row-->
            <!-- <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div> -->
            <!--Fifth Row-->
            <!-- <div class="grid grid-cols-2 gap-4">
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
            </div> -->
        </div>
    </AppLayout>
</template>

<style>
.with-border-blue {
  border-left: 4px solid blue;
}
.blue-text {
  color: blue;
}
.with-border-green {
  border-left: 4px solid green;
}
.green-text {
  color: green;
}
.with-border-lightblue {
  border-left: 4px solid #36b9cc;
}
.lightblue-text{
    color:#36b9cc;
}
.with-border-orange {
  border-left: 4px solid orange;
}
.orange-text{
    color: orange;
}
.progress-width{
  width: 95%;
  margin-left: 12px;
}
.progress-width::-webkit-progress-value {
  background-color: blue; /* Change this to your desired color */
}
.progress-width::-webkit-progress-bar {
  background-color: lightgray; /* Change this to your desired color */
}
.progress-width2{
  width: 95%;
  margin-left: 12px;
}
.progress-width2::-webkit-progress-value {
  background-color: green; /* Change this to your desired color */
}
.progress-width2::-webkit-progress-bar {
  background-color: lightgray; /* Change this to your desired color */
}
.progress-width3{
  width: 95%;
  margin-left: 12px;
}
.progress-width3::-webkit-progress-value {
  background-color: red; /* Change this to your desired color */
}
.progress-width3::-webkit-progress-bar {
  background-color: lightgray; /* Change this to your desired color */
}
.progress-width4{
  width: 95%;
  margin-left: 12px;
}
.progress-width4::-webkit-progress-value {
  background-color: cyan; /* Change this to your desired color */
}
.progress-width4::-webkit-progress-bar {
  background-color: lightgray; /* Change this to your desired color */
}


</style>
