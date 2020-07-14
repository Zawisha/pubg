import store from "./store/store";
import {
    SET_CURRENT_TIME,
    SET_GAMES,
    SET_KING_GAME,
    SET_LIVE_STREAM_URL,
    SET_NOTIFICATIONS,
    SET_RANKS, SET_STAT,
    SET_USER
} from "./store/mutation-names";
import {initUserChannels} from "./initChannels";

export const initStore = (store, moment) => {
    let userChennelsReady = false;

    store.subscribe(({type, payload}) => {
        if (type == SET_USER) {
            if (!userChennelsReady) {
                if (payload && payload.id) {
                    userChennelsReady = true;
                    initUserChannels(store);
                }
            }
        }
    });

//
    if (window.currentUser) {
        store.commit(SET_USER, window.currentUser);
    }

    if (window.games) {
        store.commit(SET_GAMES, window.games);
    }

    if (window.kingGame) {
        store.commit(SET_KING_GAME, window.kingGame);
    }

    if (window.ranks) {
        store.commit(SET_RANKS, window.ranks);
    }

    if (window.notifications) {
        store.commit(SET_NOTIFICATIONS, window.notifications);
    }

    if (window.liveStreamUrl !== undefined) {
        store.commit(SET_LIVE_STREAM_URL, JSON.parse(window.liveStreamUrl));
    }

    if (window.stat) {
        store.commit(SET_STAT, window.stat);
    }

    setInterval(() => {
        store.commit(SET_CURRENT_TIME, moment());
    }, 1000);
};