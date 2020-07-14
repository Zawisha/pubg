<template>
    <span class="notifications-menu-block" v-click-outside="hideNotifications">
        <div class="icon" @click="expand = !expand">
        </div>
                <transition
                        appear
                        mode="out-in"
                        :appear-class="appearClass"
                        :enter-active-class="enterActiveClass"
                        :appear-active-class="enterActiveClass"
                        :leave-active-class="leaveActiveClass"
                >

        <div class="notifications" v-if="expand">
            <div class="list" v-if="notifications && notifications.length > 0">
                <notification
                        v-for="notification in notifications" :key="notification.id"
                        :notification="notification"
                >
                </notification>
            </div>
            <div v-if="!notifications || notifications.length == 0">
                {{$trans('cabinet.notifications.empty')}}
            </div>
            <div class="slide-up" @click.prevent="expand = false"></div>
        </div>
                </transition>
    </span>
</template>

<script>
    import {mapGetters} from "vuex";
    import Notification from "./Notification";

    export default {
        name: "MenuBlock",
        components: {Notification},
        data() {
            return {
                expand: false
            }
        },
        computed: {
            ...mapGetters(['notifications']),
            mobile() {
                return window.innerWidth < 620;
            },
            appearClass() {
                return !this.mobile ? 'animated fadeIn faster' : 'animated fadeInLeft faster'
            },
            enterActiveClass() {
                return !this.mobile ? 'animated fadeIn faster' : 'animated fadeInLeft faster'
            },
            leaveActiveClass() {
                return !this.mobile ? 'animated fadeOut faster' : 'animated fadeOutLeft faster'
            }
        },
        methods: {
            showNotifications() {
                this.$nextTick(() => {
                    setTimeout(() => {
                        this.expand = true;
                    }, 300)
                });

            },
            hideNotifications() {
                this.expand = false;
            }
        }
    }
</script>

<style scoped>

</style>