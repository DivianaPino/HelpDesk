@extends('adminlte::page')

@section('title', 'Ticket #' . $idTicket)


@section('content')

  <div class="content ">
    <div class="container ">
      <div class="row align-items-stretch no-gutters contact-wrap ">
        <div class="col-md-12 ">
          <div class="form h-100  content-fondo" >
            <div class="content-btnVolver">
                <a  href="javascript:void(0);" class="btn btn-dark btn-volver" onclick="checkPreviousUrl({{ $ticket->id }})">
                    <i class="fa-solid fa-arrow-left fa-lg"></i>Volver
                </a>
            </div>
            <h3 class="tituloMsjTecnico">Ticket #{{$idTicket}} </h3>
            <form action="/mensaje/tecnico/ticket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
              @csrf
              <div class="row">
                <div class="col-md-8 form-group mb-3">
                  <label for="user_id" class="col-form-label">Usuario:</label>
                  <input type="text" class="form-control" name="user_id" id="user_id"   value="{{$ticket->user->name}}" disabled >
                </div>
                <div class="col-md-4 form-group mb-3 ml-auto">
                  <label for="fecha_inicio" class="col-form-label">Fecha:</label>
                  <input type="text" class="form-control" name="fecha_inicio" id="fecha_inicio" value="{{$ticket->fecha_inicio}}"  disabled>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-4 form-group mb-3">
                      <label for="area_id" class="col-form-label">Área:</label>
                      <input type="text" class="form-control" name="area_id" id="area_id" value="{{ $ticket->area->nombre }}" disabled>
                </div>
                <div class="col-md-4 form-group mb-3">
                      <label for="servicio_id" class="col-form-label">Servicio:</label>
                      <input type="text" class="form-control" name="servicio_id" id="servicio_id" value="{{ $ticket->servicio->nombre }}" disabled>
                </div>
                <div class="col-md-4 form-group mb-3">
                  <label for="prioridad_id" class="col-form-label">Prioridad:</label>
                  <input type="text" class="form-control" name="prioridad_id" id="prioridad_id" value="{{ $ticket->prioridad->nombre }}" disabled>
                </div>
              </div>     
              
              <div class="row">
                @if(is_null($ticket->asignado_a))
                  <div class="col-md-6 form-group mb-3">
                    <label for="asignado_a" class="col-form-label">Técnico asignado:</label>
                    <input type="text" class="form-control" name="asignado_a" id="asignado_a" value="Sin asignar" disabled>
                  </div>
                @else
                  <div class="col-md-6 form-group mb-3">
                    <label for="asignado_a" class="col-form-label">Técnico asignado:</label>
                    <input type="text" class="form-control" name="asignado_a" id="asignado_a" value="{{$ticket->asignado_a}}" disabled>
                  </div>
                @endif

                <div class="col-md-6 form-group mb-3">
                  <label for="estado_id" class="col-form-label">Estado:</label>
                  <input type="text" class="form-control" name="estado_id" id="estado_id" value="{{$ticket->estado->nombre}}" disabled>
                </div>
              </div>
              
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="asunto" class="col-form-label">Asunto:</label>
                  <input type="text" class="form-control inputForm" name="asunto" id="asunto" value="{{$ticket->asunto}}" placeholder="Escribe el asunto" disabled >
                </div>
              </div>
                
              <div class="row">
                <div class="col-md-12 form-group mb-3">
                  <label for="mensaje" class="col-form-label">Mensaje:</label>
                  <textarea class="form-control inputForm" name="mensaje" id="msj" cols="30" rows="4"  disabled>{{$ticket->mensaje}}</textarea>
                </div>
              </div>

              @if(isset($ticket->imagen))
                <div class="row">
                  <div class="col-md-12 form-group mb-3">
                      <img src="{{asset('images/tickets/'.$ticket->imagen)}}"
                        class="img-fluid img-rounded" width="60px"> 
                      <a href="{{ asset('images/tickets/'.$ticket->imagen) }}" style="font-size:12px;" download >Descargar Imagen</a>   
                  </div>
                </div>
              @endif

              <div class="content-chat">
                <div class="row">
                  <div class="col-md-12 form-group mb-3 cuadro2">
                      CHAT CON EL CLIENTE "{{strtoupper($cliente->name)}}"
                  </div>
            
                  <div class="card col-md-12 form-group mb-3 overflow-auto chat" id="chat">

                    @if($ticket->mensajes()->exists())
                      @foreach($ticket->mensajes as $msj)
                        @if($msj->user_id == Auth::user()->id || auth()->user()->hasRole('Administrador'))
                          @if($msj->mensaje || isset($msj['imagen']))
                            <div class="container-msjRight">
                              <div class="mensajesRight">
                                  <p class="msjChat">{{$msj->mensaje}}</p>
                                  @if(isset($msj['imagen']))
                                      <img src="{{asset('images/msjTecnico/'.$msj['imagen'])}}" class="img-fluid img-rounded imagenMsj"> 
                                      <a href="{{ asset('images/msjTecnico/'.$msj['imagen']) }}" class="txtImagen"  download>Descargar Imagen</a>   
                                  @else
                                      <img src="{{asset('images/msjTecnico/default.png')}}" class="img-fluid img-rounded" hidden> 
                                      <a href="{{ asset('images/msjTecnico/default.png') }}" download hidden>Descargar Imagen</a>   
                                  @endif    
                                  <span class="fecha_mensajes" style="text-align:right;">{{$msj->created_at->format('d/m/Y')}}, {{$msj->created_at->format('h:i A')}} </span>
                              </div>
                            </div>
                          @endif
                        @else
                          @if($msj->mensaje || isset($msj['imagen']))
                            <div class="container-msjLeft">
                              <div class="mensajesLeft">
                                  <p class="msjChat">{{$msj->mensaje}}</p>
                                  @if(isset($msj['imagen']))
                                      <img src="{{asset('images/msjCliente/'.$msj['imagen'])}}" class="img-fluid img-rounded imagenMsj" > 
                                      <a href="{{ asset('images/msjCliente/'.$msj['imagen']) }}" class="txtImagen"  download>Descargar Imagen</a>   
                                  @else
                                      <img src="{{asset('images/msjTecnico/default.png')}}" class="img-fluid img-rounded" hidden> 
                                      <a href="{{ asset('images/msjTecnico/default.png') }}" download hidden>Descargar Imagen</a>   
                                  @endif    
                                  <span class="fecha_mensajes" style="text-align:right;">{{$msj->created_at->format('d/m/Y')}}, {{$msj->created_at->format('h:i A')}} </span>
                              </div>
                            </div>
                          @endif
                        @endif
                      @endforeach
                    @else
                      <div class="sinmensajes" id="sinMsj">
                          <p>Sin mensajes...</p>
                      </div>
                    @endif
                  </div> 
               
         
                  <div id="messagesContainer" class="col-md-12 containermsj">
                    @if(session('status'))
                      <p class="alert alert-success message-alert" ><i class="fa-solid fa-circle-check fa-lg"></i>{{ Session('status') }}</p>
                    @elseif(session('error'))
                      <p class="alert alert-danger message-alert" >{{ Session('error') }}</p>
                    @elseif(session('errorText'))
                      <p class="alert alert-danger message-alert" >{{ Session('errorText') }}</p>
                    @endif
                  </div>
                </div>
            
                <div class="row" id="rowMensaje">
                  <div class="col-md-12 form-group mb-3 " style="padding:0px;">
                    <textarea  class="form-control"  name="mensaje" id="mensaje" cols="40" rows="4"  placeholder="Escribe el mensaje" ></textarea>
                  </div>
                </div>
              </div>
              <div class="row contentCheckbox-Resuelto w-100" id="rowImagenBox">
                <div class="col-md-8 form-group mb-3 content-file">
                  <input type="file" name="imagen" accept="image/*" id="imagenMsj" class="msjFile">
                </div>

                <div class="col-md-4 content_starsCheckbox d-flex justify-content-end">
                  <div class="checkbox-containerResuelto"  id="checkboxContainerResuelto">
                    <input class="checkbox" type="checkbox" id="resuelto" name="resuelto"  value="on">
                    <label class="labelResuelto" for="resuelto" >Ticket resuelto</label>
                  </div>
                  
                  <div class="stars-calif">
                    <div class="container-estrellas" >
                      <div class="estrellas estrellasTicket"></div>
                      <a href="/calificaciones/ticket/{{$idTicket}}" class="ml-2 link-calif">Ver calificación(es)</a>
                    </div>
                  </div>
                </div>
              </div>

              <div class="content-responder" id="btnEnviarMsj">
                <div>
                  <input type="submit" id="submitButton" value="Enviar mensaje" class="btn-primary rounded-0 py-2 px-4 btnResponder" >
                </div>
                <div>
                   <button id="btnReescribir" class="btn-info btnReescribir" type="button" style="display: none;">Reescribir</button>
                </div>
                <div>
                   <button id="btnCorregir" class="btn-info btnCorregir" type="button" style="display: none;">Corregir</button>
                </div>
              </div>

              <div class="textoTicketResuelto shadow-sm p-3 mb-5 bg-body rounded" id="txtTicketResuelto">
                  <p><i class="fa-solid fa-circle-check fa-xl"></i>Ticket resuelto, esperando calificación de la asistencia.</p>
              </div>

              @if($ticket->calificaciones()->exists())
                  <!-- Cuadro cuando ya esta cerrado el ticket -->
                  <div class="textoTicketCerrado shadow-sm p-3 mb-5 bg-body rounded" id="txtTicketCerrado">
                    <p><i class="fa-solid fa-circle-check fa-xl"></i>Ticket resuelto</p>
                      <div class="container-stars" >
                            <div class="stars"></div>
                          
                            <span class="calificacion_nivel" id="nivel_calif">{{$ticket->ultimaCalificacion->nivel_satisfaccion}}</span>
                            <a href="/calificaciones/ticket/{{$idTicket}}" class="text-calificacionCerrado ml-2">Ver calificación(es)</a>
                      </div>
                  </div>
              @endif
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>

  @php
    $estadoTkt = $ticket->estado->nombre;
  @endphp
@stop

@section('css')
 <link rel="stylesheet" href="/css/styleForm.css">
@stop

@section('js')
    <!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="../../js/jquery-3.3.1.min.js"></script> -->
    <!-- <script src="../../js/popper.min.js"></script> -->
    <!-- <script src="../../js/bootstrap.min.js"></script> -->
    <!-- <script src="../../js/jquery.validate.min.js"></script>
    <script src="../../js/main.js"></script> -->

    <script src="https://kit.fontawesome.com/6f3d5551a7.js" crossorigin="anonymous"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>


<script>
  function cargarPaginaAnterior() {
      window.location.href = document.referrer;
      return false;
  }
</script>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Verifica si hay un mensaje de sesión
        var statusMessage = "{{ session('status') }}";
        var errorMessage = "{{ session('error') }}";

        if (statusMessage || errorMessage) {
            // Espera un poco para asegurarse de que el mensaje de sesión se haya renderizado correctamente
            setTimeout(function() {
                // Ajusta el scroll al final de la página
                document.getElementById('messagesContainer').scrollIntoView({ behavior: 'smooth' });
            }, 500); // Espera medio segundo antes de mover el scroll
        }
    });
  </script>
  
<script>
document.addEventListener("DOMContentLoaded", function() {

    const chatContainer = document.getElementById('chat');
    chatContainer.scrollTop = chatContainer.scrollHeight;

    document.getElementById('contactForm').addEventListener('submit', function(event) {
        event.preventDefault(); // Evita que el formulario se envíe por defecto

        document.getElementById('submitButton').value = 'Enviando...'; 
        document.getElementById('submitButton').disabled = true; 
        
        var formData = new FormData(this);
        document.getElementById('mensaje').disabled = true; 




        fetch('/mensaje/tecnico/ticket/{{$ticket->id}}', { 
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {

          console.log(data);

          const checkbox=document.getElementById('resuelto');


          if (data.status === 'success') {
            // Mostrar mensaje de éxito
            let successMessageElement = document.createElement('p');
            successMessageElement.className = 'alert alert-success message-alert';
            successMessageElement.innerHTML = `<i class="fa-solid fa-circle-check fa-lg"></i>${data.msjSuccess}`;
            document.getElementById('messagesContainer').innerHTML = '';
            document.getElementById('messagesContainer').appendChild(successMessageElement);

            setTimeout(() => {
                document.getElementById('messagesContainer').innerHTML = '';
            }, 5000); // Elimina el mensaje después de 5 segundos (5000 milisegundos)

            // Limpiar los campos de entrada
            document.getElementById('mensaje').value = ''; // Limpia el textarea de mensaje
            document.getElementById('imagenMsj').value = ''; // Limpia el input de imagen

          } else if (data.errors) {
           
            // Mostrar mensaje de error
            let errorMessageElement = document.createElement('p');
            errorMessageElement.className = 'alert alert-danger message-alert';

            let errorValidation=Object.values(data)[0];
            let mensajeError=Object.values(errorValidation)[0];
      
            errorMessageElement.className = 'alert alert-danger message-alert';
            errorMessageElement.innerHTML = `<i class="fa-solid fa-circle-exclamation fa-lg"></i>${mensajeError}`;
            document.getElementById('messagesContainer').innerHTML = '';
            document.getElementById('messagesContainer').appendChild(errorMessageElement);

            alert('{{ session('error') }}');

            setTimeout(() => {
                document.getElementById('messagesContainer').innerHTML = '';
            }, 5000)



          }else if(data.animoNegativo){

            if (checkbox.checked) {
              checkbox.checked = true;
            } 
            
            // Mostrar mensaje de error de estado de animo negativo
            let errorMessageElement = document.createElement('p');
            errorMessageElement.className = 'alert alert-danger message-alert';

            let mensajeError=data.animoNegativo;
      
            errorMessageElement.className = 'alert alert-danger message-alert';
            errorMessageElement.innerHTML = `<i class="fa-solid fa-circle-exclamation fa-lg"></i>${mensajeError}`;
            document.getElementById('messagesContainer').innerHTML = '';
            document.getElementById('messagesContainer').appendChild(errorMessageElement);

            setTimeout(() => {
                document.getElementById('messagesContainer').innerHTML = '';
            }, 5000)

            const btnReescribir = document.getElementById('btnReescribir');
            const mensajeTextarea = document.getElementById('mensaje');

            btnReescribir.style.display = 'block';

            btnReescribir.addEventListener('click', function() {
                const nuevoMensaje = data.textoReescrito;
                mensajeTextarea.value =  nuevoMensaje;
                btnReescribir.style.display = 'none';
            });

            mensajeError='';

          }else if(data.textoErrores){

            if (checkbox.checked) {
                  checkbox.checked = true;
            } 

            // console.log(data.textoErrores);

            // Mostrar mensaje de error de estado de animo negativo
            let errorMessageElement = document.createElement('p');
            errorMessageElement.className = 'alert alert-danger message-alert';

            let mensajeError=data.textoErrores;

            errorMessageElement.className = 'alert alert-danger message-alert';
            errorMessageElement.innerHTML = `<i class="fa-solid fa-circle-exclamation fa-lg"></i>${mensajeError}`;
            document.getElementById('messagesContainer').innerHTML = '';
            document.getElementById('messagesContainer').appendChild(errorMessageElement);

            setTimeout(() => {
                document.getElementById('messagesContainer').innerHTML = '';
            }, 5000)

            const btnCorregir = document.getElementById('btnCorregir');
            const mensajeTextarea = document.getElementById('mensaje');

            btnCorregir.style.display = 'block';

            btnCorregir.addEventListener('click', function() {
                const nuevoMensaje = data.textoCorregido;
                mensajeTextarea.value =  nuevoMensaje;
                btnCorregir.style.display = 'none';

                mensajeError = ''; 

               
            });

     
          }

          if(!data.hasOwnProperty('errors')){

            mensajeError = ''; 
            // Crear elementos HTML para el mensaje y la imagen
            const messageElement = document.createElement('div');

            chatContainer.classList.add('contenedorRight');
            messageElement.classList.add('msjUserRight');
           
              if(data.mensaje){
                messageElement.innerHTML = `<p class="msjChat">${data.mensaje}</p>`;
              }
            
              if (data.imagen) {
                  const imageElement = document.createElement('img');
                  imageElement.src = `/images/msjTecnico/${data.imagen}`;
                  imageElement.classList.add('img-fluid', 'img-rounded');
                  imageElement.style.width = '60px';
                  messageElement.appendChild(imageElement);
                  messageElement.innerHTML += `<br><a href="/images/msjTecnico/${data.imagen}"  class="txtImagen" download>Descargar Imagen</a>`;
              }

              if(data.mensaje || data.imagen){
                const fechaActual = new Date().toLocaleString('es-ES', {
                  year: 'numeric',
                  month: '2-digit',
                  day: '2-digit',
                  hour: '2-digit',
                  minute: '2-digit',
                  hour12: true
                }).toUpperCase().replace(/\./g, '').replace(/\s+(AM|PM)/g, '$1').replace(/(A)\s+(M)/g, '$1$2').replace(/(P)\s+(M)/g, '$1$2'); // Elimina el espacio entre A y M, y P y M

                
                messageElement.innerHTML += `<span class="fecha_mensajes" style="text-align:right;">${fechaActual}</span>`;
                $('#sinMsj').hide();

                messageElement.classList.add('mensajesRight');
               
              }
         
              // Insertar el mensaje y la imagen en el div "chat"
              chatContainer.appendChild(messageElement);

              chatContainer.scrollTop = chatContainer.scrollHeight;

              mensajeError='';

          }

          
            document.getElementById('submitButton').value = 'Enviar mensaje'; 
            document.getElementById('submitButton').disabled = false; 

            document.getElementById('mensaje').disabled = false; 
        })
        .catch(error => console.error('Error:', error));

    });
});
</script>

<script>
  $(document).ready(function() {
      checkTicketStatus();

      // Verificar el estado del ticket cada 10 segundos
      setInterval(checkTicketStatus, 10000);
  });

  function checkTicketStatus() {
      $.ajax({
          url: '{{ route('ticketEstado', $idTicket) }}',
          type: 'GET',

          success: function(response) {

              if (response.estado === 'Resuelto') {

                $('#rowMensaje').hide();
                $('#rowImagenBox').hide();
                $('#btnEnviarMsj').hide();
                $('#txtTicketCerrado').hide();
                $('#txtTicketResuelto').show();
              
                  // $('#form-content').hide();

              } else if(response.estado === 'Cerrado'){
                  $('#rowMensaje').hide();
                  $('#rowImagenBox').hide();
                  $('#txtTicketResuelto').hide();
                  $('#btnEnviarMsj').hide();
                  $('#txtTicketCerrado').show();
                  $('#container-stars').show();

              } else if(response.estado === 'Reabierto') {
                  $('#rowMensaje').show();
                  $('#rowImagenBox').show();
                  $('#btnEnviarMsj').show();
                  $('#txtTicketResuelto').hide();
                  $('#txtTicketCerrado').hide();
                  $('.container-estrellas').show();
                  $('#checkboxContainerResuelto').addClass('alinear-checkbox');
                 

              } else {
                  $('#rowMensaje').show();
                  $('#rowImagenBox').show();
                  $('#btnEnviarMsj').show();
                  $('#txtTicketResuelto').hide();
                  $('#txtTicketCerrado').hide();
                  $('.container-estrellas').hide();
                  $('#checkboxContainerResuelto').removeClass('alinear-checkbox');

                  
              }

          
          },
          error: function(error) {
              console.error("Error al obtener el estado del ticket:", error);
          }
      });
  }

  
</script>

<!-- al cargar la página -->
<script>
  $(document).ready(function() {
      checkNivelSatisfaccion();

  });

  function  checkNivelSatisfaccion() {

    const form = document.getElementById('contactForm');
    const starContainer = document.querySelector('.estrellas');
    const starContent = document.querySelector('.stars');

      $.ajax({
          url: '{{ route('nivelSatisfaccion', $idTicket) }}',
          type: 'GET',

          success: function(response) {

        //  console.log(response.nivel);

              let num = 0;

              switch (response.nivel) {
                case "Totalmente satisfecho":
                  num = 5;
                break;
                case "Satisfecho":
                  num = 4;
                break;
                case "Neutral":
                  num = 3;
                break;
                case "Poco satisfecho":
                  num = 2;
                break;
                case "Nada satisfecho":
                  num = 1;
                break;
              
                default:
                  break;
              }

   
              starContainer.innerHTML = '';
              starContent.innerHTML = '';
              
              // Mostrar estrellas seleccionadas
              for (let i = 0; i < num; i++) {
                  const star = document.createElement('i');
                  star.className = 'fa-solid fa-star fa-lg';
                  star.style.color = '#FFD700'; // Color amarillo para estrellas completas
                  starContainer.appendChild(star);
              }

              // Mostrar estrellas incompletas
              for (let i = 0; i < 5 - num; i++) {
                  const star = document.createElement('i');
                  star.className = 'fa-solid fa-star fa-lg';
                  star.style.color = '#ccc'; // Color gris para estrellas incompletas
                  starContainer.appendChild(star);
              }

              // ------------------------------
              for (let i = 0; i < num; i++) {
                  const star = document.createElement('i');
                  star.className = 'fa-solid fa-star fa-lg';
                  star.style.color = '#FFD700'; // Color amarillo para estrellas completas
                  starContent.appendChild(star);
              }

              // Mostrar estrellas incompletas
              for (let i = 0; i < 5 - num; i++) {
                  const star = document.createElement('i');
                  star.className = 'fa-solid fa-star fa-lg';
                  star.style.color = '#ccc'; // Color gris para estrellas incompletas
                  starContent.appendChild(star);
              }

              
             

          
          },
          error: function( error) {
              console.error("Error al obtener la calificacion del ticket:", error);
          }
      });
  }
</script>
<script>
function checkPreviousUrl(ticketId) {
    // Obtener la URL anterior
    var previousUrl = document.referrer;

    // Crear la URL esperada con el ID del ticket
    var expectedUrl = '/detalles/' + ticketId;

    // Comparar con la URL deseada
    if (previousUrl.endsWith(expectedUrl)) {
        // Si es igual, regresar dos páginas atrás
        history.go(-3);
    } else {
       // Si no es igual, regresa una página atrás
       history.go(-1);
    }
}
</script>



@stop