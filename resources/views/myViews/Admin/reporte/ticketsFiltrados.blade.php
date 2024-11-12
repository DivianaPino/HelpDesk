@extends('adminlte::page')

@section('title', 'Reporte')

@section('content_header')
   
@stop

@section('content')

<h1 class="titulo_prin">Reportes</h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            
            <div class="form_reporteCompleto">
                <form action="{{ route('reporteCompletoPDF') }}" method="POST" class="formulario-filtrar">
                @csrf
                    <button type="submit" class="btn btn-primary btn-adminReporteCompleto">
                         Descargar reporte completo 
                    </button>
                </form>
            </div>
            <div>
                <form id="reporteForm" action=""  method="POST" class="formulario-reporte"> 
                    @csrf   
                    <div class="admin-reporte">
                        <div class="fechasReporte"> 
                            @if(session()->has('fecha_inicial'))
                                <div class="fecha-inicial">
                                    <label for="fecha_inicial">Fecha inicial:</label>
                                    <input type="date" id="fecha_inicial" name="fecha_inicial" value="{{ session('fecha_inicial') }}" class="form-control">
                                </div>
                            @else
                                <div class="fecha-inicial">
                                    <label for="fecha_inicial">Fecha inicial:</label>
                                    <input type="date" id="fecha_inicial" name="fecha_inicial" class="form-control">
                                </div>
                            @endif
                            @if(session()->has('fecha_fin'))
                                <div class="fecha-fin">
                                    <label for="fecha_fin">Fecha fin:</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" value="{{ session('fecha_fin') }}" class="form-control">
                                </div>
                            @else
                                <div class="fecha-fin">
                                    <label for="fecha_fin">Fecha fin:</label>
                                    <input type="date" id="fecha_fin" name="fecha_fin" class="form-control">
                                </div>
                            @endif
                        </div>       
                        <button type="button" id="filtrar"  class="btn btn-success btn-adminReporteRango">
                            Filtrar
                        </button>
                        <button type="button" id="descargar"  class="btn btn-adminReporte">
                            Descargar reporte (rango)
                        </button>
                    </div>
                </form>

            </div>
            <table id="tabla_tickets" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"  style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Clasif.</th>
                      <th>Asunto</th>
                      <th>Agente</th>
                      <th>Prioridad</th>
                      <th>Estado</th>
                      <th>Creado</th>
                      <th class="th-respondido">Respondido</th>
                      <th>Caducidad</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                    @foreach ($tickets as $ticket )
                        <tr>
                            <td>{{$ticket->id}}</td>
                            <td>{{$ticket->user->name}}</td>
                            <td>{{$ticket->clasificacion->nombre}}</td>

                            <td>{{$ticket->asunto}}</td>

                            @if(empty($ticket->asignado_a))
                            <td>---</td>
                            @else
                            <td>{{$ticket->asignado_a}}</td>
                            @endif
                            
                            @if($ticket->prioridad->nombre == "Urgente")
                              <td class="prd_urgente">{{$ticket->prioridad->nombre}}</td>
                            @elseif($ticket->prioridad->nombre == "Alta")
                              <td class="prd_alta">{{$ticket->prioridad->nombre}}</td>
                            @elseif($ticket->prioridad->nombre == "Media")
                              <td class="prd_media">{{$ticket->prioridad->nombre}}</td>
                            @elseif($ticket->prioridad->nombre == "Baja")
                              <td class="prd_baja">{{$ticket->prioridad->nombre}}</td>
                            @endif

                            @if($ticket->estado->nombre == "Nuevo")
                              <td class="nuevo">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Abierto")
                              <td class="abierto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "En espera")
                              <td class="enEspera">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "En revisión")
                              <td class="enRevision">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Resuelto")
                              <td class="resuelto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Reabierto")
                              <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Cerrado")
                              <td class="cerrado">{{$ticket->estado->nombre}}</td>
                            @endif

                            <td>{{\Carbon\Carbon::parse($ticket->fecha_inicio)->format('d-m-Y')}}</td>

                            @if ($ticket->respuestas->count() > 0)
                              <td>{{ \Carbon\Carbon::parse($ticket->respuestas->last()['updated_at']) }}</td>
                            @else
                              <td>---</td>
                            @endif

                            @if($ticket->estado->nombre == "En espera")
                              <td>En pausa</td>
                            @else
                              <td>{{\Carbon\Carbon::parse($ticket->fecha_caducidad)->format('d-m-Y')}}</td>
                            @endif
                             
                            <td class="content-btnOpciones" >
                              @if($ticket->estado->nombre == "Nuevo")
                                <a class="btn btn-info" href="/detalles/{{$ticket->id}}">Ver</a>
                              @elseif($ticket->estado->nombre == "Abierto" ||$ticket->estado->nombre == "En espera" || $ticket->estado->nombre == "Reabierto" || $ticket->estado->nombre == "En revisión" )
                                <a class="btn btn-info" href="/detalles/{{$ticket->id}}">Ver</a>
                                <a class="btn btn-warning" href="/reasignar/ticket/{{$ticket->id}}">Reasignar</a>
                              @else
                                <a class="btn btn-info" href="/historial/ticket/{{$ticket->id}}" >Ver</a>
                              @endif

                                <form action="{{route('tickets.destroy',$ticket->id)}}" method="POST" class="formulario-eliminar">
                                @csrf
                                @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
                                </form> 
                            </td> 
                             
                        </tr>
                    @endforeach
               </tbody>
            </table>
        </div>
     </div>
</div>

@stop

@section('css')
<link rel="stylesheet" href="/css/styles.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">

@stop

@section('js')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>

<script  type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('eliminar') == 'ok')
  <script>
      Swal.fire({
      title: "¡Eliminado!",
      text: "El ticket se elimino con éxito",
      icon: "success"
      });
  </script>
@endif


<script>

    $(".formulario-eliminar").submit(function(e){
        e.preventDefault();

        Swal.fire({
        title: "¿Estás seguro?",
        text: "El ticket se eliminará definitivamente",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "¡Si, eliminar!",
        cancelButtonText: "Cancelar"
        }).then((result) => {
        if (result.isConfirmed) {

            this.submit();
        }
        });
    });
    
</script>

<script>
$(document).ready(function() {
    $('#tabla_tickets').DataTable({

        responsive:true,

        //Opciones de paginación
        "lengthMenu": [
            [10, 30, 50, -1],
            [10, 30, 50, "All"]
        ],
        "language":{
            "info": "_TOTAL_ registros", 
            "search":"Buscar",
            "paginate": {
                "next": "Siguiente",
                "previous":"Anterior",
            },
            "lengthMenu":'Mostrar <select>'+
                        '<option value="10">10</option>'+
                        '<option value="30">30</option>'+
                        '<option value="50">50</option>'+
                        '<option value="-1">Todos</option>'+
                        '</select> registros',
            "loadingRecords": "Cargando...",
            "processing": "Procesando...",
            "emptyTable": "No hay datos",
            "zeroRecords":"No hay coincidencias",
            "infoEmpty": "",
            "infoFiltered":"",
        },

        "order": [[0, 'asc']],
        "columnDefs": [
            {
                "targets": [7,8], 
                "type": "date",
                "render": function (data, type, row) {
                    // Verificar si 'data' es una fecha válida
                    if (moment(data, 'YYYY-MM-DD HH:mm:ss', true).isValid()) {
                        // Si es una fecha válida, convertirla al formato 'DD-MM-YYYY' para la visualización
                        if (type === 'display') {
                            return moment(data, 'YYYY-MM-DD HH:mm:ss').format('DD-MM-YYYY');
                        }
                    }
                    // Para la ordenación y otros usos, devuelve el valor original
                    // Esto incluye el caso en que 'data' no es una fecha válida, por lo que se devuelve tal cual
                    return data;
                }
                
            }
        ]
        
        });
});
</script>

<script>
    $.ajax({
    url: "{{ route('reporteCompletoPDF') }}",
    type: "POST",
    data: {},
    success: function(response) {
        var blob = new Blob([response], {type: 'application/pdf'});
        var link = document.createElement("a");
        link.href = window.URL.createObjectURL(blob);
        link.download = "reporte-tickets.pdf";
        link.click();
    }
});
</script>

<script>
        document.getElementById('filtrar').addEventListener('click', function() {
            document.getElementById('reporteForm').action = "/tickets/filtrados";
            document.getElementById('reporteForm').submit();
        });

        document.getElementById('descargar').addEventListener('click', function() {
            document.getElementById('reporteForm').action = "/reporteRango-pdf";
            document.getElementById('reporteForm').submit();
        });
</script>




@stop














