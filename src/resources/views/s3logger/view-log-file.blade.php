@extends('s3loggerView::layout.app')
@section('title',  'View log file '.$fileName)
@section('content')
<h1>Log File: {{ $fileName }}</h1>
<pre>{{ $fileContents }}</pre>
@endsection
