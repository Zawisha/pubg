<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@lang('meta.password_restore_title')</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
          content="@lang('meta.content')">
    <meta name="keywords" content="@lang('meta.keywords')">
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

        ym(55164412, "init", {
            clickmap: true,
            trackLinks: true,
            accurateTrackBounce: true,
            webvisor: true
        });
    </script>
    <noscript>
        <div><img src="https://mc.yandex.ru/watch/55164412" style="position:absolute; left:-9999px;" alt=""/></div>
    </noscript>
    <!-- /Yandex.Metrika counter -->
    <meta name="yandex-verification" content="e6ce2da8d16b2e42"/>
    <meta name="google-site-verification" content="Dts8D6R6gDvquAKNjCJ9ljoG96R0DLAxi5DDWLRRtl0"/>

    <link rel="icon" href="/images/cropped-pubg-favicon-L-1-32x32.png" sizes="32x32"/>
    <link rel="icon" href="/images/cropped-pubg-favicon-L-1-192x192.png" sizes="192x192"/>
    <link rel="apple-touch-icon-precomposed" href="/images/cropped-pubg-favicon-L-1-180x180.png"/>
    <link href="{{ asset('css/app.css') }}?sdf24wrgq2gw3qg" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.2/animate.min.css">
</head>
<body style="background-image: url(/images/block-1-bg.png);
    background-size: cover;
    background-repeat: no-repeat;
    height: 100vh;
    background-position: center center;">
<div class="container d-flex justify-content-center align-items-center"
     style="position: fixed; left: 0; top: 0; right: 0; bottom: 0;">
    @yield('content')
</div>
<script src="{{ asset('js/app.js') }}?wdfg3q5h465yertew3" defer></script>
</body>
</html>