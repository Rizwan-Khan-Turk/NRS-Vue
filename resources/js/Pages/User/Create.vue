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
	name: '',
	last_name: '',
	email: '',
	password: '',
	
})
const resetForm = () => {
	createForm.name= '';
	createForm.last_name = '';
	createForm.email = '';
	createForm.password = '';
}
onMounted(async () => {
	watchFields(createForm);
	errors.value = {}
});

const createUser = async () => {
	isSubmitting.value = true;

	// Validate the form
	//const isValid = validateForm(createForm);
	

	// If the form is not valid, display a toast message and return
	//if (!isValid) {
		//toast.error("Please fill in all fields.");
		//return;
	//}

	const formData = new FormData();
	formData.append('name', createForm.name);
	formData.append('last_name', createForm.last_name);
	formData.append('email', createForm.email);
	formData.append('password', createForm.password);

	

	try {
        const response = await axios.post('/user/store', formData);
        resetForm();
        toast.success(response.data.message);
 		// Delay the redirection for a brief moment to allow the toaster message to display
 		setTimeout(() => {
      	window.location.href = '/users';
    	}, 2000); 
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
	<AppLayout title="Add User">
		<template #header>
			<h2 class="text-lg font-semibold leading-6 text-gray-900">
				Add User
			</h2>
		</template>

		<div class="max-w-full bg-white p-5 rounded-md">
			<form @submit.prevent="createUser" enctype="multipart/form-data" class="space-y-5">
				<div class="grid grid-cols-12 gap-5">

					<div class="col-span-6">
						<label for="name" class="mb-2 block text-sm font-medium text-gray-700">First Name</label>
						<input v-model="createForm.name" type="text" id="name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.name }" placeholder="First Name" />
						<div v-if="errors.name" class="text-sm text-red-500 mt-1">
							{{ errors.name }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">Last Name</label>
						<input v-model="createForm.last_name" type="text" id="last_name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.last_name }" placeholder="Last Name" />
						<div v-if="errors.last_name" class="text-sm text-red-500 mt-1">
							{{ errors.last_name }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
						<input v-model="createForm.email" type="text" id="email"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.email }" placeholder="Email" />
						<div v-if="errors.email" class="text-sm text-red-500 mt-1">
							{{ errors.email }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password</label>
						<input v-model="createForm.password" type="password" id="password"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.password }" placeholder="Password" />
						<div v-if="errors.password" class="text-sm text-red-500 mt-1">
							{{ errors.password }}
						</div>
					</div>
					<div class="col-span-12">

					</div>

					<div class="col-span-12">
						<button type="submit" :disabled="isSubmitting"
							class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
							Add User
						</button>
					</div>

				</div>
			</form>
		</div>

	</AppLayout>
</template>