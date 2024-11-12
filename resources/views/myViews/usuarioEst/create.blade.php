@extends('adminlte::page')

@section('title', 'Crear Ticket')


@section('content')

<div class="content">
    <div class="container">
      <div class="row align-items-stretch no-gutters contact-wrap">
        <div class="col-md-12">
          <div class="form h-100">
            <h3>Crear Ticket</h3>
            <form action="{{ url('usuario/tickets') }}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
            @csrf
              <div class="row">
                <div class="col-md-8 form-group mb-3">
                  <label for="user_id" class="col-form-label">Usuario:</label>
                  <input type="text" class="form-control" name="user_id" id="user_id"   value="{{$usuarios->name}}" disabled >
                </div>
                <div class="col-md-4 form-group mb-3 ml-auto">
                  <label for="fecha_inicio" class="col-form-label">Fecha:</label>
                  <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{$fecha_actual}}"  disabled>
                </div>
              </div>
              
              

              <div class="row">
            
                    <div class="col-md-3 form-group mb-3 ">
                        <label for="area" class="col-form-label">Área:</label>
                        <!--select areas  -->
                        <select id="areaSelect" name="area_id" onchange="cargarServicios(this)" class="custom-select">
                          <option value="">Seleccionar área...</option> 
                          @foreach($areas as $area)
                              <option value="{{$area->id}}">{{$area->nombre}}</option> 
                          @endforeach
                      </select>

                        <div class="msj_error">
                            @error('area')
                                {{$message}}
                            @enderror
                        </div>
                    </div>

               
                    
                    <div class="col-md-4 form-group mb-3">
                        <label for="servicio" class="col-form-label">Servicio:</label>
                        <!--select servicios  -->
                        <select id="servicioSelect" name="servicio_id" class="custom-select">
                            <option value="">Seleccionar servicio...</option> 
                        </select>

                        <div class="msj_error" >
                            @error('servicio')
                                {{$message}}
                            @enderror
                        </div>
                    </div>
               

                <div class="col-md-3 form-group mb-3">
                  <label for="prioridad_id" class="col-form-label">Prioridad:</label>
                  <select class="custom-select" id="prioridad_id" name="prioridad_id" >
                    <option selected value="">Seleccionar</option>
                      @foreach($prioridades as $prioridad)
                      <option value="{{ $prioridad->id }}" {{old('prioridad_id') == $prioridad->id ? 'selected' : '' }}>{{ $prioridad->nombre }}</option>
                      @endforeach
                  </select>
                  
              
                  <div class="msj_error">
                    @error('prioridad_id')
                      {{$message}}
                    @enderror
                  </div>
                </div>
                <div class="col-md-2 form-group mb-3">
                  <label for="estado_id" class="col-form-label">Estado:</label>
                  <input type="text" class="form-control" name="estado_id" id="estado_id" value="{{$estadoFirst->nombre}}" disabled>
                </div>
             
              </div>

            

              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="asunto" class="col-form-label">Asunto:</label>
                  <input type="text" class="form-control" name="asunto" id="asunto" value="{{old('asunto')}}" placeholder="Escribe el asunto" >
                </div>
              </div>
              

              <div class="msj_error">
                @error('asunto')
                  {{$message}}
                @enderror
              </div>
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="mensaje" class="col-form-label">Mensaje:</label>
                  <textarea class="form-control" name="mensaje" id="mensaje" cols="30" rows="4"  placeholder="Escribe tu incidencia" >{{old('mensaje')}}</textarea>
                </div>
              </div>
             
              <div class="msj_error">
                @error('mensaje')
                  {{$message}}
                @enderror
              </div>

              <div class="row">
                <div class="col-md-6 form-group mb-3 content-file">
                    <input type="file" name="imagen" accept="image/*" >
                </div>
              </div>

              <div class="msj_error">
                @error('imagen')
                  {{$message}}
                @enderror
              </div>

              <div class="row">
                  <div class="col-md-12 form-group mb-3">
                      <input type="submit" id="submitButton" value="Enviar incidencia" class="btnForm btn-primary rounded-0 py-2 px-4" >
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
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>


<script>
  document.addEventListener('DOMContentLoaded', function() {
      document.getElementById('submitButton').addEventListener('click', function(e) {
          e.preventDefault();
          this.disabled = true;
          this.value = 'Enviando incidencia...';
          this.form.submit();
      });

      document.addEventListener('ajax:success', function(event) {
          if (event.detail.status === 200) {
              alert('Ticket enviado exitosamente!');
              document.getElementById('submitButton').disabled = true;
              document.getElementById('submitButton').value = 'Enviado';
          }
      });
  });
</script>
<script>

function cargarServicios(selectAreas){
    let areaId = selectAreas.value;
    
    // Verificar si es la opción "Seleccionar área..."
    if(areaId === "") {
        // Limpiar completamente el select de servicios
        let servicioSelect = document.getElementById('servicioSelect');
        servicioSelect.innerHTML = '<option value="">Seleccionar servicio...</option>';
    } else {
        // Cargar servicios para la área seleccionada
        fetch(`/servicios/area/${areaId}`)
            .then(function (response) {
                if (!response.ok) { // Comprobamos si la respuesta es OK (200-299)
                    throw new Error('Error al cargar servicios');
                }
                return response.json();
            })
            .then(function(jsonData){
                if (jsonData) {
                    buildServiciosSelect(jsonData);
                } else {
                    console.error('Datos de servicios inválidos:', jsonData);
                }
            })
            .catch(function(error) {
                console.error('Error al cargar servicios:', error);
            });
    }
}


function buildServiciosSelect(jsonServicios){
    let serviciosSelect = document.getElementById('servicioSelect');
    limpiarSelect(serviciosSelect);

    if (Array.isArray(jsonServicios)) {
        jsonServicios.forEach(function(servicio){
            let opcion = document.createElement('option');
            opcion.value = servicio.id;
            opcion.innerHTML = servicio.nombre;
            serviciosSelect.appendChild(opcion);
        });
    } else {
        console.error('Los datos de servicios no son un arreglo válido:', jsonServicios);
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
    const areaSelect = document.getElementById('areaSelect');
    const servicioSelect = document.getElementById('servicioSelect');

    // Deseablecer el select de servicios por defecto
    servicioSelect.disabled = true;

    // Manejar eventos de cambio en el select de áreas
    areaSelect.addEventListener('change', function() {
        servicioSelect.disabled = false;
    });

    // Manejar eventos de teclado
    areaSelect.addEventListener('keydown', function(e) {
        if (e.key === 'Tab') {
            e.preventDefault();
            servicioSelect.focus();
        }
    });

    // Manejar eventos de blur en el select de áreas
    areaSelect.addEventListener('blur', function() {
        servicioSelect.disabled = false;
    });
});

</script>



@stop