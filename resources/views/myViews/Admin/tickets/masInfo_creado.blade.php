@extends('adminlte::page')

@section('title', 'Mas información')


@section('content')
    <div class="content">
        <div class="container">
            <div class="row align-items-stretch no-gutters contact-wrap">
                <div class="col-md-12">
                    <div class="form h-100">
                        <h3>Más información <spa class="spanTitulo">(El ticket quedará en estado "en espera")</span></h3>
                        @if(session('status'))
                        <p class="alert alert-success">{{ Session('status') }}</p>
                        @endif
                        <form action="/masInformacion/ticket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
                         @csrf

                            <div class="row">
                                <div class="col-md-8 form-group mb-3">
                                    <label for="user_id" class="col-form-label">Cliente:</label>
                                    <input type="text" class="form-control" name="user_id" id="user_id"   value="{{$ticket->user->name}}" disabled >
                                </div>

                                <div class="col-md-4 form-group mb-3 ml-auto">
                                    <label for="fecha_inicio" class="col-form-label">Fecha:</label>
                                    <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{$fecha_actual}}"  disabled>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="mensaje" class="col-form-label">Mensaje:</label>
                                    <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$mensaje->mensaje}}</textarea>
                                </div>
                            </div>
                            
                           
                            @if(isset($mensaje->imagen))
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <img src="{{asset('images/masInfo/tickets/'.$mensaje->imagen)}}"
                                        class="img-fluid img-rounded" width="60px"> 
                                        <a href="{{ asset('images/masInfo/tickets/'.$mensaje->imagen) }}" style="font-size:12px;" download >Descargar Imagen</a>   
                                    </div>
                                </div>
                            @else
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <img src="{{asset('images/masInfo/tickets/'.$mensaje->imagen)}}" class="img-fluid img-rounded" width="120px" hidden> 
                                        <a href="{{ asset('images/masInfo/tickets/'.$mensaje->imagen)}}" download hidden>Descargar Imagen</a>   
                                    </div>
                                </div>
                            @endif  

                            <div class="content-responder">
                                <div>
                                    <a href="/form/respuesta/{{$ticket->id}}" class="btn btn-dark btn-volverInfo" style="margin:0;">Volver</a>
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