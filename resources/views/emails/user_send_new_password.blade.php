@extends('emails.master')

@section('content')
    <p>Hola: <strong>{{ $name }}</strong></p>
    <p>Esta es la nueva contraseña para tu cuenta en nuestra plataforma.</p>
    <p><h2>{{ $password }}</h2></p>
    <p>Para iniciar sesión haga click en el siguiente botón:</p>
    <p><a href="{{ url('/login') }}" class="boton">Resetear mi contraseña</a></p>
    <p>Si el botón anterior no funciona, copie y pegue la siguiente url en su navegador:</p>
    <p>{{ url('/login') }}</p>
    
@endsection
