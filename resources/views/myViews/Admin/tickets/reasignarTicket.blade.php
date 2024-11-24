@extends('adminlte::page')

@section('title', 'Reasignar ticket')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div>
    <div class="card">
        <div class="card-body car-body-reasignar"> 
            <div class="content-btnVolver volver-Reasignar">
              <a style="margin-top:8px;" href="/tickets" class="btn btn-dark btn-volver">
              <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
          </div>
            <h3 class="titulo_prin" style="margin:0px; padding:0px;">Reasignar ticket #{{$ticket->id}}</h3>
           
            @if(session('status'))
              <p class="alert alert-success" style="margin-top:20px; background-color:#3de64b;">{{ Session('status') }}</p>
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
                          <label for="area_id" class="col-form-label">Área:</label>
                          <input type="text" class="form-control" name="area_id" id="area_id" value="{{ $ticket->area->nombre }}" disabled>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                          <label for="servicio_id" class="col-form-label">Servicio:</label>
                          <input type="text" class="form-control" name="servicio_id" id="servicio_id" value="{{ $ticket->servicio->nombre }}" disabled>
                    </div>
                    <div class="col-md-4 form-group mb-3">
                      <label for="prioridad_id" class="col-form-label">Prioridad:</label>
                      <input type="text" class="form-control" name="prioridad_id" id="prioridad_id" value="{{ $ticket->prioridad->nombre }}" disabled>
                    </div>
                  </div>     
                  
                  <div class="row">
                   @if(is_null($ticket->asignado_a))
                      <div class="col-md-6 form-group mb-3">
                        <label for="asignado_a" class="col-form-label">Técnico asignado:</label>
                        <input type="text" class="form-control" name="asignado_a" id="asignado_a" value="Sin asignar" disabled>
                      </div>
                    @else
                     <div class="col-md-6 form-group mb-3">
                        <label for="asignado_a" class="col-form-label">Técnico asignado:</label>
                        <input type="text" class="form-control" name="asignado_a" id="asignado_a" value="{{$ticket->asignado_a}}" disabled>
                      </div>
                    @endif

                    <div class="col-md-6 form-group mb-3">
                      <label for="estado_id" class="col-form-label">Estado:</label>
                      <input type="text" class="form-control" name="estado_id" id="estado_id" value="{{$ticket->estado->nombre}}" disabled>
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
                    <textarea class="form-control inputForm" name="mensaje" id="msj" cols="30" rows="4"  disabled>{{$ticket->mensaje}}</textarea>
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
                    <div class="col-md-6 form-group mb-3 centrado  reasignarArea">
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
    let areaId = selectAreas.value;
    
    // Verificar si es la opción "Seleccionar área..."
    if(areaId === "") {
        // Limpiar completamente el select de tecnicos
        let tecnicoSelect = document.getElementById('tecnicoSelect');
        tecnicoSelect.innerHTML = '<option value="">Seleccionar tecnico...</option>';
    } else {
        // Cargar tecnicos para la área seleccionada
        fetch(`/area/${areaId}/agentes`)
            .then(function (response) {
                if (!response.ok) { // Comprobamos si la respuesta es OK (200-299)
                    throw new Error('Error al cargar tecnicos');
                }
                return response.json();
            })
            .then(function(jsonData){
                if (jsonData) {
                    buildTecnicosSelect(jsonData);
                } else {
                    console.error('Datos de tecnicos inválidos:', jsonData);
                }
            })
            .catch(function(error) {
                console.error('Error al tecnicos:', error);
            });
    }
}


function buildTecnicosSelect(jsonTecnicos){
    let tecnicosSelect = document.getElementById('tecnicoSelect');
    limpiarSelect(tecnicosSelect);

    if (Array.isArray(jsonTecnicos)) {
        jsonTecnicos.forEach(function(tecnico){
            let opcion = document.createElement('option');
            opcion.value = tecnico.id;
            opcion.innerHTML = tecnico.name;
            tecnicosSelect.appendChild(opcion);
        });
    } else {
        console.error('Los datos de tecnicos no son válidos:', jsonTecnicos);
    }
}


function limpiarSelect(select){
    while(select.options.length > 1){
        select.remove(1);
    }
}
</script>

<script>
   document.addEventListener('DOMContentLoaded', function() {
         document.getElementById('btn-reasignar').addEventListener('click', function(e) {
            e.preventDefault();
            this.disabled = true;
            this.value = 'Enviando...';
            this.form.submit();
         });

         document.addEventListener('ajax:success', function(event) {
            if (event.detail.status === 200) {
               alert('Ticket reasignado exitosamente!');
               document.getElementById('btn-reasignar').disabled = true;
               document.getElementById('btn-reasignar').value = 'Enviado';
            }
         });
   });
</script>


 
@stop


