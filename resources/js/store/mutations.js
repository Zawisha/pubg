import {
    ADD_NOTIFICATION,
    DUMMY,
    ECHO_GAME_CHANGED,
    ECHO_GAME_COUNT_CHANGED,
    SET_CURRENT_GAME, SET_CURRENT_GAME_CODE, SET_CURRENT_TIME,
    SET_GAMES, SET_KING_GAME, SET_LIVE_STREAM_URL, SET_NOTIFICATIONS, SET_RANKS, SET_STAT, SET_TEAMS,
    SET_USER, SET_USER_AVATAR, UPDATE_GAME,
    SET_CURRENT_SHOW_RESULTS_IMG
} from "./mutation-names";
import Vue from 'vue';

export default {
    [SET_CURRENT_SHOW_RESULTS_IMG](state, img_ref) {
        state.current_show_results_img = img_ref;
    },
    [SET_USER](state, user) {
        state.user = {...state.user, ...user};
    },
    [SET_GAMES](state, games) {
        state.games = [...games];
        // this._vm.$set(state.games, 'nearest', games[0]);
        // this._vm.$set(state.games, 'game2', games[1]);
        // this._vm.$set(state.games, 'game3', games[2]);
        // this._vm.$set(state.games, 'game4', games[3]);
        // this._vm.$set(state.games, 'game5', games[4]);
        // this._vm.$set(state.games, 'game6', games[5]);
        // this._vm.$set(state.games, 'game7', games[6]);
        // this._vm.$set(state.games, 'game8', games[7]);
    },
    [SET_KING_GAME](state, game) {
        // this._vm.$set(state.games, 'king', game);
    },
    [SET_STAT](state, stat) {
        state.stat = stat;
    },
    [SET_RANKS](state, ranks) {
        state.ranks = [...ranks];
        state.maxKillReward = _.maxBy(ranks, 'kill_reward').kill_reward / 100;
        state.maxKillReward2 = _.maxBy(ranks, 'kill_reward2').kill_reward / 100;
        state.minKillReward = _.minBy(ranks, 'kill_reward').kill_reward / 100;
        state.minKillReward2 = _.minBy(ranks, 'kill_reward2').kill_reward / 100;
    },
    [SET_CURRENT_GAME](state, game) {
        state.currentGame = game;
    },
    [SET_CURRENT_GAME_CODE](state, code) {
        state.currentGameCode = code;
    },
    [DUMMY](state, payload) {
    },
    [ECHO_GAME_CHANGED](state, gameEvent) {
        let game = gameEvent.game;
        game.members_count = gameEvent.count;
        if (game.is_king) {
            if (state.games.king && state.games.king.isMember) {
                game.isMember = true;
            }

            Vue.set(state.games, 'king', game);
        } else {
            for (let gameIdx in state.games) {
                if (state.games[gameIdx] && state.games[gameIdx].id == game.id) {
                    Vue.set(state.games, gameIdx, game);
                }
            }
        }
    },
    [ECHO_GAME_COUNT_CHANGED](state, gameEvent) {
        state.games.forEach(game => {
            if (game.id == gameEvent.gameId) {
                game.members_count = gameEvent.count;
            }
        })
        // for(let gameIdx in state.games)
        // for (let gameName in state.games) {
        //     if (state.games[gameName] && state.games[gameName].id == gameEvent.gameId) {
        //         state.games[gameName].members_count = gameEvent.count;
        //     }
        // }
    },
    [UPDATE_GAME](state, game) {
        console.log(game);
        for (let gameName in state.games) {
            if (state.games[gameName] && state.games[gameName].id == game.id) {
                Vue.set(state.games, gameName, game);
            }
        }
    },
    [SET_TEAMS](state, teams) {
        state.teams = {...teams};
    },
    [SET_USER_AVATAR](state, url) {
        state.user.avatar = url;
    },
    [SET_NOTIFICATIONS](state, notifications) {
        state.notifications = [...notifications];
    },
    [ADD_NOTIFICATION](state, notification) {
        state.notifications = [notification, ...state.notifications];
    },
    [SET_LIVE_STREAM_URL](state, url) {
        state.liveStreamUrl = url;
    },
    [SET_CURRENT_TIME](state, time) {
        state.currentTime = time;
    }
}
