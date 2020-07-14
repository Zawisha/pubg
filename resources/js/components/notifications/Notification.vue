<template>
    <div class="notification" v-html="notificationText">
    </div>
</template>

<script>
    export default {
        name: "Notification",
        props: ['notification'],
        computed: {
            notificationText() {
                try {
                    return this.notification.data.text.split("\n").join('<br />');
                } catch (err) {
                    return this.notification.data.text;
                }
            }
        }
    }
</script>

<style scoped>

</style>