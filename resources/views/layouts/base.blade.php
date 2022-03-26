<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    @yield('styles')
    <title>Анализатор страниц</title>
</head>

<body class="min-vh-100 d-flex flex-column">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

<header class="flex-shrink-0">
    <nav class="navbar navbar-expand-md navbar-dark bg-dark px-3">
        <a class="navbar-brand" href="{{ route('home') }}">Анализатор страниц</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav">
                <li class="nav-item">
                    @if (Request::url() === route('home'))
                        <a class="nav-link active" href="{{ route('home') }}">Главная</a>
                    @else
                        <a class="nav-link" href="{{ route('home') }}">Главная</a>
                    @endif
                </li>
                <li class="nav-item">
                    @if (Request::url() === route('urls'))
                        <a class="nav-link active" href="{{ route('urls') }}">Сайты</a>
                    @else
                        <a class="nav-link" href="{{ route('urls') }}">Сайты</a>
                    @endif
                </li>
            </ul>
        </div>
    </nav>
</header>

<main class="flex-grow-1">
    @yield('content')
</main>

<footer class="border-top py-3 mt-5 flex-shrink-0">
    <div class="container-lg">
        <div class="text-center">
            <a href="https://github.com/Foreachq/php-project-lvl3" target="_blank">Site source code</a>
        </div>
    </div>
</footer>

</body>
</html>
