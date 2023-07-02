<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">Dashboard</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                    <ul v-for="room in rooms" :key="room.id"
                        class="max-w-md space-y-1 text-gray-500 list-none list-inside dark:text-gray-400">
                        <li>
                            <Link :href="route('chat.index', { id: room.id })">
                                {{ room.users[0].name }}
                            </Link>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<script>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { useForm, Head, Link } from '@inertiajs/vue3';

export default {
    props: {
        rooms: Array,
    },
    components: {
        AuthenticatedLayout,
        Link
    },
    data() {
        return {
            form: useForm({
                id: 4
            })
        }
    },
    created() {

    },  
    methods: {

    }
}
</script>