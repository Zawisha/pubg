<template>
    <span class="hint-button" v-click-outside="hideHint">
        <span
                @mouseenter="mouseEnter"
                @mouseleave="mouseLeave"
                @click.prevent.stop="toggleHint"
        >?</span>

        <transition
                appear
                mode="out-in"
                appear-class="animated fadeIn faster"
                enter-active-class="animated fadeIn faster"
                appear-active-class="animated fadeIn faster"
                leave-active-class="animated fadeOut faster"
        >
                <div class="hint-content"
                     @mouseenter.stop.prevent="hintTextHovered = true"
                     @mouseleave.stop.prevent="hintTextHovered = false"
                     v-if="hint"
                     @click.prevent.stop="hint = false"
                >
                    <div v-if="title" class="question__title">{{title}}</div>
                    <div class="question__description" v-html="this.hintText"></div>
                </div>
        </transition>
    </span>
</template>

<script>
    export default {
        name: "HintButton",
        data() {
            return {
                hint: false,
                hintTextHovered: false,
            }
        },
        props: {
            hintText: {
                default: ''
            },
            title: {
                default: ''
            }
        },
        methods: {
            mouseEnter() {
                if (window.innerWidth > 620) {
                    this.hint = true;
                }
            },
            mouseLeave() {
                if (window.innerWidth > 620) {
                    this.hint = false;
                }
            },
            toggleHint() {
                this.hint = !this.hint;
            },
            hideHint() {
                this.hint = false;
            }

        }
    }
</script>

<style scoped>

</style>
