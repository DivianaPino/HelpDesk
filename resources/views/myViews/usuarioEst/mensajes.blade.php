@extends('adminlte::page')

@section('title', 'Mensajes Ticket')

@section('content')
  <div class="content">
      <div class="container">
        <div class="row align-items-stretch no-gutters contact-wrap">
          <div class="col-md-12">
            <div class="form h-100">
              <div class="content-btnVolver">
                <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volver">
                <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
              </div>
              <h3>Ticket ID: {{$ticket->id}} </h3>
              @if(session('status'))
                <p class="alert alert-success">{{ Session('status') }}</p>
              @elseif(session('error'))
                <p class="alert alert-danger">{{ Session('error') }}</p>
              @endif
              <form action="/asignarTicket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm">
                @csrf
                @method('PUT')
                  <div class="row">
                    <div class="col-md-8 form-group mb-3">
                      <label for="user_id" class="col-form-label">Usuario:</label>
                      <input type="text" class="form-control" name="user_id" id="user_id"   value="{{$ticket->user->name}}" disabled >
                    </div>
                    <div class="col-md-4 form-group mb-3 ml-auto">
                      <label for="fecha_inicio" class="col-form-label">Fecha:</label>
                      <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{$ticket->fecha_inicio}}"  disabled>
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-4 form-group mb-3">
                        <label for="clasificacion_id" class="col-form-label">Clasificación:</label>
                        <select class="custom-select" id="clasificacion_id" name="clasificacion_id"  value="{{old('clasificacion_id')}}" disabled >
                            <option value="">{{ $ticket->clasificacion->nombre }}</option>
                        </select>

                    </div>
                    <div class="col-md-4 form-group mb-3">
                      <label for="prioridad_id" class="col-form-label">Prioridad:</label>
                      <select class="custom-select" id="prioridad_id" name="prioridad_id"  disabled>
                          <option value="">{{ $ticket->prioridad->nombre }}</option>
                      </select>
                    </div>

                    <div class="col-md-4 form-group mb-3">
                      <label for="estado_id" class="col-form-label">Estado:</label>
                      <input type="text" class="form-control" name="estado_id" id="estado_id" value="{{$ticket->estado->nombre}}" disabled>
                    </div>
                
                  </div>
                

                  <div class="row">
                    <div class="col-md-12 form-group mb-3">
                      <label for="asunto" class="col-form-label">Asunto:</label>
                      <input type="text" class="form-control" name="asunto" id="asunto" value="{{$ticket->asunto}}" disabled >
                    </div>
                  </div>
                  
                  <div class="row">
                    <div class="col-md-12 form-group mb-3">
                      <label for="mensaje" class="col-form-label">Mensaje:</label>
                      <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$ticket->mensaje}}</textarea>
                    </div>
                  </div>

                  @if(isset($ticket->imagen))
                    <div class="row">
                      <div class="col-md-12 form-group mb-3">
                          <img src="{{asset('images/tickets/'.$ticket->imagen)}}"
                            class="img-fluid img-rounded" width="120px"> 

                          <a href="{{ asset('images/tickets/'.$ticket->imagen) }}" download>Descargar Imagen</a>   
                      </div>
                    </div>
                  @endif
                 
                  @if(is_null($ticket->asignado_a))
                    <div class="row content-row" >
                      <div class="col-md-4 form-group btnAsistencia" >
                        <input type="submit" value="Dar asistencia" id="btn-asignarTecnico" class="btnForm btn-primary ">
                      </div>
                      @can('tecnicos_tkt_asignados')
                        <div class="col-md-4 form-group btnAsistencia" >
                          <a href="/tecnicos/tickets/asignados/{{$ticket->id}}"  class="btn btn-info btn-asignarTec">ASIGNAR TÉCNICO</a>
                        </div>
                      @endcan 
                   </div>
                  @endif
                  
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
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>    
@stop