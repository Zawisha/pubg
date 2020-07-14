<template>
    <div class="game-list">
        <div class="game-tabs">
            <div class="game-tab game-tab-freefire"
                 :class="{active: currentGameCode == 1}"
                 @click="selectGameCode(1)">
                <div class="game-title" v-if="!is_mob">Free Fire</div>
            </div>
            <div class="game-tab game-tab-pubg"
                 :class="{active: currentGameCode == 0}"
                 @click="selectGameCode(0)">
                <div class="game-title" v-if="!is_mob">PUBG Mobile</div>
            </div>
            <div class="game-tab game-tab-cod"
                 :class="{active: currentGameCode == 2}"
                 @click="selectGameCode(2)">
                <div class="game-title" v-if="!is_mob">CALL OF DUTY</div>
            </div>
            <div class="selected-game-mobile" v-if="!is_mob">
                {{$trans('game.selected_game_name')}} <span class="game-name">{{selectedGameName}}</span>
            </div>
        </div>

    <span class=" cont_mob_list" v-if="is_mob">

            <div class="mob_list_h " v-on:click="show_h">
                <span class="closer">{{$trans('game.frag.hour')}}</span>
                 <img :src="'/images/mobile/'+img_cross_h" class="mob_cross" alt=""/>
            </div>
        <div  v-for="(game,index) in games_h_ar" v-show="visible_h">
        <div
            :key="game.id"
            class="block-1"
            :class="'mob-game-' + game.game_code + '-' + game.image"
        >
            <mobile-short :game="game" :game-index="index"/>
        </div>
        <div class="ten_minutes"
             v-if="visible_ar.includes(game.id)"
        >
            <span class="game_comment">login: {{game.login}} password: {{game.password}}</span>
        </div>
        </div>
            <div class="block-1"
                 :class="`mob-game-${currentGameCode}-0`"
                 v-if="games_h_ar.length==0"
                 v-show="visible_h">
            <div class="battle-info-mob-no-game">
                <div class="title">{{$trans('game.no_games')}}</div>
                <div class="mode">
                    {{$trans('game.no_games_notify')}}
                </div>
            </div>
            </div>

        <!--            th-->
            <div class="mob_list_h " v-on:click="show_th">
                <span class="closer">{{$trans('game.frag.t_hour')}}</span>
                 <img :src="'/images/mobile/'+img_cross_th" class="mob_cross" alt=""/>
            </div>
        <div  v-for="(game,index) in games_th_ar" v-show="visible_th">
        <div
            :key="game.id"
            class="block-1"
            :class="'mob-game-' + game.game_code + '-' + game.image"
        >
            <mobile-short :game="game" :game-index="index"/>
        </div>
        <div class="ten_minutes"
             v-if="visible_ar.includes(game.id)"
        >
            <span class="game_comment">login: {{game.login}} password: {{game.password}}</span>
        </div>
        </div>
            <div class="block-1"
                 :class="`mob-game-${currentGameCode}-0`"
                 v-if="games_th_ar.length==0"
                 v-show="visible_th">
            <div class="battle-info-mob-no-game">
                <div class="title">{{$trans('game.no_games')}}</div>
                <div class="mode">
                    {{$trans('game.no_games_notify')}}
                </div>
            </div>
        </div>
        <!--end th-->

        <!--            td-->
            <div class="mob_list_h " v-on:click="show_td">
                <span class="closer">{{$trans('game.frag.today')}}</span>
                 <img :src="'/images/mobile/'+img_cross_td" class="mob_cross" alt=""/>
            </div>
        <div  v-for="(game,index) in games_td_ar" v-show="visible_td">
        <div
            :key="game.id"
            class="block-1"
            :class="'mob-game-' + game.game_code + '-' + game.image"
        >
            <mobile-short :game="game" :game-index="index"/>
        </div>
        <div class="ten_minutes"
             v-if="visible_ar.includes(game.id)"
        >
            <span class="game_comment">login: {{game.login}} password: {{game.password}}</span>
        </div>
        </div>
            <div class="block-1"
                 :class="`mob-game-${currentGameCode}-0`"
                 v-if="games_td_ar.length==0"
                 v-show="visible_td">
            <div class="battle-info-mob-no-game">
                <div class="title">{{$trans('game.no_games')}}</div>
                <div class="mode">
                    {{$trans('game.no_games_notify')}}
                </div>
            </div>
        </div>
        <!--end today-->

        <!--            tm-->
            <div class="mob_list_h " v-on:click="show_tm">
                <span class="closer">{{$trans('game.frag.tomorrow')}}</span>
                 <img :src="'/images/mobile/'+img_cross_tm" class="mob_cross" alt=""/>
            </div>
        <div  v-for="(game,index) in games_tm_ar" v-show="visible_tm">
        <div
            :key="game.id"
            class="block-1"
            :class="'mob-game-' + game.game_code + '-' + game.image"
        >
            <mobile-short :game="game" :game-index="index"/>
        </div>
        <div class="ten_minutes"
             v-if="visible_ar.includes(game.id)"
        >
            <span class="game_comment">login: {{game.login}} password: {{game.password}}</span>
        </div>
        </div>
            <div class="block-1"
                 :class="`mob-game-${currentGameCode}-0`"
                 v-if="games_tm_ar.length==0"
                 v-show="visible_tm">
            <div class="battle-info-mob-no-game">
                <div class="title">{{$trans('game.no_games')}}</div>
                <div class="mode">
                    {{$trans('game.no_games_notify')}}
                </div>
            </div>
        </div>
        <!--end tm-->

        <!--            all-->
            <div class="mob_list_h " v-on:click="show_all">
                <span class="closer">{{$trans('game.frag.ended')}}</span>
                 <img :src="'/images/mobile/'+img_cross_all" class="mob_cross" alt=""/>
            </div>
        <div  v-for="(game,index) in games_all_ar" v-show="visible_all">
        <div
            :key="game.id"
            class="block-1"
            :class="'mob-game-' + game.game_code + '-' + game.image"
        >
            <mobile-short :game="game" :game-index="index"/>
        </div>
        <div class="ten_minutes"
             v-if="visible_ar.includes(game.id)"
        >
            <span class="game_comment">login: {{game.login}} password: {{game.password}}</span>
        </div>
        </div>
            <div class="block-1"
                 :class="`mob-game-${currentGameCode}-0`"
                 v-if="games_all_ar.length==0"
                 v-show="visible_all">
            <div class="battle-info-mob-no-game">
                <div class="title">{{$trans('game.no_games')}}</div>
                <div class="mode">
                    {{$trans('game.no_games_notify')}}
                </div>
            </div>
        </div>
        <!--end all-->

        </span>



        <span v-if="!is_mob">
        <div v-for="(game,index) in selectedGames"
             v-if="selectedGames.length"
             :key="game.id"
             class="block-1"
             :class="'game-' + game.game_code + '-' + game.image"
        >
            <game-short :game="game" :game-index="index"/>
        </div>
        <div class="block-1"
             :class="`game-${currentGameCode}-0`"
             v-if="!selectedGames.length">
            <div class="battle-info">
                <div class="title">{{$trans('game.no_games')}}</div>
                <div class="mode">
                    {{$trans('game.no_games_notify')}}
                </div>
            </div>
        </div>
        </span>


    </div>
</template>

<script>
    import {mapGetters, mapState} from "vuex";
    import GameShort from "./GameShort";
    import MobileShort from "./MobileShort";
    import {SET_CURRENT_GAME_CODE} from "../../store/mutation-names";
    import store from "../../store/store";
    import {
        SET_CURRENT_TIME,
        SET_GAMES,
        SET_KING_GAME,
        SET_LIVE_STREAM_URL,
        SET_NOTIFICATIONS,
        SET_RANKS, SET_STAT,
        SET_USER
    } from "../../store/mutation-names";

    export default {
        name: "GameList",
        components: {GameShort,MobileShort},
        data() {
            return {
                img_cross_h:'cross.png',
                img_cross_th:'cross.png',
                img_cross_td:'cross.png',
                img_cross_tm:'cross.png',
                img_cross_all:'plus.png',
                visible_h:true,
                visible_th:true,
                visible_td:true,
                visible_tm:true,
                visible_all:false,
                games_h_ar:[],
                games_th_ar:[],
                games_td_ar:[],
                games_tm_ar:[],
                games_all_ar:[],
                check_time_ar:[],
                visible_ar:[],
                offset_h:0,
                offset_th:0,
                offset_td:0,
                offset_tm:0,
                offset_all:0,
                mob_size:0,
                is_mob:false,
                gameCodes: {
                    0: 'pubg',
                    1: 'cod',
                    2: 'freeplay'
                }
            }
        },
        mounted() {
            this.mob_size=document.documentElement.clientWidth;
            if(this.mob_size<641)
            {
                this.is_mob=true
            }
            document.addEventListener("scroll", this.onScroll);
            this.download_h(this.games_h_ar)


        },
        computed: {
            ...mapState({
                games: state => state.games,
                currentGameCode: state => state.currentGameCode
            }),
            ...mapGetters(['selectedGames']),
            selectedGameName() {
                if (this.currentGameCode == 0) {
                    return 'PUBG MOBILE'
                }

                if (this.currentGameCode == 1) {
                    return 'FREE FIRE'
                }

                if (this.currentGameCode == 2) {
                    return 'CALL OF DUTY'
                }
            }
        },
        methods: {
            onScroll(e) {
                let scrollTop = window.pageYOffset || document.documentElement.scrollTop;
                if(((document.documentElement.scrollHeight - scrollTop)<3500)&&(this.visible_all))
                {
                    this.download_all(this.games_all_ar)
                }
            },
            selectGameCode(code) {
                this.$store.commit(SET_CURRENT_GAME_CODE, code);
                this.img_cross_h='cross.png';
                this.img_cross_th='cross.png';
                this.img_cross_td='cross.png';
                this.img_cross_tm='cross.png';
                this.img_cross_all='plus.png';
                this.visible_h=true;
                this.visible_th=true;
                this.visible_td=true;
                this.visible_tm=true;
                this.visible_all=false;
                this.games_h_ar=[];
                this.games_th_ar=[];
                this.games_td_ar=[];
                this.games_tm_ar=[];
                this.games_all_ar=[];
                this.check_time_ar=[];
                this.visible_ar=[];
                this.offset_h=0;
                this.offset_th=0;
                this.offset_td=0;
                this.offset_tm=0;
                this.offset_all=0;
                this.download_h(this.games_h_ar);
            },
            show_h()
            {
                this.visible_h=(!this.visible_h)
                if(this.img_cross_h=='plus.png')
                {
                    this.img_cross_h='cross.png'
                }
                else
                {
                    this.img_cross_h='plus.png'
                }
            },
            show_th()
            {
                this.visible_th=(!this.visible_th)
                if(this.img_cross_th=='plus.png')
                {
                    this.img_cross_th='cross.png'
                }
                else
                {
                    this.img_cross_th='plus.png'
                }
            },
            show_td()
            {
                this.visible_td=(!this.visible_td)
                if(this.img_cross_td=='plus.png')
                {
                    this.img_cross_td='cross.png'
                }
                else
                {
                    this.img_cross_td='plus.png'
                }
            },
            show_tm()
            {
                this.visible_tm=(!this.visible_tm)
                if(this.img_cross_tm=='plus.png')
                {
                    this.img_cross_tm='cross.png'
                }
                else
                {
                    this.img_cross_tm='plus.png'
                }
            },
            show_all()
            {
                this.visible_all=(!this.visible_all)
                if(this.img_cross_all=='plus.png')
                {
                    this.img_cross_all='cross.png'
                }
                else
                {
                    this.img_cross_all='plus.png'
                }
            },

            watch_func(inp_h,inp_th,inp_td,inp_tm,visible_ar)
            {
                this.check_time_ar_h=[];
                this.check_time_ar_th=[];
                this.check_time_ar_td=[];
                this.check_time_ar_tm=[];
                let ms = new Date();
                let time_now= ms.getTime();

                for(let i = 0; i <this.games_h_ar.length; i++)
                {
                    let tourn_date=(new Date(this.games_h_ar[i].planned_at)).getTime();

                    if(((tourn_date-(time_now+((3600*3))))<600000)&&( (tourn_date-(time_now+((3600*3))))>0))
                    {
                        this.check_time_ar_h.push(this.games_h_ar[i].id)
                    }
                }
                for(let i = 0; i <this.games_th_ar.length; i++)
                {
                    let tourn_date=(new Date(this.games_th_ar[i].planned_at)).getTime();

                    if(((tourn_date-(time_now+((3600*3))))<600000)&&( (tourn_date-(time_now+((3600*3))))>0))
                    {
                        this.check_time_ar_th.push(this.games_th_ar[i].id)
                    }
                }
                for(let i = 0; i <this.games_td_ar.length; i++)
                {
                    let tourn_date=(new Date(this.games_td_ar[i].planned_at)).getTime();

                    if(((tourn_date-(time_now+((3600*3))))<600000)&&( (tourn_date-(time_now+((3600*3))))>0))
                    {
                        this.check_time_ar_td.push(this.games_td_ar[i].id)
                    }
                }
                for(let i = 0; i <this.games_tm_ar.length; i++)
                {
                    let tourn_date=(new Date(this.games_tm_ar[i].planned_at)).getTime();

                    if(((tourn_date-(time_now+((3600*3))))<600000)&&( (tourn_date-(time_now+((3600*3))))>0))
                    {
                        this.check_time_ar_tm.push(this.games_tm_ar[i].id)
                    }
                }

                axios
                    .post('/check_time',{
                        check_time_ar:this.check_time_ar_h,
                    })
                    .then(({ data }) => {
                        if (data.length != 0) {
                            data.forEach(function (entry) {
                                for(let i = 0; i <inp_h.length; i++)
                                {
                                    if(inp_h[i].id==entry.id)
                                    {
                                        inp_h[i].login=entry.login
                                        inp_h[i].password=entry.password
                                        visible_ar.push(inp_h[i].id)

                                    }
                                }
                            })
                        }
                    })

                    .then(() => {
                        axios
                            .post('/check_time',{
                                check_time_ar:this.check_time_ar_th,
                            })
                            .then(({ data }) => {
                                if (data.length != 0) {
                                    data.forEach(function (entry) {
                                        for(let i = 0; i <inp_th.length; i++)
                                        {
                                            if(inp_th[i].id==entry.id)
                                            {
                                                inp_th[i].login=entry.login
                                                inp_th[i].password=entry.password
                                                visible_ar.push(inp_th[i].id)
                                            }
                                        }
                                    })
                                }
                            })
                    })

                    .then(() => {
                        axios
                            .post('/check_time',{
                                check_time_ar:this.check_time_ar_td,
                            })
                            .then(({ data }) => {
                                if (data.length != 0) {
                                    data.forEach(function (entry) {
                                        for(let i = 0; i <inp_td.length; i++)
                                        {
                                            if(inp_td[i].id==entry.id)
                                            {
                                                inp_td[i].login=entry.login
                                                inp_td[i].password=entry.password
                                                visible_ar.push(inp_td[i].id)
                                            }
                                        }
                                    })
                                }
                            })
                    })

                    .then(() => {

                        axios
                            .post('/check_time',{
                                check_time_ar:this.check_time_ar_tm,
                            })
                            .then(({ data }) => {
                                if (data.length != 0) {
                                    data.forEach(function (entry) {
                                        for(let i = 0; i <inp_tm.length; i++)
                                        {
                                            if(inp_tm[i].id==entry.id)
                                            {
                                                inp_tm[i].login=entry.login
                                                inp_tm[i].password=entry.password
                                                visible_ar.push(inp_tm[i].id)
                                            }
                                        }
                                    })
                                }
                            })
                    })

                setTimeout(()=>{this.watch_func(this.games_h_ar,this.games_th_ar,this.games_td_ar,this.games_tm_ar, this.visible_ar)}, 60000);
            },

            download_h(inp)
            {
                axios
                    .post('/get_list_mob',{
                        offset:this.offset_h,
                        from_time:0,
                        time:1,
                        game_type:this.currentGameCode
                    })
                    .then(({ data }) => {
                            if (data.length != 0) {
                                data.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        name:entry.name,
                                        planned_at:entry.planned_at,
                                        started_at:entry.started_at,
                                        finished_at:entry.finished_at,
                                        price:entry.price,
                                        login:entry.login,
                                        password:entry.password,
                                        image:entry.image,
                                        status:entry.status,
                                        type:entry.type,
                                        comment:entry.comment,
                                        map_name:entry.map_name,
                                        max_players:entry.max_players,
                                        created_at:entry.created_at,
                                        updated_at:entry.updated_at,
                                        face:entry.face,
                                        results_published:entry.results_published,
                                        top1_prize:entry.top1_prize,
                                        is_king:entry.is_king,
                                        planned_at2:entry.planned_at2,
                                        planned_at3:entry.planned_at3,
                                        results_published2:entry.results_published2,
                                        results_published3:entry.results_published3,
                                        top2_prize:entry.top2_prize,
                                        top3_prize:entry.top3_prize,
                                        login2:entry.login2,
                                        password2:entry.password2,
                                        login3:entry.login3,
                                        password3:entry.password3,
                                        use_max_kill:entry.use_max_kill,
                                        mul:entry.mul,
                                        game_code:entry.game_code,
                                        type_name:entry.type_name,
                                        show_face:entry.show_face,
                                        question_text:entry.question_text,
                                        is_top:entry.is_top,
                                        top_limit:entry.top_limit,
                                        top_value:entry.top_value,
                                        cod_instructions:entry.cod_instructions,
                                        cod_instructions2:entry.cod_instructions2,
                                        time_to:entry.time_to,
                                        members_count:entry.members_count,
                                        isMember:entry.isMember
                                    });

                                })


                                this.offset_h+=10;
                                this.download_h(this.games_h_ar);
                            }
                            else {
                                this.download_th(this.games_th_ar);
                            }
                        }
                    )
            },
            download_th(inp)
            {

                axios
                    .post('/get_list_mob',{
                        offset:this.offset_th,
                        from_time:1,
                        time:3,
                        game_type:this.currentGameCode
                    })
                    .then(({ data }) => {
                            if (data.length != 0) {

                                data.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        name:entry.name,
                                        planned_at:entry.planned_at,
                                        started_at:entry.started_at,
                                        finished_at:entry.finished_at,
                                        price:entry.price,
                                        login:entry.login,
                                        password:entry.password,
                                        image:entry.image,
                                        status:entry.status,
                                        type:entry.type,
                                        comment:entry.comment,
                                        map_name:entry.map_name,
                                        max_players:entry.max_players,
                                        created_at:entry.created_at,
                                        updated_at:entry.updated_at,
                                        face:entry.face,
                                        results_published:entry.results_published,
                                        top1_prize:entry.top1_prize,
                                        is_king:entry.is_king,
                                        planned_at2:entry.planned_at2,
                                        planned_at3:entry.planned_at3,
                                        results_published2:entry.results_published2,
                                        results_published3:entry.results_published3,
                                        top2_prize:entry.top2_prize,
                                        top3_prize:entry.top3_prize,
                                        login2:entry.login2,
                                        password2:entry.password2,
                                        login3:entry.login3,
                                        password3:entry.password3,
                                        use_max_kill:entry.use_max_kill,
                                        mul:entry.mul,
                                        game_code:entry.game_code,
                                        type_name:entry.type_name,
                                        show_face:entry.show_face,
                                        question_text:entry.question_text,
                                        is_top:entry.is_top,
                                        top_limit:entry.top_limit,
                                        top_value:entry.top_value,
                                        cod_instructions:entry.cod_instructions,
                                        cod_instructions2:entry.cod_instructions2,
                                        time_to:entry.time_to,
                                        members_count:entry.members_count,
                                        isMember:entry.isMember
                                    });

                                })

                                this.offset_th+=10;
                                this.download_th(this.games_th_ar);
                            }
                            else{
                                this.download_today(this.games_td_ar)
                            }
                        }
                    );
            },
            download_today(inp)
            {

                axios
                    .post('/get_list_mob',{
                        offset:this.offset_td,
                        from_time:0,
                        time:24,
                        game_type:this.currentGameCode
                    })
                    .then(({ data }) => {
                            if (data.length != 0) {

                                data.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        name:entry.name,
                                        planned_at:entry.planned_at,
                                        started_at:entry.started_at,
                                        finished_at:entry.finished_at,
                                        price:entry.price,
                                        login:entry.login,
                                        password:entry.password,
                                        image:entry.image,
                                        status:entry.status,
                                        type:entry.type,
                                        comment:entry.comment,
                                        map_name:entry.map_name,
                                        max_players:entry.max_players,
                                        created_at:entry.created_at,
                                        updated_at:entry.updated_at,
                                        face:entry.face,
                                        results_published:entry.results_published,
                                        top1_prize:entry.top1_prize,
                                        is_king:entry.is_king,
                                        planned_at2:entry.planned_at2,
                                        planned_at3:entry.planned_at3,
                                        results_published2:entry.results_published2,
                                        results_published3:entry.results_published3,
                                        top2_prize:entry.top2_prize,
                                        top3_prize:entry.top3_prize,
                                        login2:entry.login2,
                                        password2:entry.password2,
                                        login3:entry.login3,
                                        password3:entry.password3,
                                        use_max_kill:entry.use_max_kill,
                                        mul:entry.mul,
                                        game_code:entry.game_code,
                                        type_name:entry.type_name,
                                        show_face:entry.show_face,
                                        question_text:entry.question_text,
                                        is_top:entry.is_top,
                                        top_limit:entry.top_limit,
                                        top_value:entry.top_value,
                                        cod_instructions:entry.cod_instructions,
                                        cod_instructions2:entry.cod_instructions2,
                                        time_to:entry.time_to,
                                        members_count:entry.members_count,
                                        isMember:entry.isMember
                                    });

                                })

                                this.offset_td+=10;
                                this.download_today(this.games_td_ar);
                            }
                            else
                            {
                                this.download_tomorrow(this.games_tm_ar)
                            }
                        }
                    );
            },
            download_tomorrow(inp)
            {
                axios
                    .post('/get_list_mob',{
                        offset:this.offset_tm,
                        from_time:0,
                        time:48,
                        game_type:this.currentGameCode
                    })
                    .then(({ data }) => {
                            if (data.length != 0) {

                                data.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        name:entry.name,
                                        planned_at:entry.planned_at,
                                        started_at:entry.started_at,
                                        finished_at:entry.finished_at,
                                        price:entry.price,
                                        login:entry.login,
                                        password:entry.password,
                                        image:entry.image,
                                        status:entry.status,
                                        type:entry.type,
                                        comment:entry.comment,
                                        map_name:entry.map_name,
                                        max_players:entry.max_players,
                                        created_at:entry.created_at,
                                        updated_at:entry.updated_at,
                                        face:entry.face,
                                        results_published:entry.results_published,
                                        top1_prize:entry.top1_prize,
                                        is_king:entry.is_king,
                                        planned_at2:entry.planned_at2,
                                        planned_at3:entry.planned_at3,
                                        results_published2:entry.results_published2,
                                        results_published3:entry.results_published3,
                                        top2_prize:entry.top2_prize,
                                        top3_prize:entry.top3_prize,
                                        login2:entry.login2,
                                        password2:entry.password2,
                                        login3:entry.login3,
                                        password3:entry.password3,
                                        use_max_kill:entry.use_max_kill,
                                        mul:entry.mul,
                                        game_code:entry.game_code,
                                        type_name:entry.type_name,
                                        show_face:entry.show_face,
                                        question_text:entry.question_text,
                                        is_top:entry.is_top,
                                        top_limit:entry.top_limit,
                                        top_value:entry.top_value,
                                        cod_instructions:entry.cod_instructions,
                                        cod_instructions2:entry.cod_instructions2,
                                        time_to:entry.time_to,
                                        members_count:entry.members_count,
                                        isMember:entry.isMember
                                    });

                                })

                                this.offset_tm+=10;
                                this.download_tomorrow(this.games_tm_ar);
                            }
                            else
                            {
                                this.watch_func(this.games_h_ar,this.games_th_ar,this.games_td_ar,this.games_tm_ar, this.visible_ar);
                                this.download_all(this.games_all_ar)
                            }
                        }
                    );
            },
            download_all(inp)
            {

                axios
                    .post('/get_list_mob_all',{
                        offset:this.offset_all,
                        game_type:this.currentGameCode
                    })
                    .then(({ data }) => {
                            if (data.length != 0) {

                                data.forEach(function(entry) {
                                    inp.push({
                                        id:entry.id,
                                        name:entry.name,
                                        planned_at:entry.planned_at,
                                        started_at:entry.started_at,
                                        finished_at:entry.finished_at,
                                        price:entry.price,
                                        login:entry.login,
                                        password:entry.password,
                                        image:entry.image,
                                        status:entry.status,
                                        type:entry.type,
                                        comment:entry.comment,
                                        map_name:entry.map_name,
                                        max_players:entry.max_players,
                                        created_at:entry.created_at,
                                        updated_at:entry.updated_at,
                                        face:entry.face,
                                        results_published:entry.results_published,
                                        top1_prize:entry.top1_prize,
                                        is_king:entry.is_king,
                                        planned_at2:entry.planned_at2,
                                        planned_at3:entry.planned_at3,
                                        results_published2:entry.results_published2,
                                        results_published3:entry.results_published3,
                                        top2_prize:entry.top2_prize,
                                        top3_prize:entry.top3_prize,
                                        login2:entry.login2,
                                        password2:entry.password2,
                                        login3:entry.login3,
                                        password3:entry.password3,
                                        use_max_kill:entry.use_max_kill,
                                        mul:entry.mul,
                                        game_code:entry.game_code,
                                        type_name:entry.type_name,
                                        show_face:entry.show_face,
                                        question_text:entry.question_text,
                                        is_top:entry.is_top,
                                        top_limit:entry.top_limit,
                                        top_value:entry.top_value,
                                        cod_instructions:entry.cod_instructions,
                                        cod_instructions2:entry.cod_instructions2,
                                        time_to:entry.time_to,
                                        members_count:entry.members_count,
                                        isMember:entry.isMember
                                    });

                                })

                                this.offset_all+=10;
                            }

                        }
                    );
            }

        },
    }
</script>

<style scoped>
</style>
