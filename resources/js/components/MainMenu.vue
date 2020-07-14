<template>
    <div class="menu">
        <transition
                appear
                mode="out-in"
                appear-class="animated fadeIn faster"
                enter-active-class="animated fadeInRight faster"
                appear-active-class="animated fadeInRight faster"
                leave-active-class="animated fadeOutRight faster"
        >
            <div class="menu-div" v-if="!mobile || expand" v-click-outside="hideMenu">
                <span class="logo" v-if="!mobile || !userAuthorized"></span>
                <div v-if="mobile && userAuthorized" class="avatar-div">
                    <span class="avatar" v-if="user.avatar"
                          :style="'background-image: url('+user.avatar+')'"
                    ></span>
                    <span class="avatar" v-if="!user.avatar"
                    ></span>
                    <span class="balance">{{user.balance}} {{$trans('menu.currency')}}</span>
                </div>
                <a href="#" class="menu-item" v-if="mobile" @click.prevent="showNotifications">{{$trans('menu.notifications')}}</a>

                <a href="#" class="menu-item" @click.prevent="showHowWork">{{$trans('menu.how_its_work')}}</a>
                <span class="menu-item" @mouseenter="liveVisible = true" @mouseleave="liveVisible = false">{{$trans('menu.live')}}
                    <span class="live-menu"
                          :class="{active: liveVisible}"
                    >
                        <!-- <a :href="streamUrlFreefire" target="_blank" class="menu-item lives active"
                           v-if="streamUrlFreefire">FREE FIRE</a>
                        <span class="menu-item lives nonactive" v-else>FREE FIRE</span>
                        <a :href="streamUrlPubg" target="_blank" class="menu-item lives active"
                           v-if="streamUrlPubg">PUBG Mobile</a>
                        <span class="menu-item lives nonactive" v-else>PUBG Mobile</span>
                        <a :href="streamUrlCod" target="_blank" class="menu-item lives active"
                           v-if="streamUrlCod">Call of Duty</a>
                        <span class="menu-item lives nonactive" v-else>Call of Duty</span> -->
                    </span>
                </span>
                <a href="https://t.me/unitourn_bot" class="menu-item telegram"
                   @click.prevent="showTelegram"
                   :class="{active: userAuthorized && user.telegram_id, nonactive: userAuthorized && !user.telegram_id}"
                >{{$trans('menu.bot')}}</a>
                <a href="https://discordapp.com/invite/XHRG5eB" target="_blank" class="menu-item">{{$trans('menu.support')}}</a>
            </div>
        </transition>
        <profile-info ref="profileInfo">
                <span class="profile" id="profile">
                    <animated-button class="menu-button btn"
                                     @click="showLogin">{{$trans('menu.sign_up')}}</animated-button>
                </span>
        </profile-info>
        <div class="menu-lines" @click.prevent="expand=true">
            <span class="line-1"></span>
            <span class="line-2"></span>
            <span class="line-3"></span>
        </div>
    </div>
</template>

<script>
    import ProfileInfo from "./ProfileInfo";
    import {mapGetters} from "vuex";
    import AnimatedButton from "./AnimatedButton";

    export default {
        name: "MainMenu",
        components: {AnimatedButton, ProfileInfo},
        data() {
            return {
                expand: false,
                liveVisible: false
            }
        },
        computed: {
            ...mapGetters(['user', 'userAuthorized']),
            streamUrlFreefire() {
                return this.$store.state.liveStreamUrl.url_freefire;
            },
            streamUrlCod() {
                return this.$store.state.liveStreamUrl.url_cod;
            },
            streamUrlPubg() {
                return this.$store.state.liveStreamUrl.url;
            },
            mobile() {
                return window.innerWidth < 620;
            }
        },
        methods: {
            showNotifications() {
                this.expand = false;
                this.$refs.profileInfo.showNotifications();
            },
            hideMenu() {
                this.expand = false;
            },
            showHowWork() {
                this.hideMenu();
                this.$root.showHowWork();
            },

            showRules() {
                this.hideMenu();
                this.$root.showRules();
            },
            showTelegram() {
                this.hideMenu();
                this.$root.showTelegram();
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