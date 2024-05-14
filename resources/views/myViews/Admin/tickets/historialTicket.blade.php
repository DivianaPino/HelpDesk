@extends('adminlte::page')

@section('title', 'Historial del ticket')

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
                        <h3>Historial - ticket #{{$ticket_id}}</h3>
                        @if(session('status'))
                            <p class="alert alert-success">{{ Session('status') }}</p>
                        @endif
                        <form action="" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
                         @csrf

                            <div class="row">
                                <div class="col-md-12 form-group mb-3">
                                    <label for="mensaje" class="col-form-label" style="color: #0E6251; font-size:20px;">Incidente:</label>
                                    <span style="display: block; font-size:14px;">{{$ticket->created_at->format('d-m-Y H:i:s')}}</span>
                                    <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$ticket['mensaje']}}</textarea>
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
                            @else
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                    <img src="{{asset('images/tickets/'.$ticket->imagen)}}" class="img-fluid img-rounded" width="120px" hidden> 
                                        <a href="{{ asset('images/tickets/'.$ticket->imagen) }}" download hidden>Descargar Imagen</a>   
                                    </div>
                                </div>
                            @endif 
                            
                         

                            @foreach($masInfo as $masInf)

                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mensaje" class="col-form-label" style="color: #979A9A;">Mensaje del Agente Técnico:</label>
                                        <span style="display: block; font-size:14px;">{{Carbon\Carbon::parse($masInf->fecha)->format('d-m-Y H:i:s')}}</span>
                                        <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$masInf['mensaje']}}</textarea>
                                    </div>
                                </div>

                                @if(isset($masInf->imagen))
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <img src="{{asset('images/masInfo/tickets/'.$masInf->imagen)}}"
                                            class="img-fluid img-rounded" width="120px"> 

                                        <a href="{{ asset('images/masInfo/tickets/'.$masInf->imagen) }}" download>Descargar Imagen</a>   
                                    </div>
                                </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-3">
                                        <img src="{{asset('images/masInfo/tickets/'.$masInf->imagen)}}" class="img-fluid img-rounded" width="120px" hidden> 
                                            <a href="{{ asset('images/masInfo/tickets/'.$masInf->imagen) }}" download hidden>Descargar Imagen</a>   
                                        </div>
                                    </div>
                                @endif 



                                @foreach($respMasInfo as $resp)
                                
                                    @if($resp->masInfo_id == $masInf->id)
                                        <div class="row">
                                            <div class="col-md-12 form-group mb-3">
                                                <label for="mensaje" class="col-form-label" style="color: #5499C7;">Respuesta del cliente:</label>
                                                <span style="display: block; font-size:14px;">{{Carbon\Carbon::parse($resp->fecha)->format('d-m-Y H:i:s')}}</span>
                                                <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$resp['mensaje']}}</textarea>
                                            </div>
                                        </div>

                                        
                                        @if(isset($resp->imagen))
                                        <div class="row">
                                            <div class="col-md-12 form-group mb-3">
                                                <img src="{{asset('images/respMasInfo/tickets/'.$resp->imagen)}}"
                                                    class="img-fluid img-rounded" width="120px"> 

                                                <a href="{{ asset('images/respMasInfo/tickets/'.$resp->imagen) }}" download>Descargar Imagen</a>   
                                            </div>
                                        </div>
                                        @else
                                            <div class="row">
                                                <div class="col-md-12 form-group mb-3">
                                                <img src="{{asset('images/respMasInfo/tickets/'.$resp->imagen)}}" class="img-fluid img-rounded" width="120px" hidden> 
                                                    <a href="{{ asset('images/respMasInfo/tickets/'.$resp->imagen) }}" download hidden>Descargar Imagen</a>   
                                                </div>
                                            </div>
                                        @endif 

                                    @endif

                                @endforeach

                            @endforeach

                            @if($solucion)
                                <hr style=" border:none; height: 3px; background-color:#76D7C4;">
                                
                                <div class="row">
                                    <div class="col-md-12 form-group mb-3">
                                        <label for="mensaje" class="col-form-label titulo-resolucion">Resolución del incidente</label>
                                        <span style="display: block; font-size:14px;">{{Carbon\Carbon::parse($solucion->fecha)->format('d-m-Y H:i:s')}}</span>
                                        <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  disabled>{{$solucion['mensaje']}}</textarea>
                                    </div>
                                </div>
                                
                                @if(isset($solucion->imagen))
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-3">
                                            <img src="{{asset('images/respuestas/tickets/'.$solucion->imagen)}}"
                                                class="img-fluid img-rounded" width="120px"> 

                                            <a href="{{ asset('images/respuestas/tickets/'.$solucion->imagen) }}" download>Descargar Imagen</a>   
                                        </div>
                                    </div>
                                @else
                                    <div class="row">
                                        <div class="col-md-12 form-group mb-3">
                                        <img src="{{asset('images/respuestas/tickets/'.$solucion->imagen)}}" 
                                        class="img-fluid img-rounded" width="120px" hidden> 
                                            <a href="{{ asset('images/respuestas/tickets/'.$solucion->imagen) }}" download hidden>Descargar Imagen</a>   
                                        </div>
                                    </div>
                                @endif 

                                <hr style="border:none; height: 3px; background-color:#76D7C4;">

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