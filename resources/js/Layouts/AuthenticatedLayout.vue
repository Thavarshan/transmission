<script setup>
import { ref, nextTick } from 'vue';
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import NavLink from '@/Components/NavLink.vue';
import InputLabel from '@/Components/InputLabel.vue';
import InputError from '@/Components/InputError.vue';
import TextInput from '@/Components/TextInput.vue';
import ResponsiveNavLink from '@/Components/ResponsiveNavLink.vue';
import Modal from '@/Components/Modal.vue';
import { Link, useForm } from '@inertiajs/vue3';
import { PlusCircleIcon } from '@heroicons/vue/24/solid';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import SecondaryButton from '@/Components/SecondaryButton.vue';

const showingNavigationDropdown = ref(false);
const addingNewVehicle = ref(false);
const modelInput = ref(null);

const form = useForm({
    model: '',
    make: '',
    registration: '',
});

const openAddNewVehicle = () => {
    addingNewVehicle.value = true;

    nextTick(() => modelInput.value.focus());
};

const addNewVehicle = () => {
    form.post(route('vehicles.store'), {
        preserveScroll: true,
        onSuccess: () => closeAddNewVehicleModal(),
        onError: () => modelInput.value.focus(),
        onFinish: () => form.reset(),
    });
};

const closeAddNewVehicleModal = () => {
    addingNewVehicle.value = false;

    form.clearErrors();
    form.reset();
};
</script>

<template>
    <div class="pb-12">
        <div class="min-h-screen">
            <nav class="bg-transparent">
                <!-- Primary Navigation Menu -->
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="flex justify-between h-16">
                        <div class="flex">
                            <!-- Logo -->
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('home')">
                                    <ApplicationLogo
                                        class="block h-9 w-auto fill-current text-gray-800"
                                    />
                                </Link>
                            </div>

                            <!-- Navigation Links -->
                            <div class="hidden space-x-8 sm:-my-px sm:ml-10 sm:flex">
                                <!-- <NavLink :href="route('home')" :active="route().current('home')">
                                    Home
                                </NavLink> -->
                            </div>
                        </div>

                        <div class="hidden sm:flex sm:items-center sm:ml-6">
                            <button
                                type="button"
                                @click="openAddNewVehicle"
                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150">
                                <PlusCircleIcon class="-ml-0.5 mr-2 h-4 w-4" aria-hidden="true" />
                                <span>Add Vehicle</span>
                            </button>

                            <!-- Settings Dropdown -->
                            <div class="ml-3 relative">
                                <Dropdown align="right" width="48">
                                    <template #trigger>
                                        <span class="inline-flex rounded-md">
                                            <button
                                                type="button"
                                                class="inline-flex items-center px-3 py-2 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 bg-white hover:text-gray-700 focus:outline-none transition ease-in-out duration-150"
                                            >
                                                {{ $page.props.auth.user.name }}

                                                <svg
                                                    class="ml-2 -mr-0.5 h-4 w-4"
                                                    xmlns="http://www.w3.org/2000/svg"
                                                    viewBox="0 0 20 20"
                                                    fill="currentColor"
                                                >
                                                    <path
                                                        fill-rule="evenodd"
                                                        d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                                                        clip-rule="evenodd"
                                                    />
                                                </svg>
                                            </button>
                                        </span>
                                    </template>

                                    <template #content>
                                        <DropdownLink :href="route('profile.edit')"> Profile </DropdownLink>
                                        <DropdownLink :href="route('logout')" method="post" as="button">
                                            Log Out
                                        </DropdownLink>
                                    </template>
                                </Dropdown>
                            </div>
                        </div>

                        <!-- Add New Vehicle Modal -->
                        <Modal maxWidth="xl" :show="addingNewVehicle" @close="closeAddNewVehicleModal">
                            <div class="p-6">
                                <h2 class="text-lg font-medium text-gray-900">
                                    Add New Vehicle Information
                                </h2>

                                <p class="mt-1 text-sm text-gray-600">
                                    The information provided will be used to create a new vehicle registration and presisted to the database.
                                </p>

                                <div class="mt-6 md:grid md:grid-cols-12 md:gap-6">
                                    <div class="col-span-6">
                                        <InputLabel for="model" value="Model" class="sr-only" />

                                        <TextInput
                                            id="model"
                                            required="true"
                                            ref="modelInput"
                                            v-model="form.model"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="Model"
                                        />

                                        <InputError :message="form.errors.model" class="mt-2" />
                                    </div>

                                    <div class="col-span-6">
                                        <InputLabel for="make" value="Make" class="sr-only" />

                                        <TextInput
                                            id="make"
                                            required="true"
                                            v-model="form.make"
                                            type="text"
                                            class="mt-1 block w-full"
                                            placeholder="Make"
                                        />

                                        <InputError :message="form.errors.make" class="mt-2" />
                                    </div>
                                </div>

                                <div class="mt-4">
                                    <InputLabel for="registration" value="Registration" class="sr-only" />

                                    <TextInput
                                        id="registration"
                                        required="true"
                                        v-model="form.registration"
                                        type="text"
                                        class="mt-1 block w-full"
                                        placeholder="Registration"
                                    />

                                    <InputError :message="form.errors.registration" class="mt-2" />
                                </div>

                                <div class="mt-6 flex justify-end">
                                    <SecondaryButton @click="closeAddNewVehicleModal">Cancel</SecondaryButton>

                                    <PrimaryButton
                                        class="ml-3"
                                        :class="{ 'opacity-25': form.processing }"
                                        :disabled="form.processing"
                                        @click="addNewVehicle"
                                    >
                                        Add
                                    </PrimaryButton>
                                </div>
                            </div>
                        </Modal>

                        <!-- Hamburger -->
                        <div class="-mr-2 flex items-center sm:hidden">
                            <button
                                @click="showingNavigationDropdown = !showingNavigationDropdown"
                                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
                            >
                                <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                    <path
                                        :class="{
                                            hidden: showingNavigationDropdown,
                                            'inline-flex': !showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M4 6h16M4 12h16M4 18h16"
                                    />
                                    <path
                                        :class="{
                                            hidden: !showingNavigationDropdown,
                                            'inline-flex': showingNavigationDropdown,
                                        }"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12"
                                    />
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Responsive Navigation Menu -->
                <div
                    :class="{ block: showingNavigationDropdown, hidden: !showingNavigationDropdown }"
                    class="sm:hidden"
                >
                    <div class="pt-2 pb-3 space-y-1">
                        <!-- <ResponsiveNavLink :href="route('home')" :active="route().current('home')">
                            Home
                        </ResponsiveNavLink> -->
                    </div>

                    <!-- Responsive Settings Options -->
                    <div class="pt-4 pb-1 border-t border-gray-200">
                        <div class="px-4">
                            <div class="font-medium text-base text-gray-800">
                                {{ $page.props.auth.user.name }}
                            </div>
                            <div class="font-medium text-sm text-gray-500">{{ $page.props.auth.user.email }}</div>
                        </div>

                        <div class="mt-3 space-y-1">
                            <ResponsiveNavLink :href="route('profile.edit')"> Profile </ResponsiveNavLink>
                            <ResponsiveNavLink :href="route('logout')" method="post" as="button">
                                Log Out
                            </ResponsiveNavLink>
                        </div>
                    </div>
                </div>
            </nav>

            <!-- Page Content -->
            <main>
                <slot />
            </main>
        </div>
    </div>
</template>
