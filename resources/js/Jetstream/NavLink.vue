<template>
    <Link :href="href" :class="classes">
        <slot />
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Link,
        },
        props: ['href', 'active'],

        computed: {
            classes() {
                return this.active
                    ? 'inline-flex items-center px-1 pt-1 border-b-2 border-cat-hard-blue-2 text-sm font-medium leading-5 text-gray-200 focus:outline-none focus:border-indigo-700 transition'
                    : 'inline-flex items-center px-1 pt-1 border-transparent text-sm font-medium leading-5 text-gray-200 hover:text-gray-300 hover:border-gray-300 focus:outline-none focus:text-gray-700 focus:border-gray-300 transition'
            }
        }
    }
</script>
