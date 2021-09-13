<template>
    <button
        class=" flex justify-center items-center w-full p-2 m-2 rounded-lg bg-gray-600 bg-opacity-10 focus:outline-none focus:ring">
        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"> 
        <path stroke-linecap="round" 
            stroke-linejoin="round" 
            stroke-width="2" 
            :d="category.d" />
        </svg>
    <p class="text-xs font-semibold">{{category.name}}</p>
    </button>
</template>
<script>
export default {
    props:{
        category:{}
    }
    
}
</script>