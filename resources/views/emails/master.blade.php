<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        body {
            margin: 0px; 
            padding: 0px; 
            background-color: #f0f0f0;
        }
        .contenedor{
            max-width: 728; 
            display: block;
            margin: 0px auto; 
            width: 60%;
        }
        .imagen {
            width: 100%; 
            display: block;
        }
        .boton {
            display: inline-block;
            background-color: #2caaff;
            color: #FFF;
            padding: 12px;
            border-radius: 4px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="contenedor">
        <img src="https://i.imgur.com/VxCUgdE.png" class="imagen">
        <div style="background-color: #FFF">
            @yield('content')
        </div>
    </div>
</body>
</html>