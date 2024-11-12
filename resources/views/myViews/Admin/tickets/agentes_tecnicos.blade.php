@extends('adminlte::page')

@section('title', 'Agentes técnicos ')

@section('content_header')
   
@stop

@section('content')

<h1 class="titulo_prin  titulo_tecnicosSop">Todos los agentes técnicos</h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_tecnicosSop" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"  style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Técnico</th>
                      <th>Área(s)</th>
                      <th>Rol(es)</th>
                      <th>Email</th>
                   </tr>
               </thead>

               <tbody>

                    @php
                        $lastArea = true;
                    @endphp
                    @foreach ($tecnicos as $tecnico )   
              
                        <tr>
                            <td>{{$tecnico->id}}</td>
                            <td>{{$tecnico->name}}</td>
                            <td>
                                @foreach ($tecnico->areas as $area)
                                 {{ $area->nombre }}{{ $loop->last? '' : ', ' }}
                                @endforeach
                            </td>
                            <td>{{$tecnico->roles()->pluck('name')->implode(', ')}}</td>
                            <td>{{$tecnico->email}}</td> 
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

<script  type="text/javascript" src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

@if(session('eliminar') == 'ok')
  <script>
      Swal.fire({
      title: "¡Eliminado!",
      text: "El ticket se elimino con éxito",
      icon: "success"
      });
  </script>
@endif


<script>

    $(".formulario-eliminar").submit(function(e){
        e.preventDefault();

        Swal.fire({
        title: "¿Estás seguro?",
        text: "El ticket se eliminará definitivamente",
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
    $('#tabla_tecnicosSop').DataTable({

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