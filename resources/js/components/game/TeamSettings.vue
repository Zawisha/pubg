<template>
    <div class="team-settings become-member profile register">
        <div class="title">{{$trans('game.settings.title')}} {{gameCodeName}}</div>
        <div class="link-title">{{plannedAt | moment('DD.MM.YYYY в HH:mm')}} / {{$trans('game.remain')}} {{remain}}
        </div>
        <div class="link-title">{{$trans('game.mode')}}: <strong>{{currentGameTypeName}}</strong>
            {{$trans('game.map')}}: <strong>{{game.map_name}}</strong>
            {{$trans('game.face.title')}}: <strong>{{faceTypeName}}</strong>
        </div>
        <div class="item">
            <tabber :tabs="teamModes" v-model="selectedTeamMode"></tabber>
        </div>
        <div class="item" v-if="isSingle">
            {{$trans('game.settings.random_team_hint')}}
        </div>
        <div class="item" v-if="!isSingle">
            {{$trans('game.settings.select_team')}}
            <div class="teams" id="style-6">
                <div v-for="(group, groupIndex) in teams"
                     v-if="groupIndex != null"
                     :key="groupIndex"
                >
                    <div class="game-name" v-if="game.mul > 1">{{$trans('game.settings.game', {game: groupIndex+1})}}
                    </div>
                    <div class="team"
                         :class="{active: currentTeam == name && currentGroup == groupIndex}"
                         @click="setTeam(name, groupIndex)"
                         v-for="(cnt, name) in group" :key="name">
                        {{name.replace('team', $trans('game.settings.team_name'))}} ({{cnt}} / {{game.type == 1 ? 2 :
                        4}})
                    </div>
                </div>
            </div>
        </div>
        <div class="item">
            <div class="text-center justify-content-center d-flex">
                <animated-button class="menu-button btn" @click="saveTeams">{{$trans('game.settings.save_teams')}}
                </animated-button>
            </div>
        </div>
        <div class="item leave-game" v-if="remainMin > 60">
            <a href="#" @click.prevent="leaveGame">{{$trans('game.leave_game')}}</a>
        </div>
        <div class="hint" v-if="!isSingle">
            {{$trans('game.settings.choose_team')}}
        </div>
    </div>
</template>

<script>
    import Tabber from "../Tabber";
    import {mapGetters} from "vuex";
    import Vue from 'vue';
    import {GET_GAME_MODE, GET_TEAMS, LEAVE_GAME, SET_GAME_MODE} from "../../store/action-names";
    import {SET_TEAMS, UPDATE_GAME} from "../../store/mutation-names";
    import AnimatedButton from "../AnimatedButton";

    export default {
        name: "TeamSettings",
        components: {AnimatedButton, Tabber},
        data() {
            return {
                currentTeam: '',
                currentGroup: null,
                // currentTime: Vue.moment(),
                timerId: 0,
                selectedTeamMode: 'single',
                teamModes: [
                    {
                        text: _.$trans('game.settings.modes.single'),
                        name: 'single'
                    },
                    {
                        text: _.$trans('game.settings.modes.team'),
                        name: 'team'
                    }
                ],
                selectedHaveTeam: 'old',
                haveTeam: [
                    {
                        text: 'Yes',
                        name: 'old'
                    },
                    {
                        text: 'No',
                        name: 'new'
                    }
                ]
            }
        },
        mounted() {
            // this.timerId = setInterval(() => {
            //     this.currentTime = this.$moment();
            // }, 1000)

            this.loadMode();
        },
        beforeDestroy() {
            // clearInterval(this.timerId);
            // window.Echo.channel('pubgmb_database_App.Game')

        },
        computed: {
            ...mapGetters([
                'currentGameName',
                'currentGameTypeName',
                'user',
                'userAuthorized',
                'game',
                'teams',
                'currentTime'
            ]),
            gameCodeName() {
                if (this.game.game_code == 0) {
                    return 'PUBG';
                }

                if (this.game.game_code == 1) {
                    return 'FREE FIRE';
                }

                if (this.game.game_code == 2) {
                    return 'COD';
                }
            },
            isSingle() {
                return this.selectedTeamMode == 'single';
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
            remainMin() {
                return this.plannedAt.diff(this.currentTime, 'minutes');
            },
            remain() {
                return this.$moment.duration(this.plannedAt.diff(this.currentTime)).format(this.$trans('game.remain_format'));
            },
        },
        methods: {
            loadMode() {
                this.$store.dispatch(GET_GAME_MODE, this.game.id)
                    .then(response => {
                        this.currentTeam = response.team;
                        this.currentGroup = response.gi;
                        if (!response.team) {
                            this.selectedTeamMode = 'single';
                        } else {
                            this.selectedTeamMode = 'team';
                            // this.$store.dispatch(GET_TEAMS, this.game.id);
                        }
                    })
            },
            saveTeams() {
                this.$bvModal.hide('team-settings-modal');
            },
            setTeam(teamName, groupIndex) {
                this.$store.dispatch(SET_GAME_MODE, {
                    gameId: this.game.id,
                    isSingle: this.isSingle ? 1 : 0,
                    teamNum: this.isSingle ? null : teamName.replace('team ', ''),
                    groupIndex
                })
                    .then(result => {
                        // console.log(result);
                        if (!result.error) {
                            this.$store.commit(SET_TEAMS, result.teams)
                            this.currentTeam = teamName;
                            this.currentGroup = groupIndex;
                        } else {
                            this.$bvModal.msgBoxOk(result.error, {centered: true});
                        }
                    })
                //this.currentTeam = name
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

                                this.$bvModal.hide('team-settings-modal');
                            })
                    }
                });
            }
        },
        watch: {
            selectedTeamMode(n, o) {
                if (n != o) {
                    if (this.isSingle) {
                        this.$store.dispatch(SET_GAME_MODE, {
                            gameId: this.game.id,
                            isSingle: 1,
                            teamNum: null
                        })
                            .then(response => {
                                if (response.error) {
                                    this.$bvModal.msgBoxOk(response.error, {centered: true})
                                }
                            })
                    } else {
                        this.$store.dispatch(GET_TEAMS, this.game.id);
                    }
                }
            }
        }
    }
</script>
