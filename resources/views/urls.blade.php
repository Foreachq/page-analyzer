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
            @foreach($urls as $url)
            <tr>
                <td>{{ $url->getId() }}</td>
                <td><a href="{{ route('url', $url->getId()) }}">{{ $url->getName() }}</a></td>
                <td>{{ $url->getCreatedAt() }}</td>
                <td></td>
            </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
