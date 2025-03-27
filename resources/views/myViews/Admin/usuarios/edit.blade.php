@extends('adminlte::page')

@section('title', 'Editar Usuario')

@section('content_header')
    <h1>Editar rol de usuario</h1>
@stop

@section('content')

@if (Session('info'))
    <div class="alert alert-success" id="successMessage">
        <strong>{{ session('info') }}</strong>
    </div>
@endif
<div class="messages"></div>

<div class="card">
    <div class="card-body">
        <p class="h5">Nombre</p>
        <p class="form-control">{{ $usuario->name }}</p>
        
        <form action="{{ route('usuarios.update', $usuario) }}" method="POST" id="formularioRoles">
            @csrf
            @method('PUT')

            @foreach ($roles as $role)
                <div class="role-checkboxes">
                    <label>
                        <input type="checkbox" name="roles[]" value="{{ $role->id }}" class="mr-1" id="{{ $role->id }}"
                            {{ $usuario->hasRole($role->id) ? 'checked' : '' }}>
                        {{ $role->name }}
                    </label>
                </div>
            @endforeach
            <button type="submit" class="btn btn-primary mt-2" id="submitButton" name="submitButton">Asignar rol</button>

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
        
        var formData = $('#formularioRoles').serialize();
        $.ajax({
            url: '{{ route('usuarios.update', $usuario->id) }}',
            method: 'PUT',
            data: formData,
            success: function(response) {
                if (response.success) {
                    // Actualiza la lista de roles en la vista
                    updateRolesList(response.roles);
                    showMessage('success', 'La asignación de rol(es) se realizó correctamente.');
                } else {
                    console.error('Error al actualizar los roles');
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