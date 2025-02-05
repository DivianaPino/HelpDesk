@extends('adminlte::page')

@section('title', 'Usuarios')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Todos los usuarios</h1>
<div>
     <div class="card">
        <div class="card-body">
            <table id="tabla_usuarios" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%">
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Email</th>
                      <th>Área(s)</th>
                      <th>Rol(es)</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                 
                    @foreach ($usuarios as $usuario )

                        @php
                            $uniqueAreas = array_unique($usuario->areas->pluck('nombre')->toArray());
                            $uniqueRoles = array_unique($usuario->roles->pluck('name')->toArray());
                        @endphp
    
                            <tr>
                                <td>{{$usuario->id}}</td>
                                <td>{{$usuario->name}}</td>
                                <td>{{$usuario->email}}</td>
                                <td>
                                    @foreach ($uniqueAreas as $area)
                                        {{ $area }}{{ $loop->last? '' : ', ' }}
                                    @endforeach
                                </td>
                                <td>
                                    @foreach ($uniqueRoles as $rol)
                                        {{ $rol }}{{ $loop->last? '' : ', ' }}
                                    @endforeach
                                </td>
                                <td class="content-btnOpciones" >
                                <a class="btn btn-info"  href="{{route('usuarios.edit', $usuario)}}">Editar rol</a>
                    
                                @if($usuario->hasRole(['Administrador', 'Técnico de soporte']))
                                <a class="btn btn-warning" href="{{url('asignar_area', $usuario)}}">Asignar área</a>
                                @endif
                                <form action="{{route('usuarios.destroy',$usuario->id)}}" method="POST" class="formulario-eliminar">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger">Eliminar</button>
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


<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.min.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.bootstrap5.css">

<link rel="stylesheet" href="https://cdn.datatables.net/2.0.2/css/dataTables.dataTables.css">
@stop

@section('js')
<script type="text/javascript" src="https://code.jquery.com/jquery-3.7.1.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.min.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.js"></script>

<script type="text/javascript" src="https://cdn.datatables.net/2.0.2/js/dataTables.bootstrap5.js"></script>

<script  type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('eliminar') == 'ok')
  <script>
      Swal.fire({
      title: "¡Eliminado!",
      text: "El usuario se elimino con éxito",
      icon: "success"
      });
  </script>
@endif


<script>

    $(".formulario-eliminar").submit(function(e){
        e.preventDefault();

        Swal.fire({
        title: "¿Estás seguro?",
        text: "El usuario se eliminará definitivamente",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "¡Si, eliminar!",
        cancelButtonText: "Cancelar"
        }).then((result) => {
        if (result.isConfirmed) {

            this.submit();
        }
        });
    });
    
</script>

<script>
$(document).ready(function() {
    $('#tabla_usuarios').DataTable({

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