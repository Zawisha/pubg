<template>
    <div class="rank" :class="('rank-'+rank.id) + (active ? ' active' : '')">
        <div class="image"><img class="" :src="'/'+rank.image"></div>
        <div class="title">{{rank.name}}</div>
        <div class="description" v-html="description"></div>
        <div class="rq-title">{{$trans('cabinet.ranks.requirements')}}</div>
        <div class="rq" v-html="requirements"></div>
    </div>
</template>

<script>
    export default {
        name: "Rank",
        props: ['rank', 'active'],
        computed: {
            requirements() {
                return this.rank.requirements.split("\n").join('<br />');
            },
            description() {
                return this.rank.description.split("\n").join('<br />');
            }
        }
    }
</script>

<style scoped>

</style>