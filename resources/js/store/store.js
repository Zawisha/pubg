import Vue from "vue";
import Vuex from 'vuex';
import state from './state';
import mutations from './mutations';
import actions from './actions';
import _ from 'lodash';

_.$trans = (string, args) => {
    let value = _.get(window.i18n, string, string);

    _.eachRight(args, (paramVal, paramKey) => {
        //value = _.replace(value, `:${paramKey}`, paramVal);
        value = value.replace(new RegExp(`:${paramKey}`, 'g'), paramVal);
    });
    return value;
};

Vue.use(Vuex);

const types = {
    0: _.$trans('game.types.solo'),
    1: _.$trans('game.types.duo'),
    2: _.$trans('game.types.squad')
};

export default new Vuex.Store({
    state,
    mutations,
    actions,
    getters: {
        img_path_result: (state, getters) => {
            return state.current_show_results_img;
        },
        user: (state, getters) => {
            return state.user;
        },
        userAuthorized: (state, getters) => {
            return state.user && state.user.id;
        },
        currentGame: (state, getters) => {
            return state.currentGame;
        },
        currentGameName: (state, getters) => {
            const idx = getters.selectedGames.findIndex(game => game.id == state.currentGame.id);
            if (state.currentGame.mul == 1) {
                return getters.gameNames(idx);
            }else{
                return getters.gameNamesMul(idx);
            }
            //return 'currentGameName not implemented'; //names[state.currentGame];
        },
        gameNames: (state, getters) => {
            return (index) => index
                ? _.$trans('game.names.game', {index})
                : _.$trans('game.names.nearest');
        },
        gameNamesMul: (state, getters) => {
            return (index) => index
                ? _.$trans('game.names_m.game', {index})
                : _.$trans('game.names_m.nearest');
        },
        gameTypes: (state, getters) => {
            return types;
        },
        currentGameTypeName: (state, getters) => {
            return types[state.currentGame.type];
        },
        selectedGames: (state, getters) => {
            return state.games.filter(game => game.game_code == state.currentGameCode);
        },
        game: (state, getters) => {
            return state.currentGame;
        },
        teams: (state, getters) => {
            return state.teams;
        },
        notifications: (state, getters) => {
            return state.notifications;
        },
        currentRank: (state, getter) => {
            if (!state.user) {
                return {}
            } else {
                return state.ranks.find(rank => rank.id == state.user.rank_id);
            }
        },
        currentTime: (state, getter) => {
            return state.currentTime;
        }
    }
});
