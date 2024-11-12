@extends('adminlte::page')

@section('title', 'Áreas')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Todas las áreas</h1>
<div>   
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_areas" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%"  >
               <a href="areas/create" class="btn btn-primary btn-crear mb-3" >Crear</a> 
               @if(session('status'))
                 <p class="alert alert-success">{{ Session('status') }}</p>
               @endif

               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Nombre</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                    @foreach ($areas as $area )
                        <tr>
                            <td>{{$area->id}}</td>
                            <td>{{$area->nombre}}</td>
                            <td  class="align-items-center">
                                <div>

                                    <a class="btn btn-info" href="{{url('/area/' . $area->id . '/tecnicos')}}" >Ver técnicos</a>

                                    <a href="/area/{{$area->id}}/servicios" class="btn btn-secondary ">Servicios</a>
                                </div>
                       
                                <div  class="align-items-center">

                                    <!-- <p class="linea">|</p> -->

                                    <a href="/areas/{{$area->id}}/edit" class="btn btn-warning ">Editar</a>
                                    <form action="{{route('areas.destroy',$area->id)}}" method="POST" style="display:inline" class="formulario-eliminar">  
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" >Eliminar</button>
                                    </form> 
                                </div>
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
      text: "El área se elimino con éxito",
      icon: "success"
      });
  </script>
@endif


<script>

    $(".formulario-eliminar").submit(function(e){
        e.preventDefault();

        Swal.fire({
        title: "¿Estás seguro?",
        text: "El área se eliminará definitivamente",
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
    $('#tabla_areas').DataTable({

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