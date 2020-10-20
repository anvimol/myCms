@extends('master')

@section('title', 'Inicio')

@section('content')
    <div class="home_action_bar">
        <div class="row">
            <div class="col-md-3">
                <div class="categories">
                    <a href="#"><i class="fas fa-stream"></i> Categorias</a>
                    <ul class="shadow">
                        @foreach ($categories as $category)
                            <li>
                                <a href="{{ url('/store/category/'.$category->id.'/'.$category->slug) }}">
                                    <img src="{{ url('/uploads/'.$category->file_path.'/'.$category->icon) }}" alt="">
                                    {{ $category->name }}
                                </a>
                            </li>

                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection