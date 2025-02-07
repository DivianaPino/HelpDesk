@extends('adminlte::page')

@section('title', 'Tickets en espera - técnico')

@section('content_header')
    <!-- <h1 class="tituloAgenteT">Tickets en espera del técnico de soporte: {{$usuario->name}}</h1> -->
@stop

@section('content')
<div>
     <div  class="card">
        <div  class="card-body" >
        <div style="margin-bottom:30px;">
            <div class="content-btnVolverTable">
                <a  href="javascript:history.back()" class="btn btn-dark btn-volver">
                <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
            </div>
            <h3 class="tituloAgenteT" style="margin:0px; padding:0px;">Tickets en espera del técnico de soporte: {{$usuario->name}}</h3>
        </div>
        <table id="tabla_tickets" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"  style="width:100%;"  >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Cliente</th>
                      <th>Área</th>
                      <th>Servicio</th>
                      <th>Estado</th>
                      <th>Prioridad</th>
                      <th>Asunto</th>
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
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>
<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>
<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

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
       
        
        });

});
</script>
@stop