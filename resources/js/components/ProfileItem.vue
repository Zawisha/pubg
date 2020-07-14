<template>
    <div class="profile-item">
        <div class="profile-item-title">{{name}}</div>
        <div class="profile-item-value" v-if="!editable">{{shortValue}}
            <hint-button :hint-text="hint" v-if="hint"></hint-button>
        </div>
        <div class="profile-item-value" v-if="editable">
            <template v-if="!editing">
                {{shortValue}}
                <button class="btn btn-edit btn-small" @click="editing=true"></button>
            </template>
            <template v-else>
                <input :type="inputType" v-model="val"/>
                <button class="btn btn-cancel btn-small" @click="editing=false"></button>
                <button class="btn btn-ok btn-small" @click="saveInput"></button>
            </template>

            <hint-button :hint-text="hint" v-if="hint"></hint-button>
        </div>
    </div>
</template>

<script>
    import HintButton from "./HintButton";
    import {mapGetters} from "vuex";

    export default {
        name: "ProfileItem",
        components: {HintButton},
        props: {
            'shorten': {
                type: Boolean,
                default: false
            },
            'name': {
                type: String,
                default: ''
            },
            'value': {
                default: '',
            },
            'hint': {
                type: String,
                default: null,
            },
            'editable': {
                type: Boolean,
                default: false
            },
            inputType: {
                type: String,
                default: 'text'
            }
        },
        data() {
            return {
                editing: false,
                val: this.value
            }
        },
        computed: {
            shortValue() {
                if (this.shorten &&  this.value && this.value.toString().length > 10) {
                    return this.value.toString().substr(0, 8) + '…';
                } else {
                    return this.value;
                }

            }
        },
        methods: {
            // valueChanged(newValue) {
            //     this.val = newValue;
            // },
            saveInput() {
                this.$emit('input', this.val);
                this.editing = false;
            }
        }
    }
</script>

<style scoped>

</style>