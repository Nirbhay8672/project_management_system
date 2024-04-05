<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="PMS System">
    <meta name="author" content="PMS">

    <link rel="shortcut icon" href="{{ asset('/images/favicon.png') }}" />

    @inertiaHead

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600&display=swap" rel="stylesheet">
</head>

<body>
    @inertia
    @vite('resources/js/app.js')

    <script src="{{ asset('/js/app.js') }}"></script>
</body>

</html>