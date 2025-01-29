@extends('adminlte::page')

@section('title', 'Reasignar ticket')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div>
    <div class="card">
        <div class="card-body car-body-reasignar"> 
            <div class="content-btnVolverTable volver-Reasignar">
              <a style="margin-top:8px;" href="/area_usuario/tickets" class="btn btn-dark btn-volver">
              <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
          </div>
            <h3 class="titulo_prin" style="margin:0px; padding:0px;">Reasignar ticket #{{$ticket->id}}</h3>
           
            @if(session('status'))
              <p class="alert alert-success" style="margin-top:20px;">{{ Session('status') }}</p>
            @endif
            <form action="/guardar/reasignacion/ticket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
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
                    <div class="col-md-6 form-group mb-3">
                        <label for="asunto" class="col-form-label">Asunto:</label>
                        <input type="text" class="form-control" name="asunto" id="asunto" value="{{$ticket->asunto}}" disabled >
                    </div>

                    <div class="col-md-6 form-group mb-3">
                        <label for="tecnico" class="col-form-label">Técnico asignado:</label>
                        <input type="text" class="form-control" name="tecnico" id="tecnico" value="{{$ticket->asignado_a}}" disabled >
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

                <div class="row content-selectResignar">
                    <div class="col-md-6 form-group mb-3 centrado reasignarArea">
                        <label for="area" class="col-form-label">Área:</label>
                        <!--select areas  -->
                        <select id="areaSelect" name="area" onchange="cargarTecnicos(this)" class="form-select form-select-sm col-6 custom-select">
                            <option value="">Seleccionar área...</option> 
                            @foreach($areas as $area)
                                <option value="{{$area->id}}">{{$area->nombre}}</option> 
                            @endforeach
                        </select>

                        <div class="msj_error" style="color:#c40f0f; font-weight: 600; margin-top:5px;">
                            @error('area')
                                {{$message}}
                            @enderror
                        </div>
                    </div>

               
                    
                    <div class="col-md-6 form-group mb-3 centrado reasignarTecnico">
                        <label for="tecnico" class="col-form-label">Técnico de soporte:</label>
                        <!--select tecnicos  -->
                        <select id="tecnicoSelect" name="tecnico" class="form-select form-select-sm col-6 custom-select">
                            <option value="">Seleccionar técnico...</option> 
                        </select>

                        <div class="msj_error" style="color:#c40f0f; font-weight: 600; margin-top:5px;">
                            @error('tecnico')
                                {{$message}}
                            @enderror
                        </div>
                    </div>
                </div>
                <div class="col-md-12 form-group btnReasignar" >
                    <input type="submit" value="REASIGNAR" id="btn-reasignar" class="btn btn-info btn-reasignarTec"  >
                </div>

            </form>
        </div>
     </div>
</div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/styles.css">    
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.8/css/dataTables.bootstrap5.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>




<script>

    function cargarTecnicos(selectAreas){

        let areaId=selectAreas.value;
      
        fetch(`/area/${areaId}/agentes`)
            .then(function (response){
                return response.json();
            })

            .then(function(jsonData){
               
                buildTecnicosSelect(jsonData);
            });
    }

    function buildTecnicosSelect(jsonTecnicos){

        let tecnicosSelect = document.getElementById('tecnicoSelect');
        limpiarSelect(tecnicosSelect);

    // Asegúrate de que los IDs sean únicos aquí
    const ids = Array.from(new Set(jsonTecnicos.map(tecnico => tecnico.id)));
    
    ids.forEach(function(id){
        const tecnico = jsonTecnicos.find(tecnico => tecnico.id === id);
        if (tecnico) {
            let opcion = document.createElement('option');
            opcion.value = tecnico.id;
            opcion.innerHTML = tecnico.name;
            tecnicosSelect.append(opcion);
        }
    });
    }

    function limpiarSelect(select){
        while(select.options.length > 1){
            select.remove(1);
        }
        
    }

</script>


 
@stop


