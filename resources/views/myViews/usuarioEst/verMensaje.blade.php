@extends('adminlte::page')

@section('title', 'Mensaje')

@section('content')
  <div class="content">
        <div class="container">
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-12">
                   <div class="form h-100">
                         <div class="content-btnVolver">
                            <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volverInfo">
                            <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
                        </div>
                        <h3>Mensaje - ticket #{{$idTicket}}</h3>
                        @if(session('status'))
                            <p class="alert alert-success">{{ Session('status') }}</p>
                        @endif
                        <form action="/respuesta/mas_info/ticket/{{$idTicket}}/{{$idMensaje}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
                            @csrf
                            
                
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mensaje" class="col-form-label">Mensaje:</label>
                                        <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$mensaje['mensaje']}}</textarea>
                                    </div>
                                </div>

                
                                @if(isset($mensaje['imagen']))
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-3">
                                            <img src="{{asset('images/masInfo/tickets/'.$mensaje['imagen'])}}"
                                                class="img-fluid img-rounded" width="120px"> 

                                            <a href="{{ asset('images/masInfo/tickets/'.$mensaje['imagen'])}}" download>Descargar Imagen</a>   
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                            <div class="col-md-12 form-group mb-3">
                                                <img src="{{asset('images/masInfo/tickets/'.$mensaje['imagen'])}}"
                                                    class="img-fluid img-rounded" width="120px" hidden> 

                                                <a href="{{ asset('images/masInfo/tickets/'.$mensaje['imagen'])}}" download hidden>Descargar Imagen</a>   
                                            </div>
                                    </div>
                                @endif
                            
                     
        
                            <div class="cuadro2">
                            RESPONDER AQUÍ <i class="fa-solid fa-arrow-down iconoResponder"  ></i>
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
                            <div>
                            
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