<template>
    <div class="bloggers" id="bloggers">
        <div class="description">
            <h2>{{$trans('bloggers.title')}}</h2>
            <div>{{$trans('bloggers.subtitle')}}</div>
        </div>
        <div class="form text-center">
            <input type="text" :placeholder="$trans('bloggers.name')" v-model="name"/>
            <input type="email" :placeholder="$trans('bloggers.email')" v-model="email"/>
            <span class="connect-type" :class="{expand}" @click="expand = !expand">
                {{names[phone]}}
                <div class="items" v-if="expand">
                    <div class="item" @click="phone='WhatsApp'">
                        {{$trans('bloggers.whatsapp')}}
                    </div>
                </div>
            </span>
            <input type="text" :placeholder="connectionTypes[phone]" v-model="vk"/>
        </div>
        <div class="buttons text-center">
            <animated-button class="btn menu-button" @click="submitData">{{$trans('bloggers.send')}}</animated-button>
        </div>
    </div>
</template>

<script>
    import {SEND_BLOGGERS_REQUEST} from "../store/action-names";
    import AnimatedButton from "./AnimatedButton";

    export default {
        name: "Bloggers",
        components: {AnimatedButton},
        data() {
            return {
                name: '',
                email: '',
                phone: 'WhatsApp',
                vk: '',
                expand: false,
                names: {
                    // 'Phone': _.$trans('bloggers.phone'),
                    'WhatsApp': _.$trans('bloggers.whatsapp'),
                    // 'VK': _.$trans('bloggers.vk')
                },
                connectionTypes: {
                    // 'Phone': _.$trans('bloggers.number_placeholder'),
                    'WhatsApp': _.$trans('bloggers.link_placeholder'),
                    // 'VK': _.$trans('bloggers.link_placeholder')
                }
            }
        },
        methods: {
            submitData() {
                if (this.name.trim() == '') {
                    this.$bvModal.msgBoxOk(this.$trans('bloggers.error_not_all'), {centered: true})
                    return;
                }

                if (this.email.trim() == '' && this.vk.trim() == '') {
                    this.$bvModal.msgBoxOk(this.$trans('bloggers.error_not_all'), {centered: true})
                    return;
                }

                this.$store.dispatch(SEND_BLOGGERS_REQUEST, {
                    name: this.name,
                    phone: this.phone,
                    email: this.email,
                    vk: this.vk
                }).then(response => {
                    this.$bvModal.msgBoxOk(this.$trans('bloggers.confirm'), {centered: true});
                    this.name = '';
                    this.phone = 'WhatsApp';
                    this.email = '';
                    this.vk = '';
                }).catch(error => {
                    this.$bvModal.msgBoxOk(this.$trans('bloggers.error'), {centered: true})
                })
            }
        }
    }
</script>