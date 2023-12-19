<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, watch, nextTick, defineProps, onMounted, reactive } from 'vue'
import { usePage } from "@inertiajs/vue3"
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import { validateForm, errors, watchFields } from '@/Validation/Vendor/Index'

const isSubmitting = ref(false)
const toast = useToast()

//const { pet } = usePage().props

// Get the pet data from the prop
const props = defineProps({
	vendor: {
		type: Object,
		required: true
	}
})
console.log("Props are:" + props.vendor);

// Initialize the form with the pet data
const editForm = reactive({
	vname: props.vendor.vname,
	vcode: props.vendor.vcode,
	po_ip: props.vendor.po_ip,
	po_port: props.vendor.po_port,
	po_directory: props.vendor.po_directory,
	ftp_username: props.vendor.ftp_username,
	ftp_password: props.vendor.ftp_password,
	invoice_ip: props.vendor.invoice_ip,
	invoice_port: props.vendor.invoice_port,
	invoice_directory: props.vendor.invoice_directory,
	ftp_username_invoice: props.vendor.ftp_username_invoice,
	ftp_password_invoice: props.vendor.ftp_password_invoice,
	file_pattern: props.vendor.file_pattern
	
})

const editVendor = async () => {
  isSubmitting.value = true;

  
  const formData = new FormData();
  Object.keys(editForm).forEach((key) => {
  	formData.append(key, editForm[key]);
	});

	// for (let pair of formData.entries()) {
  	// 	console.log(pair[0] + ', ' + pair[1]);
	// }

  
  const response = await axios.post(`/vendor/${props.vendor.id}`, formData)

   toast.success(response.data.message)

  isSubmitting.value = false;
}
</script>

<template>
	<AppLayout title="Edit Vendor">
		<template #header>
			<h2 class="text-lg font-semibold leading-6 text-gray-900">
				Edit Vendor: {{ vendor.vname }}
			</h2>
		</template>

		<div class="max-w-full bg-white p-5 rounded-md">
			<form @submit.prevent="editVendor" enctype="multipart/form-data" class="space-y-5">
				<div class="grid grid-cols-12 gap-5">
					<div class="col-span-6">
						<label for="vname" class="mb-2 block text-sm font-medium text-gray-500">Name</label>
						<input v-model="editForm.vname" type="text" id="vname"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.vname }"
							placeholder="Pet Name" />
						<div v-if="errors.vname" class="text-sm text-red-500 mt-1">
							{{ errors.vname }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="vcode" class="mb-2 block text-sm font-medium text-gray-700">Vendor Code</label>
						<input v-model="editForm.vcode" type="text" id="vcode"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.vcode }" placeholder="Vendor Code" />
						<div v-if="errors.vcode" class="text-sm text-red-500 mt-1">
							{{ errors.vcode }}
						</div>
					</div>
					<!--Purchase Order Fields-->
					<div class="col-span-6">
						<label for="po_ip" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Server IP</label>
						<input v-model="editForm.po_ip" type="text" id="po_ip"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.po_ip }" placeholder="PO FTP Server IP" />
						<div v-if="errors.po_ip" class="text-sm text-red-500 mt-1">
							{{ errors.po_ip }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="po_port" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Server Port</label>
						<input v-model="editForm.po_port" type="text" id="po_port"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.po_port }" placeholder="PO FTP Server Port" />
						<div v-if="errors.po_port" class="text-sm text-red-500 mt-1">
							{{ errors.po_port }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="po_directory" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Server Directory</label>
						<input v-model="editForm.po_directory" type="text" id="po_directory"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.po_directory }" placeholder="PO FTP Server Directory" />
						<div v-if="errors.po_directory" class="text-sm text-red-500 mt-1">
							{{ errors.po_directory }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_username" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Username</label>
						<input v-model="editForm.ftp_username" type="text" id="ftp_username"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_username }" placeholder="PO FTP Username" />
						<div v-if="errors.ftp_username" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_username }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_password" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Password</label>
						<input v-model="editForm.ftp_password" type="password" id="ftp_password"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_password }" placeholder="PO FTP Password" />
						<div v-if="errors.ftp_password" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_password }}
						</div>
					</div>
					<!--Invoice Fields-->
					<div class="col-span-6">
						<label for="invoice_ip" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Server IP</label>
						<input v-model="editForm.invoice_ip" type="text" id="invoice_ip"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.invoice_ip }" placeholder="Invoice FTP Server IP" />
						<div v-if="errors.invoice_ip" class="text-sm text-red-500 mt-1">
							{{ errors.invoice_ip }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="invoice_port" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Server Port</label>
						<input v-model="editForm.invoice_port" type="text" id="invoice_port"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.invoice_port }" placeholder="Invoice FTP Server Port" />
						<div v-if="errors.invoice_port" class="text-sm text-red-500 mt-1">
							{{ errors.invoice_port }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="invoice_directory" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Server Directory</label>
						<input v-model="editForm.invoice_directory" type="text" id="invoice_directory"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.invoice_directory }" placeholder="Invoice FTP Server Directory" />
						<div v-if="errors.invoice_directory" class="text-sm text-red-500 mt-1">
							{{ errors.invoice_directory }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_username_invoice" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Username</label>
						<input v-model="editForm.ftp_username_invoice" type="text" id="ftp_username_invoice"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_username_invoice }" placeholder="Invoice FTP Username" />
						<div v-if="errors.ftp_username_invoice" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_username_invoice }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_password_invoice" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Password</label>
						<input v-model="editForm.ftp_password_invoice" type="password" id="ftp_password_invoice"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_password_invoice }" placeholder="Invoice FTP Password" />
						<div v-if="errors.ftp_password_invoice" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_password_invoice }}
						</div>
					</div>
					<div class="col-span-12">
						<label for="file_pattern" class="mb-2 block text-sm font-medium text-gray-700">File Pattern</label>
						<input v-model="editForm.file_pattern" type="text" id="file_pattern"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.file_pattern }" placeholder="File Pattern" />
						<div v-if="errors.file_pattern" class="text-sm text-red-500 mt-1">
							{{ errors.file_pattern }}
						</div>
					</div>
					

					<div class="col-span-12">
						
					</div>

					<div class="col-span-12">
						<button type="submit" :disabled="isSubmitting"
							class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
							Edit Vendor
						</button>
					</div>

				</div>
			</form>
		</div>

		<div class="max-w-full px-2 py-10 sm:px-0">
			
		</div>


	</AppLayout>
</template>