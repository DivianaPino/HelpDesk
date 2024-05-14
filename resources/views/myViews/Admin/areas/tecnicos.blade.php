@extends('adminlte::page')

@section('title', 'Técnicos')


@section('content')
<div>
     <div class="card">
        <div class="card-body">
        <h1>Técnicos que pertenecen al área: {{ $area->nombre }}</h1>
            <table id="tabla_usuarios" class="table table-striped table-bordered shadow-lg mt-4" style="width:100%">
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
                             <td style="text-align: center;">
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
    <link rel="stylesheet" href="/css/admin_custom.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.24/css/dataTables.bootstrap5.min.css">
@stop

@section('js')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.10.24/js/dataTables.bootstrap5.min.js"></script>

<script>
$(document).ready(function() {
    $('#tabla_usuarios').DataTable({
      //Opciones de paginación
        "lengthMenu": [
            [5, 10, 50, -1],
            [5, 10, 50, "All"]
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


