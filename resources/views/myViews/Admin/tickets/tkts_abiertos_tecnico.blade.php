@extends('adminlte::page')

@section('title', 'Tickets abiertos - técnico')

@section('content_header')
    <h1 class="tituloAgenteT">Tickets abiertos del técnico de soporte: {{$usuario->name}}</h1>
@stop

@section('content')
<div>
     <div  class="card">
        <div  class="card-body" >
            <div class="content-btnVolverTable">
                <a style="" href="javascript:history.back()" class="btn btn-dark btn-volver">
                <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
            </div>
            <table id="tabla_tickets" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"  style="width:100%;"  >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Cliente</th>
                      <th>Área</th>
                      <th>Servicio</th>
                      <th >Estado</th>
                      <th>Prioridad</th>
                      <th >Asunto</th>
                      <th>Creado</th>
                      <th>Caducidad</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                 
               @foreach ($tickets as $ticket )
   
                        <tr>
                             <td>{{$ticket->id}}</td>
                             <td>{{$ticket->user->name}}</td>
                             <td>{{$ticket->area->nombre}}</td>
                             <td>{{$ticket->servicio->nombre}}</td>
                             <td>{{$ticket->estado->nombre}}</td>
                             <td>{{$ticket->prioridad->nombre}}</td>
                             <td>{{$ticket->asunto}}</td>
                             <td>{{\Carbon\Carbon::parse($ticket->fecha_inicio)->format('d-m-Y')}}</td>
                             <td>{{\Carbon\Carbon::parse($ticket->fecha_caducidad)->format('d-m-Y')}}</td>
                             <td>
                                <a class="btn btn-info" href="/ver/ticket/{{$ticket->id}}" >Ver</a>

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
<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>
<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

<script>
$(document).ready(function() {
    $('#tabla_tickets').DataTable({
      //Opciones de paginación
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
@stop