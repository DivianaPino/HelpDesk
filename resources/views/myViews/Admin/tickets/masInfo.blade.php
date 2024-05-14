@extends('adminlte::page')

@section('title', 'Más información')


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
                                    <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  placeholder="Escribe la información que necesitas" >{{old('mensaje')}}</textarea>
                                </div>
                            </div>
                            
                            <div class="msj_error">
                                @error('mensaje')
                                {{$message}}
                                @enderror
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
                                    <input type="submit" value="Enviar" class="btn-primary  rounded-0 py-2 px-4 btnResponder">
                                </div>

                                <div class="">
                                    <a href="/form/respuesta/{{$ticket->id}}" class="btn btn-dark btn-volverInfo">Volver</a>
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