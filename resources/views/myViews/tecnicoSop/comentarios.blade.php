@extends('adminlte::page')

@section('title', 'Comentario')

@section('content')

<div class="content ">
  <div class="container ">
    <div class="row align-items-stretch no-gutters contact-wrap ">
      <div class="col-md-12 ">
        <div class="form h-100  content-fondo form-respuesta" >
          <div class="content-btnVolver">
              <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volverInfo">
              <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
          </div>
          <h3 class="tituloRespuesta">Comentario de la respuesta del Ticket #{{$idTicket}} </h3>
          
          @if(session('status'))
            <p class="alert alert-success">{{ Session('status') }}</p>
          @endif
          <form action="/comentar/respuesta/{{$respuesta['id']}}/ticket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
           @csrf

            <div class="cuadro1">
              INFORMACIÓN DEL TICKET
            </div>
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
                      class="img-fluid img-rounded" width="60px"> 
                    <a href="{{ asset('images/tickets/'.$ticket->imagen) }}" style="font-size:12px;" download >Descargar Imagen</a>   
                </div>
              </div>
            @else
              <div class="row">
                      <div class="col-md-12 form-group mb-3">
                          <img src="{{asset('images/tickets/'.$ticket->imagen)}}"
                              class="img-fluid img-rounded" width="120px" hidden> 

                          <a href="{{ asset('images/tickets/'.$ticket->imagen) }}" download hidden>Descargar Imagen</a>   
                      </div>
              </div>
            @endif  

            <div class="cuadro2">
                RESPUESTA <i class="fa-solid fa-arrow-down iconoResponder"  ></i>
            </div>

            <div class="row">
                <div class="col-md-12 form-group mb-3">
                    <label for="fecha_respuesta" class="col-form-label">Fecha:</label>
                    <input type="text" class="form-control" name="fecha_respuesta" id="fecha_respuesta" value="{{$respuesta['fecha']}}"  disabled>
                </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-3">
                <label for="mensaje" class="col-form-label">Mensaje:</label>
                <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4" disabled >{{$respuesta['mensaje']}}</textarea>
              </div>
            </div>

            @if(isset($respuesta['imagen']))
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                    <img src="{{asset('images/respuestas/tickets/'.$respuesta['imagen'])}}"
                      class="img-fluid img-rounded" width="60px"> 
                    <a href="{{ asset('images/respuestas/tickets/'.$respuesta['imagen']) }}" style="font-size:12px;" download >Descargar Imagen</a>   
                </div>
              </div>
            @else
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <img src="{{asset('images/respuestas/tickets/'.$respuesta['imagen'])}}" class="img-fluid img-rounded" width="120px" hidden> 
                    <a href="{{ asset('images/respuestas/tickets/'.$respuesta['imagen']) }}" download hidden>Descargar Imagen</a>   
                </div>
              </div>
            @endif  
            <hr style="font-size:20px;">

            <div class="cuadro3">
                COMENTARIO <i class="fa-solid fa-arrow-down iconoResponder"  ></i>
            </div>

            <div class="row">
                <div class="col-md-12 form-group mb-3">
                    <label for="fecha_comentario" class="col-form-label">Fecha:</label>
                    <input type="text" class="form-control" name="fecha_comentario" id="fecha_comentario" value="{{$comentario['created_at']->format('d-m-Y H:i:s')}}"  disabled>
                </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-3">
                <label for="mensaje" class="col-form-label">Mensaje:</label>
                <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4" disabled >{{$comentario['mensaje']}}</textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-3">
                <label for="nivel_satisfaccion" class="col-form-label">Nivel de satisfacción:</label>
                <textarea class="form-control" name="nivel_satisfaccion" id="nivel_satisfaccion" cols="30" rows="4" disabled >{{$comentario['nivel_satisfaccion']}}</textarea>
              </div>
            </div>

            <div class="row">
              <div class="col-md-12 form-group mb-3">
                <label for="reabrir" class="col-form-label" style="color:#000;">¿Reabierto?:</label>
                 @if($comentario['bool_reabrir'] == 1 )
                 <p style="display:inline-block; font-size:30px; color:#000;">Si</p>
                 @else
                 <p style="display:inline-block; font-size:30px; color:#000;">NO</p>
                 @endif
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
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>



@stop