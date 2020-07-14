<template>
    <div class="register row">
        <div class="col-md-12 text-center mt-4" v-if="joinName">
            {{$trans('auth.registration.referral')}} {{joinName}}
        </div>
        <div class="col-12 align-items-center d-flex flex-column align-items-center">
            <div class="item">
                <label>{{$trans('auth.registration.games_hint')}}</label>
            </div>
        </div>
        <div class="game-list game-list__register">
            <div class="game-tabs game-tabs__register">

                <input type="checkbox" 
                    v-model="form.gameCodes"
                    name="gameCodes"
                    value="1"
                    class="game-tab__checkbox"
                    id="freefire">
                <label for="freefire" class="game-tab game-tab_register game-tab-freefire_register" :class="{ active : form.gameCodes.includes('1') }">
                    <div class="game-title">Free Fire</div>
                </label>
                <input type="checkbox" 
                    v-model="form.gameCodes"
                    name="gameCodes"
                    value="0"
                    class="game-tab__checkbox"
                    id="pubg">
                <label for="pubg" class="game-tab game-tab_register game-tab-pubg_register" :class="{ active : form.gameCodes.includes('0') }">
                    <div class="game-title">PUBG Mobile</div>
                </label>
                <input type="checkbox" 
                    v-model="form.gameCodes"
                    name="gameCodes"
                    value="2"
                    class="game-tab__checkbox"
                    id="cod">
                <label for="cod" class="game-tab game-tab_register game-tab-cod_register" :class="{ active : form.gameCodes.includes('2') }">
                    <div class="game-title">CALL OF DUTY</div>
                </label>
                
            </div>
        </div>

        <div class="col-md-12 col-12 align-items-center d-flex flex-column align-items-center">
            <div class="item item_top">
                <label>{{$trans('auth.registration.email')}}<span>*</span></label>
                <input type="email" :placeholder="$trans('auth.registration.email_placeholder')" v-model="form.email"
                       :class="{error: $v.form.email.$invalid}"
                >
            </div>
        </div>
        <div class="col-md-12 col-12 align-items-center d-flex flex-column align-items-center">
            <div class="item">
                <label>{{$trans('auth.registration.password')}}<span>*</span></label>
                <input type="password" :placeholder="$trans('auth.registration.password_placeholder')"
                       v-model="form.password"
                       :class="{error: $v.form.password.$invalid}"
                >
            </div>
        </div>
        <div class="col-md-12 col-12 align-items-center d-flex flex-column align-items-center">
            <div class="item">
                <label>{{$trans('auth.registration.password_confirmation')}}<span>*</span></label>
                <input type="password" :placeholder="$trans('auth.registration.password_confirmation_placeholder')"
                       v-model="form.password_confirmation"
                       :class="{error: $v.form.password_confirmation.$invalid}"
                >
            </div>
        </div>
        <div class="col-md-12 hint text-center">
            {{$trans('auth.registration.password_length')}}
        </div>
        <!-- <div class="col-md-6 col-6 align-items-center d-flex flex-column align-items-md-start">
            <div class="item">
                <label>{{$trans('auth.registration.facebook_link')}}</label>
                <input type="text" :placeholder="$trans('auth.registration.facebook_link_placeholder')" v-model="form.vk">
            </div>
        </div> -->
        <div class="col-md-12">
            <div class="">
                <div class="item">
                    <b-form-checkbox
                            name="age_approved"
                            v-model="form.ageAppoved"
                            :value="true"
                            :unchecked-value="false"
                    >
                        {{$trans('auth.registration.approve_start')}}
                        <a href="/documents/termsofuse.pdf" target="_blank">{{$trans('auth.registration.approve_terms')}}</a>
                        {{$trans('auth.registration.approve_and')}}
                        <a href="/documents/privacypolicy.pdf" target="_blank">{{$trans('auth.registration.approve_policy')}}</a>
                    </b-form-checkbox>
                </div>
            </div>
        </div>
        <div class="col-md-12">
            <div class="item">
                <animated-button :disabled="$v.$invalid"
                                 class="button-modal btn menu-button"
                                 @click="submitForm"
                >{{$trans('auth.registration.register')}}
                </animated-button>
            </div>
        </div>
        <div class="validation col-md-12">
            <!--<template v-if="$v.$invalid">-->
            <!--{{$trans('auth.registration.fill_all')}}-->
            <!--</template>-->
        </div>
    </div>
</template>

<script>
    import {required, minLength, between, sameAs, email, requiredUnless, or} from 'vuelidate/lib/validators'
    import {mapGetters} from "vuex";
    import {SET_USER} from "../../store/mutation-names";
    import AnimatedButton from "../AnimatedButton";

    export default {
        name: "Register",
        components: {AnimatedButton},
        data() {
            return {
                form: {
                    password: '',
                    password_confirmation: '',
                    email: '',
                    ageAppoved: false,
                    vk: '',
                    gameCodes: [],
                }
            }
        },
        validations: {
            form: {
                password: {
                    required,
                    minLength: minLength(8)
                },
                password_confirmation: {
                    required,
                    sameAsPassword: sameAs('password')
                },
                ageAppoved: {
                    required,
                    isTrue: (value) => value
                },
                email: {
                    required,
                    email
                }
            }
        },
        computed: {
            joinName() {
                return window.joinName;
            },
        },
        mounted() {
            history.pushState({}, 'PUBG BATTLES REGISTRATION', '/registration');
        },
        beforeDestroy() {
            history.pushState({}, 'PUBG BATTLES', '/');
        },
        methods: {
            submitForm() {
                this.$v.$touch();

                if (this.$v.$invalid) {
                    return;
                }

                axios.post('/api/register', {...this.form, joinTo: this.joinName})
                    .then(result => {
                        this.$store.commit(SET_USER, result.data.user);
                        this.$bvModal.hide('login-modal')
                        window.location.href = '/?show_telegram=true';

                        //this.$root.showTelegram()
                        // console.log(result);
                    })
                    .catch(error => {
                        if (error.response.status == 422) {
                            let errors = '';
                            for (let err in error.response.data.errors) {
                                errors += error.response.data.errors[err].join("\n") + "\n";
                            }

                            this.$bvModal.msgBoxOk(this.$trans('auth.registration.form_error') +
                                "\n" + errors, {
                                centered: true
                            });
                        }
                    })
            }
        }
    }
</script>