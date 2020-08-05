@extends('emails.master')

@section('content')
    <p>Hola: <strong>{{ $name }}</strong></p>
    <p>Este es un correo electrónico que le ayudara a restablecer la contraseña de su cuenta.</p>
    <p>Para continuar haga click en el siguiente botón e ingrese el siguiente código:</p>
    <h2>{{ $code }}</h2>
    <p><a href="{{ url('/reset?email='.$email) }}" class="boton">Resetear mi contraseña</a></p>
    <p>Si el botón anterior no funciona, copie y pegue la siguiente url en su navegador:</p>
    <p>{{ url('/reset?email='.$email)  }}</p>
    
@endsection
