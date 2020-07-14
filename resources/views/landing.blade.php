<!doctype html>
@php
    $join = request('join', false) ? request('join', false) : '' ;
    if(!$join){
        $join = session('referral_id', false) ? session('referral_id', false) : '' ;;
    }

    if(!$join){
        $join = Cookie::get('referral_name', false) ? Cookie::get('referral_name', false) : '' ;;
    }
@endphp
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@if($referalId)
            @lang('meta.title_reflink', ['referral' => $referalId])
        @else
            @lang('meta.title')
        @endif</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
          content="@lang('meta.content')">
    <meta name="keywords" content="@lang('meta.keywords')">
@if(!app()->environment('local'))
    <!-- Yandex.Metrika counter -->
        <script type="text/javascript">
            (function (m, e, t, r, i, k, a) {
                m[i] = m[i] || function () {
                    (m[i].a = m[i].a || []).push(arguments)
                };
                m[i].l = 1 * new Date();
                k = e.createElement(t), a = e.getElementsByTagName(t)[0], k.async = 1, k.src = r, a.parentNode.insertBefore(k, a)
            })
            (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

            ym(57610426, "init", {
                clickmap: true,
                trackLinks: true,
                accurateTrackBounce: true,
                webvisor: true
            });
        </script>
        <noscript>
            <div><img src="https://mc.yandex.ru/watch/57610426" style="position:absolute; left:-9999px;" alt=""/></div>
        </noscript>
        <!-- /Yandex.Metrika counter -->

        <!-- Global site tag (gtag.js) - Google Analytics -->
        <script async src="https://www.googletagmanager.com/gtag/js?id=UA-158763548-1"></script>
        <script>
            window.dataLayer = window.dataLayer || [];

            function gtag() {
                dataLayer.push(arguments);
            }

            gtag('js', new Date());

            gtag('config', 'UA-158763548-1');
        </script>

    @endif
    <meta name="yandex-verification" content="66f80cdbea3ab3d1"/>
    <meta name="google-site-verification" content="Dts8D6R6gDvquAKNjCJ9ljoG96R0DLAxi5DDWLRRtl0"/>

    <link rel="icon" href="/images/cropped-pubg-favicon-L-1-32x32.png" sizes="32x32"/>
    <link rel="icon" href="/images/cropped-pubg-favicon-L-1-192x192.png" sizes="192x192"/>
    <link rel="apple-touch-icon-precomposed" href="/images/cropped-pubg-favicon-L-1-180x180.png"/>
    <link href="{{ asset('css/app.css') }}?sfhgjbw98ogeiawbiofiqheabsldsdf2" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
    <script
            defer
            src="https://www.paypal.com/sdk/js?client-id={{config('paypal.client')}}&disable-card=jcb&currency=USD">
    </script>
    <script type="text/javascript">!function () {
            var t = document.createElement("script");
            t.type = "text/javascript", t.async = !0, t.src = "https://vk.com/js/api/openapi.js?162", t.onload = function () {
                VK.Retargeting.Init("VK-RTRG-408022-h1Zsn"), VK.Retargeting.Hit()
            }, document.head.appendChild(t)
        }();</script>
    <noscript><img src="https://vk.com/rtrg?p=VK-RTRG-408022-h1Zsn" style="position:fixed; left:-999px;" alt=""/>
    </noscript>
    <script type="text/javascript">
        var currentUser = {!! $user ? json_encode($user, JSON_UNESCAPED_UNICODE) : '{}' !!};
        var games = {!! json_encode($games, JSON_UNESCAPED_UNICODE)  !!};
        var kingGame = {!! json_encode($kingGame, JSON_UNESCAPED_UNICODE)  !!};
        var content = {!! json_encode($content, JSON_UNESCAPED_UNICODE) !!};
        var ranks = {!! json_encode($ranks, JSON_UNESCAPED_UNICODE) !!};
        var joinName = '{{$join}}';
        var notifications =
                {!! json_encode($notifications, JSON_UNESCAPED_UNICODE) !!}
        var liveStreamUrl = '{!! json_encode($liveStreamUrl) !!}';
        var echoPort = {!! config('pubg.echo_port') !!};
        var regClosed = {!! config('pubg.reg_closed') !!};
        var activeMerchant = '{!! config('payment.activeMerchant') !!}';
        var stat = {!! json_encode($stat, JSON_UNESCAPED_UNICODE) !!}
    </script>
    <meta name="interkassa-verification" content="4800f2723d6e0a52b5f062f47cb8433c"/>
    <script src="https://kit.fontawesome.com/ace769c44f.js" crossorigin="anonymous"></script>
</head>
<body>
<div class="preloader">
</div>
<div id="cabinet">
    <div class="block-1 menu-block-1">
        <div class="menu" id="main-menu">
            <span class="logo"></span>
            <a href="#" class="menu-item">@lang('menu.how_its_work')</a>
            <a href="#" class="menu-item lives">@lang('menu.live')</a>
            <a href="#" class="menu-item">@lang('menu.bot')</a>
            <a href="https://vk.com/pubgbattles" target="_blank" class="menu-item">@lang('menu.support')</a>
            <span class="profile-info">
                <span class="profile" id="profile">
                    <button class="menu-button btn" @click="$bvModal.show('login-modal')">@lang('menu.sign_up')</button>
                </span>
            </span>
        </div>
    </div>
    <div id="stat-container">
        <div class="slogan-block">
            <div class="second-line">
                {{__('game.stat.take_part')}}
            </div>
            <div class="first-line">
                {{__('game.stat.take_money')}}
            </div>
        </div>
        <div class="counters-block" @click="stat.users++">
            <div class="counter-wrap counter-1">
                <div>{{$stat['users']}}</div>
                <div class="counter-name">
                    {{__('game.stat.users_1')}}
                    <span>{{__('game.stat.users_2')}}</span>
                </div>
            </div>
            <div class="counter-wrap counter-2">
                <div>{{$stat['games']}}</div>
                <div class="counter-name">
                    {{__('game.stat.games_1')}}
                    <span>{{__('game.stat.games_2')}}</span>
                </div>
            </div>
            <div class="counter-wrap counter-3">
                <div>{{$stat['total_payed']}}</div>
                <div class="counter-name">
                    {{__('game.stat.payed_1')}}
                    <span>{{__('game.stat.payed_2')}}</span>
                </div>
            </div>
            <div class="counter-wrap counter-reference">
                <div class="counter-name">
                    {{__('game.stat.reference')}}
                </div>
            </div>
        </div>
        <div class="select-game">
            {{__('game.stat.select_game')}}
        </div>
    </div>
    <div id="games-container">
        @for($i = 0; $i<count($games); $i++)
            <div class="block-1" ref="gb{{$i}}">
                {{--        <div class="left  game-{{isset($games[1]) ? $games[1]->id%5 : 0}}" ref="game2div">--}}
                <div class="battle-info" id="battle-{{$i+1}}">
                    @if(isset($games[$i]))
                        @include('game', ['game' => $games[$i], 'button'=>'menu-button', 'killValue' => $killValue, 'type' => 'game' . ($i+1)])
                    @endif
                </div>
                {{--        </div>--}}
            </div>
        @endfor
    </div>
    {{--    <div class="block-1 game-king" ref="kingGameDiv">--}}
    {{--        <div class="battle-info" id="king-battle">--}}
    {{--            @if($kingGame)--}}
    {{--                @include('game', ['game' => $kingGame ?? null, 'killValue' => $killValue / 3, 'type' => 'king'])--}}
    {{--            @endif--}}
    {{--        </div>--}}
    {{--    </div>--}}
    <div class="end-block">
        <div class="ranks">
            <div class="smoke"></div>
            <div class="description">
                <h2>@lang('cabinet.ranks.title')</h2>
                <div>@lang('cabinet.ranks.description')</div>
            </div>
            <div class="list" id="ranks-list">
                @foreach($ranks as $rank)
                    <div class="rank rank-{{$rank->id}}">
                        <div class="image"><img class="" src="/{{$rank->image}}"></div>
                        <div class="title">{{$rank->name}}</div>
                        <div class="description">{!!nl2br($rank->description)!!}</div>
                        <div class="rq-title">@lang('cabinet.ranks.requirements')</div>
                        <div class="rq">{!!nl2br($rank->requirements)!!}</div>
                    </div>
                @endforeach
            </div>
            <div class="bloggers" id="bloggers">
                <div class="description">
                    <h2>@lang('bloggers.title')</h2>
                    <div>@lang('bloggers.subtitle')</div>
                </div>
                <div class="form text-center">
                    <input type="text" placeholder="Имя"/>
                    <input type="email" placeholder="E-Mail"/>
                    <input type="tel" placeholder="Телефон"/>
                    <input type="text" placeholder="ВКонтакте"/>
                </div>
                <div class="buttons text-center">
                    <button class="btn menu-button">@lang('bloggers.send')</button>
                </div>
            </div>
        </div>
        <div class="footer">
            <div class="logo"></div>
            <div class="slogan">@lang('landing.bigest_platform')</div>
            <div class="email">
                @lang('landing.support_mail')<br/>
                <a href="@lang('landing.mail')">@lang('landing.mail')</a>
            </div>
            <div class="terms">
                <a href="/documents/termsofuse.pdf" target="_blank">@lang('landing.terms_of_use')</a>
                <a href="/documents/privacypolicy.pdf" target="_blank">@lang('landing.conf_pol')</a>
            </div>
            <div class="rights">@lang('landing.rights')</div>
        </div>
    </div>
    <cabinet ref="cabinet"></cabinet>
</div>
<script src="{{ asset('js/lang.' . app()->getLocale() . '.js')}}"></script>
<script src="{{ asset('js/vendor.js') }}?asdklfhbq298wofegu39q8woehbkq3iuwe"></script>
<script src="{{ asset('js/manifest.js') }}?asdklfhbq298wofegu39q8woehbkq3iuwe"></script>
<script src="{{ asset('js/app.js') }}?ahdfgwueyfow7yakwhjfawiw9flfa230fjf"></script>
<script type="text/javascript" src="https://vk.com/js/api/openapi.js?162"></script>

</body>
</html>
