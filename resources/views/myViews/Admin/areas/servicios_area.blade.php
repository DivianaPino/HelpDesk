@extends('adminlte::page')

@section('title', 'Servicios de ' . $area->nombre)

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin tituloServiciosArea">Servicios del área de <span>{{$area->nombre}}</span></h1>
<div>   
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_areas" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%"  >
             
               <div class="content-btnVolver contentVolverServiciosArea">
                    <a href="/crear/servicio/area/{{$area->id}}" class="btn btn-primary btn-crear mb-3" >Crear</a> 
                    <a style="margin-top:8px;" href="/areas" class="btn btn-dark btn-volver btnVolver-serviciosArea"><i class="fa-solid fa-arrow-left fa-lg"></i>Todas las áreas</a>
                </div>
               @if(session('status'))
                 <p class="alert alert-success">{{ Session('status') }}</p>
               @endif

               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Servicio</th>
                      <th>Creado</th>
                      <th>Acciones</th>
                   </tr>
               </thead>

               <tbody>
                    @foreach ($servicios as $servicio )
                        <tr>
                            <td>{{$servicio->id}}</td>
                            <td>{{$servicio->nombre}}</td>
                            <td>{{\Carbon\Carbon::parse($servicio->created_at)->format('d-m-Y')}}</td>
                            <td  class="align-items-center">
                            
                                <!-- <p class="linea">|</p> -->
                                <a href="/servicios/{{$servicio->id}}/edit" class="btn btn-warning ">Editar</a>
                                <form action="{{route('servicios.destroy',$servicio->id)}}" method="POST" style="display:inline" class="formulario-eliminar">  
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger" >Eliminar</button>
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

<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

@if(session('eliminar') == 'ok')
  <script>
      Swal.fire({
      title: "¡Eliminado!",
      text: "El servicio se elimino con éxito",
      icon: "success"
      });
  </script>
@endif


<script>

    $(".formulario-eliminar").submit(function(e){
        e.preventDefault();

        Swal.fire({
        title: "¿Estás seguro?",
        text: "El servicio se eliminará definitivamente",
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

        "order": [[0, 'desc']],
        "columnDefs": [
            {
                "targets": 2, 
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