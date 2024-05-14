@extends('adminlte::page')

@section('title', 'Tickets de área')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Tickets de área</h1>

<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_tktArea" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"  collspacing="0" style="max-width: 1200px ; width:100%; font-size:14px;"  >
                <div class="leyenda_tktArea">
                    <div>
                        <p>Tickets nuevos: <a href="/noasignados"> {{$cant_tkt_nuevos}}</a></p>
                        <p>Tickets abiertos: <a href="/abiertos">{{$cant_tkt_abiertos}}</a></p>
                    </div>
                    <div>
                        <p>Tickets en espera:<a href="/enEspera"> {{$cant_tkt_enEspera}}</a></p>
                        <p>Tickets en revisión:<a href="/enRevision"> {{$cant_tkt_enRevision}}</a></p>
                    </div>
                    <div>
                        <p>Tickets resueltos:<a href="/resueltos"> {{$cant_tkt_resueltos}}</a></p>
                        <p>Tickets reabiertos:<a href="/reabiertos"> {{$cant_tkt_reAbiertos}}</a></p>
                    </div>
                    <div>
                        <p>Tickets cerrados:<a href="/cerrados"> {{$cant_tkt_cerrados}}</a></p>
                        <p>Tickets vencidos:<a href="/vencidos"> {{$cant_tkt_vencidos}}</a></p>
                    </div>
                </div>
               <thead class=" bg-dark text-white" >
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Clasif.</th>
                      <th>Prioridad</th>
                      <th>Estado</th>
                      <th>Agente</th>
                      <th>Creado</th>
                      <th>Caducidad</th>
                      <th>Respondido</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                 
               @foreach ($tickets as $ticket )
   
                    <tr>
                        <td>TK-{{$ticket->id}}</td>
                        <td>{{$ticket->user->name}}</td>
                        <td>{{$ticket->clasificacion->nombre}}</td>

                        <!-- Prioridades -->
                        @if($ticket->prioridad->nombre == "Urgente")
                            <td class="prd_urgente">{{$ticket->prioridad->nombre}}</td>
                        @elseif($ticket->prioridad->nombre == "Alta")
                            <td class="prd_alta">{{$ticket->prioridad->nombre}}</td>
                        @elseif($ticket->prioridad->nombre == "Media")
                            <td class="prd_media">{{$ticket->prioridad->nombre}}</td>
                        @elseif($ticket->prioridad->nombre == "Baja")
                            <td class="prd_baja">{{$ticket->prioridad->nombre}}</td>
                        @endif
                        
                       <!-- Estados -->
                        @if($ticket->estado->nombre == "Nuevo")
                            <td class="nuevo">{{$ticket->estado->nombre}}</td>
                        @elseif($ticket->estado->nombre == "Abierto")
                            <td class="abierto">{{$ticket->estado->nombre}}</td>
                        @elseif($ticket->estado->nombre == "En espera")
                            @forelse($ticket->respMasInfo as $respuesta)
                                @isset($respuesta->mensaje)
                                    <td class="enEspera">{{$ticket->estado->nombre}}<br>(El cliente ha respondido)</td>
                                @endisset
                            @empty
                                <td class="enEspera">{{$ticket->estado->nombre}}</td>
                            @endforelse
                        @elseif($ticket->estado->nombre == "En revisión")
                            <td class="enRevision">{{$ticket->estado->nombre}} <br>(El cliente ha respondido)</td>
                        @elseif($ticket->estado->nombre == "Resuelto")
                            <td class="resuelto">{{$ticket->estado->nombre}}</td>
                        @endif

                        <!-- Agente tecnico asignado -->
                        <td>{{$ticket->asignado_a}}</td>

                        <td class="td-fecha_inicio">{{\Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y')}}</td>

                        <!-- fecha caducidad -->
                        @if($ticket->estado->nombre == "En espera")
                            <td>En pausa</td>
                        @else
                            <td>{{\Carbon\Carbon::parse($ticket->fecha_caducidad)}}</td>
                        @endif

                        <!-- Fecha de respuesta -->
                        @if ($ticket->respuestas->count() > 0)
                        <td>{{\Carbon\Carbon::parse($ticket->ultimaRespuesta['fecha'])->format('d-m-Y')}}</td>
                        @else
                        <td>---</td>
                        @endif

                        <!-- Botones - opciones -->
                        <td class="content-btnOpciones" >
                            <a class="btn btn-info" href="/detalles/{{$ticket->id}}">Ver</a>
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

<script>
$(document).ready(function() {
    $('#tabla_tktArea').DataTable({

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

        "order": [[7, 'desc']],
        "columnDefs": [
            {
                "targets": 7, 
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
@stop