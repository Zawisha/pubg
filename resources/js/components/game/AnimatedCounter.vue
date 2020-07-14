<template>
    <div class="animated-counter">
        <animated-digit v-for="(digit, idx) in digitsArray"
                        :key="idx"
                        :value="digit"
        />
    </div>
</template>

<script>
    import AnimatedDigit from "../AnimatedDigit";

    export default {
        name: "AnimatedCounter",
        components: {AnimatedDigit},
        props: {
            value: {
                default: 0
            },
            addSign: {
                default: ''
            },
            digits: {
                default: 'auto'
            }
        },
        computed: {
            digitsArray() {
                let res = ("" + this.value).split("");
                if (this.addSign) {
                    res.push(this.addSign);
                }

                return res;
            }
        },
    }
</script>

<style scoped>

</style>