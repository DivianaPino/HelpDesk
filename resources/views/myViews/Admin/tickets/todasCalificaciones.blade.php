@extends('adminlte::page')

@section('title', 'Calificaciones')

@section('content_header')
    
@stop

@section('content')
<h1 class="titulo_prin">Todas las calificaciones</h1>
<div>
     <div  class="card">
        <div  class="card-body" >
            <table id="tabla_todasCalificaciones" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap" style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th class="th-califTodas">ID Calif.</th>
                      <th class="th-califTodas">ID Ticket</th>
                      <th class="th-califTodas">Cliente</th>
                      <th >Nivel de <br> satisfacción</th>
                      <th class="th-califTodas">Acción</th>
                      <th class="th-califTodas">Comentario</th>
                      <th class="th-califTodas">Fecha</th>
                      <th class="th-califTodas">Opciones</th>
                   </tr>
               </thead>

               <tbody>
                 
                    @foreach ($calificaciones as $calificacion )

                        @php
                            $ticket= App\Models\Ticket::find($calificacion->ticket_id);
                            $cliente=$ticket->user->name;
                        @endphp

                        <tr>
                            <td>{{$calificacion->id}}</td>
                            <td>{{$calificacion->ticket_id}}</td>
                            <td>{{$cliente}}</td>
                            <td>{{$calificacion->nivel_satisfaccion}}</td>

                            @if($calificacion->accion == "Cerrarlo")
                                <td>{{$calificacion->accion}} <i class="fa-solid fa-lock iconCerrado"></i></td>
                            @elseif($calificacion->accion == "Reabrirlo")
                                <td>{{$calificacion->accion}} <i class="fa-solid fa-unlock iconReabierto"></i></td>
                                @else
                                <td>{{$calificacion->accion}}</td>
                            @endif

                            <td>
                                @if (strlen($calificacion->comentario) > 30) 
                                    {{ substr($calificacion->comentario, 0, 30) }}...
                                @else
                                    {{$calificacion->comentario}}
                                @endif
                            </td>
                            <td>{{$calificacion->created_at}}</td>

                            <!-- Botones - opciones -->
                            <td class="tdStyle">
                            <a class="btn btn-info" href="#" data-ticket-id="{{$ticket->id}}" data-nivel-satisfaccion="{{$calificacion->nivel_satisfaccion}}" 
                                 data-accion="{{$calificacion->accion}}" data-comentario="{{$calificacion->comentario}}" data-created-at="{{$calificacion->created_at}}">Ver</a>
                            </td>
                        </tr>
                    @endforeach

               </tbody>
            </table>
        </div>
     </div>
</div>

<!-- Modal para ver cada calificación -->
<div class="modal fade" id="modalCalificacion" tabindex="-1" aria-labelledby="modalCalificacionLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalCalificacionLabel">Detalles de Calificación</h5>
                <div class="button-modal-cerrar">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"><i class="fa-solid fa-xmark"></i></button>
                </div>
            </div>
            <div class="modal-body">

                <p style="text-align:right;">Fecha: <span id="fechaCreacion"></span></p>
                <p>Ticket ID: <span id="ticketId"></span></p>
                <p>Nivel de satisfacción: <span id="nivelSatisfaccion"></span></p>
                <p>Acción: <span id="accion"></span></p>
                <p>Comentario: </p>
                <textarea name="" id="comentario" cols="60" rows="6" disabled></textarea>
            </div>
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


<script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.29.1/moment.min.js"></script>


<script>
$(document).ready(function() {
    $('#tabla_todasCalificaciones').DataTable({

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

<!-- js del modal para ver cada calificación -->
<script>
   document.addEventListener('DOMContentLoaded', function() {
    const modalCalificacion = new bootstrap.Modal(document.getElementById('modalCalificacion'));

    document.querySelectorAll('.btn-info').forEach(button => {
        button.addEventListener('click', function(event) {
            event.preventDefault();

            const ticketId = this.getAttribute('data-ticket-id');
            const nivelSatisfaccion = this.getAttribute('data-nivel-satisfaccion');
            const accion = this.getAttribute('data-accion');
            const comentario = this.getAttribute('data-comentario');
            const fechaCreacion = this.getAttribute('data-created-at');

            document.getElementById('ticketId').textContent = ticketId;
            document.getElementById('nivelSatisfaccion').textContent = nivelSatisfaccion;
            document.getElementById('accion').textContent = accion;
            document.getElementById('comentario').textContent = comentario;
            document.getElementById('fechaCreacion').textContent = fechaCreacion;

            modalCalificacion.show();
        });
    });

    // Evento para el botón secundario
    document.querySelector('.btn-secondary').addEventListener('click', function() {
        modalCalificacion.hide();
    });
});

</script>
@stop