
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aprendible</title>
    </head>
    <body>

            <div class="welcome">
                <h1>Bienvenido {{ Auth::user()->name; }}</h1>
                {{-- <a href="/logout">Cerrar sesión.</a> --}}
                <form action="/logout" method="POST">
                    @csrf
                    <a href="#" onclick="this.closest('form').submit()">Logout</a>
                </form>
            </div>

    </body>
</html>
