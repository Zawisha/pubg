<template>
    <button @click="onClick" :disabled="disabled"
            class="animated-button autoflash"
    >
        <div class="circle" ref="circle">
        </div>
        <div class="caption"
             @mouseenter="calcXY"
             @mouseout="calcXY"
        >
            <slot></slot>
        </div>
    </button>
</template>

<script>
    export default {
        name: "AnimatedButton",
        props: ["disabled", "value"],
        methods: {
            onClick(event) {
                this.$emit('click', event);
            },
            calcXY(event) {
                // console.log(event);
                this.$refs.circle.style.left = event.offsetX + 'px';
                this.$refs.circle.style.top = event.offsetY + 'px';
            }
        }
    }
</script>

<style scoped>

</style>