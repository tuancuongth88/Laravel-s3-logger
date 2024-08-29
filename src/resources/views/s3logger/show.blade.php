@extends('s3loggerView::layout.app')
@section('title', 'File List '. $folder)
@section('content')
    <h1>Files in {{ $folder }}</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>File name</th>
            <th>Action</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($paginatedItems as $key => $file)
            <tr>
                <td>{{ $paginatedItems->firstItem() + $key }}</td>
                <td>{{ basename($file) }}</td>
                <td>
                    <a href="{{ route('s3logger.view', [$folder, basename($file)]) }}">View</a> |
                    <a href="{{ route('s3logger.download', [$folder, basename($file)]) }}">Download</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    {{-- Hiển thị các liên kết phân trang --}}
    <div class="pagination">
        {{ $paginatedItems->links() }}
    </div>
@endsection
