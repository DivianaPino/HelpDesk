@extends('adminlte::page')

@section('title', 'Tickets cerrados')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Tickets cerrados</h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_tktCerrados" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Área</th>
                      <th>Asunto</th>
                      <th>Prioridad</th>
                      <th>Estado</th>
                      <th>Creado</th>
                      <th>Cerrado</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                 
                    @foreach ($tickets as $ticket )
   
                        <tr>
                             <td>TK-{{$ticket->id}}</td>
                             <td>{{$ticket->user->name}}</td>
                             <td>{{$ticket->area->nombre}}</td>
                             <td>{{$ticket->asunto}}</td>
                            
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
                                <td class="enEspera">{{$ticket->estado->nombre}}</td>
                             @elseif($ticket->estado->nombre == "Resuelto")
                                <td class="resuelto">{{$ticket->estado->nombre}}</td>
                             @elseif($ticket->estado->nombre == "Reabierto")
                                <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                             @elseif($ticket->estado->nombre == "Cerrado")
                                <td class="cerrado">{{$ticket->estado->nombre}}</td>
                             @endif

                            <!-- Fecha de creación -->
                            <td>{{\Carbon\Carbon::parse($ticket->fecha_inicio)->format('d-m-Y')}}</td>
                            <td>{{\Carbon\Carbon::parse($ticket->updated_at)}}</td>

                             <td>
                                <a class="btn btn-info" href="/historial/ticket/{{$ticket->id}}" >Ver</a>
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
    $('#tabla_tktCerrados').DataTable({

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
                    // Asegurar de que 'data' esté en el formato 'YYYY-MM-DD'
                    // y luego convertirlo al formato 'DD-MM-YYYY' para la visualización
                    if (type === 'display') {
                        return moment(data, 'YYYY-MM-DD').format('DD-MM-YYYY');
                    }
                    // Para la ordenación y otros usos, devuelve el valor original
                    return data;
                }
            }
        ]

    });
});
</script>
@stop