@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Asignar área al usuario</h1>
@stop

@section('content')

@if (Session('info'))
   <div class="alert alert-success">
     <strong>{{ session('info') }}</strong>
   </div>
@endif
<div class="messages"></div>

<div class="card">
   <div class="card-body">
         <p class="h5">Nombre</p>
         <p class="form-control">{{ $usuario->name }}</p>
          
         <form action="{{ route('actualizar_area', $usuario) }}" method="POST" id="formularioAreas">
               @csrf
               @method('PUT')

               @foreach ($areas as $area)
                     <div class="area-checkboxes">
                        <label>
                           <input type="checkbox" name="areas[]" value="{{ $area->id }}" class="mr-1" 
                           {{ $usuario->areas->contains($area->id) ? 'checked' : '' }}>
                           {{ $area->nombre }}
                        </label>
                     </div>
                @endforeach

               <button type="submit" class="btn btn-primary mt-2" id="submitButton" name="submitButton">Asignar área</button>
               
                <div style="display:inline-block; margin-left:20px;">
                     <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volver">Volver</a>
                </div>
         </form>
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
                if (response.success) {
                    updateAreasList(response.areas);
                    showMessage('success', 'La asignación de área(s) se realizó correctamente.');
                } else {
                    console.error('Error al actualizar las áreas');
                }
            },
            error: function(xhr, status, error) {
                console.error('Ocurrió un error:', error);
            }
        });
    });

    function updateAreasList(areas) {
        $('.area-checkboxes').each(function(index, element) {
            $(element).find('input[type="checkbox"]').prop('checked', false);
        });

        areas.forEach(function(area) {
            $('input[value="' + area.id + '"]').prop('checked', true);
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