<template>
    <span class="profile-info">
        <template v-if="user.id">
            <menu-block ref="menuBlock"></menu-block>
            <template v-if="!mobile">
                <span class="avatar" v-if="user.avatar"
                      :style="'background-image: url('+user.avatar+')'"
                ></span>
                <span class="avatar" v-if="!user.avatar"
                ></span>
                <span class="balance">{{user.balance}} $<!--@TODO: CURRENCY RUPI--></span>
            </template>
            <animated-button class="menu-button btn" @click="clickProfile">{{$trans('profile.title')}}</animated-button>
        </template>
        <template v-else>
            <animated-button class="menu-button btn"
                             @click="showLogin">{{$trans('menu.sign_up')}}</animated-button>
        </template>
    </span>
</template>

<script>
    import {mapGetters} from "vuex";
    import MenuBlock from "./notifications/MenuBlock";
    import AnimatedButton from "./AnimatedButton";

    export default {
        name: "ProfileInfo",
        components: {AnimatedButton, MenuBlock},
        mounted() {

        },
        computed: {
            ...mapGetters(['user']),
            mobile() {
                return window.innerWidth < 620;
            }
        },
        methods: {
            showNotifications() {
                this.$refs.menuBlock.showNotifications();
            },
            clickProfile() {
                this.$bvModal.show('profile-modal')
            },
            showLogin() {
                this.$bvModal.hide('login-warning-modal');
                this.$bvModal.hide('register-reminder-modal');
                if (window.regClosed) {
                    return;
                }
                this.$bvModal.show('login-modal');
            }
        }
    }
</script>

<style scoped>

</style>