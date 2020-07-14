<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PUBG: Misson Control</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="description"
          content="Онлайн турниры PUBG MOBILE. Выплаты с каждого килла. Играй и зарабатывай на турнирах. Повышай статусы и увеличивай коэффициент выплаты за каждый килл">
    <meta name="keywords" content="Pubgmobile, Pubg, Pubgtournaments">
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
    <div class="row justify-content-center">
        <div class="col-md-4 justify-content-center" style="min-width: 32rem;">
            <form method="POST" action="{{ route('admin.login') }}" aria-label="{{ __('Login') }}">
                @csrf

                <div class="form-group text-center" style="font-size: 3rem; font-weight: bold; color: #ffc000;">
                    PUBG: Misson Control
                </div>
                <div class="form-group row text-center justify-content-center">
                    <label for="email" class="col-form-label text-center">@lang('E-MAIL')</label>

                    <input id="email" type="email"
                           style="font-size: 1.6rem;"
                           class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email"
                           value="{{ old('email') }}" required autofocus>

                    @if ($errors->has('email'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('email') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group row text-center justify-content-center">
                    <label for="password" class="col-form-label text-center">{{ __('Password') }}</label>

                    <input id="password" type="password"
                           style="font-size: 1.6rem;"
                           class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password"
                           required>

                    @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                        <strong>{{ $errors->first('password') }}</strong>
                    </span>
                    @endif
                </div>

                <div class="form-group row justify-content-center">
                    <button type="submit" class="btn button-modal menu-button">
                        {{ __('Login') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<script src="{{ asset('js/app.js') }}?wdfg3q5h465yertew3" defer></script>
</body>
</html>