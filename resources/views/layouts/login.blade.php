<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name', 'Laravel') }} | Login</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Styles -->
  <link href="{{ mix('css/login.css') }}" rel="stylesheet">
</head>
<body class="hold-transition login-page">
    @yield('content')
    <!-- Script -->
    <script src="{{ mix('js/login.js') }}"></script>
</body>
</html>
