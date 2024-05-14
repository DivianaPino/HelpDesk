@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar rol de usuario</h1>
@stop

@section('content')

@if (Session('info'))
   <div class="alert alert-success">
     <strong>{{session('info')}}</strong>
   </div>
    
@endif
   <div class="card">
      <div class="card-body">
          <p class="h5">Nombre</p>
          <p class="form-control">{{$usuario->name}}</p>
          
          {!!Form::model($usuario,['route'=>['usuarios.update', $usuario], 'method'=> 'put'])!!}

                @foreach ($roles as $role )
                     <div>
                        <label>
                           {!!Form::checkbox('roles[]', $role->id, null, ['class'=>'mr-1'])!!}
                           {{$role->name}}
                        
                        </label>
                     </div>
                @endforeach

                {!!Form::submit('Asignar rol', ['class'=>'btn btn-primary mt-2'])!!}
                <div style="display:inline-block; margin-left:20px;">
                     <a style="margin-top:8px;" href="/usuarios" class="btn btn-dark btn-volverInfo">Volver</a>
                </div>
          {!!Form::close()!!}
      </div>
   </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop