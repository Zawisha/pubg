<template>
    <span class="fill-progress">
        <span class="fill-block"
              :class="{'fill-block_filled': bnum <= toFill}"
              v-for="bnum in totalBlocks"
              :key="bnum"
        ></span>
    </span>
</template>

<script>
    export default {
        name: "FillProgress",
        props: {
            total: {
                default: 100,
            },
            now: {
                default: 0
            },
            totalBlocks: {
                default: 25
            }
        },
        computed: {
            toFill() {
                let result = Math.floor(this.now / this.total * this.totalBlocks);
                if ((result == 0) && this.now > 0) {
                    return 1;
                }

                return result;
            }
        },
    }
</script>

<style scoped>

</style>