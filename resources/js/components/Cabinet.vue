<template>
    <div class="cabinet">
        <b-modal id="login-modal" centered hide-footer>
            <div class="d-block text-center">
                <auth-chooser @close="closeAuth"></auth-chooser>
            </div>
        </b-modal>

        <b-modal id="login-recover-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('auth.restore.title')}}</div>
                    <password-recover-mail></password-recover-mail>
                </div>
            </div>
        </b-modal>

        <b-modal id="profile-modal" centered hide-footer>
            <div class="d-block text-center">
                <profile></profile>
            </div>
        </b-modal>

        <b-modal id="become-member-modal" centered hide-footer>
            <div class="d-block text-center">
                <become-member :type="memberType"></become-member>
            </div>
        </b-modal>

        <b-modal id="become-member-modal-mob" centered hide-footer>
            <div class="d-block text-center">
                <become-member-mob :type="memberType"></become-member-mob>
            </div>
        </b-modal>

        <b-modal id="team-settings-modal" centered hide-footer>
            <div class="d-block text-center">
                <team-settings></team-settings>
            </div>
        </b-modal>

        <b-modal id="rules-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.rules_modal.title')}}</div>
                    <div v-html="getContent('rules')" class="text-left">
                    </div>
                    <div>
                        <animated-button @click="$bvModal.hide('rules-modal')" class="button-modal btn menu-button">
                            {{$trans('cabinet.rules_modal.button')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="king-help-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.king_help_modal.title')}}</div>
                    <div v-html="getContent('king-help')" class="text-left">
                    </div>
                    <div>
                        <animated-button @click="$bvModal.hide('king-help-modal')" class="button-modal btn menu-button">
                            {{$trans('cabinet.king_help_modal.button')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="how-work-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.rules_modal.title')}}</div>
                    <div v-html="getContent('how-its-work')" class="text-left">
                    </div>
                    <div>
                        <animated-button @click="$bvModal.hide('how-work-modal')" class="button-modal btn menu-button">
                            {{$trans('cabinet.rules_modal.button')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="telegram-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member register profile">
                    <div class="title">{{$trans('cabinet.telegram_modal.title')}}</div>
                    <div>
                        <span v-html="$trans('cabinet.telegram_modal.text')"></span>
                        <br/>
                        <small>{{$trans('cabinet.telegram_modal.code')}}</small>
                        <br/>
                        <strong>{{telegramCode}}</strong>
                    </div>
                    <div class="btn-div">
                        <animated-button class="button-modal btn menu-button" @click="connectTelegram">
                            {{$trans('cabinet.telegram_modal.button')}}
                        </animated-button>
                    </div>
                </div>
                <a href="https://t.me/unitourn_bot" target="_blank" ref="linkItem" style="display: none;"></a>
            </div>
        </b-modal>

        <b-modal id="login-warning-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.auth_rq_modal.title')}}</div>
                    <div>
                        {{$trans('cabinet.auth_rq_modal.text')}}
                    </div>
                    <div class="btn-div">
                        <animated-button @click="goToLogin" class="button-modal btn menu-button">
                            {{$trans('cabinet.auth_rq_modal.button')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="free-payment-modal" centered hide-footer>
            <div class="d-block text-center">
                <free-payment></free-payment>
            </div>
        </b-modal>

        <b-modal id="withdraw-modal" centered hide-footer>
            <div class="d-block text-center">
                <withdraw></withdraw>
            </div>
        </b-modal>

        <b-modal id="register-reminder-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.now_modal.title')}}</div>
                    <div>
                        {{$trans('cabinet.now_modal.text')}}
                    </div>
                    <div class="btn-div">
                        <animated-button @click="goToLogin" class="button-modal btn menu-button">
                            {{$trans('cabinet.now_modal.register')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="bonus-reminder-modal" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.bonus_game_modal.title')}}</div>
                    <div>
                        {{$trans('cabinet.bonus_game_modal.text')}}
                    </div>
                    <div class="btn-div">
                        <animated-button @click="closeBonusReminder" class="button-modal btn menu-button">
                            {{$trans('cabinet.bonus_game_modal.register')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="warn-about-fill" centered hide-footer>
            <div class="d-block text-center">
                <div class="become-member profile register">
                    <div class="title">{{$trans('cabinet.fill_data.title')}}</div>
                    <div>
                        {{$trans('cabinet.fill_data.text')}}
                    </div>
                    <div class="btn-div">
                        <animated-button @click="closeFillData" class="button-modal btn menu-button">
                            {{$trans('cabinet.fill_data.button')}}
                        </animated-button>
                    </div>
                </div>
            </div>
        </b-modal>

        <b-modal id="show-results-modal" centered hide-footer>
            <div class="d-block text-center">
                <show-results :type="memberType"></show-results>
            </div>
        </b-modal>

    </div>
</template>

<script>
    import AuthChooser from "./login/AuthChooser";
    import Profile from "./Profile";
    import BecomeMember from "./BecomeMember";
    import BecomeMemberMob from "./BecomeMemberMob";
    import ShowResults from "./game/ShowResults";
    import {mapGetters} from "vuex";
    import TeamSettings from "./game/TeamSettings";
    import FreePayment from "./FreePayment";
    import Withdraw from "./Withdraw";
    import AnimatedButton from "./AnimatedButton";
    import PasswordRecoverMail from "./login/PasswordRecoverMail";

    const codeMap = (code) => {
        const map = [
            8, //0
            5, //1
            4, //2
            0, //3
            9, //4
            2, //5
            3, //6
            7, //7
            1, //8
            6, //9
        ];

        return ('00000' + code)
            .slice(-6)
            .split('')
            .map(char => map[parseInt(char)])
            .join('');
    };

    export default {
        name: "Cabinet",
        components: {
            PasswordRecoverMail,
            AnimatedButton, Withdraw, FreePayment, TeamSettings, BecomeMember, Profile,BecomeMemberMob,ShowResults, AuthChooser
        },
        mounted() {
            let status = this.getParameterByName('status');

            if (status == 'success') {
                this.$bvModal.msgBoxOk(this.$trans('payment.success'),
                    {centered: true});
                history.pushState({}, 'PUBG Battles', '/');
            }

            if (status == 'fail') {
                this.$bvModal.msgBoxOk(this.$trans('payment.fail'),
                    {centered: true});
                history.pushState({}, 'PUBG Battles', '/');
            }

            if (this.getParameterByName('show_telegram')) {
                this.showTelegram();
                history.pushState({}, 'PUBG Battles', '/');
            }

            if (window.location.pathname == '/registration') {
                this.goToLogin();
            }

            if (!this.userAuthorized) {
                // setTimeout(() => {
                //         this.registerReminder();
                //     },
                //     15000
                // );
            }

            if (this.$moment(this.user.created_at + ".000Z").diff(this.currentTime, 'minutes') >= -2) {
                setTimeout(() => {
                        this.bonusReminder();
                    },
                    2000
                );
            }
        },
        data() {
            return {
                memberType: 'nearest'
            }
        },
        computed: {
            ...mapGetters(['user', 'userAuthorized', 'currentTime','img_path_result']),
            telegramCode() {
                return codeMap(this.user.id);
            },
        },
        methods: {
            bonusReminder() {
                this.$bvModal.show('bonus-reminder-modal');
            },
            closeBonusReminder() {
                this.$bvModal.hide('bonus-reminder-modal');
            },
            registerReminder() {
                this.$bvModal.show('register-reminder-modal');
            },
            connectTelegram() {
                this.$refs.linkItem.click();
            },
            getParameterByName(name, url) {
                if (!url) url = window.location.href;
                name = name.replace(/[\[\]]/g, '\\$&');
                var regex = new RegExp('[?&]' + name + '(=([^&#]*)|&|#|$)'),
                    results = regex.exec(url);
                if (!results) return null;
                if (!results[2]) return '';
                return decodeURIComponent(results[2].replace(/\+/g, ' '));
            },
            getContent(name) {
                return window.content[name];
            },
            closeAuth() {
                this.$bvModal.hide('login-modal');
            },
            closeFillData() {
                this.$bvModal.hide('warn-about-fill');
                this.$bvModal.show('profile-modal');
            },
            becomeMemberMob(type) {
                this.memberType = type;
                this.$bvModal.show('become-member-modal-mob');

            },
            becomeMember(type) {
                this.memberType = type;
                this.$bvModal.show('become-member-modal');
                // this.$bvModal.msgBoxOk('Become member ' + type);
            },
            showRules() {
                this.$bvModal.show('rules-modal');
            },
            showHowWork() {
                this.$bvModal.show('how-work-modal');
            },
            showLoginWarning() {
                this.$bvModal.show('login-warning-modal');
            },
            showTelegram() {
                if (this.userAuthorized) {
                    this.$bvModal.show('telegram-modal');
                } else {
                    this.showLoginWarning();
                }
            },
            goToLogin() {
                this.$bvModal.hide('login-warning-modal');
                this.$bvModal.hide('register-reminder-modal');
                if (window.regClosed) {
                    return;
                }
                this.$bvModal.show('login-modal');
            }
        }
    }
</script>
