"use strict";

Vue.component('members-editor', Vue.extend({
    props: {
        gameId: {
            type: Number,
            default: 0,
        },
        multiple: {
            type: Number,
            default: 1
        },
        index: {
            default: null
        }
    },
    data() {
        return {
            parseResult: {},
            screensStatus: '',
            text: 'dfsdaf',
            game: {},
            showSaved: false,
            showError: false,
            error: '',
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
        reloadGame() {
            this.loading = true;
            axios.post('/missioncontrol/getGameMembers/' + this.gameId, {
                multiple: this.multiple,
                index: this.index
            })
                .then(result => {
                    this.loading = false;
                    this.game = result.data;
                    // console.log(this.game);
                })
        },
        saveMembers() {
            this.loading = true;
            this.showError = false;
            axios.post('/missioncontrol/setGameMembers',
                {game: this.game})
                .then(result => {
                    // console.log(result);
                    this.loading = false;
                    this.showSaved = true;
                    setTimeout(() => {
                        this.showSaved = false;
                    }, 2500)
                })
                .catch(error => {
                    this.loading = false;
                    this.error = error;
                    this.showError = true;
                })
        },
        publishMembers() {
            Admin.Messages.confirm('Подтвердите публикацию', 'Внимание! После публицкации результаты изменить будет невозможно. Продолжить?').then(
                (param) => {
                    if (param.value) {
                        this.loading = true;
                        this.showError = false;
                        axios.post('/missioncontrol/publishGameMembers',
                            {
                                game: this.game,
                                index: this.index
                            })
                            .then(result => {
                                this.loading = false;
                                // console.log(result);
                                this.showPublished = true;
                                this.game = result.data;
                                setTimeout(() => {
                                    this.showPublished = false;
                                }, 2500)
                            })
                            .catch(error => {
                                this.loading = false;
                                this.error = error;
                                this.showError = true;
                            })
                    }
                }
            )
        },
        fillTeams() {
            this.loading = true;
            axios.post('/missioncontrol/fillTeams',
                {
                    game: this.game,
                    index: this.index
                })
                .then(result => {
                    this.loading = false;
                    // console.log(result);
                    this.showTeamed = true;
                    this.game = result.data;
                    setTimeout(() => {
                        this.showTeamed = false;
                    }, 2500)
                })
                .catch(error => {
                    this.loading = false;
                    this.error = error;
                    this.showError = true;
                })
        },
        processParsedData() {
            axios.post('/missioncontrol/screenshot/parse',
                {
                    data: this.parseResult,
                    gameId: this.game.id
                })
                .then(result => {
                    this.$refs.files.disabled = false;
                    console.log(result.data);
                    this.screensStatus = 'Обработка файлов завершена. Найдено ' + result.data.data.found.length + ' участников';
                    result.data.data.found.forEach(user => {
                        let member = this.game.members.find(member => member.id == user.id);
                        this.$set(member.pivot, 'color', 'green');
                        this.$set(member.pivot, 'kills', user.cnt);
                        this.$set(member.pivot, 'visit', true);
                    })

                    this.game.members.forEach(member => {
                        if (member.pivot.color != 'green') {
                            this.$set(member.pivot, 'color', 'red');
                        }
                    })
                })
                .catch(error => {
                    this.loading = false;
                    this.error = error;
                    this.showError = true;
                    this.$refs.files.disabled = false;
                })
        },
        getProgressString(idx) {
            return `[${idx + 1} / ${this.$refs.files.files.length}] `;
        },
        loadFile(idx) {
            const config = {
                onUploadProgress: (progressEvent) => {
                    var percentCompleted = Math.round((progressEvent.loaded * 100) / progressEvent.total)

                    if (percentCompleted > 99) {
                        this.screensStatus = this.getProgressString(idx) + 'Загрузка файла ' + this.$refs.files.files[idx].name + ' завершена. Обработка...';
                    } else {
                        this.screensStatus = this.getProgressString(idx) + ' Загрузка файла ' + this.$refs.files.files[idx].name + ' - ' + percentCompleted + '%';
                    }
                    // console.log()
                },
                headers: {
                    'Content-Type': 'multipart/form-data'
                }
            }

            let data = new FormData()

            data.append('file', this.$refs.files.files[idx])

            this.screensStatus = this.getProgressString(idx) + 'Загрузка файла ' + this.$refs.files.files[idx].name + ' - 0%';

            axios.post('/missioncontrol/screenshot', data, config)
                .then(res => {
                    this.parseResult = {...this.parseResult, ...res.data.result};
                    // console.log(res.data.result);
                    if (idx + 1 < this.$refs.files.files.length) {
                        this.loadFile(idx + 1);
                    } else {
                        this.processParsedData();
                    }
                })
                .catch(error => {
                    this.loading = false;
                    this.error = error;
                    this.showError = true;
                    this.$refs.files.disabled = false;
                })

        },
        loadScreens() {
            if (this.$refs.files.files.length) {
                this.$refs.files.disabled = true;
                this.parseResult = {};
                this.loadFile(0);
            } else {
                this.screensStatus = 'Файлы не выбраны!';
            }
        }
    },
    computed: {
        resultsPublished() {
            if (this.game.mul != 2) {
                return this.game.results_published;
            } else {
                if (this.index == 0) {
                    return this.game.results_published;
                }

                if(this.index == 1){
                    return this.game.results_published2;
                }
            }
        },
        members() {
            return this.game.members ? this.game.members : []
        },
        readOnlyGame() {
            return this.game.status != 2 || !!this.resultsPublished
        },
        totalKills() {
            return this.members.reduce((acc, member) => {
                return acc + parseInt(member.pivot.kills);
            }, 0)
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

                //console.log(parseInt(b.pivot.team.replace('team ', '')));

                return (parseInt(b.pivot.team.replace('team ', '')) > parseInt(a.pivot.team.replace('team ', ''))) ? -1 : 1;
            })
        }
    }
}));
