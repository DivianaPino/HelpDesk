@extends('adminlte::page')

@section('title', 'Detalles Ticket')

@section('content')
  <div class="content">
      <div class="container">
        <div class="row align-items-stretch no-gutters contact-wrap">
          <div class="col-md-12">
            <div class="form h-100">   
               <div class="row content-row-ver" >
                    <div class="col-md-4 form-group btnAsistencia" style="justify-content:end">
                      <a href="javascript:history.back()" class="btnForm btn-dark btnVolver-verTicket"> <i class="fa-solid fa-arrow-left fa-lg"></i> VOLVER </a>
                    </div>
              </div>
              <h3 class="titulo-detalles">Detalles de ticket</h3>
          
              @if(session('status'))
                <p class="alert alert-success">{{ Session('status') }}</p>
              @endif
              <form action="" class="mb-5" method="" id="contactForm" name="contactForm">
                @csrf
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
                    <div class="col-md-3 form-group mb-3">
                      <label for="clasificacion_id" class="col-form-label">Clasificación:</label>
                      <input type="text" class="form-control" name="clasificacion_id" id="clasificacion_id" value="{{ $ticket->clasificacion->nombre }}" disabled>
                    </div>
                    
                    <div class="col-md-3 form-group mb-3">
                      <label for="prioridad_id" class="col-form-label">Prioridad:</label>
                      <input type="text" class="form-control" name="prioridad_id" id="prioridad_id" value="{{ $ticket->prioridad->nombre }}" disabled>
                    </div>

                    <div class="col-md-3 form-group mb-3">
                      <label for="estado_id" class="col-form-label">Estado:</label>
                      <input type="text" class="form-control" name="estado_id" id="estado_id" value="{{$ticket->estado->nombre}}" disabled>
                    </div>

                    <div class="col-md-3 form-group mb-3">
                      <label for="asignado_a" class="col-form-label">Técnico asignado:</label>
                      <input type="text" class="form-control" name="asignado_a" id="asignado_a" value="{{$ticket->asignado_a}}" disabled>
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
   <script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="../../js/jquery-3.3.1.min.js"></script> -->
    <!-- <script src="../../js/popper.min.js"></script> -->
    <!-- <script src="../../js/bootstrap.min.js"></script> -->
    <!-- <script src="../../js/jquery.validate.min.js"></script>
    <script src="../../js/main.js"></script> -->

@stop