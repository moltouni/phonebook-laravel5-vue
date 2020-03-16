<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}" defer></script>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Raleway:300,400,600" rel="stylesheet" type="text/css">

    <!-- Styles -->
    <link href="{{ asset('css/login.css') }}" rel="stylesheet">

    <link rel="manifest" crossorigin="use-credentials" href="manifest.json" />

    <script>
      if ("serviceWorker" in navigator) {
        window.addEventListener("load", function() {
          navigator.serviceWorker.register("/service-worker.js").then(
            function(registration) {
              console.log(
                "ServiceWorker registration successful with scope: ",
                registration.scope
              );
            },
            function(err) {
              console.log("ServiceWorker registration failed: ", err);
            }
          );
        });

        self.addEventListener("fetch", function(event) {
          event.respondWith(
            fetch(event.request).catch(function() {
              return caches.match(event.request);
            })
          );
        });
      }
    </script>

    <script>
      function clearCache() {
        if ("caches" in window) {
          caches.keys().then(function(keyList) {
            return Promise.all(
              keyList.map(function(key) {
                return caches.delete(key);
              })
            );
          });
        }
      }

      clearCache();
    </script>

    <link
      rel="apple-touch-icon"
      sizes="57x57"
      href="img/favicon/apple-icon-57x57.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="60x60"
      href="img/favicon/apple-icon-60x60.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="72x72"
      href="img/favicon/apple-icon-72x72.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="76x76"
      href="img/favicon/apple-icon-76x76.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="114x114"
      href="img/favicon/apple-icon-114x114.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="120x120"
      href="img/favicon/apple-icon-120x120.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="144x144"
      href="img/favicon/apple-icon-144x144.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="152x152"
      href="img/favicon/apple-icon-152x152.png"
    />
    <link
      rel="apple-touch-icon"
      sizes="180x180"
      href="img/favicon/apple-icon-180x180.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="192x192"
      href="img/favicon/android-icon-192x192.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="32x32"
      href="img/favicon/favicon-32x32.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="96x96"
      href="img/favicon/favicon-96x96.png"
    />
    <link
      rel="icon"
      type="image/png"
      sizes="16x16"
      href="img/favicon/favicon-16x16.png"
    />
    <meta name="msapplication-TileColor" content="#9b4dca" />
    <meta
      name="msapplication-TileImage"
      content="img/favicon/ms-icon-144x144.png"
    />
    <meta name="theme-color" content="#9b4dca" />
</head>
<body>
    <div id="app">
            @yield('content')
    </div>
</body>
</html>
