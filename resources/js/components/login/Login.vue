<template>
    <div class="register login row justify-content-center">
        <div class="col-md-6 d-flex flex-column align-items-center">
            <div class="item">
                <label>{{$trans('auth.login.email')}}</label>
                <input type="email" :placeholder="$trans('auth.login.email_placeholder')" v-model="form.email">
            </div>
            <div class="item">
                <label>{{$trans('auth.login.password')}}</label>
                <input type="password" :placeholder="$trans('auth.login.password_placeholder')" v-model="form.password">
            </div>
            <div class="">
                <a href="#" style="opacity: .7;" @click="goRecover">{{$trans('auth.login.password_forgot')}}</a>
            </div>
            <div class="item">
                <animated-button :disabled="$v.$invalid" @click="sendForm" class="btn button-modal menu-button">{{$trans('auth.login.login')}}
                </animated-button>
            </div>
            <div class="validation col-md-12">
                <!--<template v-if="$v.$invalid">-->
                    <!--{{$trans('auth.login.invalid_form')}}-->
                <!--</template>-->
            </div>
        </div>
    </div>
</template>

<script>
    import {required, minLength, between, sameAs, email} from 'vuelidate/lib/validators'
    import {SET_USER} from "../../store/mutation-names";
    import AnimatedButton from "../AnimatedButton";

    export default {
        name: "Login",
        components: {AnimatedButton},
        data() {
            return {
                form: {
                    email: '',
                    password: '',
                }
            }
        },
        validations: {
            form: {
                email: {
                    required,
                    email
                },
                password: {
                    required,
                },
            }
        },
        methods: {
            sendForm() {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                axios.post('/api/login', this.form)
                    .then(response => {
                        this.$store.commit(SET_USER, response.data);
                        this.$emit('close');
                        window.location.href = '/';
                    })
                    .catch(error => {
                        this.$bvModal.msgBoxOk(this.$trans('auth.login.error'), {
                            centered: true
                        });
                    })
            },
            goRecover(){
                this.$bvModal.hide('login-modal');
                this.$bvModal.show('login-recover-modal');

            }
        }
    }
</script>

<style scoped>

</style>