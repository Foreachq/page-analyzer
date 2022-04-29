@extends('layouts.base')

@section('content')
<div class="container-lg">
    <h1 class="mt-5 mb-3">Сайты</h1>
    <div class="table-responsive">
        <table class="table table-bordered table-hover text-nowrap">
            <tbody>
            <tr>
                <th>ID</th>
                <th>Имя</th>
                <th>Последняя проверка</th>
                <th>Код ответа</th>
            </tr>
            @foreach($urlsInfo as $urlInfo)
            <tr>
                <td>{{ $urlInfo['id'] }}</td>
                <td><a href="{{ route('urls.index', $urlInfo['id']) }}">{{ $urlInfo['name'] }}</a></td>
                <td>{{ $urlInfo['check_date']}}</td>
                <td>{{ $urlInfo['check_code']}}</td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
    <nav class="d-flex justify-items-center justify-content-between">
        <div class="d-none flex-sm-fill d-sm-flex align-items-sm-center justify-content-sm-between">
            <div>
                <p class="small text-muted">
                    Showing
                    <span class="font-medium">{{ $urlsFrom }}</span>
                    to
                    <span class="font-medium">{{ $urlsTo }}</span>
                    of
                    <span class="font-medium">{{ $urlsCount }}</span>
                    results
                </p>
            </div>

            <div>
                <ul class="pagination">
                    @if ($currentPage === 1)
                    <li class="page-item disabled" aria-disabled="true" aria-label="pagination.previous">
                        <span class="page-link" aria-hidden="true">‹</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ route('urls', ['page' => $currentPage - 1]) }}" rel="prev" aria-label="pagination.previous">‹</a>
                    </li>
                    @endif

                    @foreach(range(1, $pagesCount) as $page)
                        @if ($currentPage == $page)
                            <li class="page-item active" aria-current="page">
                                <span class="page-link">{{ $page }}</span>
                            </li>
                        @else
                            <li class="page-item">
                                <a class="page-link" href="{{ route('urls', ['page' => $page]) }}">{{ $page }}</a>
                            </li>
                        @endif
                    @endforeach

                    @if($currentPage == $pagesCount)
                    <li class="page-item disabled" aria-disabled="true" aria-label="pagination.next">
                        <span class="page-link" aria-hidden="true">›</span>
                    </li>
                    @else
                    <li class="page-item">
                        <a class="page-link" href="{{ route('urls', ['page' => $currentPage + 1]) }}" rel="next" aria-label="pagination.next">›</a>
                    </li>
                    @endif
                </ul>
            </div>
        </div>
    </nav>
</div>
@endsection
