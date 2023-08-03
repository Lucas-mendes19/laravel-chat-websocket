<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Bate-papo</h2>
        </template>

        <div class="py-10">
            <div class="container mx-auto">
                <div class="h-screen">
                    <div class="flex h-3/4">

                        <!-- Left -->
                        <div class="w-1/3 border flex flex-col">
                            <!-- Search -->
                            <div class="py-2 px-2 dark:bg-gray-800">
                                <!-- <input type="text" class="w-full px-2 py-2 text-sm rounded"
                                    placeholder="Search or start new room" /> -->

                                <button @click="toggleModal = !toggleModal"
                                    class="inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 transition ease-in-out duration-150">
                                    Criar Sala
                                </button>
                            </div>

                            <!-- Contacts -->
                            <div class="dark:bg-gray-800 shadow flex-1 overflow-auto">
                                <div v-for="room in rooms" :key="room.id" @click="enterTheRoom(room.id)"
                                    class="px-2 flex items-center hover:bg-grey-lighter cursor-pointer"
                                    :class="{ 'bg-cyan-500': (room.id === this.room.id ? true : false) }">

                                    <div class="flex-1 border-b border-grey-lighter py-4">
                                        <div class="flex items-bottom justify-between">
                                            <a class="dark:text-gray-200">
                                                {{ room.name }}
                                            </a>
                                            <p class="text-xs dark:text-gray-200">
                                                12:45 pm
                                            </p>
                                        </div>
                                        <p class="dark:text-gray-200 mt-1 text-sm">
                                            {{ listUsers(room.users) }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Right -->
                        <div class="w-2/3 border flex flex-col">

                            <!-- Header -->
                            <div v-if="room.id"
                                class="py-2 px-3 dark:bg-gray-800 flex flex-row justify-between items-center border-b border-grey-lighter">
                                <div class="flex items-center w-full">
                                    <div class="ml-4 w-full">
                                        <div class="flex justify-between">
                                            <p class="dark:text-gray-200">
                                                Sala - {{ room.name }}
                                            </p>

                                            <Dropdown align="right" width="48">
                                                <template #trigger>
                                                    <span class="inline-flex rounded-md">
                                                        <button type="button"
                                                            class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">

                                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                                                                width="24" height="24" fill="currentColor">
                                                                <path fill-rule="evenodd"
                                                                    d="M12 7a2 2 0 1 0-.001-4.001A2 2 0 0 0 12 7zm0 2a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 9zm0 6a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 15z">
                                                                </path>
                                                            </svg>
                                                        </button>
                                                    </span>
                                                </template>

                                                <template #content>
                                                    <DropdownLink @click="leaveTheGroup()" href="#">Sair do grupo</DropdownLink>
                                                </template>
                                            </Dropdown>
                                        </div>

                                        <p class="dark:text-gray-200 text-xs mt-1">
                                            {{ listUsers(this.room.users) }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Messages -->
                            <div ref="scrollContainer" class="flex-1 overflow-auto dark:bg-gray-800 shadow">
                                <div v-for="message in room.messages" :key="message.id" class="py-2 px-3">
                                    <div v-if="message.user_id === $page.props.auth.user.id" class="flex justify-end mb-2">
                                        <div class="rounded py-2 px-3" style="background-color: #E2F7CB">
                                            <p class="text-sm mt-1">
                                                {{ message.message }}
                                            </p>
                                            <p class="text-right text-xs text-grey-dark mt-1">
                                                {{ dateFormat(message.created_at) }}
                                            </p>
                                        </div>
                                    </div>

                                    <div v-else class="flex mb-2">
                                        <div class="rounded py-2 px-3" style="background-color: #F2F2F2">
                                            <p class="text-sm text-purple">
                                                {{ message.user.name }}
                                            </p>
                                            <p class="text-sm mt-1">
                                                {{ message.message }}
                                            </p>
                                            <p class="text-right text-xs text-grey-dark mt-1">
                                                {{ dateFormat(message.created_at) }}
                                            </p>
                                        </div>
                                    </div>

                                </div>
                            </div>

                            <!-- Input -->
                            <form v-if="room.id" @submit.prevent="sendMessage()" class="dark:bg-gray-800 px-4 py-4 flex items-center border-t border-grey-lighter">
                                <div class="text-white hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24" height="24">
                                        <path opacity=".45" fill="currentColor"
                                            d="M9.153 11.603c.795 0 1.439-.879 1.439-1.962s-.644-1.962-1.439-1.962-1.439.879-1.439 1.962.644 1.962 1.439 1.962zm-3.204 1.362c-.026-.307-.131 5.218 6.063 5.551 6.066-.25 6.066-5.551 6.066-5.551-6.078 1.416-12.129 0-12.129 0zm11.363 1.108s-.669 1.959-5.051 1.959c-3.505 0-5.388-1.164-5.607-1.959 0 0 5.912 1.055 10.658 0zM11.804 1.011C5.609 1.011.978 6.033.978 12.228s4.826 10.761 11.021 10.761S23.02 18.423 23.02 12.228c.001-6.195-5.021-11.217-11.216-11.217zM12 21.354c-5.273 0-9.381-3.886-9.381-9.159s3.942-9.548 9.215-9.548 9.548 4.275 9.548 9.548c-.001 5.272-4.109 9.159-9.382 9.159zm3.108-9.751c.795 0 1.439-.879 1.439-1.962s-.644-1.962-1.439-1.962-1.439.879-1.439 1.962.644 1.962 1.439 1.962z">
                                        </path>
                                    </svg>
                                </div>

                                <div class="flex-1 mx-4">
                                    <input v-model="form.message" class="w-full border rounded px-2 py-2" type="text" />
                                </div>

                                <button type="submit" class="btn btn-link text-white hover:text-blue-400">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor"
                                        class="h-6 w-6 ml-2 transform rotate-90">
                                        <path opacity=".45"
                                            d="M10.894 2.553a1 1 0 00-1.788 0l-7 14a1 1 0 001.169 1.409l5-1.429A1 1 0 009 15.571V11a1 1 0 112 0v4.571a1 1 0 00.725.962l5 1.428a1 1 0 001.17-1.408l-7-14z">
                                        </path>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <ModalCreateRoom :users="this.users" :show="toggleModal" @close="toggleModal = false"></ModalCreateRoom>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import ModalCreateRoom from '@/Components/ModalCreateRoom.vue';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';
import { useForm } from '@inertiajs/vue3';
import moment from 'moment';

export default {
    props: {
        rooms: Object,
        users: Object,
    },
    components: {
        AuthenticatedLayout,
        ModalCreateRoom,
        Dropdown,
        DropdownLink
    },
    data() {
        return {
            room: {},
            toggleModal: false,
            form: this.$inertia.form({
                message: null
            })
        }
    },
    methods: {
        sendMessage() {
            axios.post('/message', {
                roomId: this.room.id,
                message: this.form.message
            });
        },
        listUsers(users = null) {
            if (users === undefined || users === null) {
                return '';
            }

            const listName = users.map(user => user.name);
            return listName.join(', ');
        },
        scrollToEnd() {
            this.$nextTick(() => {
                const scrollContainer = this.$refs.scrollContainer;
                scrollContainer.scrollTop = scrollContainer.scrollHeight;
            });
        },
        async enterTheRoom(id) {
            const response = await axios.get(`/room/${id}`);
            
            this.room = response.data.data;
            const channel = Echo.private('room.' + this.room.id);

            channel.subscribed(() => {
                console.log('inscrito');
            }).listen('MessageSentEvent', (e) => {
                axios.get(`/room/${this.room.id}`)
                    .then(res => {
                        this.room.messages = res.data.data.messages;
                        this.scrollToEnd();
                    });
            });
        },
        leaveTheGroup() {
            let data = {};
            if (this.room.users.length <= 1) {
                data.user = this.$page.props.auth.user.id;
            }
            
            const id = this.rooms[0].id;
            useForm(data).delete(`/room/${id}`, {
                onSuccess: () => {
                    if (this.rooms[0] !== undefined) {
                        this.enterTheRoom(this.rooms[0].id);
                        return;
                    }

                    this.room = {};
                }
            });
        },
        dateFormat(date) {
            return moment(date).fromNow();
        }
    },
    async created() {
        try {
            if (this.rooms[0] !== undefined) {
                this.enterTheRoom(this.rooms[0].id);
            }

            this.scrollToEnd();
        } catch (error) {
            console.log(error);
        }
    }
}
</script>