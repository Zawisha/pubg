<template>
    <div class="profile" ref="profile">
        <div class="title">{{$trans('profile.title')}}
            <a href="/logout" class="btn-logout"></a>
        </div>
        <div class="avatar">
            <span @click.prevent="show=true"
                  :style="user.avatar?('background-image:url('+user.avatar+');'):''"
            ><div class="btn btn-edit"></div></span>
        </div>
        <div class="link-title">{{$trans('profile.reflink')}}</div>
        <div class="link">
            <span>{{reflink}}</span><br/>
            <button class="btn btn-link"
                    @click="copyToClipboard"
            >{{$trans('profile.copy')}}
            </button>
        </div>
        <div class="link-refs">{{$trans('profile.partners', )}}{{user.subscribers_count}}</div>
        <div class="row justify-content-center">
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.nik')"
                              :editable="true"
                              :value="user.name"
                              @input="setNik($event, 'pubg')"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.id')"
                              :editable="true"
                              :value="user.pubg_id"
                              input-type="number"
                              @input="setId($event, 'pubg')"></profile-item>
            </div>

            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.nik_cod')"
                              :editable="true"
                              :value="user.name_cod"
                              @input="setNik($event, 'cod')"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.id_cod')"
                              :editable="true"
                              :value="user.cod_id"
                              input-type="number"
                              @input="setId($event, 'cod')"></profile-item>
            </div>

            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.nik_freefire')"
                              :editable="true"
                              :value="user.name_freefire"
                              @input="setNik($event, 'freefire')"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.id_freefire')"
                              :editable="true"
                              :value="user.freefire_id"
                              input-type="number"
                              @input="setId($event, 'freefire')"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.facebook_link')" :shorten="true" :editable="true" :value="user.vk_link"
                              @input="setFacebookLink"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.games')" :value="user.games"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.frags')" :value="user.kills"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.email')" :value="user.email"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item class="right-hint" :name="$trans('profile.rank')" :value="user.rank.name" :hint="description"></profile-item>
            </div>
            <div class="col-md-4 col-6 justify-content-center d-flex">
                <profile-item :name="$trans('profile.balance')" :value="user.balance + $trans('menu.currency')"></profile-item>
            </div>
        </div>
        <div class="text-center justify-content-center d-flex btn-div">
            <animated-button class="button-modal btn menu-button" @click="showWithdraw">{{$trans('profile.withdraw')}}</animated-button>
        </div>
        <div class="text-center justify-content-center d-flex btn-div">
            <animated-button class="button-modal btn menu-button" @click="showFreePayment">{{$trans('profile.refill')}}</animated-button>
        </div>
        <vue-image-crop-upload field="img"
                               @crop-success="cropSuccess"
                               @crop-upload-success="cropUploadSuccess"
                               @crop-upload-fail="cropUploadFail"
                               v-model="show"
                               :width="300"
                               :height="300"
                               :url="uploadUrl"
                               :params="params"
                               :headers="headers"
                               langType="ru"
                               noCircle
                               no-square
                               :noRotate="false"
                               img-format="jpg"></vue-image-crop-upload>
    </div>
</template>

<script>
    import {mapGetters} from "vuex";
    import ProfileInfo from "./ProfileInfo";
    import ProfileItem from "./ProfileItem";
    import VueImageCropUpload from "vue-image-crop-upload";
    import {USER_UPLOAD_AVATAR} from "../back-routes";
    import {SET_USER_AVATAR} from "../store/mutation-names";
    import {SET_USER_NAME_ID} from "../store/action-names";
    import AnimatedButton from "./AnimatedButton";

    export default {
        name: "Profile",
        components: {AnimatedButton, ProfileItem, ProfileInfo, VueImageCropUpload},
        data() {
            return {
                userLanguage: "en",
                themeEditing: false,
                teLoading: false,
                showTotalBalance: false,
                params: {},
                langOptions: {},
                headers: {
                    smail: '*_~',
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    // Authorization: 'Bearer ' + window.apiToken,
                },
                imgDataUrl: '',
                show: false
            }
        },
        computed: {
            uploadUrl() {
                return USER_UPLOAD_AVATAR;
            },
            reflink() {
                return document.location.href + this.user.pubg_id;
            },
            ...mapGetters(['user', 'currentRank']),
            description() {
                return this.currentRank.description.split("\n").join('<br />');
            }
        },
        methods: {
            copyToClipboard() {
                this.$copyText(this.reflink, this.$refs.profile)
                    .then(this.linkCoped, this.linkError);
            },
            linkCoped() {
                this.$bvModal.msgBoxOk(this.$trans('profile.link_copied'), {centered: true})
            },
            linkError() {
                this.$bvModal.msgBoxOk(this.$trans('profile.link_not_copied'), {centered: true})
            },
            showFreePayment() {
                this.$bvModal.show('free-payment-modal');
            },
            showWithdraw() {
                this.$bvModal.show('withdraw-modal');
            },
            setNik(nik, game) {
                this.$store.dispatch(SET_USER_NAME_ID, {game_name: nik, game})
                    .catch(error => {
                        this.$bvModal.msgBoxOk(this.$trans('profile.nik_in_use'))
                    });
            },
            setFacebookLink(link) {
                console.log(link);
                this.$store.dispatch(SET_USER_NAME_ID, {vk_link: link})
                    .catch(error => {
                        this.$bvModal.msgBoxOk(this.$trans('profile.facebook_link_error'))
                    });
            },
            setId(id, game) {
                console.log(id)
                this.$store.dispatch(SET_USER_NAME_ID, {game_id: id, game})
                    .catch(error => {
                        this.$bvModal.msgBoxOk(this.$trans('profile.id_in_use'))
                    });
            },

            cropSuccess(imgDataUrl, field) {
                console.log('-------- crop success --------');
            },

            cropUploadSuccess(jsonData, field) {
                this.$nextTick(() => {
                    this.$store.commit(SET_USER_AVATAR, jsonData.url);
                    this.$forceUpdate();
                })
            },

            cropUploadFail(status, field) {
                console.log('-------- upload fail --------');
                console.log(status);
                console.log('field: ' + field);
            },
        }
    }
</script>

<style scoped>

</style>