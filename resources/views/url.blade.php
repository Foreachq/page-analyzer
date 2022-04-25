@extends('layouts.base')

@section('content')
    @include('flash::message')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайт: {{ $url->getName() }}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody><tr>
                    <td>ID</td>
                    <td>{{ $url->getId() }}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{ $url->getName() }}</td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td>{{ $url->getCreatedAt() }}</td>
                </tr>
                </tbody></table>
        </div>
        <h2 class="mt-5 mb-3">Проверки</h2>
        <form method="post" action="{{ route('check', $url->getId()) }}">
            @csrf
            <input type="submit" class="btn btn-primary" value="Запустить проверку">
        </form>
        <table class="table table-bordered table-hover text-nowrap mt-3">
            <tbody><tr>
                <th>ID</th>
                <th>Код ответа</th>
                <th>h1</th>
                <th>title</th>
                <th>description</th>
                <th>Дата создания</th>
            </tr>
            @foreach ($checks as $check)
            <tr>
                <td>{{ $check->getId() }}</td>
                <td>{{ $check->getStatusCode() }}</td>
                <td>{{ \Illuminate\Support\Str::limit($check->getH1(), 10) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($check->getTitle(), 30) }}</td>
                <td>{{ \Illuminate\Support\Str::limit($check->getDescription(), 30) }}</td>
                <td>{{ $check->getCreatedAt() }}</td>
            </tr>
            @endforeach
            </tbody></table>
    </div>
@endsection
