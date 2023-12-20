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
	user: {
		type: Object,
		required: true
	}
})
console.log("Props are:" + props.user);

// Initialize the form with the pet data
const editForm = reactive({
	name: props.user.name,
	last_name: props.user.last_name,
	email: props.user.email,
	password: props.user.password,
})

const editUser = async () => {
  isSubmitting.value = true;

  
  const formData = new FormData();
  Object.keys(editForm).forEach((key) => {
  	formData.append(key, editForm[key]);
	});

	// for (let pair of formData.entries()) {
  	// 	console.log(pair[0] + ', ' + pair[1]);
	// }

  
  const response = await axios.post(`/user/${props.user.id}`, formData)

   toast.success(response.data.message)

  isSubmitting.value = false;
}
</script>

<template>
	<AppLayout title="Edit User">
		<template #header>
			<h2 class="text-lg font-semibold leading-6 text-gray-900">
				Edit User: {{ user.name }}
			</h2>
		</template>

		<div class="max-w-full bg-white p-5 rounded-md">
			<form @submit.prevent="editUser" enctype="multipart/form-data" class="space-y-5">
				<div class="grid grid-cols-12 gap-5">
					<div class="col-span-6">
						<label for="name" class="mb-2 block text-sm font-medium text-gray-700">First Name</label>
						<input v-model="editForm.name" type="text" id="name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.name }" placeholder="First Name" />
						<div v-if="errors.name" class="text-sm text-red-500 mt-1">
							{{ errors.name }}
						</div>
					</div>
					<div class="col-span-6">
						<label for="last_name" class="mb-2 block text-sm font-medium text-gray-700">Last Name</label>
						<input v-model="editForm.last_name" type="text" id="last_name"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.last_name }" placeholder="Last Name" />
						<div v-if="errors.last_name" class="text-sm text-red-500 mt-1">
							{{ errors.last_name }}
						</div>
					</div>
					<div class="col-span-12">
						<label for="email" class="mb-2 block text-sm font-medium text-gray-700">Email</label>
						<input v-model="editForm.email" type="text" id="email"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.email }" placeholder="Email" />
						<div v-if="errors.email" class="text-sm text-red-500 mt-1">
							{{ errors.email }}
						</div>
					</div>
					<!-- <div class="col-span-6">
						<label for="password" class="mb-2 block text-sm font-medium text-gray-700">Password</label>
						<input v-model="editForm.password" type="password" id="password"
							class="block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-700 disabled:cursor-not-allowed disabled:bg-gray-50 disabled:text-gray-500 placeholder:text-sm"
							:class="{ 'border-red-500 focus:border-red-500 focus:ring-red-500': errors.password }" placeholder="Password" />
						<div v-if="errors.password" class="text-sm text-red-500 mt-1">
							{{ errors.password }}
						</div>
					</div> -->
					

					<div class="col-span-12">
						
					</div>

					<div class="col-span-12">
						<button type="submit" :disabled="isSubmitting"
							class="w-full rounded-lg border border-indigo-700 bg-indigo-700 px-8 py-4 text-center text-lg font-medium text-white shadow-sm transition-all hover:border-indigo-800 hover:bg-indigo-800 disabled:cursor-not-allowed disabled:border-indigo-300 disabled:bg-indigo-300">
							Edit User
						</button>
					</div>

				</div>
			</form>
		</div>

		<div class="max-w-full px-2 py-10 sm:px-0">
			
		</div>


	</AppLayout>
</template>