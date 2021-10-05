<template>
    <Head title="Crea tu cuenta de agente" />

    <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        <Link :href="route('inicio')" class="ml-4 text-sm text-gray-700 dark:text-white underline hover:text-gray-500">
             Regresar al inicio
        </Link>   
    </div>

    <jet-authentication-card>
        <template #logo>
            <jet-authentication-card-logo />
        </template>

        <jet-validation-errors class="mb-4" />

        <form @submit.prevent="submit">
            <div>
                <jet-label for="name" value="Nombre" class="text-cat-dark dark:text-white text-lg font-medium"/>
                <jet-input id="name" type="text" class="mt-1 block w-full bg-blue-50 dark:bg-cat-gray" v-model="form.name" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <jet-label for="email" value="Email" class="text-cat-dark dark:text-white text-lg font-medium"/>
                <jet-input id="email" type="email" class="mt-1 block w-full bg-blue-50 dark:bg-cat-gray" v-model="form.email" required />
            </div>

            <div class="mt-4">
                <jet-label for="password" value="Contraseña" class="text-cat-dark dark:text-white text-lg font-medium"/>
                <jet-input id="password" type="password" class="mt-1 block w-full bg-blue-50 dark:bg-cat-gray" v-model="form.password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <jet-label for="password_confirmation" value="Confirmar contraseña" class="text-cat-dark dark:text-white text-lg font-medium"/>
                <jet-input id="password_confirmation" type="password" class="mt-1 block w-full bg-blue-50 dark:bg-cat-gray" v-model="form.password_confirmation" required autocomplete="new-password" />
            </div>

            <div class="mt-4" v-if="$page.props.jetstream.hasTermsAndPrivacyPolicyFeature">
                <jet-label for="terms">
                    <div class="flex items-center">
                        <jet-checkbox name="terms" id="terms" v-model:checked="form.terms" />

                        <div class="ml-2">
                            I agree to the <a target="_blank" :href="route('terms.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Terms of Service</a> and <a target="_blank" :href="route('policy.show')" class="underline text-sm text-gray-600 hover:text-gray-900">Privacy Policy</a>
                        </div>
                    </div>
                </jet-label>
            </div>

            <div class="flex items-center justify-end mt-4">
                <Link :href="route('login')" class="underline text-white hover:text-gray-900 text-lg font-medium">
                    ¿Ya está registrado?
                </Link>

                <jet-button class="ml-4 bg-cat-blue" :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                    Registrar
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

        data() {
            return {
                form: this.$inertia.form({
                    name: '',
                    email: '',
                    password: '',
                    password_confirmation: '',
                    terms: false,
                })
            }
        },

        methods: {
            submit() {
                this.form.post(this.route('register'), {
                    onFinish: () => this.form.reset('password', 'password_confirmation'),
                })
            }
        }
    }
</script>
