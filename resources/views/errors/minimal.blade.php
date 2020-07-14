<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <style>
        html, body {
            background-color: #fff;
            color: #636b6f;
            font-family: 'Nunito', sans-serif;
            font-weight: 100;
            height: 100vh;
            margin: 0;
            background: url(/images/error-bg.jpg) no-repeat center center;
        }

        .full-height {
            height: 100vh;
        }

        .flex-center {
            align-items: center;
            display: flex;
            justify-content: center;
        }

        .position-ref {
            position: relative;
        }

        .code {
            border-right: 2px solid;
            font-size: 26px;
            padding: 0 15px 0 15px;
            text-align: center;
        }

        .message {
            font-size: 18px;
            text-align: center;
        }

        .mobile-image img {
            max-width: 80%;
        }
    </style>
</head>
<body>
<div class="flex-center position-ref full-height mobile-image">
    <img src="@yield('image')" onclick="window.location.href='/';"/>
</div>
{{--<div class="flex-center position-ref full-height">--}}
{{--<div class="code">--}}
{{--@yield('code')--}}
{{--</div>--}}

{{--<div class="message" style="padding: 10px;">--}}
{{--@yield('message')--}}
{{--</div>--}}
{{--</div>--}}
</body>
</html>
