<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link rel="stylesheet" href="{{ asset('assets/chat/css/index.css'); }}">
    <link rel="stylesheet" href="{{ asset('assets/chat/css/home.css'); }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.2/css/all.min.css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.0/css/all.css" integrity="sha384-lZN37f5QGtY3VHgisS14W3ExzMWZxybE1SJSEsQp9S+oqd12jhcu+A56Ebc1zFSJ" crossorigin="anonymous">
    
    <link rel="stylesheet" href="{{ asset('assets/chat/css/styles.css'); }}" />
    <script src="https://unpkg.com/picmo@latest/dist/umd/index.js"></script>
    <script src="https://unpkg.com/@picmo/popup-picker@latest/dist/umd/index.js"></script>
    <script src="{{ asset('assets/chat/js/popup.js'); }}"></script>
    {{-- <script src="{{ asset('assets/chat/js/test.js'); }}"></script> --}}
</head>
<body>
    {{-- <audio id="player" controls="controls" autoplay="autoplay"> --}}
    </audio>
    {{-- <div id="header">
        Header
    </div> --}}
    <div id="content">
        <div id="nav">
            @yield('nav')
        </div>
        <div id="chat">
            @yield('chat')
        </div>
    </div>
    
    {{-- <div class="container"> --}}
      
    {{-- </div> --}}
</body>
</html>