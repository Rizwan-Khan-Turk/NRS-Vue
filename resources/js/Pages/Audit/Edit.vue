<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, onMounted, defineProps } from 'vue'
import { useToast } from "vue-toastification"
import { errors } from '@/Validation/Vendor/Index'

const props = defineProps({
  audit: {
    type: Object,
    required: true
  }
})
console.log("Props are:" + props.audit.object);

const toast = useToast();

onMounted(() => {
  errors.value = {}
});
const parsedObject = JSON.parse(props.audit.object);
</script>

<template>
  <AppLayout title="Audit Log Details for Purchase Order">
    <template #header>
      <h2 class=" font-semibold h2-custom text-gray-900">Audit Log Details for Invoice</h2>
    </template>
	
	<h2 class=" font-semibold h2-custom leading-18 text-gray-900">Transaction Details</h2>
    <div class="max-w-full bg-white p-5 rounded-md">
      <!-- Display the table for po_number and its data -->
      <table class="table-auto w-full mb-5" id="modalDataTable">
        <thead>
          	<tr>
            	<th>PO NUMBER</th>
            	<th>NRS VENDOR ID</th>
            	<th>EDI VENDOR ID</th>
          	</tr>
        </thead>
        <tbody>
          	<tr>
				<td>{{ parsedObject.po_number }}</td>
            	<td>{{ parsedObject.nrs_vendor_id }}</td>
            	<td>{{ parsedObject.edi_vendor_id }}</td>
			</tr>
        </tbody>
      </table>
    </div>
	<h2 class=" font-semibold h2-custom  text-gray-900">Data</h2>
	<div class="max-w-full bg-white p-5 rounded-md">
      <!-- Display the table for po_number and its data -->
      <table class="table-auto w-full mb-5" id="modalDataTable">
        <thead>
          	<tr>
            	<th>UPC</th>
				<th>Quantity Shipped</th>
            	<th>Cost</th>
				<th>Cost Quantity</th>
          	</tr>
        </thead>
		<tbody>
			<tr v-for="(item, index) in parsedObject.items" :key="index">
				<td>{{ item.upc }}</td>
				<td>{{ item.qty_shipped }}</td>
				<td>{{ item.cost }}</td>
				<td>{{ item.cost_qty }}</td>
			</tr>
		</tbody>
      </table>
    </div>
	<h2 class=" font-semibold h2-custom  text-gray-900">Details</h2>
	<div class="max-w-full bg-white p-5 rounded-md">
      <!-- Display the table for po_number and its data -->
      <table class="table-auto w-full mb-5" id="modalDataTable">
        <thead>
          	<tr>
            	<th>Status</th>
            	<th>Payment Type</th>
            	<th>Payment Cents</th>
            	<th>Payment Closed</th>
				<th>Order Notes</th>
          	</tr>
        </thead>
		<tbody>
			<tr>
				<td>{{ parsedObject.status }}</td>
				<td>{{ parsedObject.payment_type }}</td>
				<td>{{ parsedObject.payment_cents }}</td>
				<td>{{ parsedObject.payment_closed }}</td>
				<td>{{ parsedObject.order_notes }}</td>
			</tr>
		</tbody>
      </table>
    </div>
	<div class="max-w-full bg-white p-5 rounded-md">
      <!-- Display the table for po_number and its data -->
      <table class="table-auto w-full mb-5" id="modalDataTable">
        <thead>
          	<tr>
            	<th>Payment Due</th>
            	<th>Shipped Date</th>
          	</tr>
        </thead>
		<tbody>
			<tr >
				<td>{{ parsedObject.payment_due }}</td>
				<td>{{ parsedObject.shipped_date }}</td>
			</tr>
		</tbody>
      </table>
    </div>
	<h2 class=" font-semibold h2-custom  text-gray-900">Contact Information</h2>
	<div class="max-w-full bg-white p-5 rounded-md">
      <!-- Display the table for po_number and its data -->
      <table class="table-auto w-full mb-5" id="modalDataTable">
        <thead>
          	<tr>
            	<th>Name</th>
				<th>Vendor Name</th>
            	<th>Phone</th>
				<th>Role</th>
				<th>Email</th>
          	</tr>
        </thead>
		<tbody>
			<tr v-for="(item, index) in parsedObject.contact_info" :key="index">
				<td>{{ item.name }}</td>
				<td>{{ item.vendor_name }}</td>
				<td>{{ item.phone }}</td>
				<td>{{ item.role }}</td>
				<td>{{ item.email }}</td>
			</tr>
		</tbody>
      </table>
    </div>
	<h2 class=" font-semibold h2-custom  text-gray-900">Additional Information</h2>
	<div class="max-w-full bg-white p-5 rounded-md">
      <!-- Display the table for po_number and its data -->
      <table class="table-auto w-full mb-5" id="modalDataTable">
        <thead>
          	<tr>
				<th>Status</th>
				<th>Vendor</th>
				<th>Transaction Type</th>
				<th>Error Details</th>
          	</tr>
        </thead>
		<tbody>
			<tr>
				<td>{{ props.audit.status }}</td>
				<td>{{ props.audit.vendor }}</td>
				<td>{{ props.audit.transactionType }}</td>
				<td>{{ props.audit.error_detail }}</td>
			</tr>
		</tbody>
      </table>
    </div>
  </AppLayout>
</template>
<style>
#modalDataTable {
  margin-bottom: 10px; /* Adjust the margin as needed */
  border-collapse: collapse;
  width: 100%;
}

#modalDataTable th, #modalDataTable td {
  border: 1px solid black; /* Add borders */
  padding: 8px; /* Adjust the padding as needed */
  text-align: center; /* Adjust the text alignment as needed */
}
#modalDataTable th {
  background-color: #f2f2f2; /* Add background color for header */
}
.h2-custom {
    text-align: center; /* Center the text */
    color: #2d3748; /* Set the text color */
    font-size: 1.75rem; /* Set the font size */
    margin-bottom: 20px; /* Add bottom margin for spacing */
    line-height: 2.5rem;
}
</style>

