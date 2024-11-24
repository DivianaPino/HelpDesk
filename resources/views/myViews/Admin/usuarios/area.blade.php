@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Asignar area al usuario</h1>
@stop

@section('content')

@if (Session('info'))
   <div class="alert alert-success">
     <strong>{{session('info')}}</strong>
   </div>
@endif
<div class="messages"></div>

<div class="card">
   <div class="card-body">
         <p class="h5">Nombre</p>
         <p class="form-control">{{$usuario->name}}</p>
          
         {!!Form::model($usuario,['url'=>['actualizar_area', $usuario], 'method'=> 'put', 'id'=>'formularioAreas'])!!}

               @foreach ($areas as $area )
                     <div class="area-checkboxes">
                        <label>
                           {!!Form::checkbox('areas[]', $area->id, null, ['class'=>'mr-1'])!!}
                           {{$area->nombre}}
                           <div id="selectedAreas"></div>
                        
                        </label>
                     </div>
                @endforeach

               {!! Form::submit('Asignar área', [
               'class' => 'btn btn-primary mt-2',
               'id' => 'submitButton',
               'name' => 'submitButton'
               ]) !!}
               
                <div style="display:inline-block; margin-left:20px;">
                     <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volver">Volver</a>
                </div>
         {!!Form::close()!!}
   </div>
</div>
@stop

@section('css')
    <!-- <link rel="stylesheet" href="/css/admin_custom.css"> -->
@stop

@section('js')
<script>
$(document).ready(function() {
    $('#submitButton').click(function(e) {
        e.preventDefault();
        
        var formData = $('#formularioAreas').serialize();
        $.ajax({
            url: '{{ route('actualizar_area', $usuario->id) }}',
            method: 'PUT',
            data: formData,
            success: function(response) {
               console.log(response);
                if (response.success) {
                    // Actualiza la lista de roles en la vista
                    updateRolesList(response.areas);
                    showMessage('success', 'La asignación de area(s) se realizó correctamente.');
                } else {
                    console.error('Error al actualizar las areas');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ocurrió un error:', error);
            }
        });
    });

    function updateRolesList(roles) {
        
        $('.role-checkboxes').each(function(index, element) {
           
            $(element).find('input[type="checkbox"]').prop('checked', false);
        });

        roles.forEach(function(role) {
         
            $('#' + role.id).prop('checked', true);


        });
    }

    function showMessage(type, message) {
      var messageDiv = $('.messages');
      
      // Elimina cualquier mensaje existente
      messageDiv.empty();
      
      // Agrega el nuevo mensaje
      messageDiv.append(`
      <div class="alert alert-${type} alert-dismissible fade show" role="alert">
         <strong>${message}</strong>
      </div>
   `);

   setTimeout(function() {
      $('.alert-dismissible').fadeOut(500);
   }, 5000);
   }

    
});
</script>
@stop