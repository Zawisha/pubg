"use strict";

Vue.component('members-editor', Vue.extend({
    props: {
        gameId: {
            type: Number,
            default: 0,
        }
    },
    data() {
        return {
            text: 'dfsdaf',
            game: {},
            showSaved: false,
            showPublished: false,
            loading: false,
            showTeamed: false,
        }
    },
    created() {
        // console.log('ME Created');
        this.reloadGame();
    },
    mounted() {
        // console.log('ME MOUNTED!');
    },
    methods: {
        updateKills(pivot) {
            pivot.visit = true;
            pivot.kills = 0;
            if(pivot.kills1){
                pivot.kills += parseInt(pivot.kills1);
            }
            if(pivot.kills2){
                pivot.kills += parseInt(pivot.kills2);
            }
            if(pivot.kills3){
                pivot.kills += parseInt(pivot.kills3);
            }
        },
        reloadGame() {
            this.loading = true;
            axios.post('/missioncontrol/getGameMembers/' + this.gameId, {multiple: 1, index: 0})
                .then(result => {
                    this.loading = false;
                    this.game = result.data;
                    console.log(this.game);
                })
        },
        saveMembers() {
            this.loading = true;
            axios.post('/missioncontrol/setGameMembers',
                {game: this.game})
                .then(result => {
                    console.log(result);
                    this.loading = false;
                    this.showSaved = true;
                    setTimeout(() => {
                        this.showSaved = false;
                    }, 2500)
                })
        },
        publishMembers() {
            this.loading = true;
            axios.post('/missioncontrol/publishGameMembers',
                {game: this.game})
                .then(result => {
                    this.loading = false;
                    console.log(result);
                    this.showPublished = true;
                    this.game = result.data;
                    setTimeout(() => {
                        this.showPublished = false;
                    }, 2500)
                })
        },
        fillTeams() {
            this.loading = true;
            axios.post('/missioncontrol/fillTeams',
                {game: this.game})
                .then(result => {
                    this.loading = false;
                    console.log(result);
                    this.showTeamed = true;
                    this.game = result.data;
                    setTimeout(() => {
                        this.showTeamed = false;
                    }, 2500)
                })
        }
    },
    computed: {
        members() {
            return this.game.members ? this.game.members : []
        },
        readOnlyGame() {
            return this.game.status != 2 || !!this.game.results_published
        },
        membersOrdered() {
            if (!this.game.members) {
                return this.game.members;
            }

            return this.game.members.sort((a, b) => {
                if (a.pivot.team == b.pivot.team) {
                    return 0;
                }

                if (a.pivot.team && !b.pivot.team) {
                    return -1;
                }

                if (b.pivot.team && !a.pivot.team) {
                    return 1;
                }

                console.log(parseInt(b.pivot.team.replace('team ', '')));

                return (parseInt(b.pivot.team.replace('team ', '')) > parseInt(a.pivot.team.replace('team ', ''))) ? -1 : 1;
            })
        }
    }
}));
