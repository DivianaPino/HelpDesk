@extends('adminlte::page')

@section('title', 'Asignar técnico')

@section('content_header')
    <h1></h1>
@stop

@section('content')

<div>
     <div class="card">
        <div class="card-body">
            <div style="margin-bottom:30px;">
                <div class="content-btnVolver">
                    <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volverInfo">
                    <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
                </div>
                <h3 class="titulo_prin" style="margin:0px; padding:0px;">Asignar técnico</h3>
            </div>
           
            @if(session('status'))
              <p class="alert alert-success">{{ Session('status') }}</p>
            @endif

            <table id="tabla_asignarTec" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>Técnico de soporte</th>
                      <th>Nro de tickets asignados (actualmente)</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>   
                    @foreach ($usuarios as $usuario )
                            <tr style="text-align: center;">
                                <td>{{$usuario->name}}</td>
                                <td>
                                   Abiertos: <a href="/tickets/abiertos/tecnico/{{$usuario->id}}" style="text-decoration: underline; margin-right:30px;">{{$usuario->ticket_count_a}}</a> 
                                   En espera: <a href="/tickets/enEspera/tecnico/{{$usuario->id}}" style="text-decoration: underline;">{{$usuario->ticket_count_esp}}</a>
                                </td>                            
                                <td style="text-align: center;">
                                    <form action="/asignar/tecnico/{{$usuario->id}}/ticket" method="POST" style="display:inline">  
                                     @csrf
                                        <button type="submit" class="btn btn-info" >Asignar técnico</button>
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabla_asignarTec').DataTable({
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
        }
    });
});
</script>
@stop


