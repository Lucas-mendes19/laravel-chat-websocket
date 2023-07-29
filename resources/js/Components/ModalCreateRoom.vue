<template>
    <!-- Main modal -->
    <div v-if="show" id="crypto-modal" tabindex="-1" class="fixed top-0 left-0 right-0 z-50 w-full p-4 overflow-x-hidden overflow-y-auto md:inset-0 h-[calc(100%-1rem)] max-h-full flex justify-center">
        <div class="relative w-full max-w-md max-h-full m-auto">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <button @click="closeModal()" type="button" class="absolute top-3 right-2.5 text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ml-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white" data-modal-hide="crypto-modal">
                    <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6"/>
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
                
                <!-- Modal header -->
                <div class="px-6 py-4 border-b rounded-t dark:border-gray-600">
                    <h3 class="text-base font-semibold text-gray-900 lg:text-xl dark:text-white">
                        Criar Sala
                    </h3>
                </div>

                <!-- Modal body -->
                <form @submit.prevent="form.post(route('room.store'))" class="p-6">
                    <p class="text-sm font-normal text-gray-500 dark:text-gray-400">Selecione o(s) usuario(s) a serem inserido na sala.</p>
                    <div class="overflow-y-auto h-80 my-1">
                        <ul class="my-4 space-y-3" v-for="user in users" :key="user.id">
                            <li>
                                <a href="#" class="flex items-center p-3 text-base font-bold text-gray-900 rounded-lg bg-gray-50 hover:bg-gray-100 group hover:shadow dark:bg-gray-600 dark:hover:bg-gray-500 dark:text-white">
                                    <input v-model="form.checkedUsers" :id="user.id" :value="user.id"
                                    type="checkbox" class="w-4 h-4 text-blue-600 bg-gray-100 border-gray-300 rounded focus:ring-blue-500 dark:focus:ring-blue-600 dark:ring-offset-gray-700 dark:focus:ring-offset-gray-700 focus:ring-2 dark:bg-gray-600 dark:border-gray-500">

                                    <label :for="user.id" class="flex-1 ml-3 whitespace-nowrap truncate">
                                        {{ user.name }}
                                    </label>
                                </a>
                            </li>
                        </ul>
                    </div>
                    <div>
                        <input v-model="form.name" type="text" id="success" autocomplete="off"
                            class="bg-green-50 dark:text-white text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700" placeholder="Escreva o nome da sala.">
                        
                        <input type="submit" class="w-full inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150 mt-3" value="Criar">
                    </div>
                </form>
            </div>
        </div>
    </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { defineComponent } from 'vue';

export default defineComponent({
    props: ['show', 'users'],
    name: 'ModalCreateRoom',
    setup() {
        const form = useForm({
            checkedUsers: [],
            name: ''
        });
        
        return { form };
    },
    methods: {
        closeModal() {
            this.$emit('close')
        },
    }
});
</script>
