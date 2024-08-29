@include('s3loggerView::layout.header')
<div class="container">
<!-- Breadcrumb -->
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('s3-logger/logs') }}">Home</a></li>

        @if(\Illuminate\Support\Facades\Request::is('s3-logger/logs/*') && !\Illuminate\Support\Facades\Request::is('s3-logger/logs/view/*'))
            <li class="breadcrumb-item active" aria-current="page">{{ $folder }}</li>
        @endif

        @if(\Illuminate\Support\Facades\Request::is('s3-logger/logs/view/*'))
            <li class="breadcrumb-item"><a href="{{ route('s3logger.show', $folder) }}">{{ $folder }}</a></li>
            <li class="breadcrumb-item active" aria-current="page">{{ $fileName }}</li>
        @endif
    </ol>
</nav>
@yield('content')
</div>
@include('s3loggerView::layout.footer')
