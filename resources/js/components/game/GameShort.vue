<template>
    <!-- <div class="battle-info" :class="{'max-payment':game.use_max_kill, ['mul-' + game.mul]: true}"> -->
    <div class="battle-info" :class="{['mul-' + game.mul]: true}">
        <div class="title">{{game.mul == 1 ? gameNames(gameIndex) : gameNamesMul(gameIndex)}}</div>
        <div class="date" v-if="game.mul > 1">
            {{$trans('game.double_subtitle')}}
            <hint-button :hint-text="$trans('game.double_subtitle_hint')"></hint-button>
        </div>
        <div class="king-help" v-if="isKing">
            <span @click.prevent="showKingHelp">{{$trans('game.king_help')}}</span>
        </div>
        <div class="kill-price"><span class="text">
            <template v-if="(!user || !user.id) && !game.use_max_kill">{{$trans('game.frag.from')}} </template>
                <span class="color-orange">{{killReward}}{{$trans('menu.currency')}}</span> {{$trans('game.frag.frag')}}</span>
            <hint-button :hint-text="description"></hint-button>
        </div>
        <div class="mode">{{$trans('game.mode')}}: <strong>{{gameTypeName}}</strong>
            <template v-if="!isKing">{{$trans('game.map')}}: <strong>{{game.map_name}}</strong></template>
            <span>{{$trans('game.face.title')}}: <strong>{{faceTypeName}}</strong></span>
        </div>
        <div class="date">{{plannedAt | moment('DD.MM.YYYY [at] hh:mm A')}}
            <hint-button :hint-text="$trans('game.time_hint')"></hint-button>
            <back-timer :time="plannedAt" v-if="remainMin > 0"></back-timer>
        </div>
        <div class="competitors" v-if="!isKing">{{$trans('game.members')}}:
            <!-- <fill-progress :now="game.members_count" :total="game.max_players * game.mul"/> -->
            {{ game.members_count + '/' + game.max_players }}
        </div>
        <div class="king-hint" v-if="isKing">
            {{$trans('game.king_hint')}}
        </div>
        <div class="button" v-if="!game.isMember">
            <animated-button
                    class="btn menu-button"
                    v-if="(remainMin > 0) && (game.status != 1)"
                    :disabled="!canBuy || (game.members_count >= game.max_players * game.mul)"
                    @click="becomeMember()">{{$trans('game.become_member')}}&nbsp;<i class="fas fa-play"></i>
            </animated-button>
            <div class="game-running" v-else>
                {{$trans('game.running')}}
            </div>
        </div>
        <template v-if="game.isMember && !isKing">
            <div class="date" v-if="canBuy && game.type != 0">
                <a href="#" @click.prevent="changeSettings">{{$trans('game.settings.title')}}</a>
            </div>
            <div class="date" v-if="canBuy && game.type == 0 && (remainMin > 60)">
                <a href="#" @click.prevent="leaveGame">{{$trans('game.leave_game')}}</a>
            </div>
        </template>
        <div v-if="!game.isMember && !isKing" class="rules"><a href="#" @click.prevent="showHowWork">{{$trans('game.how_it_works')}}</a>
        </div>
        <div class="competitors" v-if="isKing">{{$trans('game.members')}}:
            <!-- <fill-progress :now="game.members_count" :total="game.max_players * game.mul"/> -->
            {{ game.members_count + '/' + game.max_players }}
        </div>
        <div v-if="game.isMember" class="login-info">
            <template v-if="remainMin > 10">
                {{$trans('game.lobby.wait')}}
            </template>
            <template v-else>
                {{$trans('game.lobby.login')}}: {{gameLogin}}
                {{$trans('game.lobby.password')}}: {{gamePassword}}
                <template v-if="game.type !=0">{{$trans('game.lobby.team')}}:
                    {{game.members[0]
                    && game.members[0].pivot
                    && game.members[0].pivot.team
                    ? game.members[0].pivot.team.replace('team ', '')
                    : $trans('game.lobby.soon')}}
                </template>
            </template>
            <template v-if="game.isMember && isKing ">
                <div class="leave-link">Убедитесь что у вас скачены 3 карты: Эрангель, Мирамар, Викенди</div>
                <div class="leave-link" v-if="canBuy && game.type != 0">
                    <a href="#" @click.prevent="changeSettings">{{$trans('game.settings.title')}}</a>
                </div>
                <div class="leave-link" v-if="canBuy && game.type == 0 && (remainMin > 60)">
                    <a href="#" @click.prevent="leaveGame">{{$trans('game.leave_game')}}</a>
                </div>
            </template>
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
        name: "GameShort",
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
                timerId: 0
            }
        },
        mounted() {
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
                    if (this.game.game_code == 0 && !this.user.pubg_id) {
                        this.$bvModal.show('warn-about-fill');
                        return;
                    }

                    if (this.game.game_code == 1 && !this.user.freefire_id) {
                        this.$bvModal.show('warn-about-fill');
                        return;
                    }


                    if (this.game.game_code == 2 && !this.user.cod_id) {
                        this.$bvModal.show('warn-about-fill');
                        return;
                    }

                    this.$store.commit(SET_CURRENT_GAME, this.game);
                    this.$root.becomeMember();
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
