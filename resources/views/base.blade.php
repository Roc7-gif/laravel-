<!DOCTYPE html>
<html lang="fr  ">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
    @yield(section: 'title')

    </title>
</head>

<body>
    <div class="container">
        @yield('content')
    </div>
    <style>
    body
    {
        background-color: #f7f7f7;
        font-family: Georgia, 'Times New Roman', Times, serif;
    }
</style>
</body>
</html>