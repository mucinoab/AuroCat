<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Aprendible</title>
    </head>
    <body>
        <form method="POST">
        @csrf
            <label>
                <input name="email" type="email" placeholder="Email...">
            </label><br>
            <label>
                <input name="password" type="password" placeholder="Contraseña...">
            </label>
            <button type="submit">Login</button>
        </form>
    </body>
</html>

