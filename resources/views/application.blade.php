<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width,initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">

  <title>{{ env('APP_NAME') }}</title>
</head>

<body>
  <div align="center">
    <h1>Welcome To </h1><br>
    <img align="center" border="0" src="{{ url('storage/partner-logos/default-logo.png') }}" alt="Logo"
      width="400" /><br>
    <h3><a href="https://app.decompass.com">Click here to visit the app</a></h3>
  </div>
</body>

</html>
