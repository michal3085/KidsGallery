<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <title>JuniorsGallery</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />
    <!-- Font Awesome icons (free version)-->
    <script src="https://use.fontawesome.com/releases/v5.15.1/js/all.js" crossorigin="anonymous"></script>
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Saira+Extra+Condensed:500,700" rel="stylesheet" type="text/css" />
    <link href="https://fonts.googleapis.com/css?family=Muli:400,400i,800,800i" rel="stylesheet" type="text/css" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="{{ asset('css/styles.css') }}" rel="stylesheet" />
</head>
<body id="page-top">
<!-- Navigation-->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary fixed-top shadow-lg p-3 mb-5 rounded" id="sideNav">
    <a href="{{ route('profiles.gallery', ['name' => $user->name]) }}" class="navbar-brand js-scroll-trigger">
        <span class="d-block d-lg-none">SmallGallery</span>
        <span class="d-none d-lg-block"><img class="img-fluid img-profile rounded-circle mx-auto mb-2 shadow p-2 mb-3 rounded" src="{{ asset('/storage') . '/' . $user->avatar }}" alt="" style="width: 160px; height: 160px;" /></span>
     </a>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
        <ul class="navbar-nav">
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('moderator.index') }}">{{ __('Gallery') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('reported.pictures') }}">{{ __('Reported Pictures') }} ({{ \App\Models\PicturesReport::all()->count() }})</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('reported.comments') }}">{{ __('Reported Comments') }} ({{ \App\Models\CommentsReport::all()->count() }})</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('reported.messages') }}">{{ __('Reported Messages') }} ({{ \App\Models\ReportedMessage::all()->count() }})</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('show.blocked') }}">{{ __('Blocked Pictures') }} ({{ \App\Models\Picture::where('accept', 0)->count() }})</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('users.edit', ['user' => $user->id]) }}">{{ __('Settings') }}</a></li>
            <li class="nav-item"><a class="nav-link js-scroll-trigger" href="{{ route('pictures.index') }}">{{ __('Exit') }}</a></li>

        </ul>
    </div>
    <form method="post" action="{{ route('logout') }}">
        @csrf
        <button class="btn btn-outline-light" type="submit" name="logout">{{ __('Logout') }}</button>
    </form>
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
