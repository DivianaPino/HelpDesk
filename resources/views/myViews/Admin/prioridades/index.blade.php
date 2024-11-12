@extends('adminlte::page')

@section('title', 'Prioridades')

@section('content_header')
   
@stop

@section('content')

<h1 class="titulo_prin">Todas las prioridades</h1>
<div>    
     <div  class="card">
        <div  class="card-body">
            <table id="tabla_prioridades" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%" >
            <a href="prioridades/create" class="btn btn-primary btn-crear mb-3">Crear</a>
               @if(session('status'))
                <p class="alert alert-success">{{ Session('status') }}</p>
               @endif

               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Días de resolución</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody >
                    @foreach ($prioridades as $prioridad )
                        <tr>
                            <td>{{$prioridad->id}}</td>
                            <td>{{$prioridad->nombre}}</td>
                            <td>{{$prioridad->tiempo_resolucion}}</td>
                            <td style="text-align: center;">
                                <form action="{{route('prioridades.destroy',$prioridad->id)}}" method="POST" class="formulario-eliminar">
                                    <a href="/prioridades/{{$prioridad->id}}/edit" class="btn btn-warning">Editar</a>
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger ">Eliminar</button>
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
      title: "¡Eliminada!",
      text: "La prioridad se elimino con éxito",
      icon: "success"
      });
  </script>
@endif


<script>

    $(".formulario-eliminar").submit(function(e){
        e.preventDefault();

        Swal.fire({
        title: "¿Estás seguro?",
        text: "La prioridad se eliminará definitivamente",
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
    $('#tabla_prioridades').DataTable({

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