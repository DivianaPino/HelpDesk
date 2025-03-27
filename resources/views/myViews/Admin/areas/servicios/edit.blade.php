@extends('adminlte::page')

@section('title',  'Editar servicio')

@section('content')
<div class="content ">
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap centrar-form">
        <div class="col-md-6">
          <div class="form h-100 sombra ">
            <h3 class="title-editServicio">Editar servicio de <span class="nameArea">{{$area->nombre}}</span></h3>
            <form action="/servicios/{{$servicio->id}}" class="mb-5" method="post" id="contactForm" name="contactForm">
            @csrf
            @method('PUT')
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="nombre" class="col-form-label nameService">Nombre del servicio:</label>
                  <input type="text" class="form-control" name="nombre" id="nombre"  value="{{$servicio->nombre}}" >
                </div>
              </div>
              
              <div class="msj_error">
                 @error('nombre')
                    {{$message}}
                  @enderror
              </div>
              
            
              <div class="row btn-createService">
                  <div class="col-md-6 form-group d-flex justify-content-start">
                      <input type="submit" id="submitButton" value="Crear" class="btnForm btn-primary rounded-0 py-2 px-4 btnCreateService">
                  </div>

                  <div class="col-md-6 form-group d-flex justify-content-end">
                      <a href="/area/{{$area->id}}/servicios" class="btnForm btn-dark rounded-0 py-2 px-4 btn_volver btnVolverService"> Ver todos</a>
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
               alert('Servicio modificado exitosamente!');
               document.getElementById('submitButton').disabled = true;
               document.getElementById('submitButton').value = 'Enviado';
            }
         });
   });
</script>

@stop