require('./bootstrap');
require('./fixrem');

import _ from 'lodash';
import Vue from 'vue'

_.$trans = Vue.prototype.$trans = (string, args) => {
    let value = _.get(window.i18n, string, string);

    _.eachRight(args, (paramVal, paramKey) => {
        //value = _.replace(value, `:${paramKey}`, paramVal);
        value = value.replace(new RegExp(`:${paramKey}`, 'g'), paramVal);
    });
    return value;
};

import Vuex, {mapGetters} from 'vuex';
import BootstrapVue from 'bootstrap-vue'
import Vuelidate from 'vuelidate'
import store from './store/store';
import Cabinet from './components/Cabinet';
import ProfileInfo from "./components/ProfileInfo";
import GameShort from "./components/game/GameShort";
import MobileShort from "./components/game/MobileShort";
import Bloggers from "./components/Bloggers";
import moment from "moment-timezone";
import vueMoment from "vue-moment";
import MainMenu from './components/MainMenu';
import GameList from "./components/game/GameList";
import RanksList from "./components/promotion/RanksList";
import GameStat from "./components/game/GameStat";
import vClickOutside from 'v-click-outside'
import VueClipboard from 'vue-clipboard2'
import {initStore} from './initStore';
import {initChannels} from "./initChannels";

// moment.locale('ru');
const momentDurationFormatSetup = require("moment-duration-format");
momentDurationFormatSetup(moment);

Vue.use(vueMoment, {moment: moment});
Vue.use(Vuelidate);
Vue.use(BootstrapVue);
Vue.use(Vuex);
Vue.use(VueClipboard)
Vue.use(vClickOutside);

const GameShortClass = Vue.extend(GameShort);
const GameListClass = Vue.extend(GameList);
const RanksListClass = Vue.extend(RanksList);
const BloggersClass = Vue.extend(Bloggers);
const MainMenuClass = Vue.extend(MainMenu);
const GameStatClass = Vue.extend(GameStat);

initStore(store, Vue.moment);

window.roundO = Vue.prototype.$roundO = function (number) {
    return _.ceil(_.round(number / 5, 4)) * 5
};

const cabinet = new Vue({
    store,
    components: {Cabinet, ProfileInfo},
    el: '#cabinet',
    mounted() {
        this.$moment.locale(this.$trans('cabinet.locale_code'));

        new GameStatClass({
            store,
            parent: this,
        }).$mount('#stat-container');

        new GameListClass({
            store,
            parent: this,
        }).$mount('#games-container');
        // new GameShortClass({
        //     store,
        //     parent: this,
        //     propsData: {
        //         gameType: 'king'
        //     }
        // }).$mount('#king-battle');

        new RanksListClass({
            store,
            parent: this,
        }).$mount('#ranks-list');

        new BloggersClass({
            store,
            parent: this,
        }).$mount('#bloggers');

        new MainMenuClass({
            store,
            parent: this,
        }).$mount('#main-menu')

        initChannels(store);
    },
    computed: {
        games() {
            return this.$store.state.games;
        },
        isMobile() {
            return window.innerWidth < 620;
        }
    },
    methods: {
        becomeMemberMob(type) {
            this.$refs.cabinet.becomeMemberMob(type);
        },
        becomeMember(type) {
            this.$refs.cabinet.becomeMember(type);
        },
        showRules() {
            this.$refs.cabinet.showRules();
        },
        showHowWork() {
            this.$refs.cabinet.showHowWork();
        },
        showLoginWarning() {
            this.$refs.cabinet.showLoginWarning();
        },
        showTelegram() {
            this.$refs.cabinet.showTelegram();
        },
    },
});

Promise.all([
    new Promise(resolve => {
        $(document).ready(resolve);
    }),
    new Promise(resolve => {
        setTimeout(resolve, 2500);
    })
]).then(() => {
    $('.preloader').fadeOut();
});


$(function () {
    $('.circleflash')
        .on('mouseenter', function (e) {
            var parentOffset = $(this).offset(),
                relX = e.pageX - parentOffset.left,
                relY = e.pageY - parentOffset.top;
            $(this).find('span').css({top: relY, left: relX})
        })
        .on('mouseout', function (e) {
            var parentOffset = $(this).offset(),
                relX = e.pageX - parentOffset.left,
                relY = e.pageY - parentOffset.top;
            $(this).find('span').css({top: relY, left: relX})
        });
});
