@extends('adminlte::page')

@section('title', 'Crear Ticket')


@section('content')

<div class="content">
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap">
        <div class="col-md-12">
          <div class="form h-100">
            <h3>Crear Ticket</h3>
            <form action="{{ url('usuario/tickets') }}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
            @csrf
              <div class="row">
                <div class="col-md-8 form-group mb-3">
                  <label for="user_id" class="col-form-label">Usuario:</label>
                  <input type="text" class="form-control" name="user_id" id="user_id"   value="{{$usuarios->name}}" disabled >
                </div>
                <div class="col-md-4 form-group mb-3 ml-auto">
                  <label for="fecha_inicio" class="col-form-label">Fecha:</label>
                  <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{$fecha_actual}}"  disabled>
                </div>
              </div>
              
              

              <div class="row">
                <div class="col-md-4 form-group mb-3">
                    <label for="clasificacion_id" class="col-form-label">Clasificación:</label>
                    <select class="custom-select" id="clasificacion_id" name="clasificacion_id"  value="{{old('clasificacion_id')}}" >
                        <option selected value="">Seleccionar</option>
                        @foreach($clasificacions as $clasificacion)
                        <option value="{{ $clasificacion->id }}" {{old('clasificacion_id') == $clasificacion->id ? 'selected' : '' }}>{{ $clasificacion->nombre }}</option>
                        @endforeach
                    </select>

                    <div class="msj_error">
                    @error('clasificacion_id')
                        {{$message}}
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 form-group mb-3">
                  <label for="prioridad_id" class="col-form-label">Prioridad:</label>
                  <select class="custom-select" id="prioridad_id" name="prioridad_id" >
                    <option selected value="">Seleccionar</option>
                      @foreach($prioridads as $prioridad)
                      <option value="{{ $prioridad->id }}" {{old('prioridad_id') == $prioridad->id ? 'selected' : '' }}>{{ $prioridad->nombre }}</option>
                      @endforeach
                  </select>
                  
              
                  <div class="msj_error">
                    @error('prioridad_id')
                      {{$message}}
                    @enderror
                  </div>
                </div>
                <div class="col-md-4 form-group mb-3">
                  <label for="estado_id" class="col-form-label">Estado:</label>
                  <input type="text" class="form-control" name="estado_id" id="estado_id" value="{{$estadoFirst->nombre}}" disabled>
                </div>
             
              </div>
            

              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="asunto" class="col-form-label">Asunto:</label>
                  <input type="text" class="form-control" name="asunto" id="asunto" value="{{old('asunto')}}" placeholder="Escribe el asunto" >
                </div>
              </div>
              

              <div class="msj_error">
                @error('asunto')
                  {{$message}}
                @enderror
              </div>
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="mensaje" class="col-form-label">Mensaje:</label>
                  <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  placeholder="Escribe tu incidencia" >{{old('mensaje')}}</textarea>
                </div>
              </div>
             
              <div class="msj_error">
                @error('mensaje')
                  {{$message}}
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3 content-file">
                    <input type="file" name="imagen" accept="image/*" >
                </div>
              </div>

              <div class="msj_error">
                @error('imagen')
                  {{$message}}
                @enderror
              </div>

              
              <div class="row">
                <div class="col-md-12 form-group">
                  <input type="submit" value="Enviar incidencia" class="btnForm btn-primary rounded-0 py-2 px-4">
                  <!-- <span class="submitting"></span> -->
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
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="../../js/jquery-3.3.1.min.js"></script> -->
    <!-- <script src="../../js/popper.min.js"></script> -->
    <!-- <script src="../../js/bootstrap.min.js"></script> -->
    <!-- <script src="../../js/jquery.validate.min.js"></script>
    <script src="../../js/main.js"></script> -->
@stop