@extends('adminlte::page')

@section('title', 'Historial')

@section('content_header')
    
@stop

@section('content')
<div class="content-tituloTR">
  <h1 class="titulo_prin">Historial de ticket #{{$idTicket}}</h1>
</div>
<div class="">
     <div  class="card"  >
        <div  class="card-body" >
            <table id="tabla_historial" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"   style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID Ticket</th>
                      <th>Estado</th>
                      <th>Descripción</th>
                      <th>Fecha</th>
                      <th>Opciones</th>
                   </tr>
               </thead>

               <tbody>
                    @php $contadorResueltos = 1; @endphp
               
                    @foreach ($tickets as $ticket )
               
                        @if($ticket->estado->nombre == "En espera" && !is_null($ticket->masinfo_id))  

                               <tr>
                                    <td>{{$ticket->ticket_id}}</td>


                                    @if($ticket->estado->nombre == "Nuevo")
                                        <td class="abierto">Abierto</td>
                                    @elseif($ticket->estado->nombre == "Abierto")
                                        <td class="abierto">{{$ticket->estado->nombre}}</td>
                                    @elseif($ticket->estado->nombre == "En espera")
                                        @php 
                                            $masInfo_Resp= App\Models\RespMasInfo::where('ticket_id',$ticket->ticket_id)
                                                                                 ->where('masInfo_id', $ticket->masinfo_id)->first();
                                        @endphp 
                                        
                                        @isset($masInfo_Resp)
                                            <td class="enEspera">{{$ticket->estado->nombre}}<br>(mensaje respondido)</td>
                                        @else
                                            <td class="enEspera">{{$ticket->estado->nombre}}</td>
                                        @endisset
                                    @elseif($ticket->estado->nombre == "Resuelto")
                                        <td class="resuelto">{{$ticket->estado->nombre}}</td>
                                    @elseif($ticket->estado->nombre == "Reabierto")
                                        <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                                    @endif


                                    @if($ticket->estado->nombre == "Nuevo")
                                        <td >El ticket esta siendo atendido.</td>
                                    @elseif($ticket->estado->nombre == "Abierto")
                                        <td >El ticket esta siendo atendido.</td>
                                    @elseif($ticket->estado->nombre == "En espera")
                                        <td>
                                            Se necesita más información del incidente (ticket), por favor dar
                                            click en la opción <span style="color:#fd0505;">'ver mensaje'</span> 
                                            y dar respuesta'.
                                        </td>
                                    @elseif($ticket->estado->nombre == "Resuelto")
                                        <td>
                                            Su ticket ha sido resuelto, por favor dar click en 
                                            la opción <span style="color:#fd0505;"> 'ver respuesta'</span> 
                                            y califique.
                                        </td>
                                    @elseif($ticket->estado->nombre == "Reabierto")
                                        <td>holaaa</td>
                                    @endif 

                                    <td>{{$ticket->updated_at}}</td>

            

                                    <td class="content-btnInfo">
                                        @if($ticket->estado->nombre == "Nuevo")
                                                <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" hidden>Ver mensaje</a>
                                        @elseif($ticket->estado->nombre == "Abierto")
                                            <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" hidden>Ver mensaje</a>
                                        @elseif($ticket->estado->nombre == "En espera")
                                            <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" >Ver mensaje</a>
                                        @elseif($ticket->estado->nombre == "Resuelto")
                                            <a class="btn btn-success" href="/ticket/{{$idTicket}}/respuesta/{{$ticket->respuesta_id}}">Ver respuesta</a>
                                        @elseif($ticket->estado->nombre == "Reabierto")
                                            <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" hidden>Ver mensaje</a>
                                        @endif 
                                    </td>
                               </tr> 

                            @elseif($ticket->estado->nombre != "En espera")
                            <tr>
                                    <td>{{$ticket->ticket_id}}</td>

                                    @if($ticket->estado->nombre == "Nuevo")
                                        <td class="abierto">Abierto</td>
                                    @elseif($ticket->estado->nombre == "Abierto")
                                        <td class="abierto">{{$ticket->estado->nombre}}</td>
                                    @elseif($ticket->estado->nombre == "En espera")
                                        @isset($masInfo_Resp)
                                            <td class="enEspera">{{$ticket->estado->nombre}}<br>(mensaje respondido)</td>
                                        @else
                                            <td class="enEspera">{{$ticket->estado->nombre}}</td>
                                        @endisset
                                    @elseif($ticket->estado->nombre == "En revisión")
                                        <td class="enRevision">{{$ticket->estado->nombre}}</td>
                                    @elseif($ticket->estado->nombre == "Resuelto")
                                        <td class="resuelto">{{$ticket->estado->nombre}}</td>
                                    @elseif($ticket->estado->nombre == "Reabierto")
                                        <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                                    @endif


                                    @if($ticket->estado->nombre == "Nuevo")
                                        <td >El ticket esta siendo atendido.</td>
                                    @elseif($ticket->estado->nombre == "Abierto")
                                        <td >El ticket esta siendo atendido.</td>
                                    @elseif($ticket->estado->nombre == "En espera")
                                        <td>
                                            Se necesita más información del incidente (ticket), por favor dar
                                            click en la opción <span style="color:#fd0505;">'ver mensaje'</span> 
                                            y dar respuesta'.
                                        </td>
                                    
                                    @elseif($ticket->estado->nombre == "En revisión")
                                        <td>
                                            Su respuesta esta siendo revisada por el agente técnico.
                                        </td>
                                    @elseif($ticket->estado->nombre == "Resuelto")
                                        <td>
                                            Su ticket ha sido resuelto, por favor dar click en 
                                            la opción <span style="color:#fd0505;"> 'ver respuesta'</span> 
                                            y califique.
                                        </td>
                                    @elseif($ticket->estado->nombre == "Reabierto")
                                        <td></td>
                                    @endif 

                                    <td>{{\Carbon\Carbon::parse($ticket->updated_at)->format('d-m-Y H:i:s')}}</td>

                                   


                                    <td class="content-btnInfo">
                                        @if($ticket->estado->nombre == "Nuevo")
                                                <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" hidden>Ver mensaje</a>
                                        @elseif($ticket->estado->nombre == "Abierto")
                                            <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" hidden>Ver mensaje</a>
                                        @elseif($ticket->estado->nombre == "En espera")
                                            <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" >Ver mensaje</a>
                                        @elseif($ticket->estado->nombre == "Resuelto")
                                            <a class="btn btn-success" href="/ticket/{{$idTicket}}/respuesta/{{$contadorResueltos++}}">Ver respuesta</a>
                                        @elseif($ticket->estado->nombre == "Reabierto")
                                            <a class="btn btn-info" href="/ticket/{{$idTicket}}/mensaje/{{$ticket->masinfo_id}}" hidden>Ver mensaje</a>
                                        @endif 

                                        
                                    </td>
                               </tr> 


                            @endif
                           
                     
                    @endforeach
               </tbody>

                <div class="content-btnVolver">
                    <a style="margin-top:8px;" href="/usuario/tickets" class="btn btn-dark btn-volverInfo">
                    <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
                </div>

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


<script>
$(document).ready(function() {
    $('#tabla_historial').DataTable({

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

     

        });
});
</script>
@stop