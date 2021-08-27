<template>
    <Link :href="'/'">
        <img class="rounded-md w-24 h-24" src="/images/logo.png" alt="logo">
    </Link>
</template>

<script>
    import { Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Link,
        },
    }
</script>
