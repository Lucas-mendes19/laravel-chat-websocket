<template>
    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Chat</h2>
        </template>

        <div class="container mx-auto py-10 h-[45rem]">
            <div class="flex h-full">

                <!-- Left -->
                <div class="w-1/3 border flex flex-col">
                    <!-- Search -->
                    <div class="py-4 px-3 dark:bg-gray-800 flex justify-between border-b border-grey-lighter">
                        <p class="dark:text-gray-200 text-xl">
                            Rooms
                        </p>

                        <button @click="toggleModal = !toggleModal" class="btn btn-link text-white hover:text-blue-400">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" fill="currentColor"
                                class="bi bi-pencil-square m-auto" viewBox="0 0 16 16">
                                <path
                                    d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z" />
                                <path fill-rule="evenodd"
                                    d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z" />
                            </svg>
                        </button>
                    </div>

                    <!-- Contacts -->
                    <div class="dark:bg-gray-800 shadow flex-1 overflow-auto">
                        <div v-for="room in rooms" :key="room.id" @click="enterTheRoom(room.id)"
                            class="px-2 flex items-center hover:bg-grey-lighter cursor-pointer border-b border-grey-lighter"
                            :class="{ 'bg-indigo-400': (room.id === this.room.id ? true : false) }">

                            <div class="flex-1 py-4">
                                <div class="flex items-bottom justify-between">
                                    <a class="dark:text-gray-200">
                                        {{ room.name }}
                                    </a>
                                    <p class="text-xs dark:text-gray-200">
                                        {{ dateFormat(room.created_at) }}
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
                                        {{ room.name }}
                                    </p>

                                    <Dropdown align="right" width="48">
                                        <template #trigger>
                                            <span class="inline-flex rounded-md">
                                                <button type="button"
                                                    class="inline-flex items-center border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none transition ease-in-out duration-150">

                                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="24"
                                                        height="24" fill="currentColor">
                                                        <path fill-rule="evenodd"
                                                            d="M12 7a2 2 0 1 0-.001-4.001A2 2 0 0 0 12 7zm0 2a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 9zm0 6a2 2 0 1 0-.001 3.999A2 2 0 0 0 12 15z">
                                                        </path>
                                                    </svg>
                                                </button>
                                            </span>
                                        </template>

                                        <template #content>
                                            <DropdownLink @click="leaveTheGroup()" href="#">Leave the room
                                            </DropdownLink>
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
                    <form v-if="room.id" @submit.prevent="sendMessage()"
                        class="dark:bg-gray-800 px-4 py-4 flex items-center border-t border-grey-lighter">
                        <div class="flex-1 mx-4">
                            <input v-model="form.message"
                                class="dark:text-white text-sm rounded-lg block w-full p-2.5 dark:bg-gray-700 border-indigo-600"
                                type="text" />
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
        <ModalCreateRoom :users="this.users" :show="toggleModal" @create="createRoom($event)" @close="toggleModal = false">
        </ModalCreateRoom>
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
            if (this.form.message == null || this.form.message.length === 0) {
                return false;
            }

            axios.post('/message', {
                room_id: this.room.id,
                message: this.form.message
            }).then(res => {
                this.form.message = null;
            });
        },
        listUsers(users = null) {
            if (users === undefined || users === null) {
                return '';
            }

            const listName = users.map(user => user.name);
            listName.unshift('Self');
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
            if (this.room.users.length > 1) {
                data.user = this.$page.props.auth.user.id;
            }

            const id = this.room.id;
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
        createRoom(e) {
            // console.log(e.id);
        },
        dateFormat(date) {
            moment.locale('pt-br');
            return moment(date).fromNow();
        }
    },
    async created() {
        try {
            if (this.rooms[0] !== undefined) {
                this.enterTheRoom(this.rooms[0].id);
            }

            const channel = Echo.private('room-created.' + this.$page.props.auth.user.id);

            channel.subscribed(() => {
                console.log('inscrito 2');
            }).listen('RoomCreatedEvent', (e) => useForm({}).get(route('dashboard')));

            this.scrollToEnd();
        } catch (error) {
            console.log(error);
        }
    }
}
</script>