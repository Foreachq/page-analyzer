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
</div>
@endsection
