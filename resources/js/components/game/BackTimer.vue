<template>
    <div class="back-timer">
        <circle-graph :min="0" :max="2" :value="timeArray[0]" :text="timeArray[1]"></circle-graph>
        <circle-graph :min="0" :max="24" :value="timeArray[2]" :text="timeArray[3]"></circle-graph>
        <circle-graph :min="0" :max="60" :value="timeArray[4]" :text="timeArray[5]"></circle-graph>
        <circle-graph :min="0" :max="60" :value="timeArray[6]" :text="timeArray[7]"></circle-graph>
    </div>
</template>

<script>
    import HintButton from "../HintButton";
    import CircleGraph from "./CircleGraph";
    import Vue from 'vue';
    import {mapGetters} from "vuex";

    export default {
        name: "BackTimer",
        components: {CircleGraph, HintButton},
        props: {
            time: {
                type: Object,
                default: null
            }
        },
        data() {
            return {
                // currentTime: Vue.moment(),
            }
        },
        computed: {
            ...mapGetters([
                'currentTime'
            ]),
            remain() {
                return this.$moment.duration(this.time.diff(this.currentTime));
            },
            timeArray() {
                // console.log('CTIME LOCALE', this.currentTime.locale(), this.remain.locale());
                // console.log(this.currentTime.from(this.time, true));
                let numbers = this.remain.format('d hh mm ss', {
                    trim: false
                }).split(' ');
                let result = [];
                result.push(numbers[0]);
                result.push(this.getNoun(numbers[0],
                    this.$trans('game.time.day.day1'),
                    this.$trans('game.time.day.day2'),
                    this.$trans('game.time.day.day0'),));
                result.push(numbers[1]);
                result.push(this.getNoun(numbers[1],
                    this.$trans('game.time.hour.hour1'),
                    this.$trans('game.time.hour.hour2'),
                    this.$trans('game.time.hour.hour0'),));
                result.push(numbers[2]);
                result.push(this.getNoun(numbers[2],
                    this.$trans('game.time.minute.minute1'),
                    this.$trans('game.time.minute.minute2'),
                    this.$trans('game.time.minute.minute0'),))
                result.push(numbers[3]);
                result.push(this.getNoun(numbers[3],
                    this.$trans('game.time.second.second1'),
                    this.$trans('game.time.second.second2'),
                    this.$trans('game.time.second.second0'),))

                return result;
            }
        },
        methods: {
            getNoun(number, one, two, five) {
                let n = Math.abs(number);
                n %= 100;
                if (n >= 5 && n <= 20) {
                    return five;
                }
                n %= 10;
                if (n === 1) {
                    return one;
                }
                if (n >= 2 && n <= 4) {
                    return two;
                }
                return five;
            },
        }
    }
</script>

<style scoped>

</style>