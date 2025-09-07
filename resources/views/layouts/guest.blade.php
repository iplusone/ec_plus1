<!doctype html>
<html lang="ja">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title','Auth')</title>
  @vite(['resources/scss/app.scss','resources/js/app.js'])
</head>
<body class="bg-light">
  <main class="container py-5" style="max-width:560px">
    @yield('content')
  </main>
</body>
</html>
