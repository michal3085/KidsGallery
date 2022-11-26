<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>Junior's Gallery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
{{--    Google Ad Sense uncomment that--}}
    <script data-ad-client="ca-pub-4931814970460150" async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<!-- Global site tag (gtag.js) - Google Analytics -->
    <script async src="https://www.googletagmanager.com/gtag/js?id=G-F3BYTT856T"></script>
    <script>
        window.dataLayer = window.dataLayer || [];
        function gtag(){dataLayer.push(arguments);}
        gtag('js', new Date());

        gtag('config', 'G-F3BYTT856T');
    </script>
</head>
<body id="page-top">

<div id="simplecookienotification_v01" style="display: block; z-index: 99999; min-height: 35px; width: 300px; position: fixed; background: rgb(255, 243, 224); border: 1px solid rgb(255, 152, 0); text-align: center; right: 10px; color: rgb(119, 119, 119); bottom: 10px;">
    <div style="padding:10px; margin-left:15px; margin-right:15px; font-size:14px; font-weight:normal;">
        <span id="simplecookienotification_v01_powiadomienie">Używamy cookies w celach funkcjonalnych, aby ułatwić użytkownikom korzystanie z witryny oraz w celu tworzenia anonimowych statystyk serwisu. Jeżeli nie blokujesz plików cookies, to zgadzasz się na ich używanie oraz zapisanie w pamięci urządzenia.</span><span id="br_pc_title_html"><br></span>
        <a id="simplecookienotification_v01_polityka" href="http://jakwylaczyccookie.pl/polityka-cookie/" style="color: rgb(255, 152, 0);">Polityka Prywatności</a><span id="br_pc2_title_html"> &nbsp;&nbsp; </span>
        <a id="simplecookienotification_v01_info" href="http://jakwylaczyccookie.pl/jak-wylaczyc-pliki-cookies/" style="color: rgb(255, 152, 0);">Jak wyłączyć cookies?</a><span id="br_pc3_title_html"> &nbsp;&nbsp; </span>
        <a id="simplecookienotification_v01_info2" href="https://nety.pl/cyberbezpieczenstwo" style="color: rgb(255, 152, 0);">Cyberbezpieczeństwo</a><div id="jwc_hr1" style="height: 10px; display: block;"></div>
        <a id="okbutton" href="javascript:simplecookienotification_v01_create_cookie('simplecookienotification_v01',1,7);" style="position: relative; background: rgb(255, 152, 0); color: rgb(255, 255, 255); padding: 5px 15px; text-decoration: none; font-size: 12px; font-weight: normal; border: 0px solid rgb(255, 243, 224); border-radius: 0px;">AKCEPTUJĘ</a><div id="jwc_hr2" style="height: 10px; display: block;"></div>
    </div>
</div>
<script type="text/javascript">var galTable= new Array(); var galx = 0;</script><script type="text/javascript">function simplecookienotification_v01_create_cookie(name,value,days) { if (days) { var date = new Date(); date.setTime(date.getTime()+(days*24*60*60*1000)); var expires = "; expires="+date.toGMTString(); } else var expires = ""; document.cookie = name+"="+value+expires+"; path=/"; document.getElementById("simplecookienotification_v01").style.display = "none"; } function simplecookienotification_v01_read_cookie(name) { var nameEQ = name + "="; var ca = document.cookie.split(";"); for(var i=0;i < ca.length;i++) { var c = ca[i]; while (c.charAt(0)==" ") c = c.substring(1,c.length); if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length); }return null;}var simplecookienotification_v01_jest = simplecookienotification_v01_read_cookie("simplecookienotification_v01");if(simplecookienotification_v01_jest==1){ document.getElementById("simplecookienotification_v01").style.display = "none"; }</script>
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-lg p-2 mb-5 rounded"  id="sideNav">
    <a href="{{ route('profiles.gallery', ['name' => $user->name]) }}" class="navbar-brand js-scroll-trigger">
        <span class="d-block d-lg-none">
            <div class="avatar">
                  <img class="img-fluid img-profile rounded-circle mx-auto shadow p-1 rounded" src="{{ asset('/storage') . '/' . $user->avatar }}" alt="" style="width: 50px; height: 50px;" />
                {{ \Illuminate\Support\Str::limit($user->name, 20, $end="...") }}
            <div class="status offline"></div>
            </div>
        </span>
        <span class="d-none d-lg-block"><img class="img-fluid img-profile rounded-circle mx-auto mb-2 shadow p-2 mb-3 rounded" src="{{ asset('/storage') . '/' . $user->avatar }}" alt="" style="width: 160px; height: 160px;" /></span>
     </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
       @if ( auth()->user()->newMessages()->count() != 0 )
                <i class="fas fa-bell" style="font-size: 17px;"></i>
            @endif
            <span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('messages.list') }}"><i class="far fa-comments" style="width: 25px; height: 25px;"></i> <strong>{{ auth()->user()->newMessages()->count() }}</strong></a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('pictures.create') }}">{{ __('Insert picture') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('pictures.index') }}">{{ __('Main Gallery') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('yours.gallery') }}">{{ __('Your Gallery') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('favorites') }}">{{ __('Favorites') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('top10') }}">{{ __('TOP10') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('users.edit', ['user' => $user->id]) }}">{{ __('Settings') }}</a></li>
            @if (\App\Models\Role::where('user_id', $user->id)->where('role', 'moderator')->count() != 0)
                <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('moderator.index') }}">{{ __('Moderator') }}</a></li>
            @endif
            <li><a class="nav-link js-scroll-trigger" href="{{ route('lang', ['en']) }}" style="display: inline-block;"><img src="{{ asset('assets/img/united-kingdom.png')}}" style="width: 20px; height: 20px;"></a> |
                <a class="nav-link js-scroll-trigger" href="{{ route('lang', ['pl']) }}" style="display: inline-block;"><img src="{{ asset('assets/img/poland.png')}}" style="width: 20px; height: 20px;"></a>
            </li>
        </ul>
    </div>
</nav>
<!-- Page Content-->

@yield('content')
<!-- Bootstrap core JS-->
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.1/jquery.min.js') }}"></script>
<script src="{{ asset('https://cdn.jsdelivr.net/npm/bootstrap@4.5.3/dist/js/bootstrap.bundle.min.js') }}"></script>
<!-- Third party plugin JS-->
<script src="{{ asset('https://cdnjs.cloudflare.com/ajax/libs/jquery-easing/1.4.1/jquery.easing.min.js') }}"></script>
<!-- Core theme JS-->
<script src="{{ asset('js/scripts.js') }}"></script>
<script src="{{ asset('js/app.js') }}"></script>
    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        @yield('javascript')
    </script>
</body>
</html>
