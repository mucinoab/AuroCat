<template>
    <label class="block font-medium text-sm text-white">
        <span v-if="value">{{ value }}</span>
        <span v-else><slot></slot></span>
    </label>
</template>

<script>
    export default {
        props: ['value']
    }
</script>
