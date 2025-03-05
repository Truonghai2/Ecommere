<title>{{ config('chatify.name') }}</title>

{{-- Meta tags --}}
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta http-equiv="X-UA-Compatible" content="ie=edge">
<meta name="id" content="{{ $id }}">
<meta name="messenger-color" content="{{ $messengerColor }}">
<meta name="messenger-theme" content="{{ $dark_mode }}">
<meta name="csrf-token" content="{{ csrf_token() }}">
<meta name="url" content="{{ url('').'/'.config('chatify.routes.prefix') }}" data-user="{{ Auth::user()->id }}">
<link rel="icon" href="{{ secure_asset('img/logo.png') }}">
{{-- scripts --}}
<script
  src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ secure_asset('js/chatify/font.awesome.min.js') }}"></script>
<script src="{{ secure_asset('js/chatify/autosize.js') }}"></script>
<script src="{{ secure_asset('js/app.js') }}"></script>
<script src='https://unpkg.com/nprogress@0.2.0/nprogress.js'></script>


<link href='https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css' rel='stylesheet'>
    <script src="https://unpkg.com/boxicons@2.1.4/dist/boxicons.js"></script>
{{-- styles --}}
<link rel='stylesheet' href='https://unpkg.com/nprogress@0.2.0/nprogress.css'/>
<link href="{{ secure_asset('css/chatify/style.css') }}" rel="stylesheet" />
<link href="{{ secure_asset('css/chatify/'.$dark_mode.'.mode.css') }}" rel="stylesheet" />
<link href="{{ secure_asset('css/app.css') }}" rel="stylesheet" />
<link rel="stylesheet" href="{{ secure_asset('css/loading.css') }}">


<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
{{-- Setting messenger primary color to css --}}
<style>
    :root {
        --primary-color: {{ $messengerColor }};
    }
</style>
