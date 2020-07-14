<template>
    <div class="list-container">
        <div class="list"
             ref="list"
             :style="`transform: translate(${-currentDot * 27.5}rem)`"
        >
            <rank v-for="rank in ranks"
                  :key="rank.id"
                  :rank="rank"
                  :active="user && user.rank_id >= rank.id"
            ></rank>
        </div>
        <div class="slide-arrow slide-left" @click.prevent="goLeft"></div>
        <div class="slide-arrow slide-right" @click.prevent="goRight"></div>
        <div class="current-dots">
            <span class="current-dot" v-for="idx in ranks.length"
                  :key="idx"
                  :class="{active: idx-1 == currentDot}"
                  @click="currentDot = idx-1"
            ></span>
        </div>
    </div>
</template>

<script>
    import Rank from "./Rank";
    import {mapGetters} from "vuex";

    export default {
        name: "RanksList",
        components: {Rank},
        data() {
            return {
                translate: 0,
                currentDot: 0,
            }
        },
        computed: {
            ...mapGetters(['user']),
            ranks() {
                return this.$store.state.ranks;
            }
        },
        methods: {
            goRight() {
                if (this.currentDot < this.ranks.length - 1) {
                    this.currentDot++;
                }
            },
            goLeft() {
                if (this.currentDot > 0) {
                    this.currentDot--;
                }
            }
        },
    }
</script>

<style scoped>

</style>