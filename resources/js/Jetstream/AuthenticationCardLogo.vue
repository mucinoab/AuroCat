<template>
    <Link :href="'/'">
        <img class="rounded-md w-24 h-24" src="https://res.cloudinary.com/ddzj4rgox/image/upload/v1629253683/logoGatonix_av0bsc.png" alt="logo">
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
