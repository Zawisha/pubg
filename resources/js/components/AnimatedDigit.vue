<template>
    <div class="animated-digit"
         :class="{animating: animationRunning, half: isHalf}"
    >
        <div class="new-digit-top">
            {{animatingDigit}}
        </div>
        <div class="new-digit-bottom">
            {{animatingDigit}}
        </div>
        <div class="current-digit-top">
            {{curDigit}}
        </div>
        <div class="current-digit-bottom">
            {{curDigit}}
        </div>
    </div>
</template>

<script>
    export default {
        name: "AnimatedDigit",
        props: {
            value: {
                default: 0,
            }
        },
        data() {
            return {
                curDigit: this.value,
                animatingDigit: this.value,
                animationRunning: false,
                isHalf: false,
            }
        },
        methods: {
            runAnimation() {
                if (this.animationRunning) {
                    return;
                }

                this.animationRunning = true;

                this.isHalf = false;

                setTimeout(this.endAnimation, 601);
                setTimeout(()=>{
                    this.isHalf = true;
                }, 300)
            },
            endAnimation() {
                this.animationRunning = false;
                this.curDigit = this.value;
                this.isHalf = false;
            }
        },
        watch: {
            value(n) {
                this.animatingDigit = n;
                this.runAnimation();
            }
        }
    }
</script>

<style scoped>

</style>