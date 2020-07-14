<template>
    <div class="auth-chooser">
        <template v-if="!user.id">
            <tabber :tabs="loginTabs" v-model="activeTab"></tabber>
            <login @close="$emit('close')" v-if="activeTab == 'login'"></login>
            <register v-if="activeTab == 'register'"></register>
        </template>
        <div v-else class="text-center">
            {{$trans('auth.your_loged_in_as')}} {{user.name}}<br/><br/>
        </div>
    </div>
</template>

<script>
    import Tabber from "../Tabber";
    import Register from "./Register";
    import Login from "./Login";
    import {mapGetters} from "vuex";

    export default {
        name: "AuthChooser",
        components: {Login, Register, Tabber},
        data() {
            return {
                loginTabs: [
                    {
                        text: _.$trans('auth.registration.title'),
                        name: 'register'
                    },
                    {
                        text: _.$trans('auth.login.title'),
                        name: 'login'
                    }
                ],
                activeTab: 'login'
            }
        },
        computed: {
            ...mapGetters(['user'])
            // user() {
            //     console.log(this.$store.state);
            //     return this.$store.state.user;
            // }
        },
    }
</script>

<style scoped>

</style>