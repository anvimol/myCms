@extends('admin.master')

@section('title', "Categorias")

@section('breadcrumb')
<li class="breadcrumb-item">
    <a href="{{ url('/admin/categories') }}"><i class="fas fa-folder-open"></i> Categorias</a>
</li>    
                           
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-3">
                <div class="panel shadow">
                    <div class="header">
                        <h2 class="title"><i class="fas fa-folder-open"></i>Agregar categoria</h2>
                    </div>

                    <div class="inside">
                        @if (kvfj(Auth::user()->permissions, 'category_add'))
                        {!! Form::open(['url' => '/admin/category/add', 'method'=>'POST', 'files' => true, 'role' => 'form']) !!}
                        <label for="name">Nombre:</label> 
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-keyboard"></i></span>
                            
                            {!! Form::text('name',null,['class'=>'form-control', 'placeholder'=>'Nombre', 'required' => 'required']) !!}
                        </div>

                        <label for="module" class="mtop16">Módulo:</label> 
                        <div class="input-group">
                            <span class="input-group-text" id="basic-addon1"><i class="fas fa-keyboard"></i></span>
                            
                            {!! Form::select("module", getModulesArray(), 0, ["class" => "form-select"]) !!}
                        </div>

                        <label for="icon" class="mtop16">Ícono:</label> 
                        <div class="form-file">
                            {!! Form::file('icon', ['class' => "form-file-input", 'required', 'id' => "customFile", 'accept' => 'image/*']) !!}
                            <label class="form-file-label" for="customFile">
                                <span class="form-file-text">Choose file...</span>
                                <span class="form-file-button">Browse</span>
                            </label>
                        </div>

                        {!! Form::submit('Guardar', ['class' => 'btn btn-success mtop16']) !!}
                        {!! Form::close() !!}
                        @endif
                    </div>
                </div>
            </div>

            <div class="col-md-9">
                <div class="panel shadow">
                    <div class="header">
                        <h2 class="title"><i class="fas fa-folder-open"></i> Categorias</h2>
                    </div>

                    <div class="inside">
                        <nav class="nav nav-pills nav-fill">
                            @foreach (getModulesArray() as $item => $k)
                                <a class="nav-link" href="{{ url('/admin/categories/' . $item) }}"><i class="fas fa-list"></i> {{ $k }}</a>
                            @endforeach
                        </nav>
                        <table class="table mtop16">
                            <thead>
                                <tr>
                                    <th width="48"></th>
                                    <th>Nombre</th>
                                    <th width="140"></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($cats as $cat)
                                        <td>
                                            @if (!is_null($cat->icon))
                                            <img src="{{ url('/uploads/'.$cat->file_path.'/'.$cat->icon) }}" class="img-fluid">
                                            @endif
                                        </td><!-- {!! htmlspecialchars_decode($cat->icon) !!} -->
                                        <td>{{ $cat->name }}</td>
                                        <td>
                                            <div class="opts">
                                                @if (kvfj(Auth::user()->permissions, 'category_edit'))
                                                <a href="{{ url('/admin/category/'.$cat->id.'/edit') }}" data-toggle="tooltip" data-placement="top" title="Editar">
                                                    <i class="far fa-edit"></i>
                                                </a>
                                                @endif
                                                @if (kvfj(Auth::user()->permissions, 'category_delete'))
                                                <a href="#" data-path="admin/category" data-action="delete" data-object="{{ $cat->id }}" data-toggle="tooltip" data-placement="top" title="Eliminar" class="btn_delete">
                                                    <i class="fas fa-trash-alt"></i>
                                                </a>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection