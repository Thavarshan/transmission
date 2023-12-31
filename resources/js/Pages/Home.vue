<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import TextInput from '@/Components/TextInput.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';
import DangerButton from '@/Components/DangerButton.vue';
import Pagination from '@/Components/Pagination.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import Modal from '@/Components/Modal.vue';
import { Head, router, usePage, useForm } from '@inertiajs/vue3';
import { ref, nextTick } from 'vue';
import moment from 'moment';

const props = defineProps({
    filterables: Object,
    vehicles: Object,
});

const filterables = props.filterables;
let vehicles = props.vehicles;

const queryParams = (element) => {
    let queryString = usePage().url;

    if (queryString.indexOf('?') === -1) {
        return undefined;
    }

    queryString = queryString.substring(queryString.indexOf('?') + 1);

    const params = Object.fromEntries(new URLSearchParams(queryString));

    if (element === undefined) {
        return params;
    }

    const param = params[element];

    if (typeof param === 'string') {
        return param;
    }

    return undefined;
};

const form = {
    model: queryParams('model'),
    make: queryParams('make'),
    registration: queryParams('registration') === undefined ? '' : queryParams('registration'),
    processing: false,
};

const filterSearch = () => {
    form.processing = true;

    const query = {
        model: form.model,
        make: form.make,
        registration: form.registration === '' ? undefined : form.registration,
    };

    if (
        form.model === undefined &&
        form.make === undefined &&
        form.registration === undefined
    ) {
        return;
    }

    const queryString = Object.keys(query)
        .filter((key) => query[key] !== undefined)
        .map((key) => `${key}=${encodeURIComponent(query[key])}`)
        .join('&');

    router.get(`${window.location.origin}/home?${queryString}`);
};

const resetFilter = () => {
    form.model = undefined;
    form.make = undefined;
    form.registration = '';

    router.get(`${window.location.origin}/home`);
};

const modelInput = ref(null);
const updatingVehicle = ref(false);

const updateForm = useForm({
    model: '',
    make: '',
    registration: '',
});

let updatableVehicle = undefined;

const updateVehicle = () => {
    updateForm.put(route('vehicles.update', updatableVehicle.id), {
        preserveScroll: true,
        onSuccess: () => closeUpdateVehicle(),
        onError: () => modelInput.value.focus(),
        onFinish: () => updateForm.reset(),
    });
};

const openUpdateVehicle = (vehicle) => {
    updatingVehicle.value = true;

    updatableVehicle = vehicle;

    updateForm.model = vehicle.model;
    updateForm.make = vehicle.make;
    updateForm.registration = vehicle.registration;

    nextTick(() => modelInput.value.focus());
};

const closeUpdateVehicle = () => {
    updatingVehicle.value = false;

    updateForm.clearErrors();
    updateForm.reset();

    updatableVehicle = undefined;
};

const deleteForm = useForm({});

const deleteVehicle = () => {
    deleteForm.delete(route('vehicles.destroy', updatableVehicle.id), {
        preserveScroll: true,
        onSuccess: () => closeUpdateVehicle(),
        onError: () => modelInput.value.focus(),
        onFinish: () => updateForm.reset(),
    });
};
</script>

<template>
    <Head title="Home" />

    <AuthenticatedLayout>
        <div class="py-6 space-y-6">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="flex items-center space-x-3">
                    <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.model">
                        <option selected :value="undefined">Choose model</option>
                        <option v-for="model in filterables['models']" :value="model">
                            <div :key="model">
                                {{ model }}
                            </div>
                        </option>
                    </select>

                    <select class="border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm" v-model="form.make">
                        <option selected :value="undefined">Choose make</option>
                        <option v-for="make in filterables['makes']" :value="make">
                            <div :key="make">
                                {{ make }}
                            </div>
                        </option>
                    </select>

                    <TextInput
                        id="registration"
                        type="text"
                        class="block w-full"
                        v-model="form.registration"
                        placeholder="Registration"
                        required
                        autofocus
                        autocomplete="registration"
                    />

                    <div class="flex items-center space-x-3">
                        <PrimaryButton @click="filterSearch" class="ml-4" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                            Filter
                        </PrimaryButton>

                        <SecondaryButton @click="resetFilter" class="ml-4">Reset</SecondaryButton>
                    </div>
                </div>
            </div>

            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="px-6">
                        <ul role="list" class="divide-y divide-gray-100">
                            <li v-for="vehicle in vehicles.data" :key="vehicle.id" class="flex justify-between gap-x-6 py-5">
                                <div class="flex gap-x-4">
                                    <img class="h-12 w-12 flex-none rounded-full bg-gray-50" :src="`https://ui-avatars.com/api/?name=${encodeURIComponent(vehicle.registration)}'&color=7F9CF5&background=EBF4FF`" alt="" />

                                    <div class="min-w-0 flex-auto">
                                        <div>
                                            <button @click="openUpdateVehicle(vehicle)" class="uppercase text-sm font-semibold leading-6 text-gray-900 hover:underline">
                                                {{ vehicle.registration }}
                                            </button>
                                        </div>

                                        <div class="flex items-center">
                                            <p class="mt-1 truncate text-xs font-semibold text-indigo-600">{{ vehicle.make }}</p>
                                            <span class="mx-2">&middot;</span>
                                            <p class="mt-1 truncate text-xs text-gray-600">{{ vehicle.model }}</p>
                                        </div>
                                    </div>
                                </div>

                                <div class="hidden sm:flex sm:flex-col sm:items-end">
                                    <p class="text-sm leading-6 text-gray-900">{{ moment(vehicle.created_at).format('ddd MMM, Y') }}</p>

                                    <p class="mt-1 text-xs leading-5 text-gray-500">
                                        Registered <time :datetime="vehicle.created_at">{{ moment(vehicle.created_at).fromNow() }}</time>
                                    </p>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-center">
                <Pagination :links="vehicles.links"></Pagination>
            </div>

            <!-- Update Vehicle Modal -->
            <Modal maxWidth="xl" :show="updatingVehicle" @close="closeUpdateVehicle">
                <div class="p-6">
                    <h2 class="text-lg font-medium text-gray-900">
                        Update Vehicle Information
                    </h2>

                    <p class="mt-1 text-sm text-gray-600">
                        The existing information regarding the vehicle will be updated and saved to the database.
                    </p>

                    <div class="mt-6 md:grid md:grid-cols-12 md:gap-6">
                        <div class="col-span-6">
                            <InputLabel for="model" value="Model" class="sr-only" />

                            <TextInput
                                id="model"
                                required="true"
                                ref="modelInput"
                                v-model="updateForm.model"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Model"
                            />

                            <InputError :message="updateForm.errors.model" class="mt-2" />
                        </div>

                        <div class="col-span-6">
                            <InputLabel for="make" value="Make" class="sr-only" />

                            <TextInput
                                id="make"
                                required="true"
                                v-model="updateForm.make"
                                type="text"
                                class="mt-1 block w-full"
                                placeholder="Make"
                            />

                            <InputError :message="updateForm.errors.make" class="mt-2" />
                        </div>
                    </div>

                    <div class="mt-4">
                        <InputLabel for="registration" value="Registration" class="sr-only" />

                        <TextInput
                            id="registration"
                            required="true"
                            v-model="updateForm.registration"
                            type="text"
                            class="mt-1 block w-full"
                            placeholder="Registration"
                        />

                        <InputError :message="updateForm.errors.registration" class="mt-2" />
                    </div>

                    <div class="mt-6 flex items-center justify-between">
                        <DangerButton @click="deleteVehicle">Delete</DangerButton>

                        <div class="flex items-center justify-end">
                            <SecondaryButton @click="closeUpdateVehicle">Cancel</SecondaryButton>

                            <PrimaryButton
                                class="ml-3"
                                :class="{ 'opacity-25': form.processing }"
                                :disabled="form.processing"
                                @click="updateVehicle"
                            >
                                Save
                            </PrimaryButton>
                        </div>
                    </div>
                </div>
            </Modal>
        </div>
    </AuthenticatedLayout>
</template>
