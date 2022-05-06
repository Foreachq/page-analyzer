@extends('layouts.base')

@section('content')
    @include('flash::message')
    <div class="container-lg">
        <h1 class="mt-5 mb-3">Сайт: {{ $url->url_name }}</h1>
        <div class="table-responsive">
            <table class="table table-bordered table-hover text-nowrap">
                <tbody><tr>
                    <td>ID</td>
                    <td>{{ $url->url_id }}</td>
                </tr>
                <tr>
                    <td>Имя</td>
                    <td>{{ $url->url_name }}</td>
                </tr>
                <tr>
                    <td>Дата создания</td>
                    <td>{{ $url->url_created_at }}</td>
                </tr>
                </tbody></table>
        </div>
        <h2 class="mt-5 mb-3">Проверки</h2>
        <form method="post" action="{{ route('check', $url->url_id) }}">
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
                <td>{{ $check->check_id }}</td>
                <td>{{ $check->check_status_code }}</td>
                <td>{{ Str::limit($check->check_h1, 10) }}</td>
                <td>{{ Str::limit($check->check_title, 30) }}</td>
                <td>{{ Str::limit($check->check_description, 30) }}</td>
                <td>{{ $check->check_created_at }}</td>
            </tr>
            @endforeach
            </tbody></table>
    </div>
@endsection
