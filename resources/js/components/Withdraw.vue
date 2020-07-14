<template>
    <div class="withdraw">
        <div class="balance">
            <span>{{$trans('payment.withdraw.balance')}}</span> {{ balance }}{{$trans('menu.currency')}}
        </div>
        <div class="ott-card">
            <div class="payment-systems">
                    <span class="ps-icon ps-paypal" :class="{active: selected == 'paypal'}"
                          @click.prevent="setPS('paypal')"></span>
<!--                <span class="ps-icon ps-visa" :class="{active: selected == 'visa'}"-->
<!--                      @click.prevent="setPS('visa')"></span>-->
<!--                <span class="ps-icon ps-mastercard" :class="{active: selected == 'mastercard'}"-->
<!--                      @click.prevent="setPS('mastercard')"></span>-->
            </div>
            <div class="text-center">
                {{$trans('payment.withdraw.fee')}} {{ paysys[selected].fee }}%<br/>
                {{$trans('payment.withdraw.no_fee_hint')}}
            </div>
            <div class="form-item">
                <label>{{ paysys[selected].text}}</label>
                <input ref="maskSelector" type="text" v-model="changeAccountNumber"/>
            </div>
            <div class="form-item">
                <label>{{$trans('payment.withdraw.amount')}}</label>
                <!--v-bind="money"-->
                <input type="number" v-model="amount" min="0" max="15000"></input>
            </div>
            <div class="withdraw-comment">
                {{ paysys[selected].comment }}
            </div>
            <div class="button">
                <animated-button @click="withdraw" class="btn button-modal menu-button">{{$trans('payment.withdraw.button')}}
                </animated-button>
            </div>
        </div>
    </div>
</template>

<script>
    // import {MESSAGE} from "../../otp/store/mutations/mutations-names";
    // import {FINANCE_WITHDRAW} from "../../otp/vue/back-routes";
    // import {USER_REFRESH} from "../../otp/store/actions/actions-names";
    // import Inputmask from "inputmask/dist/inputmask/inputmask.numeric.extensions";

    import {FINANCE_WITHDRAW} from "../back-routes";
    import AnimatedButton from "./AnimatedButton";

    let valid = require('card-validator');

    const comment = _.$trans('payment.withdraw.comment');

    export default {
        name: "Withdraw",
        components: {AnimatedButton},
        data() {
            return {
                test: '',
                loading: false,
                showPreloader: false,
                accountNumber: '',
                amount: '',
                selected: 'paypal',
                money: {
                    decimal: ',',
                    thousands: ' ',
                    prefix: '$',
                    suffix: '',
                    precision: 0,
                    masked: false
                },
                paysys: {
                    visa: {
                        fee: 3.9,
                        text: _.$trans('payment.withdraw.card_num'),
                        comment: comment
                    },
                    paypal: {
                        fee: 5.9,
                        text: _.$trans('payment.withdraw.email'),
                        comment: _.$trans('payment.withdraw.comment_paypal')
                    },
                    mastercard: {
                        fee: 3.9,
                        text: _.$trans('payment.withdraw.card_num'),
                        comment: comment
                    },
                },
                inputMask: ''
            }
        },
        computed: {
            cRate() {
                return this.$store.state.user.c_rate;
            },
            balance() {
                return this.$store.state.user.balance;
            },
            changeAccountNumber: {
                set: function (val) {
                    if (val) {
                        //запоминаем позицию курсора
                        let startPos = this.$refs.maskSelector.selectionStart;
                        this.accountNumber = val;
                        if (startPos < val.length) {
                            //если редактируется не последнее значение строки, то возвращаем курсор
                            this.$nextTick(() => this.setCursorPosition(this.$refs.maskSelector, startPos));
                        }
                    }
                },
                get: function () {
                    if (this.accountNumber) {
                        let result = this.getFormatedValue(this.accountNumber);
                        this.accountNumber = result;
                        return result;
                    } else {
                        console.warn('accountNumber undefined')
                    }
                }
            }
        },
        mounted() {
            this.setPS(this.selected);
        },
        methods: {
            setCursorPosition(el, pos) {
                el.focus();
                el.setSelectionRange(pos, pos);
            },
            getFormatedValue(value) {
                if (this.selected == 'paypal') {
                    return value;
                }

                let formattedValue = value.replace(/\s/g, '').replace(/\D/g, '');

                switch (this.selected) {
                    case 'visa':
                        formattedValue = this.breakNumber(formattedValue);
                        break;
                    case 'mastercard':
                        formattedValue = this.breakNumber(formattedValue);
                        break;
                }
                return formattedValue;
            },
            breakNumber(n) {
                n += "";
                n = new Array(5 - n.length % 4).join("U") + n;
                return n.replace(/([0-9U]{4})/g, "$1 ").replace(/U/g, "").trim();
            },
            maxWithdraw(maxValue) {
                if (this.balance > maxValue) {
                    return this.$trans('payment.withdraw.max_amount') + maxValue;
                }
            },
            setPS(name) {
                this.selected = name;
            },
            withdraw() {
                if (this.loading) {
                    return;
                }

                this.loading = true;

                let accountNumber = this.accountNumber.trim().split(' ').join('');
                console.log(accountNumber);
                let error = null;
                if (accountNumber == "") {
                    error = this.$trans('payment.withdraw.error_account_num');
                }

                let amount;

                try {
                    amount = parseInt(this.amount);

                    if (!amount || (amount < 5) || (amount > this.balance)) {
                        error = this.$trans('payment.withdraw.error_amount');
                    }

                    if (amount && (amount > 230)) {
                        error = this.$trans('payment.withdraw.error_amount');
                    }
                } catch (err) {
                    error = this.$trans('payment.withdraw.error_amount');
                }

                if ((this.selected === 'visa') || (this.selected === 'mastercard')) {
                    // Доп валидация
                    if (!valid.number(accountNumber).isValid) {
                        error = this.$trans('payment.withdraw.error_card_num');
                    }
                }

                if (error) {
                    this.loading = false;
                    this.$bvModal.msgBoxOk(error, {centered: true});
                } else {
                    axios.post(FINANCE_WITHDRAW,
                        {
                            amount: amount,
                            paysys: this.selected,
                            account: accountNumber
                        })
                        .then((result) => {
                            this.loading = false;
                            if (result.data.error == false) {
                                this.$bvModal.msgBoxOk(this.$trans('payment.withdraw.confirmed'), {centered: true})
                                    .then(() => {
                                        // document.location.reload();
                                    });

                                this.amount = '';
                                this.accountNumber = '';
                            } else {
                                let err = result.data.error;

                                this.$bvModal.msgBoxOk(this.$trans('payment.withdraw.error'), {centered: true})
                            }
                        })
                        .catch(err => {
                            this.loading = false;
                        })
                }
            }
        },
        watch: {
            amount(n) {
                if (n < 0) {
                    this.amount = 0;
                }
            }
        }
    }
</script>