<template>
    <div class="color-white text-center">
        <div v-if="!paypalLoaded">
            {{$trans('common.paypal_wait')}}<br/>
            <a href="#" @click.stop.prevent="showPaypal">{{$trans('common.paypal_wait_click')}}</a>
        </div>
    </div>
</template>

<script>
    export default {
        name: "PayPallButton",
        props: {
            courseId: {
                type: [Number, String],
                default: null
            },
            amount: {
                type: [Number, String],
                default: null
            },
            onApprove: {
                type: Function,
                default: () => {
                }
            },
            onCreateOrder: {
                type: Function,
                default: () => {
                }
            }
        },
        data() {
            return {
                paypalLoaded: true,
                times: 20
            }
        },
        mounted() {
            this.showPaypal();
            setTimeout(this.checkExists, 500);

            setTimeout(() => {
                if (!$('.paypal-buttons').length) {
                    this.showPaypal();
                }
            }, 5000)

            setTimeout(() => {
                if (!$('.paypal-buttons').length) {
                    this.showPaypal();
                }
            }, 7000)

            setTimeout(() => {
                if (!$('.paypal-buttons').length) {
                    this.showPaypal();
                }
            }, 10000)
        },
        methods: {
            checkExists() {
                if ($('.paypal-buttons').length) {
                    this.paypalLoaded = true;
                } else {
                    this.paypalLoaded = false;
                }

                if (this.times-- > 0) {
                    setTimeout(this.checkExists, 500);
                }
            },
            showPaypal() {
                this.$nextTick(() => {
                    console.log('showButtons');
                    paypal.Buttons({
                        createOrder: this.onCreateOrder,
                        onApprove: this.onApprove,
                        onEnter: () => {
                            this.paypalLoaded = true;
                        },
                        style: {
                            layout: 'vertical',
                            color: 'blue',
                            shape: 'rect',
                            label: 'paypal',
                            size: 'medium',
                            height: 55
                        }
                    }).render(this.$el)
                        .then(() => {
                            console.log('RENDERED!');
                            this.paypalLoaded = true;
                        });
                });
            }
        }
    }
</script>

<style scoped>

</style>