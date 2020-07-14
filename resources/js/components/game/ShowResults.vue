<template>
    <div class="become-member profile register container">
        <img :src="'/images/mobile/UnitournLabel.png'" class="img_mob_logo" alt=""/>
     <div class="row col-12">
         <img :src="img_path_result.data" alt=""/>
     </div>

</div>
</template>

<script>

    import {mapGetters} from "vuex";

    export default {
        name: "ShowResults",
        components: {},
        mixins: [

        ],
        data() {
            return {

            }
        },
        mounted() {
            axios.post('/delete_show_img_results_func',
                {img_path: this.img_path_result.data})

        },
        beforeDestroy() {

        },
        computed: {
            ...mapGetters([
                'img_path_result'
            ]),

        },
        methods: {


        },
    }
</script>

<style scoped>

</style>
