@extends('adminlte::page')

@section('title', 'Mis tickets en revisión')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Mis tickets en revisión</h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_tktRevision" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%;" >
                <div class="content-btnVolver">
                    <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volverInfo">
                    <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
                </div>
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Clasificación</th>
                      <th>Asunto</th>
                      <th>Prioridad</th>
                      <th>Estado</th>
                      <th>Respondido</th>
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
                             @elseif($ticket->estado->nombre == "En revisión")
                                <td class="enRevision">{{$ticket->estado->nombre}}</td>
                             @elseif($ticket->estado->nombre == "Resuelto")
                                <td class="resuelto">{{$ticket->estado->nombre}}</td>
                             @endif

                            <!-- Respondido -->
                            <td>{{\Carbon\Carbon::parse($ticket->updated_at)}}</td>
                            <!-- Fecha de caducidad -->
                            <td>{{\Carbon\Carbon::parse($ticket->fecha_caducidad)->format('d-m-Y')}}</td>

                            <!-- Botones - opciones -->
                             <td >
                                @php
                                    $ultimoMsj = $ticket->masInformacions->last();
                                    $ultimaResp = $ticket->respMasInfo->last();
                                @endphp

                                @if($ticket->estado->nombre == "En revisión")
                                    @if($ultimaResp)
                                        @isset($ultimaResp->mensaje)
                                            <a class="btn btn-info btn-verMsj" href="respuesta/{{$loop->iteration}}/mas_info/ticket/{{$ticket->id}}" >Ver mensaje</a>
                                        @endisset
                                    @else
                                            <a class="btn btn-info btn-verMsj desactivar_enlace" href="respuesta/{{$loop->iteration}}/mas_info/ticket/{{$ticket->id}}" >Ver mensaje</a>
                                    @endif
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

<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


<script>
$(document).ready(function() {
    $('#tabla_tktRevision').DataTable({

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

        "order": [[6, 'desc']],
        "columnDefs": [
            {
                "targets": 6, 
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