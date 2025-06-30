<html lang="en">
<head>
    <title>crm getucon</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <link rel="icon" href="{{URL::asset('assets/images/brand/fav-icon.png')}}" type="image/x-icon">
    <link href="{{URL::asset('assets/css/icons.css')}}" rel="stylesheet">
    <link href="{{URL::asset('assets_errors/css/style.css')}}" rel="stylesheet" type="text/css" media="all">
</head>
<body>
<section>
    <div class="mainBg">
    </div>
    <img src="{{URL::asset('assets_errors/images/crmLogo.png')}}">
    <div class="main">
        <div class="blurBg"></div>
        <h1>{{$code}}</h1>
        <h2>{{trans('words.'.$code.'_text')}}</h2>
        <p>{!! trans('words.error_page_redirect_text',[ 'count' => '<span id="timerCount">10</span>']) !!}</p>
    </div>
</section>
<script src="{{URL::asset('assets/js/jquery-3.5.1.min.js')}}"></script>
<script>
    $(document).ready(function () {
        var counter = 10;
        var interval = setInterval(function () {
            counter--;
            if (counter == 0) {
                window.location.href = "/";
                clearInterval(interval);
            }
            $('#timerCount').html(counter);
        }, 1000);
    });
</script>


</body>
</html>
