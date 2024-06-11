@extends('adminlte::page')

@section('title', 'Mis tickets')

@section('content_header')

@stop

@section('content')
<h1 class="titulo_prin">Mis tickets</h1>
@if(session('status'))
    <p class="alert alert-success">{{ Session('status') }}</p>
@endif
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_misTickets" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%;" >
                <div class="leyenda_tktArea">
                    <div class="col-leyenda">
                        <p>Tickets abiertos: <a href="/mis_tickets/abiertos">{{$cant_tkt_abiertos}}</a></p>
                        <p>Tickets en espera:<a href="/mis_tickets/enEspera"> {{$cant_tkt_enEspera}}</a></p>
                    </div>
                    <div class="col-leyenda">
                        <p>Tickets en revisión:<a href="/mis_tickets/enRevision"> {{$cant_tkt_enRevision}}</a></p>
                        <p>Tickets resueltos:<a href="/mis_tickets/resueltos"> {{$cant_tkt_resueltos}}</a></p>
                    </div>

                    <div class="col-leyenda">
                        <p>Tickets reabiertos:<a href="/mis_tickets/reabiertos"> {{$cant_tkt_reAbiertos}}</a></p>
                        <p>Tickets cerrados:<a href="/mis_tickets/cerrados"> {{$cant_tkt_cerrados}}</a></p>
                    </div>
                    <div class="col-leyenda">
                        <p>Tickets vencidos:<a href="/mis_tickets/vencidos"> {{$cant_tkt_vencidos}}</a></p>
                    </div>
                </div>
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
                            <td>TK-{{$ticket->id}}</td>
                            <td>{{$ticket->user->name}}</td>
                            <td>{{$ticket->clasificacion->nombre}}</td>
                            <td>{{$ticket->asunto}}</td>
                            <td>{{$ticket->asignado_a}}</td>
                            
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
                                @php
                                    $ultimoMsj = $ticket->masInformacions->last();
                                    $ultimaResp = $ticket->respMasInfo->last();
                                @endphp

                                @if(isset($ultimoMsj))
                                   @if($ultimoMsj['id'] == $ultimaResp['id'])
                                        <td class="enRevision">En revisión<br>(El cliente ha respondido)</td>
                                    @else
                                        <td class="enEspera">{{$ticket->estado->nombre}}<br>(Esperando respuesta del cliente)</td>
                                    @endif
                                @endif
                            @elseif($ticket->estado->nombre == "En revisión")
                                <td class="enRevision">{{$ticket->estado->nombre}} <br>(El cliente ha respondido)</td>
                            @elseif($ticket->estado->nombre == "Resuelto")
                                <td class="resuelto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Reabierto")
                                <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                            @endif

                            <!-- fecha de inicio -->
                            <td>{{\Carbon\Carbon::parse($ticket->fecha_inicio)->format('d-m-Y')}}</td>

                            <!-- fecha de respuesta -->
                             @if ($ticket->respuestas->count() > 0)
                                <td>{{ \Carbon\Carbon::parse($ticket->respuestas->last()['updated_at']) }}</td>
                             @else
                                <td>---</td>
                             @endif
                                 
                             
                              <!-- fecha caducidad -->
                             @if($ticket->estado->nombre == "En espera")
                                <td>En pausa</td>
                             @else
                                <td>{{\Carbon\Carbon::parse($ticket->fecha_caducidad)}}</td>
                             @endif

                            <!-- Opciones - Botones  -->
                            <td class="content-btnInfo">
                                @if($ticket->estado->nombre == "Nuevo")
                                    <a class="btn btn-info btn-ver" href="/form/respuesta/{{$ticket->id}}">Ver</a>
                                @elseif($ticket->estado->nombre == "Abierto")
                                    <a class="btn btn-info btn-responder" href="/form/respuesta/{{$ticket->id}}">Responder</a>
                                @elseif($ticket->estado->nombre == "En espera")
                                         <a class="btn btn-info btn-verMsj desactivar_enlace" href="respuesta/{{$loop->iteration}}/mas_info/ticket/{{$ticket->id}}">Respuesta</a>
                                         <a  href="/historial/ticket/{{$ticket->id}}" class="btn btn-primary btn-historial">Historial</a>
                                @elseif($ticket->estado->nombre == "En revisión")
                                    @php
                                        $ultimoMsj = $ticket->masInformacions->last();
                                        $ultimaResp = $ticket->respMasInfo->last();
                                    @endphp
                                    @if($ultimaResp)
                                        @isset($ultimaResp->mensaje)
                                        <a class="btn btn-info btn-verMsj" href="respuesta/{{$loop->iteration}}/mas_info/ticket/{{$ticket->id}}" >Respuesta</a>
                                        @endisset
                                    @endif
                                @elseif($ticket->estado->nombre == "Resuelto")
                                    <a class="btn btn-success" href="/historial/ticket/{{$ticket->id}}">Ver</a>
                                @endif 
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
    $('#tabla_misTickets').DataTable({

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

        "order": [[9, 'desc']],
        "columnDefs": [
            {
                "targets": [8, 9],
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