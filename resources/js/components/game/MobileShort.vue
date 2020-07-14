<template>
    <div>
    <div class="battle-info-mob" @click="becomeMember()">

        <div class="time_tourn">{{game.time_to}}</div>
        <div class="kill-price">
            <template v-if="game.use_max_kil==0">
            <b>  <span class="color-orange kill_rew">{{killReward}}{{$trans('menu.currency')}}</span>
            <span class="for_kill">{{$trans('game.frag.frag')}}</span> </b>
            </template>
            <span v-else>
                 <b>  <span class="color-orange kill_rew">{{killReward}}{{$trans('menu.currency')}}</span>
                     <span class="for_kill">{{$trans('game.frag.top')}}{{game.top_limit}}</span>
                 </b>
            </span>
            <img :src="'/images/mobile/arrow_gr.png'" class="arrow_gr" alt=""/>

        </div>

        <div class="mode_mob">
          <b><span :class="isFreeImg">{{isFree}}{{$trans('menu.currency')}}</span></b>
            <span class="members_count_mob">{{game.members_count}}/{{tot_players}}</span>
            <span class="squad_string">{{gameTypeName}} - {{faceTypeName}} - {{game.map_name}}</span>
        </div>
    </div>

    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import {SET_CURRENT_GAME, UPDATE_GAME} from "../../store/mutation-names";
    import {LEAVE_GAME} from "../../store/action-names";
    import Vue from 'vue';
    import HintButton from "../HintButton";
    import AnimatedButton from "../AnimatedButton";
    import _ from 'lodash';
    import BackTimer from "./BackTimer";
    import FillProgress from "./FillProgress";
    import KillRewardMixin from "../KillRewardMixin";

    const types = {
        0: _.$trans('game.types.solo'),
        1: _.$trans('game.types.duo'),
        2: _.$trans('game.types.squad')
    };

    export default {
        name: "MobileShort",
        components: {FillProgress, BackTimer, AnimatedButton, HintButton},
        mixins: [
            KillRewardMixin
        ],
        props: {
            // gameType: {
            //     default: 'nearest'
            // },
            gameIndex: {
                default: 0
            },
            game: {
                type: Object,
                default: () => ({})
            }
        },
        data() {
            return {
                // currentTime: Vue.moment(),
                timerId: 0,
                tot_players:0,
                isFree:0,
                isFreeImg:'',
            }
        },
        mounted() {
            this.tot_players=this.game.max_players * this.game.mul;
            if((this.game.price==0) || (this.game.price==null))
            {
                this.isFreeImg='free_img'
            }
            else
            {
                this.isFreeImg='not_free_img'
            }
            this.isFree=this.game.price
            // this.timerId = setInterval(() => {
            //     this.currentTime = this.$moment();
            // }, 1000)
        },
        beforeDestroy() {
            // if (this.timerId) {
            //     clearInterval(this.timerId);
            // }
        },
        computed: {
            ...mapGetters([
                'user',
                'userAuthorized',
                'gameNames',
                'gameNamesMul',
                'currentRank',
                'currentTime'
            ]),
            gameLogin() {
                if (this.isKing) {
                    if (this.remainMin > -50) {
                        return this.game.login;
                    } else {
                        let p2 = this.$moment(this.game.planned_at2 + ".000Z").diff(this.currentTime, 'minutes');
                        if (p2 <= 10 && p2 > -50) {
                            return this.game.login2;
                        } else {
                            let p3 = this.$moment(this.game.planned_at3 + ".000Z").diff(this.currentTime, 'minutes');
                            if (p3 <= 10 && p3 > -50) {
                                return this.game.login3;
                            }
                        }
                    }
                }
                if (this.game.mul == 2 && this.game.members[0]) {
                    if (this.game.members[0].pivot.gi == 1) {
                        return this.game.login2;
                    }
                }
                return this.game.login;
            },
            gamePassword() {
                if (this.isKing) {
                    if (this.remainMin > -50) {
                        return this.game.password;
                    } else {
                        let p2 = this.$moment(this.game.planned_at2 + ".000Z").diff(this.currentTime, 'minutes');
                        if (p2 <= 10 && p2 > -50) {
                            return this.game.password2;
                        } else {
                            let p3 = this.$moment(this.game.planned_at3 + ".000Z").diff(this.currentTime, 'minutes');
                            if (p3 <= 10 && p3 > -50) {
                                return this.game.password3;
                            }
                        }
                    }
                }
                if (this.game.mul == 2 && this.game.members[0]) {
                    if (this.game.members[0].pivot.gi == 1) {
                        return this.game.password2;
                    }
                }

                return this.game.password;
            },
            isKing() {
                return this.game.is_king;
            },
            description() {
                return this.$trans('game.kill_hint');
                // return (this.user && this.user.id)
                //     ? this.currentRank.description.split("\n").join('<br />')
                //     : 'Размер выплаты зависит от вашего ранга';
            },
            faceTypeName() {
                if (this.game.face == 0) {
                    return this.$trans('game.face.first')
                } else {
                    return this.$trans('game.face.third')
                }
            },
            canBuy() {
                return (this.remainMin > 0);
            },
            plannedAt() {
                return this.$moment(this.game.planned_at + ".000Z");
            },
            remainMin() {
                return this.plannedAt.diff(this.currentTime, 'minutes');
            },
            remain() {
                return this.$moment.duration(this.plannedAt.diff(this.currentTime)).format(this.$trans('game.remain_format'));
            },
            // game() {
            //     return this.$store.state.games[this.gameType]
            //         ? this.$store.state.games[this.gameType]
            //         : {};
            // },
            gameTypeName() {
                return types[this.game.type];
            },
        },
        methods: {
            showRules() {
                // console.log(this.$root);
                this.$root.showRules()
            },
            showHowWork() {
                this.$root.showHowWork();
            },
            becomeMember() {
                if (this.userAuthorized) {
                    this.$store.commit(SET_CURRENT_GAME, this.game);
                    this.$root.becomeMemberMob();
                } else {
                    this.$root.showLoginWarning();
                }
            },
            changeSettings() {
                this.$store.commit(SET_CURRENT_GAME, this.game);
                this.$bvModal.show('team-settings-modal');
            },
            showKingHelp() {
                this.$bvModal.show('king-help-modal');
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
                                }
                            })
                    }
                });
            }
        }
    }
</script>
