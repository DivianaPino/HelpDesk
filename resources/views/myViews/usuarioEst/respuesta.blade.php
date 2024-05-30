@extends('adminlte::page')

@section('title', 'Respuesta')

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
          <h3 class="tituloRespuesta">Ticket #{{$idTicket}}: <span class="span_respTkt"> Respondido</span></h3>
          
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
                    <input type="text" class="form-control" name="fecha_respuesta" id="fecha_inicio" value="{{$respuesta['fecha']}}"  disabled>
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
            <p class="text-select">Por favor ingresar si fue útil la respuesta</p>
            <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  placeholder="Ingresa aquí un comentario sobre la respuesta" required>{{old('mensaje')}}</textarea>            
                </div>
            </div>
            <div class="containerSelectCheck">
              <div class="select-container">
                <select class="select-box" name="opcion" id="opcion" required>
                    <option value="">Nivel de satisfacción</option>
                    <option value="Totalmente satisfecho">Totalmente satisfecho</option> 
                    <option value="Muy satisfecho">Muy satisfecho</option>
                    <option value="Neutral">Neutral</option>
                    <option value="Poco satisfecho">Poco satisfecho</option>
                    <option value="Nada satisfecho">Nada satisfecho</option>
                </select>
                <div class="icon-container">
                    <i class="fa-solid fa-caret-down"></i>
                </div>
              </div> 

              <div class="checkbox-container" style="display:none;">
                <input class="checkbox" type="checkbox" id="reabrir" name="reabrir">
                <label class="labelReabrir" for="reabrir" style="color:#000;">Reabrir ticket</label>
             </div>
            </div>

            <div class="row">
                <div class="col-md-12 form-group" style="text-align:center; ">
                  <input type="submit" value="Enviar" class="btnForm btnForm-comentario btn-primary rounded-0 py-2 px-4" >
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

<script>
$(document).ready(function() {
    $('.select-box').change(function() {
        var selectedOption = $(this).val();
        if (selectedOption === "Nada satisfecho" || selectedOption === "Poco satisfecho" || selectedOption === "Neutral") {
            // Muestra el checkbox y su etiqueta si la opción seleccionada es "Nada satisfecho", "Poco satisfecho" o "Neutral"
            $('.checkbox-container').show();
        } else {
            // Oculta el checkbox y su etiqueta si la opción seleccionada no es ninguna de las anteriores
            $('.checkbox-container').hide();
        }
    });
});
</script>


@stop