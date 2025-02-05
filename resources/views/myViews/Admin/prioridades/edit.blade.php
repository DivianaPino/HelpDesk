@extends('adminlte::page')

@section('title', 'Editar prioridad')

@section('content')
<div class="content ">
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap centrar-form">
        <div class="col-md-6">
          <div class="form h-100 sombra ">
            <h3>Editar prioridad</h3>
            <form action="/prioridades/{{$prioridad->id}}" class="mb-5" method="post" id="contactForm" name="contactForm">
            @csrf
            @method('PUT')
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="nombre" class="col-form-label">Prioridad:</label>
                  <input type="text" class="form-control" name="nombre" id="nombre"  value="{{$prioridad->nombre}}">
                </div>
              </div>

              <div class="msj_error">
                 @error('nombre')
                    {{$message}}
                  @enderror
              </div>
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="tiempo_resolucion" class="col-form-label">Cantidad de días para la resolución de tickets (según la prioridad):</label>
                  <input type="text" class="form-control" name="tiempo_resolucion" id="tiempo_resolucion" value="{{$prioridad->tiempo_resolucion}}" >
                </div>
              </div>

              <div class="msj_error">
                 @error('tiempo_resolucion')
                    {{$message}}
                  @enderror
              </div>
              
            
              <div class="row content-btn-prioridades">
                <div class="col-md-8 form-group">
                  <input type="submit" id="submitButton" value="Editar" class="btnForm btn-primary rounded-0 py-2 px-4">
                </div>

                <div class="col-md-4 form-group">
                    <a href="/prioridades" class="btnForm btn-dark rounded-0 py-2 px-4 btn_volver"> Ver todas</a>
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