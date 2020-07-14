<template>
    <div class="free-payment profile register">
        <div class="title">{{$trans('payment.refill.title')}}</div>
        <div class="row justify-content-center">
            <div class="col-md-6 d-flex justify-content-center">
                <div class="item">
                    <label>{{$trans('payment.refill.amount')}}</label>
                    $<input type="number" :placeholder="$trans('payment.refill.amount_placeholder')" v-model="amount" min="1" max="100">
                </div>
            </div>
            <div class="col-md-12">
                <div class="item">
                    <animated-button
                        class="button-modal btn menu-button"
                        @click="submitForm"
                    >{{$trans('payment.refill.button')}}
                    </animated-button>
                </div>
            </div>
        </div>
    </div>
</template>
<script>
    import {GET_UN_URL} from "../store/action-names";
    import AnimatedButton from "./AnimatedButton";
    export default {
        name: "FreePayment",
        components: {AnimatedButton},
        data() {
            return {
                amount: 30
            }
        },
        methods: {
            submitForm() {
                if (this.amount > 100) {
                    this.$bvModal.msgBoxOk(this.$trans('payment.refill.max_amount'));
                    return;
                }

                this.$store.dispatch(GET_UN_URL, this.amount)
                    .then(url => {
                        document.location.href = url;
                        // window.open(url, '_blank');
                    })
            }
        }
    }
</script>