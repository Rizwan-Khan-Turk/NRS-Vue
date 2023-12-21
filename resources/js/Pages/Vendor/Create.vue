<script setup>
import AppLayout from '@/Layouts/AppLayout.vue'
import { ref, watch, nextTick, onMounted } from 'vue'
import { useForm } from "@inertiajs/vue3"
import 'vue-multiselect/dist/vue-multiselect.css'
import { useToast } from "vue-toastification"
import { errors, watchFields } from '@/Validation/Vendor/Index'

const isSubmitting = ref(false)


const toast = useToast();

const createForm = useForm({
	vname: '',
	vcode: '',
	po_ip: '',
	po_port: '',
	po_directory: '',
	ftp_username: '',
	ftp_password: '',
	invoice_ip: '',
	invoice_port: '',
	invoice_directory: '',
	ftp_username_invoice: '',
	ftp_password_invoice: '',
	file_pattern: ''
})
const resetForm = () => {
	createForm.vname = '';
	createForm.vcode = '';
	createForm.po_ip = '';
	createForm.po_port = '';
	createForm.po_directory = '';
	createForm.ftp_username = '';
	createForm.ftp_password = '';
	createForm.invoice_ip = '';
	createForm.invoice_port = '';
	createForm.invoice_directory = '';
	createForm.ftp_username_invoice = '';
	createForm.ftp_password_invoice = '';
	createForm.file_pattern = '';
}
onMounted(async () => {
	watchFields(createForm);
	errors.value = {}
});

const createVendor = async () => {
	isSubmitting.value = true;

	// Validate the form
	//const isValid = validateForm(createForm);
	

	// If the form is not valid, display a toast message and return
	//if (!isValid) {
		//toast.error("Please fill in all fields.");
		//return;
	//}

	const formData = new FormData();
	formData.append('vname', createForm.vname);
	formData.append('vcode', createForm.vcode);
	formData.append('po_ip', createForm.po_ip);
	formData.append('po_port', createForm.po_port);
	formData.append('po_directory', createForm.po_directory);
	formData.append('ftp_username', createForm.ftp_username);
	formData.append('ftp_password', createForm.ftp_password);
	formData.append('invoice_ip', createForm.invoice_ip);
	formData.append('invoice_port', createForm.invoice_port);
	formData.append('invoice_directory', createForm.invoice_directory);
	formData.append('ftp_username_invoice', createForm.ftp_username_invoice);
	formData.append('ftp_password_invoice', createForm.ftp_password_invoice);
	formData.append('file_pattern', createForm.file_pattern);

	try {
        const response = await axios.post('/vendor/store', formData);
        resetForm();
        toast.success(response.data.message);
		// Delay the redirection for a brief moment to allow the toaster message to display
		setTimeout(() => {
      	window.location.href = '/vendor';
    	}, 2000);
    } catch (error) {
        console.error(error);
        toast.error("An error occurred while submitting the form.");
    } finally {
        isSubmitting.value = false;
		errors.value = {};
    }
};

const validateForm = (form) => {
    // Clear previous errors
    errors.value = {};

    let isValid = true;

    // Check each field
    Object.keys(form).forEach((fieldName) => {
        if (!form[fieldName]) {
            // Field is empty, set error
            errors.value[fieldName] = 'Required';
            isValid = false;
        }
    });

    return isValid;
};

</script>

<template>
	<AppLayout title="Add Vendor">
		<template #header>
			<h2 class="text-lg font-semibold leading-6 text-gray-900">
				Add Vendor
			</h2>
		</template>

		<div class="max-w-full bg-white p-5 rounded-md">
			<form @submit.prevent="createVendor" enctype="multipart/form-data" class="space-y-5">
				<div class="grid grid-cols-12 gap-5">

					<div class="col-span-6">
						<label for="vname" class="mb-2 block text-sm font-medium text-gray-700">Vendor Name</label>
						<input v-model="createForm.vname" type="text" id="vname"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.vname }" placeholder="Vendor Name" />
						<div v-if="errors.vname" class="text-sm text-red-500 mt-1">
							{{ errors.vname }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="vcode" class="mb-2 block text-sm font-medium text-gray-700">Vendor Code</label>
						<input v-model="createForm.vcode" type="text" id="vcode"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.vcode }" placeholder="Vendor Code" />
						<div v-if="errors.vcode" class="text-sm text-red-500 mt-1">
							{{ errors.vcode }}
						</div>
					</div>
					<!--Purchase Order Fields-->
					<div class="col-span-6">
						<label for="po_ip" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Server IP</label>
						<input v-model="createForm.po_ip" type="text" id="po_ip"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.po_ip }" placeholder="PO FTP Server IP" />
						<div v-if="errors.po_ip" class="text-sm text-red-500 mt-1">
							{{ errors.po_ip }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="po_port" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Server Port</label>
						<input v-model="createForm.po_port" type="text" id="po_port"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.po_port }" placeholder="PO FTP Server Port" />
						<div v-if="errors.po_port" class="text-sm text-red-500 mt-1">
							{{ errors.po_port }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="po_directory" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Server Directory</label>
						<input v-model="createForm.po_directory" type="text" id="po_directory"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.po_directory }" placeholder="PO FTP Server Directory" />
						<div v-if="errors.po_directory" class="text-sm text-red-500 mt-1">
							{{ errors.po_directory }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_username" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Username</label>
						<input v-model="createForm.ftp_username" type="text" id="ftp_username"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_username }" placeholder="PO FTP Username" />
						<div v-if="errors.ftp_username" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_username }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_password" class="mb-2 block text-sm font-medium text-gray-700">PO FTP Password</label>
						<input v-model="createForm.ftp_password" type="password" id="ftp_password"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_password }" placeholder="PO FTP Password" />
						<div v-if="errors.ftp_password" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_password }}
						</div>
					</div>
					<!--Invoice Fields-->
					<div class="col-span-6">
						<label for="invoice_ip" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Server IP</label>
						<input v-model="createForm.invoice_ip" type="text" id="invoice_ip"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.invoice_ip }" placeholder="Invoice FTP Server IP" />
						<div v-if="errors.invoice_ip" class="text-sm text-red-500 mt-1">
							{{ errors.invoice_ip }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="invoice_port" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Server Port</label>
						<input v-model="createForm.invoice_port" type="text" id="invoice_port"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.invoice_port }" placeholder="Invoice FTP Server Port" />
						<div v-if="errors.invoice_port" class="text-sm text-red-500 mt-1">
							{{ errors.invoice_port }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="invoice_directory" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Server Directory</label>
						<input v-model="createForm.invoice_directory" type="text" id="invoice_directory"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.invoice_directory }" placeholder="Invoice FTP Server Directory" />
						<div v-if="errors.invoice_directory" class="text-sm text-red-500 mt-1">
							{{ errors.invoice_directory }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_username_invoice" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Username</label>
						<input v-model="createForm.ftp_username_invoice" type="text" id="ftp_username_invoice"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_username_invoice }" placeholder="Invoice FTP Username" />
						<div v-if="errors.ftp_username_invoice" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_username_invoice }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="ftp_password_invoice" class="mb-2 block text-sm font-medium text-gray-700">Invoice FTP Password</label>
						<input v-model="createForm.ftp_password_invoice" type="password" id="ftp_password_invoice"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.ftp_password_invoice }" placeholder="Invoice FTP Password" />
						<div v-if="errors.ftp_password_invoice" class="text-sm text-red-500 mt-1">
							{{ errors.ftp_password_invoice }}
						</div>
					</div>
					<div class="col-span-12">
						<label for="file_pattern" class="mb-2 block text-sm font-medium text-gray-700">File Pattern</label>
						<input v-model="createForm.file_pattern" type="text" id="file_pattern"
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
							Add Vendor
						</button>
					</div>

				</div>
			</form>
		</div>

	</AppLayout>
</template>