@extends('adminlte::page')

@section('title', 'Técnicos de área')

@section('content_header')
    
@stop

@section('content') 
<div class="content-btnVolver btnVolver_TecArea">
    <a style="margin-top:8px;" href="javascript:history.back()" class="btn btn-dark btn-volverInfo">
    <i class="fa-solid fa-arrow-left fa-lg"></i>Volver</a>
</div>

<h1 class="titulo_prin">Técnicos que pertenecen al área: {{ $area->nombre }} </h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_TecArea" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Rol(es)</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                 
                    @foreach ($usuarios as $usuario )
                          <tr>
                             <td>{{$usuario->id}}</td>
                             <td>{{$usuario->name}}</td>
                             <td>{{$usuario->email}}</td>
                             <td>{{$usuario->roles()->pluck('name')->implode(', ')}}</td>
                             <td class="content-btnOpciones">
                                 <a class="btn btn-info"  href="{{route('usuarios.edit', $usuario)}}">Editar rol</a>
                                 <a class="btn btn-warning" href="{{url('asignar_area', $usuario)}}">Cambiar área</a>
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

<script>
$(document).ready(function() {
    $('#tabla_TecArea').DataTable({

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

        "order": [[0, 'desc']],
    });
});
</script>
@stop


