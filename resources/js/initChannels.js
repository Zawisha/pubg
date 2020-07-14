import {
    ADD_NOTIFICATION,
    ECHO_GAME_CHANGED,
    ECHO_GAME_COUNT_CHANGED,
    SET_GAMES,
    SET_LIVE_STREAM_URL, SET_STAT,
    SET_TEAMS, SET_USER
} from "./store/mutation-names";
import {GET_GAMES} from "./store/action-names";

export const initChannels = (store) => {
    window.Echo.channel('App.Game')
        .listen('.game.changed', (e) => {
            store.commit(ECHO_GAME_CHANGED, e);
        })
        .listen('.game.count_changed', (e) => {
            store.commit(ECHO_GAME_COUNT_CHANGED, e);
        })
        .listen('.stream.changed', (e) => {
            store.commit(SET_LIVE_STREAM_URL, JSON.parse(e.url))
        })
        .listen('.games.changed', (e) => {
            // console.log(e);
            if (store.state.user && store.state.user.id) {
                store.dispatch(GET_GAMES)
            } else {
                store.commit(SET_GAMES, e.games);
            }
        })
        .listen('.game.teams_changed', (e) => {
            let game = store.getters.game;
            if (game && game.id && game.id == e.gameId) {
                store.commit(SET_TEAMS, e.teams);
            }
            // console.log(e);
        })
        .listen('.stat.updated', (e) => {
            store.commit(SET_STAT, e.stat);
        });
};

export const initUserChannels = (store) => {
    if (store.state.user)
        window.Echo.private(`App.Models.User.${store.state.user.id}`)
            .listen('.user.changed', (e) => {
                store.commit(SET_USER, e.user);
                // console.log(e.user);
            })
            .notification((n) => {
                if (n.type == 'App\\Notifications\\NewRank') {
                    store.commit(SET_USER, {rank_id: n.rank.id})
                }

                store.commit(ADD_NOTIFICATION, {data: n})
                // console.log(n);
            });
};
