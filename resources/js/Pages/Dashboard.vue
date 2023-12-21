<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { ref, onMounted } from 'vue';
import axios from 'axios';
import { CalendarIcon } from '@heroicons/vue/24/outline';


  const vendorCount = ref(0);
  const successfulTransactions = ref(0);
  const purchaseOrderCount = ref(0);
  const invoiceCount = ref(0);

  onMounted(async () => {
    try {
      const response = await axios.get('/dashboard/get');
      vendorCount.value = response.data.vendorCount;
      successfulTransactions.value = response.data.successfulTransactions;
      purchaseOrderCount.value = response.data.purchaseOrderCount;
      invoiceCount.value = response.data.invoiceCount;

    } catch (error) {
      console.error('Error fetching vendor count', error);
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
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Dashboard
            </h2>
        </template>
        <!--First Row-->
        <div class="overflow-hidden sm:rounded-lg">
            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-4">
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-blue">
                    <div class="flex items-center justify-center blue-text">
                        TOTAL NUMBER OF 
                    </div>
                    <div class="flex items-center justify-center blue-text">
                        VENDORS ADDED
                    </div>
                    <div class="flex items-center justify-center">
                        {{ vendorCount }}
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-green">
                    <div class="flex items-center justify-center green-text">
                        VENDORS SUCCESSFUL 
                    </div>
                    <div class="flex items-center justify-center green-text">
                        TRANSACTIONS
                    </div>
                    <div class="flex items-center justify-center">
                        {{ successfulTransactions }}
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-lightblue">
                    <div class="flex items-center justify-center lightblue-text">
                        TOTAL NUMBER OF 
                    </div>
                    <div class="flex items-center justify-center lightblue-text">
                        PO RECEIVED
                    </div>
                    <div class="flex items-center justify-center">
                        {{ purchaseOrderCount }}
                    </div>
                </div>
                <div class="border-2 border-gray-300 rounded-lg dark:border-gray-600 h-32 md:h-32 bg-white with-border-orange">
                    <div class="flex items-center justify-center orange-text">
                        TOTAL NUMBER
                    </div>
                    <div class="flex items-center justify-center orange-text">
                        OF INVOICE
                    </div>
                    <div class="flex items-center justify-center">
                        {{ invoiceCount }}
                    </div>
                </div>
            </div>
            <!--Second Row-->
            <!-- <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-96 mb-4"></div> -->
            <!--Third Row-->
            <div class="grid grid-cols-2 gap-4 mb-4">
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                    <BarChart />
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
                <div class="border-2 border-dashed rounded-lg border-gray-300 dark:border-gray-600 h-48 md:h-72">
                </div>
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

</style>
