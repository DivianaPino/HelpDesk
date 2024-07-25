@extends('adminlte::page')

@section('title', 'Comentarios')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Comentarios de tickets del agente</h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_comentariosTodos" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%;" >
               <div class="content-btnVolver">
                    <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volverInfo">
                    <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
                </div>
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Clasificación</th>
                      <th>Respuesta</th>
                      <th>Calificación</th>
                      <th>¿Reabierto?</th>
                      <th>Fecha</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>

                    @foreach ($comentarios as $ticketComent)
                         
                        @foreach($ticketComent as $comentario)
  
                            @php
                                $ticket= App\Models\Ticket::find($comentario->ticket_id);
                                $respuesta=App\Models\Respuesta::find($comentario->respuesta_id);
                            @endphp
        
                            <tr>
                                <td>{{$ticket->id}}</td>
                                <td>{{$ticket->user->name}}</td>
                                <td>{{$ticket->clasificacion->nombre}}</td>
                                <td class=th_respuesta>
                                    @if (strlen($respuesta->mensaje) > 40) 
                                        {{ substr($respuesta->mensaje, 0, 40) }}...
                                    @else
                                        {{$respuesta->mensaje}}
                                    @endif
                                </td>
                                <td>{{$comentario->nivel_satisfaccion}}</td>

                                <td> 
                                    @if($comentario->bool_reabrir == 1)
                                        SI 
                                    @else
                                        NO 
                                    @endif
                                </td>

                                <td>{{$comentario->created_at}}</td>

                                <!-- Botones - opciones -->
                                <td>
                                    <a class="btn btn-info" href="/comentario/{{$comentario->id}}" >Comentario</a>
                                </td>
                            </tr>
                        @endforeach
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
    $('#tabla_comentariosTodos').DataTable({

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