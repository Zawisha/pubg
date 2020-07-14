<template>
    <div class="become-member profile register">
        <img :src="'/images/mobile/UnitournLabel.png'" class="img_mob_logo" alt=""/>
        <div class="link-title">{{game.time_to}}</div>

        <div class="kill-price">
            <template v-if="game.use_max_kil==0">
                <b>  <span class="color-orange kill_rew for_kill">{{killReward}}{{$trans('menu.currency')}}</span>
                    <span >{{$trans('game.frag.frag')}}</span> </b>
            </template>
            <span v-else>
                 <b>  <span class="color-orange kill_rew for_kill">{{killReward}}{{$trans('menu.currency')}}</span>
                     <span class="for_kill">{{$trans('game.frag.top')}}{{game.top_limit}}</span>
                 </b>
            </span>
        </div>

        <back-timer :time="plannedAt" v-if="remainMin > 0"></back-timer>
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
                <profile-item :name="$trans('game.frag_price')"
                              :value="killReward  + $trans('menu.currency')"></profile-item>
            </div>
        </div>
        <div class="modal-content_mob" v-if="(game.cod_instructions !='') && (game.cod_instructions !=null)">
            <span  class="modal-content_mob_text">{{game.cod_instructions}}</span>
        </div>
        <div v-if="this.game.status==2" class="button_mob_write" @click="show_results"> {{$trans('game.show_results')}}</div>
    <div v-else>
            <div v-if="!this.game.isMember" class="text-center justify-content-center d-flex">
              <div v-if="game.members_count!=game.max_players" class="button_mob_write" @click="enter">
                    {{$trans('game.become_member')}}
              </div>
                <div v-else class="button_mob_write button_hide_tourn">
                    {{$trans('game.become_member_no')}}
                </div>
            </div>
        <div v-else class="rules rules-mob">
            <a href="#" @click.prevent="leaveGame"> {{$trans('game.cancel_tourn')}}</a>
        </div>
    </div>

        <div class="rules rules-mob"><a href="#" @click.prevent="showHowWork">{{$trans('game.how_it_works')}}</a>
        </div>
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
    import BackTimer from "./game/BackTimer";
    import {LEAVE_GAME} from "../store/action-names";
    import {SET_CURRENT_SHOW_RESULTS_IMG} from "../store/action-names";

    export default {
        name: "BecomeMemberMob",
        components: {AnimatedButton, ProfileItem, Tabber, BackTimer},
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
            // if(game.members_count == game.max_players)
            // {
            //
            // }

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
            remainMin() {
                return this.plannedAt.diff(this.currentTime, 'minutes');
            },
        },
        methods: {
            show_results(){

                axios.post('/show_img_results_func',
                    {game: this.game})
                    .then(result => {
                        console.log('result')
                        console.log(result)
                        // this.$store.commit(SET_CURRENT_SHOW_RESULTS_IMG, result)
                        this.$store.commit(SET_CURRENT_SHOW_RESULTS_IMG, result)
                        this.$bvModal.show('show-results-modal');


                    })
                    // .then( result => {
                    //     axios.post('/delete_show_img_results_func',
                    //         {img_path: result.data})
                    //     )
                    // })



            },
            showHowWork() {
                this.$root.showHowWork();
            },
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

            leaveGame() {
                this.$bvModal.msgBoxConfirm(this.$trans('game.leave_dialog.text'), {
                    okTitle: this.$trans('game.leave_dialog.confirm'),
                    cancelTitle: this.$trans('game.leave_dialog.cancel'),
                    centered: true
                }).then((result) => {
                    if (result) {
                        this.$store.dispatch(LEAVE_GAME, this.game.id)
                            .then(result => {
                                if (result.game) {
                                    this.$store.commit(UPDATE_GAME, result.game);
                                    this.game.isMember=(!this.game.isMember);
                                }
                            })
                    }
                    // this.$bvModal.hide('become-member-modal-mob');
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
                        this.$store.dispatch(GET_PAYMENT_URL, this.game.id)
                            .then(url => {
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
                                                this.game.isMember=(!this.game.isMember);
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

<style scoped>

</style>
