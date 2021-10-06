<template>
    <Head title="Login AuroCat" />

    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        <Link :href="route('inicio')" class="ml-4 text-sm text-gray-700 dark:text-white underline hover:text-gray-500">
             Inicio
        </Link>
        <Link :href="route('register')" class="ml-4 text-sm text-gray-700 dark:text-white underline hover:text-gray-500">
             Registrarse
        </Link>    
    </div>

    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="mb-4" />

        <div v-if="status" class="mb-4 font-medium text-sm text-green-600">
            {{ status }}
        </div>

        <form @submit.prevent="submit">
            <div>
                <jet-label for="email" value="Email" class="text-cat-dark dark:text-white text-base font-medium"/>
                <jet-input id="email" type="email" class="mt-1 block w-full bg-blue-50 dark:bg-cat-gray" v-model="form.email" required autofocus />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Contraseña" class="text-cat-dark dark:text-white text-base font-medium"/>
                <jet-input id="password" type="password" class="mt-1 block w-full bg-blue-50 dark:bg-cat-gray" v-model="form.password" required autocomplete="current-password" />
            </div>

            <div class="block mt-4">
                <label class="flex items-center">
                    <jet-checkbox name="remember" v-model:checked="form.remember" />
                    <span class="ml-2 text-cat-dark dark:text-white text-base font-medium">Recordar contraseña</span>
                </label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link v-if="canResetPassword" :href="route('password.request')" class="underline text-cat-dark dark:text-white hover:text-gray-900 text-base font-medium">
                    ¿Olvidaste tu contraseña?
                </Link>

                <jet-button class="ml-4 bg-cat-blue text-white text-sm font-bold" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Iniciar sesión
                </jet-button>
            </div>


        </form>
    </jet-authentication-card>
</template>

<script>
    import JetAuthenticationCard from '@/Jetstream/AuthenticationCard.vue'
    import JetAuthenticationCardLogo from '@/Jetstream/AuthenticationCardLogo.vue'
    import JetButton from '@/Jetstream/Button.vue'
    import JetInput from '@/Jetstream/Input.vue'
    import JetCheckbox from '@/Jetstream/Checkbox.vue'
    import JetLabel from '@/Jetstream/Label.vue'
    import JetValidationErrors from '@/Jetstream/ValidationErrors.vue'
    import { Head, Link } from '@inertiajs/inertia-vue3';

    export default {
        components: {
            Head,
            JetAuthenticationCard,
            JetAuthenticationCardLogo,
            JetButton,
            JetInput,
            JetCheckbox,
            JetLabel,
            JetValidationErrors,
            Link,
        },

        props: {
            canResetPassword: Boolean,
            status: String,
            canLogin: Boolean,
            canRegister: Boolean,
        },

        data() {
            return {
                form: this.$inertia.form({
                    email: '',
                    password: '',
                    remember: false
                })
            }
        },

        methods: {
            submit() {
                this.form
                    .transform(data => ({
                        ... data,
                        remember: this.form.remember ? 'on' : ''
                    }))
                    .post(this.route('login'), {
                        onFinish: () => this.form.reset('password'),
                    })
            }
        }
    }
</script>
