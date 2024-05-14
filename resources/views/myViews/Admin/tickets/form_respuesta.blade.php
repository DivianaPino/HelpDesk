@extends('adminlte::page')

@section('title', 'Formulario respuesta')


@section('content')

  <div class="content ">
    <div class="container ">
      <div class="row align-items-stretch no-gutters contact-wrap ">
        <div class="col-md-12 ">
          <div class="form h-100  content-fondo" >
              <div class="content-btnVolver">
                <a style="margin-top:8px;" href="{{ session('previous_url') }}" class="btn btn-dark btn-volverInfo">
                  <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
              </div> 
              <h3 class="tituloRespuesta">Formulario - Respuesta de ticket #{{$idTicket}}
                <a  href="/historial/ticket/{{$ticket->id}}" class="btn btn-primary btn-historial">Historial</a>
              </h3>
              @if(session('status'))
                <p class="alert alert-success">{{ Session('status') }}</p>
              @endif
              <form action="/respuesta/ticket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
              @csrf

                <div class="cuadro1">
                      INFORMACIÓN DEL TICKET
                </div>
                <div class="row">
                  <div class="col-md-8 form-group mb-3" >
                    <label for="user_id" class="col-form-label"  >Usuario:</label>
                    <input type="text" class="form-control inputForm" name="user_id" id="user_id"   value="{{$ticket->user->name}}"   disabled >
                  </div>
                  <div class="col-md-4 form-group mb-3">
                    <label for="estado_id" class="col-form-label">Estado:</label>
                    <input type="text" class="form-control inputForm" name="estado_id" id="estado_id" value="{{$ticket->estado->nombre}}" disabled>
                  </div>
                </div>
              

                <div class="row">
                  <div class="col-md-12 form-group mb-3">
                    <label for="asunto" class="col-form-label">Asunto:</label>
                    <input type="text" class="form-control inputForm" name="asunto" id="asunto" value="{{$ticket->asunto}}" placeholder="Escribe el asunto" disabled >
                  </div>
                </div>
                
                <div class="row">
                  <div class="col-md-12 form-group mb-3">
                    <label for="mensaje" class="col-form-label">Mensaje:</label>
                    <textarea class="form-control inputForm" name="mensaje" id="mensaje" cols="30" rows="4"  placeholder="Escribe tu incidencia" disabled>{{$ticket->mensaje}}</textarea>
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
                @endif

                <div class="cuadro2">
                    RESPONDER TICKET AQUÍ 
                      <i class="fa-solid fa-arrow-down iconoResponder"  ></i>
                </div>
                
                <div class="row">
                  <div class="col-md-12 form-group mb-3">
                    <label for="mensaje" class="col-form-label">Mensaje:</label>
                    <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  placeholder="Escribe la respuesta" >{{old('mensaje')}}</textarea>
                      
                  </div>
                </div>

                <div class="row">
                  <div class="col-md-6 form-group mb-3">
                      <input type="file" name="imagen" accept="image/*" >
                  </div>
                </div>

                <div class="msj_error">
                  @error('imagen')
                    {{$message}}
                  @enderror
                </div>
      

                <div class="content-responder">
                    <div>
                      <input type="submit" value="Responder" class="btn-primary rounded-0 py-2 px-4 btnResponder"  >
                    </div>

                    <div class="content-masInformacion">
                      <a class="masInformacion" href="/masInformación/{{$ticket->id}}" >¿Necesitas más información del ticket?</a>
                    </div>
                </div>

              </form>
            
            </div>
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

    <script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

@stop