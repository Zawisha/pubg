<template>
    <div class="game-stat">
        <div class="slogan-block">
            <div class="second-line">
                {{$trans('game.stat.take_part')}}
            </div>
            <div class="first-line">
                {{$trans('game.stat.take_money')}}
            </div>
        </div>
        <div class="counters-block">
            <div class="counter-wrap counter-1">
                <animated-counter :value="stat.users"/>
                <div class="counter-name">
                    {{$trans('game.stat.users_1')}}
                    <span>{{$trans('game.stat.users_2')}}</span>
                </div>
            </div>
            <div class="counter-wrap counter-2">
                <animated-counter :value="stat.games"/>
                <div class="counter-name">
                    {{$trans('game.stat.games_1')}}
                    <span>{{$trans('game.stat.games_2')}}</span>
                </div>
            </div>
            <div class="counter-wrap counter-3">
                <animated-counter :value="stat.total_payed" />
                <div class="counter-name">
                    (<i class="fas fa-dollar-sign"></i>) {{$trans('game.stat.payed_1')}}
                    <span>{{$trans('game.stat.payed_2')}}</span>
                </div>
            </div>
            <div class="counter-wrap counter-reference">
                <div class="counter-name">
                    {{$trans('game.stat.reference')}}
                </div>
            </div>
        </div>
        <div class="select-game">
            {{$trans('game.stat.select_game')}}
        </div>
    </div>
</template>

<script>
    import {mapState} from "vuex";
    import AnimatedCounter from "./AnimatedCounter";

    export default {
        name: "GameStat",
        components: {AnimatedCounter},
        computed: {
            ...mapState({
                stat: state => state.stat,
            })
        },
    }
</script>

<style scoped>

</style>