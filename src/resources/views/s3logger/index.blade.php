@extends('s3loggerView::layout.app')
@section('title',  'Project List')
@section('content')
    <h1>List project</h1>
    <table class="table table-bordered">
        <thead>
        <tr>
            <th>No</th>
            <th>Project Name</th>
            <th>Description</th>
            <th>Detail</th>
        </tr>
        </thead>
        <tbody>
        @foreach ($paginatedProjects as $key => $project)
            <tr>
                <td> {{ $key +1 }}</td>
                <td> {{ $project }}</td>
                <td> {{ $project }}</td>
                <td><a href="{{ route('s3logger.show', $project) }}"> View </a></td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!-- Hiển thị liên kết phân trang -->
    <div class="pagination">
        {{ $paginatedProjects->links() }}
    </div>
@endsection
