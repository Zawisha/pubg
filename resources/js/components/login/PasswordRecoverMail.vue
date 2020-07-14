<template>
    <div class="register login row justify-content-center">
        <div class="col-md-6 d-flex flex-column align-items-center">
            <div class="item">
                <label>{{$trans('auth.restore.email')}}</label>
                <input type="email" :placeholder="$trans('auth.restore.email_placeholder')" v-model="form.email">
            </div>
            <div class="item">
                <animated-button :disabled="$v.$invalid" @click="sendForm" class="btn button-modal menu-button">
                    {{$trans('auth.restore.restore')}}
                </animated-button>
            </div>
        </div>
    </div>
</template>

<script>
    import {required, minLength, between, sameAs, email} from 'vuelidate/lib/validators'
    import {SET_USER} from "../../store/mutation-names";
    import AnimatedButton from "../AnimatedButton";

    export default {
        name: "PasswordRecoverMail",
        components: {AnimatedButton},
        data() {
            return {
                form: {
                    email: '',
                }
            }
        },
        validations: {
            form: {
                email: {
                    required,
                    email
                },
            }
        },
        methods: {
            sendForm() {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                axios.post('/password/email', this.form)
                    .then(response => {
                        console.log(response);
                        if(response.data.send){
                            this.$bvModal.msgBoxOk(response.data.text, {
                                centered: true
                            });
                        }else{
                            this.$bvModal.msgBoxOk(response.data.text, {
                                centered: true
                            });
                        }
                        // window.location.href = '/';
                    })
                    .catch(error => {
                        this.$bvModal.msgBoxOk(this.$trans('auth.restore.error'), {
                            centered: true
                        });
                    })
            }
        }
    }
</script>

<style scoped>

</style>