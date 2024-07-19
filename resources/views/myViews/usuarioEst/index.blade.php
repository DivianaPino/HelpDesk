@extends('adminlte::page')

@section('title', 'Consultar Tickets')

@section('content_header')
    
@stop

@section('content')
<div class="content-tituloTR">
  <h1 class="titulo_prin">Mis tickets reportados</h1>
  @if(session('status'))
    <p class="alert alert-success">{{ Session('status') }}</p>
  @endif
</div>
<div class="">
     <div  class="card"  >
        <div  class="card-body" >
            <table id="tabla_tktReportados" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"   style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Clasif.</th>
                      <th>Prioridad</th>
                      <th>Creado</th>
                      <th>Estado</th>
                      <th>Atendido</th>
                      <th>Opciones</th>
                   </tr>
               </thead>

               <tbody>
                 
                  @foreach ($tickets as $ticket )

                    
   
                      <tr>
                           
                        <td>TK-{{$ticket->id}}</td>
                        <td>{{$ticket->user->name}}</td>
                        <td>{{$ticket->clasificacion->nombre}}</td>

                        @if($ticket->prioridad->nombre == "Urgente")
                          <td class="prd_urgente">{{$ticket->prioridad->nombre}}</td>
                        @elseif($ticket->prioridad->nombre == "Alta")
                          <td class="prd_alta">{{$ticket->prioridad->nombre}}</td>
                        @elseif($ticket->prioridad->nombre == "Media")
                          <td class="prd_media">{{$ticket->prioridad->nombre}}</td>
                        @elseif($ticket->prioridad->nombre == "Baja")
                          <td class="prd_baja">{{$ticket->prioridad->nombre}}</td>
                        @endif
    
                        <td class="td-fecha_inicio">{{\Carbon\Carbon::parse($ticket->created_at)->format('d-m-Y')}}</td>
                        

                        @if($ticket->estado->nombre == "Nuevo")
                          <td class="abierto">Abierto</td>
                        @elseif($ticket->estado->nombre == "Abierto")
                          <td class="abierto">{{$ticket->estado->nombre}}</td>
                        @elseif($ticket->estado->nombre == "En espera")
                          @php
                              $ultimoMsj = $ticket->masInformacions->last();
                              $ultimaResp = $ticket->respMasInfo->last();
                          @endphp

                          @if(isset($ultimoMsj))
                              @if($ultimoMsj['id'] == $ultimaResp['id'])
                                  <td class="enEspera">{{$ticket->estado->nombre}}<br>(mensaje respondido)</td>
                              @else
                                  <td class="enEspera">{{$ticket->estado->nombre}} <br><span class="text-EsperandoResp">(Esperando su respuesta)</span></td>
                              @endif
                          @endif
                        @elseif($ticket->estado->nombre == "En revisión")
                              <td class="enRevision">{{$ticket->estado->nombre}}</td>
                        @elseif($ticket->estado->nombre == "Resuelto")
                          <td class="resuelto">{{$ticket->estado->nombre}}</td>
                        @elseif($ticket->estado->nombre == "Reabierto")
                          <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                        @elseif($ticket->estado->nombre == "Cerrado")
                          <td class="resuelto">Resuelto</td>
                        @endif

                        <td>{{ \Carbon\Carbon::parse($ticket->respuestas->last()['updated_at']) }}</td>
                          
                        <td class="content-btnInfo">
                            <a class="btn btn-info" href="/ticket/{{$ticket->id}}/historial" >Ver</a>
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
    $('#tabla_tktReportados').DataTable({

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