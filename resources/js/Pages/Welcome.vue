<template>
    <Head title="Welcome" />
    <div class="relative flex items-top justify-center min-h-screen bg-gray-100 sm:items-center sm:pt-0 dark:bg-cat-hard-5">
        <div v-if="canLogin" class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
            <Link v-if="$page.props.user" :href="route('messenger')" class="text-sm font-medium text-gray-700 dark:text-cat-light-secundary-3 underline hover:text-gray-500">
                Messenger
            </Link>

            <template v-else>
                <Link :href="route('login')" class="text-sm font-medium text-gray-700 dark:text-cat-light-secundary-3 underline hover:text-gray-500">
                    Iniciar sesión
                </Link>

                <Link v-if="canRegister" :href="route('register')" class="ml-4 text-sm font-medium text-gray-700 dark:text-cat-light-secundary-3 underline hover:text-gray-500">
                    Registrarse
                </Link>
            </template>
        </div>

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">
            

            <div class="flex justify-center pt-8 sm:justify-start sm:pt-0">
                <img class="rounded-md w-24 h-24" src="/images/logo.png" alt="logo">
                <h1 class="pl-2 pt-4 text-cat-logo dark:text-cat-light-secundary-3 text-6xl font-black	">AuroCat</h1>
            </div>

            <div class="mt-8 bg-white dark:bg-cat-hard-1 overflow-hidden shadow sm:rounded-lg">
                <div class="grid grid-cols-1 md:grid-cols-2">
                    <div class="p-6">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
                            <div class="ml-4 text-xl leading-7 font-medium"><a href="https://github.com/mucinoab/InternAuro" class="underline text-black dark:text-cat-light-secundary-3">Documentación</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-700 dark:text-cat-gray-4 text-sm font-normal">
                                La documentación de nuestro proyecto se encuentra en nuestro repositorio de GitHub.
                            </div>
                        </div>
                    </div>


                    <div class="p-6  border-gray-200 dark:border-gray-700">
                        <div class="flex items-center">
                            <svg fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" viewBox="0 0 24 24" class="w-8 h-8 text-gray-500"><path d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                            <div class="ml-4 text-xl leading-7 font-medium"><a href="https://t.me/aeolus_help_bot" class="underline text-black dark:text-cat-light-secundary-3">Chabot Telegram</a></div>
                        </div>

                        <div class="ml-12">
                            <div class="mt-2 text-gray-700 dark:text-cat-gray-4 text-sm font-normal">
                                Comienza a jugar con nuestro chatbot en Telegram.
                            </div>
                        </div>
                    </div>

                </div>
            </div>

             <div class="flex justify-center pt-3"> 
                <img class="rounded-md h-50 w-3/12 border-solid border-8 border-cat-logo dark:border-cat" src="/images/animacionBienvenida.gif" alt="logo">
            </div>

            <div class="flex justify-center mt-4 sm:items-center sm:justify-between">
                <div class="ml-4 text-center text-sm  text-gray-700 dark:text-white sm:text-right sm:ml-0 font-normal">
                    AuroCat v 1.0
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Head,
            Link,
        },

        props: {
            canLogin: Boolean,
            canRegister: Boolean,
            laravelVersion: String,
            phpVersion: String,
        },
        async beforeCreate() {
            fetch("/dark-mode")
            .then(response => response.json())
            .then(json => {
                if(json.darkMode == null) return;
                
                document.getElementById('bighead').className = json.darkMode ? 'dark': '';
            });
        }
    }
</script>
