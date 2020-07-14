<template>
    <div class="become-member profile register">
        <template v-if="!this.game.isMember">
            <div class="title">{{currentGameName}}</div>
            <div class="link-title">{{plannedAt | moment('DD.MM.YYYY аt HH:mm')}} / at {{remain}}</div>
            <div class="row justify-content-center">
                <div class="col-md-4 col-4 justify-content-center d-flex">
                    <profile-item :name="$trans('game.mode')" :value="currentGameTypeName"></profile-item>
                </div>
                <div class="col-md-4 col-4 justify-content-center d-flex">
                    <profile-item :name="$trans('game.map')" :value="game.map_name"></profile-item>
                </div>
                <div class="col-md-4 col-4 justify-content-center d-flex">
                    <profile-item :name="$trans('game.face.title')" :value="faceTypeName"></profile-item>
                </div>
                <div class="col-md-4 col-4 justify-content-center d-flex">
                    <profile-item :name="$trans('game.members')"
                                  :value="game.members_count + '/' + game.max_players"></profile-item>
                </div>
                <div class="col-md-4 col-4 justify-content-center d-flex">
                    <profile-item :name="$trans('game.price')"
                                  :value="game.price + $trans('menu.currency')"></profile-item>
                </div>
                <div class="col-md-4 col-4 justify-content-center d-flex">
                    <profile-item :name="$trans('game.frag_price')" :hint="description"
                                  :value="killReward  + $trans('menu.currency')"></profile-item>
                </div>
            </div>
            <!--<div class="item color-orange" v-if="game.type != 0">-->
            <!--Это командный турнир, вы сможете выбрать команду после оплаты взноса-->
            <!--</div>-->
            <div class="item">
                <div class="text-center justify-content-center d-flex">
                    <animated-button :disabled="!canBuy || loading" @click="enter" class="button-modal btn menu-button">
                        {{$trans('game.become_member')}}
                    </animated-button>
                </div>
            </div>
            <div class="hint" v-if="!canBuy">
                {{$trans('game.become.no_more_available')}}
            </div>
            <div class="hint" v-if="canBuy && (game.price > user.balance)">
                {{$trans('game.become.low_money')}}
            </div>
        </template>
        <div v-else>
            {{$trans('game.become.you_member')}}
        </div>
        <b-modal id="buy-game-paypal-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="free-payment profile register">
                    <div class="title">{{$trans('payment.refill.title')}}</div>
                    <div class="row justify-content-center">
                        <div class="col-md-12">
                            <div class="item">
                                <pay-pall-button
                                        :on-create-order="createOrder"
                                        :on-approve="onApprove"
                                />
                                <div class="please-wait text-center"
                                     v-if="pleaseWait"
                                >You transaction is processing. Please wait...
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </b-modal>
    </div>
</template>

<script>
    import Tabber from "./Tabber";
    import {mapGetters} from "vuex";
    import ProfileItem from "./ProfileItem";
    import Vue from "vue";
    import {ENTER_GAME, GET_GAMES, GET_PAYMENT_URL} from "../store/action-names";
    import {UPDATE_GAME} from "../store/mutation-names";
    import AnimatedButton from "./AnimatedButton";
    import KillRewardMixin from "./KillRewardMixin";
    import PayPallButton from "./PayPallButton";

    export default {
        name: "BecomeMember",
        components: {AnimatedButton, ProfileItem, Tabber},
        mixins: [
            KillRewardMixin
        ],
        data() {
            return {
                pleaseWait: false,
                loading: false,
                // currentTime: Vue.moment(),
                // timerId: 0
            }
        },
        mounted() {
            this.pleaseWait = false;
            // this.timerId = setInterval(() => {
            //     this.currentTime = this.$moment();
            // }, 10000)
        },
        beforeDestroy() {
            // clearInterval(this.timerId);
        },
        computed: {
            ...mapGetters([
                'currentGameName',
                'currentGameTypeName',
                'user',
                'userAuthorized',
                'game',
                'currentRank',
                'currentTime'
            ]),
            isPaypal() {
                return window.activeMerchant == 'PAYPAL';
            },
            description() {
                return this.$trans('game.kill_hint');
            },
            gameName() {

            },
            canBuy() {
                return this.plannedAt.diff(this.currentTime, 'minutes') > 1;
            },
            faceTypeName() {
                if (this.game.face == 0) {
                    return this.$trans('game.face.first')
                } else {
                    return this.$trans('game.face.third')
                }
            },
            plannedAt() {
                return this.$moment(this.game.planned_at + ".000Z");
            },
            remain() {
                return this.$moment.duration(this.plannedAt.diff(this.$moment())).format(this.$trans('game.remain_format'));
            },
        },
        methods: {
            createOrder(data, actions) {
                return this.$store.dispatch(GET_PAYMENT_URL, this.game.id)
                    .then((url) => {
                        this.pleaseWait = true;
                        console.log('ORDER ID', url.id);
                        return actions.order.create({
                            purchase_units: [{
                                amount: {
                                    value: url.amount
                                },
                                custom_id: url.id
                            }]
                        });
                    });
            },
            onApprove(data, actions) {
                return actions.order.capture().then((details) => {
                    console.log('ORDER ID ', data.orderID)
                    return this.$store.dispatch(CHECK_PAYPAL_ORDER, {orderId: data.orderID})
                        .then(data => {
                            this.$bvModal.hide('buy-game-paypal-modal');
                            this.pleaseWait = false;
                            this.enter(true);
                        })
                        .catch(error => {
                        });
                });
            },
            enter(direct = false) {
                if (this.loading) {
                    return;
                }
                if (!this.canBuy) {
                    this.$bvModal.msgBoxOk(this.$trans('game.become.no_more_available'))
                } else {
                    if (this.game.price > this.user.balance) {
                        // Пополняем баланс
                        console.log(GET_PAYMENT_URL, this.game.id);
                        this.$store.dispatch(GET_PAYMENT_URL, this.game.id)
                            .then(url => {
                                console.log(url);
                                document.location.href = url;
                            })
                    } else {
                        // Прямая покупка
                        this.loading = true;
                        this.$store.dispatch(ENTER_GAME, this.game.id)
                            .then(result => {
                                if (result.error == 'to_late') {
                                    this.loading = false;
                                    this.$bvModal.msgBoxOk(this.$trans('game.become.game_started'))
                                }

                                if (result.error == 'full') {
                                    this.loading = false;
                                    this.$bvModal.msgBoxOk(this.$trans('game.become.game_full'))
                                }

                                if (result.error == 'no_money') {
                                    this.loading = false;
                                    this.$bvModal.msgBoxOk(this.$trans('game.become.no_money'))
                                }

                                if (result.error == 'no') {
                                    // this.$store.commit(UPDATE_GAME, result.game);

                                    this.$store.dispatch(GET_GAMES)
                                        .then(() => {
                                            this.loading = false;
                                            this.$bvModal.hide('become-member-modal');

                                            if (this.game.type != 0) {
                                                // Показываем настройки игры
                                                this.$bvModal.show('team-settings-modal');
                                            } else {
                                                this.$bvModal.msgBoxOk(this.$trans('game.become.registration_successful'), {centered: true})
                                            }
                                        });
                                }
                            })
                            .catch(() => {
                                this.loading = false;
                            })
                    }
                }
            }
        },
    }
</script>
