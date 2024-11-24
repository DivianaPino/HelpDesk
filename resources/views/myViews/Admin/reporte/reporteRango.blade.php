<html>
<head>
    <title>Reporte por rango PDF</title>

    <style>

        .numFechaReporte {
            position: fixed; /* Esto hace que el elemento se fije en su lugar */
            top: 0; /* Posiciona el elemento en la parte superior del viewport */
            right: 0; /* Alinea el elemento a la derecha */
        }

        .membrete-reporte{
            display: flex;
            flex-direction: column;
            align-items: center; /* Centra los elementos horizontalmente */
            justify-content: center; /* Centra los elementos verticalmente si el div tiene altura */
            text-align: center; /* Centra el texto dentro de los elementos */
        }

        .membrete-reporte img{
            width: 70px;
            height: 70px;
            margin-top: 80px;
        }

    
        .membrete-reporte h4{
           margin-top:30px;
           margin-bottom:0px;
        }

        .membrete-reporte h5{
           margin-top:5px;
        }

        table{
            max-width: 100%; /* Ajusta esto según sea necesario */
            border-collapse: collapse; /* Para eliminar espacios entre celdas */
        }

        th, td {
            border: 1px solid black;
            padding: 4px;
            text-align: left;
            word-wrap: break-word;
            overflow: auto;
        }

        .nuevo{
            background-color:#09ac17 !important;
            color:aliceblue !important; 
        }

        .abierto{
            background-color:   #ff6a06 !important;
            color:aliceblue !important; 
        }

        .enEspera{
            background-color:#ffdd00   !important; 
        }

        .enRevision{
            background-color: #8E44AD !important;
            color: aliceblue !important;
        }

        .resuelto{
            background-color:#067af7 !important;
            color:aliceblue !important;
        }

        .cerrado{
            background-color:#1a1919 !important;
            color:aliceblue !important;
        }

        .vencido{
            background-color:#5c5757 !important;
            color:aliceblue !important;
        }

        .reAbierto{
            background-color:#f706e3 !important;
            color:aliceblue !important;
        }

        .prd_urgente{
            color: #d80b0b !important;
            font-weight: bold !important;
        }

        .prd_alta{
            color:  #fc6f04  !important; 
            font-weight: bold !important;
        }

        .prd_media{
            color:#066f12   !important; 
            font-weight: bold !important;
        }

        .prd_baja{
            color: #5e6264   !important; 
            font-weight: bold !important;
            }

    </style>
</head>
<body>
<div class="contenedor-encabezado">
    <div class="numFechaReporte">
        <h3 class="num-reporte">Nro: 00{{$idReporte}} </h3>
        <span class="fecha-reporte">FECHA: {{ \Carbon\Carbon::now()->setTimezone('America/Caracas')->format('d/m/Y') }} </span>
    </div>
    <div class="membrete-reporte">
        <img src="data:image/jpg;base64,{{ base64_encode(file_get_contents(public_path('assets/logoUneg.jpg'))) }}" alt="Logo Uneg"> 
        <h3 class="texto1">REPÚBLICA BOLIVARIANA DE VENEZUELA </h3>
        <h3 class="texto2"> UNIVERSIDAD NACIONAL EXPERIMENTAL DE GUAYANA </h3>
        <h4> REPORTE DE TICKETS POR RANGO</h4>

        @if(empty($fecha_inicial) || empty($fecha_fin))
            <h5>(
                <span>HASTA:</span> {{\Carbon\Carbon::parse($fecha_actual)->format('d/m/Y')}}
            )
            </h5>
        @else
            <h5>(
                <span>DESDE:</span> {{\Carbon\Carbon::parse($fecha_inicial)->format('d/m/Y')}} -- 
                <span>HASTA:</span> {{\Carbon\Carbon::parse($fecha_fin)->format('d/m/Y')}}
            )
            </h5>
        @endif
                
    </div>
</div>
<div>
    <div  class="card">
        <div  class="card-body" >
            <table id="tabla_tickets" class="table table-striped table-bordered shadow-lg mt-4 display responsive nowrap"  style="width:100%;" >
               <thead class="text-center bg-dark text-white">
                   <tr>
                      <th>ID</th>
                      <th>Usuario</th>
                      <th>Área</th>
                      <th>Asunto</th>
                      <th>Agente</th>
                      <th>Prioridad</th>
                      <th>Estado</th>
                      <th>Creado</th>
                      <th class="th-respondido">Respondido</th>
                      <th>Caducidad</th>
                   </tr>
               </thead>

               <tbody>
                    @foreach ($tickets as $ticket )
                        <tr>
                            <td>{{$ticket->id}}</td>
                            <td>{{$ticket->user->name}}</td>
                            <td>{{$ticket->area->nombre}}</td>

                            <td>{{$ticket->asunto}}</td>

                            @if(empty($ticket->asignado_a))
                            <td>---</td>
                            @else
                            <td>{{$ticket->asignado_a}}</td>
                            @endif
                            
                            @if($ticket->prioridad->nombre == "Urgente")
                              <td class="prd_urgente">{{$ticket->prioridad->nombre}}</td>
                            @elseif($ticket->prioridad->nombre == "Alta")
                              <td class="prd_alta">{{$ticket->prioridad->nombre}}</td>
                            @elseif($ticket->prioridad->nombre == "Media")
                              <td class="prd_media">{{$ticket->prioridad->nombre}}</td>
                            @elseif($ticket->prioridad->nombre == "Baja")
                              <td class="prd_baja">{{$ticket->prioridad->nombre}}</td>
                            @endif

                            @if($ticket->estado->nombre == "Nuevo")
                              <td class="nuevo">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Abierto")
                              <td class="abierto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "En espera")
                              <td class="enEspera">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "En revisión")
                              <td class="enRevision">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Resuelto")
                              <td class="resuelto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Reabierto")
                              <td class="reAbierto">{{$ticket->estado->nombre}}</td>
                            @elseif($ticket->estado->nombre == "Cerrado")
                              <td class="cerrado">{{$ticket->estado->nombre}}</td>
                            @endif

                            <td>{{\Carbon\Carbon::parse($ticket->fecha_inicio)->format('d-m-Y')}}</td>

                            @if($ticket->estado->nombre == "Resuelto")
                              <td>{{ \Carbon\Carbon::parse($ticket->updated_at)->format('d-m-Y') }}</td>
                            @else
                              <td>---</td>
                            @endif

                            @if($ticket->estado->nombre == "En espera")
                              <td>En pausa</td>
                            @else
                              <td>{{\Carbon\Carbon::parse($ticket->fecha_caducidad)->format('d-m-Y')}}</td>
                            @endif
                             
                        </tr>
                    @endforeach
               </tbody>
            </table>
        </div>
    </div>
</div>



<script>
$(document).ready(function() {
    $('#tabla_tickets').DataTable({

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

        "order": [[0, 'asc']],
        "columnDefs": [
            {
                "targets": [7,8], 
                "type": "date",
                "render": function (data, type, row) {
                    // Verificar si 'data' es una fecha válida
                    if (moment(data, 'YYYY-MM-DD HH:mm:ss', true).isValid()) {
                        // Si es una fecha válida, convertirla al formato 'DD-MM-YYYY' para la visualización
                        if (type === 'display') {
                            return moment(data, 'YYYY-MM-DD HH:mm:ss').format('DD-MM-YYYY');
                        }
                    }
                    // Para la ordenación y otros usos, devuelve el valor original
                    // Esto incluye el caso en que 'data' no es una fecha válida, por lo que se devuelve tal cual
                    return data;
                }
                
            }
        ]
        
        });
});
</script>
</body>
</html>