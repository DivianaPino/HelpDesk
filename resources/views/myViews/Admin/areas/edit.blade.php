@extends('adminlte::page')

@section('title', 'Editar área')

@section('content')
<div class="content ">
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap centrar-form">
        <div class="col-md-6">
          <div class="form h-100 sombra ">
            <h3>Editar área</h3>
            @if(session('status'))
              <p class="alert alert-success">{{ Session('status') }}</p>
            @endif
            <form action="/areas/{{$area->id}}" class="mb-5" method="post" id="contactForm" name="contactForm">
            @csrf
            @method('PUT')
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="nombre" class="col-form-label">Nombre del área o departamento:</label>
                  <input type="text" class="form-control" name="nombre" id="nombre"  value="{{$area->nombre}}" >
                </div>
              </div>
              <div class="col-md-12 form-group mb-3">
                  <label for="nombre" class="col-form-label">Notificación al correo de:</label>
                  <div class="select-containerCorreo w-100"> 
                      <select class="select-box w-100" name="opcionCorreo" id="opcionCorreo" style="height:40px;">
                          <option value="">Seleccionar</option>
                          <option value="Todos" data-stars="5" {{ (old('opcionCorreo', $area->notif_correo) == 'Todos') ? 'selected' : '' }}>Todos</option> 
                          <option value="Jefe de area" data-stars="4" {{ (old('opcionCorreo', $area->notif_correo) == 'Jefe de area') ? 'selected' : '' }}>Jefe de área</option>
                      </select>
                  </div>
              </div>
              <div class="col-md-12 form-group mb-3">
                  <label for="nombre" class="col-form-label">Notificación al telegram de:</label>
                  <div class="select-containerTelegram"> 
                    <select class="select-box w-100" name="opcionTelegram" id="opcionTelegram" style="height:40px;">
                        <option value="">Seleccionar</option>
                        <option value="Todos" data-stars="5" {{ (old('opcionTelegram', $area->notif_telegram) == 'Todos') ? 'selected' : '' }}>Todos</option> 
                        <option value="Jefe de area" data-stars="4" {{ (old('opcionTelegram', $area->notif_telegram) == 'Jefe de area') ? 'selected' : '' }}>Jefe de área</option>
                    </select>
                  </div>
              </div>
              
              <div class="msj_error">
                 @error('nombre')
                    {{$message}}
                  @enderror
              </div>
              
            
              <div class="row content-btn-area">
                <div class="col-md-8 form-group">
                  <input type="submit" id="submitButton" value="Editar" class="btnForm btn-primary rounded-0 py-2 px-4">
                </div>

                <div class="col-md-4 form-group">
                    <a href="/areas" class="btnForm btn-dark rounded-0 py-2 px-4 btn_volver"> Ver todas</a>
                </div>
              </div>
               

            </form>

          </div>
        </div>
      </div>
    </div>

  </div>
@stop

@section('css')
 <link rel="stylesheet" href="/css/styleForm.css">
@stop

@section('js')

<script>
   document.addEventListener('DOMContentLoaded', function() {
         document.getElementById('submitButton').addEventListener('click', function(e) {
            e.preventDefault();
            this.disabled = true;
            this.value = 'Enviando...';
            this.form.submit();
         });

         document.addEventListener('ajax:success', function(event) {
            if (event.detail.status === 200) {
               alert('Ticket enviado exitosamente!');
               document.getElementById('submitButton').disabled = true;
               document.getElementById('submitButton').value = 'Enviado';
            }
         });
   });
</script>

@stop