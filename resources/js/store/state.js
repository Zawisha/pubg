import moment from 'moment-timezone';

export default {
    user: {
        id: 0
    },
    games: [],
    currentGameCode: 2,
    currentGame: null,
    teams: {},
    notifications: [],
    ranks: [],
    liveStreamUrl: {
        url: '',
        url_freefire: '',
        url_cod: ''
    },
    currentTime: moment(),
    maxKillReward: .75,
    maxKillReward2: .75,
    minKillReward: .5,
    minKillReward2: .5,
    stat: {
        users: 0,
        games: 0,
        total_payed: 0
    },
    current_show_results_img:''
}
