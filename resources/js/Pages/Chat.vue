<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">{{ title }}</h2>
        </template>

        <div class="py-12">
            <div class="container mx-auto">
                <div class="py-6 h-screen">
                    <div class="flex h-full">

                        <!-- Left -->
                        <!-- <div class="w-1/3 border flex flex-col"> -->
                            <!-- Search -->
                            <!-- <div class="py-2 px-2 dark:bg-gray-800">
                                <input type="text" class="w-full px-2 py-2 text-sm rounded"
                                    placeholder="Search or start new chat" />
                            </div> -->

                            <!-- Contacts -->
                            <!-- <div class="dark:bg-gray-800 shadow flex-1 overflow-auto">
                                <div v-for="user in users" :key="user.id"
                                    class="px-2 flex items-center hover:bg-grey-lighter cursor-pointer">
                                    
                                    <div class="flex-1 border-b border-grey-lighter py-4">
                                        <div class="flex items-bottom justify-between">
                                            <p class="dark:text-gray-200">
                                                {{ user.name }}
                                            </p> -->
                                            <!-- <p class="text-xs dark:text-gray-200">
                                                12:45 pm
                                            </p> -->
                                        <!-- </div> -->
                                        <!-- <p class="dark:text-gray-200 mt-1 text-sm">
                                            I'll be back
                                        </p> -->
                                    <!-- </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Right -->
                        <div class="w-2/3 border flex flex-col">

                            <!-- Header -->
                            <div class="py-2 px-3 dark:bg-gray-800 flex flex-row justify-between items-center border-b border-grey-lighter">
                                <div class="flex items-center">
                                    <div class="ml-4">
                                        <p class="dark:text-gray-200">
                                            New Movie! Expendables 4
                                        </p>
                                        <p class="dark:text-gray-200 text-xs mt-1">
                                            Andrés, Tom, Harrison, Arnold, Sylvester
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages -->
                            <div class="flex-1 overflow-auto dark:bg-gray-800 shadow">
                                <div v-for="message in room.messages" :key="message.id" class="py-2 px-3">
                                    <div v-if="message.user_id === $page.props.auth.user.id" class="flex justify-end mb-2">
                                        <div class="rounded py-2 px-3" style="background-color: #E2F7CB">
                                            <p class="text-sm mt-1">
                                                {{ message.message }}
                                            </p>
                                            <p class="text-right text-xs text-grey-dark mt-1">
                                                12:45 pm
                                            </p>
                                        </div>
                                    </div>

                                    <div v-else class="flex mb-2">
                                        <div class="rounded py-2 px-3" style="background-color: #F2F2F2">
                                            <p class="text-sm text-purple">
                                                Tom Cruise
                                            </p>
                                            <p class="text-sm mt-1">
                                                Get Andrés on this movie ASAP!
                                            </p>
                                            <p class="text-right text-xs text-grey-dark mt-1">
                                                12:45 pm
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Input -->
                            <div class="dark:bg-gray-800 px-4 py-4 flex items-center border-t border-grey-lighter">
                                <div class="flex-1 mx-4">
                                    <input v-model="form.message" class="w-full border rounded px-2 py-2" type="text" />
                                    <button @click="sendMessage()" type="submit">Submit</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { router } from '@inertiajs/vue3';

export default {
    props: {
        room: Array
    },
    components: {
        AuthenticatedLayout,
    },
    data() {
        return {
            form: this.$inertia.form({
                message: null
            })
        }
    },
    methods: {
        sendMessage() {
            axios.post('/message', { message: this.form.message })
                .then((res) => {
                    console.log(res);
                });
        },
    },

    created() {
        const channel = Echo.channel('message');

        channel.subscribed(() => {
            console.log('inscrito');
        }).listen('.testando', (e) => {
            console.log(e);
        });;
    },
}
</script>

<style>

</style>