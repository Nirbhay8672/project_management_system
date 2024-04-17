<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PMS System">
    <meta name="author" content="PMS">
    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <meta name="url" content="{{ url('/') }}" />

    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" />

    @inertiaHead

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>
    @inertia
    @vite('resources/js/app.js')

    <script src="{{ asset('/js/app.js') }}"></script>
</body>

</html>