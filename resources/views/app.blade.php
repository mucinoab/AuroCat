<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <title inertia>{{ config('app.name', 'Laravel') }}</title>

        <!-- Styles -->
        <link rel="stylesheet" href="{{ mix('css/app.css') }}">
        @routes
        <script src="{{ mix('js/app.js') }}" defer></script>
        <script src="https://js.pusher.com/7.0.3/pusher.min.js"></script>
        <script>
            var pusher = new Pusher('{{env("MIX_PUSHER_APP_KEY")}}', {
                cluster: '{{env("PUSHER_APP_CLUSTER")}}',
                encrypted: true
            });
        </script>
        <script src="https://cdn.jsdelivr.net/npm/vue@3.2.4/dist/vue.global.prod.min.js" defer></script>
        <script src="/js/utils.js" defer></script>


    </head>
    <body id="bighead" class="font-cat antialiased dark">
        @inertia

        @env ('local')
            <script src="http://localhost:3000/browser-sync/browser-sync-client.js"></script>
        @endenv
    </body>
</html>
