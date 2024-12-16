@extends('adminlte::page')

@section('title', 'Ticket #' . $idTicket)


@section('content')
  <div class="content ">
    <div class="container ">
      <div class="row align-items-stretch no-gutters contact-wrap ">
        <div class="col-md-12 ">
          <div class="form h-100  content-fondo" >
              <div class="content-btnVolver">
                  <a style="margin-top:8px;" href="#" class="btn btn-dark btn-volver" onclick="return cargarPaginaAnterior();">
                      <i class="fa-solid fa-arrow-left fa-lg"></i>Volver
                  </a>
              </div>
              <h3 class="tituloMsjTecnico">Ticket #{{$idTicket}} </h3>
          

              <form action="/mensaje/cliente/ticket/{{$ticket->id}}" class="mb-5" method="post" id="contactForm" name="contactForm" enctype="multipart/form-data">
              @csrf

                <div class="cuadro1">
                      INFORMACIÓN DEL TICKET
                </div>
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
 
                @if($ticket->asignado_a)
                  <div class="content-chat">
                      <div class="row">
                        <div class="col-md-12 form-group mb-3 cuadro2">
                            CHAT CON EL TÉCNICO DE SOPORTE "{{strtoupper($tecnico->name)}}"
                        </div>
                      </div>

                      <div class="row">   
                        <div class="card col-md-12 form-group mb-3 overflow-auto chat" id="chat">
                          @if($ticket->mensajes()->exists())
                            @foreach($ticket->mensajes as $msj)
                              @if($msj->user_id == Auth::user()->id)
                                @if($msj->mensaje || isset($msj['imagen']))
                                  <div class="mensajesRight">
                                      <h5>{{$msj->mensaje}}</h5>
                                      @if(isset($msj['imagen']))
                                          <img src="{{asset('images/msjCliente/'.$msj['imagen'])}}" class="img-fluid img-rounded" width="60px"> 
                                          <a href="{{ asset('images/msjCliente/'.$msj['imagen']) }}" style="font-size:12px;" download>Descargar Imagen</a>   
                                      @else
                                          <img src="{{asset('images/msjCliente/default.png')}}" class="img-fluid img-rounded" width="60px" hidden> 
                                          <a href="{{ asset('images/msjCliente/default.png') }}" download hidden>Descargar Imagen</a>   
                                      @endif    
                                      <span class="fecha_mensajes">{{$msj->created_at->format('d-m-Y')}}, {{$msj->created_at->format('H:i:s')}} </span>
                                  </div>
                                @endif
                              @else
                                @if($msj->mensaje || isset($msj['imagen']))
                                  <div class="mensajesLeft">
                                      <h5>{{$msj->mensaje}}</h5>
                                      @if(isset($msj['imagen']))
                                          <img src="{{asset('images/msjTecnico/'.$msj['imagen'])}}" class="img-fluid img-rounded" width="60px"> 
                                          <a href="{{ asset('images/msjTecnico/'.$msj['imagen']) }}" style="font-size:12px;" download>Descargar Imagen</a>   
                                      @else
                                          <img src="{{asset('images/msjTecnico/default.png')}}" class="img-fluid img-rounded" width="60px" hidden> 
                                          <a href="{{ asset('images/msjTecnico/default.png') }}" download hidden>Descargar Imagen</a>   
                                    @endif    
                                    <span class="fecha_mensajes">{{$msj->created_at->format('d-m-Y')}}, {{$msj->created_at->format('H:i:s')}}</span>
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
                      </div>  
                      <div class="row">
                        <div id="messagesContainer" class="col-md-12 containermsj">
                          @if(session('status'))
                            <p class="alert alert-success message-alert" ><i class="fa-solid fa-circle-check fa-lg"></i>{{ Session('status') }}</p>
                          @elseif(session('error'))
                            <p class="alert alert-danger message-alert" >{{ Session('error') }}</p>
                          @endif
                        </div>
                    </div>   
                    
                  </div>
      
                    <div class="row"  id="rowMensaje">
                      <div class="col-md-12 form-group mb-3 " style="padding:0px;">
                        <textarea class="form-control" name="mensaje" id="mensaje" cols="40" rows="4"  placeholder="Escribe el mensaje" >{{old('mensaje')}}</textarea>
                      </div>
                    </div>

                    <div class="row" id="rowImagen">
                        <div class="col-md-6 form-group mb-3 content-file">
                          <input type="file" name="imagen" accept="image/*" id="imagenMsj">
                        </div>
                        
                        @if($ticket->calificaciones()->exists())
                          <div  class="col-md-6 d-flex justify-content-end" >
                            <div class="container-estrellas" >
                                <div class="estrellas"></div>

                                <a href="/calificaciones/ticket/{{$idTicket}}" class="ml-2">Ver calificación(es)</a>
                            </div>
                          </div>
                        @endif
                    </div>
                    
                    <div class="content-responder" id="botonEnviarMensaje">
                      <div>
                        <input type="submit" id="submitButton" value="Enviar mensaje" class="btn-primary rounded-0 py-2 px-4 btnResponder" >
                      </div>
                    </div>

                
                    <div class="col-md-6  content-opcionesResuelto mx-auto" id="content-opcionesResuelto" >
                        <h5 class="texto_ticketResuelto">El ticket ha sido resuelto</h5>
                        <div class="row">
                          <div class="col-md-3">
                            <h6 class="textoSeleccionar">Seleccionar:</h6>
                          </div>
                          <div class="col-md-3">
                              <div class="select-container"> 
                                  <select class="select-box" name="opcion" id="opcion">
                                      <option value="">Nivel de satisfacción</option>
                                      <option value="Totalmente satisfecho" data-stars="5">Totalmente satisfecho</option> 
                                      <option value="Satisfecho" data-stars="4">Satisfecho</option>
                                      <option value="Neutral" data-stars="3">Neutral</option>
                                      <option value="Poco satisfecho" data-stars="2">Poco satisfecho</option>
                                      <option value="Nada satisfecho" data-stars="1">Nada satisfecho</option>
                                  </select>
                              </div>
                          </div>
                        </div>

                          <h6 class="preguntaCheckbox">¿Qué quieres hacer?</h6>
                    
                        <div class="content-checkbox">
                          <div class="checkboxReabrir">
                              <input class="checkbox" type="checkbox" id="reabrir" name="accion" value="Reabrirlo">
                              <label class="labelReabrir" for="reabrir" >Reabrirlo</label>
                          </div>
                          <div class="checkboxCerrar">
                              <input class="checkbox" type="checkbox" id="cerrar" name="accion" value="Cerrarlo">
                              <label class="labelCerrar" for="cerrar">Cerrarlo</label>
                          </div>
                          <input type="hidden" name="accion_seleccionada" id="accion_seleccionada">
                        </div>

                        <div class="row mensajeResuelto">
                            <div class="col-md-12 form-group mb-3 " style="padding:0px;">
                                <textarea class="form-control" name="comentario" id="comentario" cols="40" rows="4"  placeholder="Escribe un comentario (opcional)" ></textarea>
                            </div>
                        </div>

                        
                        <div class="contentResponderResuelto">
                            <div>
                                <input type="submit" id="btnResponder_resuelto"  value="Enviar" class="btn-primary rounded-0 py-2 px-4 btnResponder">
                            </div>
                        </div>
                        
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
                  </div>
                @endif
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
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

<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
   const backButton = document.querySelector('.btn-volver');
   
   backButton.addEventListener('click', function() {
       const previousUrl = document.referrer;
       
       // Verifica si el referrer es diferente de la misma página
       if (previousUrl !== window.location.href) {
           window.history.back();
       } else {
           // Si es la misma página, busca una vista diferente en la historia
           let currentUrl = window.location.href;
           while (currentUrl === previousUrl) {
               previousUrl = history.go(-1);
               currentUrl = window.location.href;
               
               // Si llega al inicio de la historia y aún no encontró una vista diferente, redirige a una ruta específica
               if (history.length <= 1 && currentUrl === previousUrl) {
                   window.location.href = '/dashboard'; // Reemplaza con la ruta que desees
                   break;
               }
           }
       }
       
   });
});
</script> -->

  <script>
    document.addEventListener("DOMContentLoaded", function() {
        var contentChat = document.getElementById('chat');
        contentChat.scrollTop = contentChat.scrollHeight;
    });
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
  document.addEventListener('DOMContentLoaded', function() {
    const checkboxes = document.querySelectorAll('.checkbox');
    
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const accionSeleccionada = this.value;
            document.getElementById('accion_seleccionada').value = accionSeleccionada;
            
       
        });
    });
});

</script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    document.getElementById('contactForm').addEventListener('submit', function(event) {
    event.preventDefault(); // Evita que el formulario se envíe por defecto

      document.getElementById('submitButton').value = 'Enviando...'; 
      document.getElementById('submitButton').disabled = true; 
      document.getElementById('btnResponder_resuelto').disabled = true; 
      document.getElementById('btnResponder_resuelto').value = 'Enviando...'; 
  
    
        var formData = new FormData(this);
        toggleFormElements(true);

        fetch('/mensaje/cliente/ticket/{{$idTicket}}', { 
            method: 'POST',
            body: formData
            
        })
        .then(response => response.json())
        .then(data => {
       
          // console.log(data.accion);

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

                $('#content-opcionesResuelto').hide();

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
                
                setTimeout(() => {
                    document.getElementById('messagesContainer').innerHTML = '';
                }, 5000)
            }

          if(!data.hasOwnProperty('errors')){
            // Crear elementos HTML para el mensaje y la imagen
            const messageElement = document.createElement('div');
    
            if(data.mensaje){
             messageElement.innerHTML = `<h5>${data.mensaje}</h5>`;
            }
            
            
            if (data.imagen) {
                const imageElement = document.createElement('img');
                imageElement.src = `/images/msjCliente/${data.imagen}`;
                imageElement.classList.add('img-fluid', 'img-rounded');
                imageElement.style.width = '60px';
                messageElement.appendChild(imageElement);
                messageElement.innerHTML += `<br><a href="/images/msjCliente/${data.imagen}" style="font-size:12px;" download>Descargar Imagen</a>`;
            }

            if(data.mensaje || data.imagen){
              const fechaActual= new Date().toLocaleString('es-Es');
              messageElement.innerHTML += `<span class="fecha_mensajes">${fechaActual}</span>`;
                $('#sinMsj').hide();

                messageElement.classList.add('mensajesRight');
            }

            if(data.accion === 'Reabrirlo'){
                  $('#content-opcionesResuelto').hide();
                  $('#rowMensaje').show();
                  $('#rowImagen').show();
                  $('#botonEnviarMensaje').show();

                  document.getElementById('opcion').value = ''; 
                  $('.checkbox').prop('checked', false);
                  $('#accion_seleccionada').val('');
                  document.getElementById('comentario').value = ''; 
                  document.getElementById('estado_id').value = 'Reabierto';
            }else if(data.accion === 'Cerrarlo'){
                document.getElementById('estado_id').value = 'Cerrado';

                  document.getElementById('opcion').value = ''; 
                  $('.checkbox').prop('checked', false);
                  $('#accion_seleccionada').val('');
                  document.getElementById('comentario').value = ''; 
                  document.getElementById('estado_id').value = 'Reabierto';
                  
            }
         
          
            // Insertar el mensaje y la imagen en el div "chat"
            document.getElementById('chat').appendChild(messageElement);
          }

            // Limpiar los campos de entrada
            document.getElementById('mensaje').value = ''; // Limpia el textarea de mensaje
            document.getElementById('imagenMsj').value = ''; // Limpia el input de imagen
            document.getElementById('submitButton').value = 'Enviar mensaje'; 
            document.getElementById('submitButton').disabled = false; 
            document.getElementById('btnResponder_resuelto').disabled = false; 
            document.getElementById('btnResponder_resuelto').value = 'Enviar'; 
  
           
            toggleFormElements(false);

        })
        .catch(error => console.error('Error:', error));
    });

    
});
function toggleFormElements(disable) {

  const formElements = [
    '#opcion',
    '.checkbox',
    '#comentario',
    '.btnResponder'
];

    formElements.forEach(element => {
        const el = document.querySelector(element);
        if (el) {
            el.disabled = disable;
        }
      });
      document.getElementById('mensaje').readOnly = disable;
  }
  
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

            // console.log(response);

            

              if (response.estado === 'Resuelto') {

                $('#content-opcionesResuelto').show();
                $('#rowMensaje').hide();
                $('#rowImagen').hide();
                $('#botonEnviarMensaje').hide();
                $('#txtTicketCerrado').hide();
              
                  // $('#form-content').hide();

              } else if(response.estado === 'Cerrado'){
                  $('#content-opcionesResuelto').hide();
                  $('#rowMensaje').hide();
                  $('#rowImagen').hide();
                  $('#botonEnviarMensaje').hide();
                  $('#txtTicketCerrado').show();


              } else {
                  $('#content-opcionesResuelto').hide();
                  $('#rowMensaje').show();
                  $('#rowImagen').show();
                  $('#botonEnviarMensaje').show();
                  $('#txtTicketCerrado').hide();
              }


          },
          error: function(xhr, status, error) {
              console.error("Error al obtener el estado del ticket:", error);
          }
      });
  }

  
</script>

<script>
    $(document).ready(function() {
        // Función para controlar las selecciones de los checkboxes
        function controlCheckboxes() {
            if ($('#reabrir').is(':checked')) {
                $('#cerrar').prop('disabled', true);
            } else {
                $('#cerrar').prop('disabled', false);
            }
            
            if ($('#cerrar').is(':checked')) {
                $('#reabrir').prop('disabled', true);
            } else {
                $('#reabrir').prop('disabled', false);
            }
            
        }

        // Agregar evento de cambio a los checkboxes
        $('#reabrir, #cerrar').change(controlCheckboxes);

        // Llamar a la función inicialmente para establecer el estado correcto
        controlCheckboxes();
    });
</script>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('contactForm');
    const starContainer = document.querySelector('.estrellas');
    const starContent = document.querySelector('.stars');
    const selectElement = document.querySelector('#opcion'); 

    form.addEventListener('change', function(e) {
        if (e.target.id === 'opcion') {
            const selectedOption = e.target.selectedOptions[0];
            const starsNumber = parseInt(selectedOption.getAttribute('data-stars'));
            updateStars(starsNumber);

             // Actualizar el valor del span
            const nivelCalificacionSpan = document.getElementById('nivel_calif');
            nivelCalificacionSpan.textContent = selectedOption.value;
        }

    });

    function updateStars(numberOfStars) {
        starContainer.innerHTML = '';
        starContent.innerHTML = '';
        
        // Mostrar estrellas seleccionadas
        for (let i = 0; i < numberOfStars; i++) {
            const star = document.createElement('i');
            star.className = 'fa-solid fa-star fa-lg';
            star.style.color = '#FFD700'; // Color amarillo para estrellas completas
            starContainer.appendChild(star);
        }

        // Mostrar estrellas incompletas
        for (let i = 0; i < 5 - numberOfStars; i++) {
            const star = document.createElement('i');
            star.className = 'fa-solid fa-star fa-lg';
            star.style.color = '#ccc'; // Color gris para estrellas incompletas
            starContainer.appendChild(star);
        }

        // -------------------------------------------------------------------------

        // Mostrar estrellas seleccionadas
        for (let i = 0; i < numberOfStars; i++) {
            const star = document.createElement('i');
            star.className = 'fa-solid fa-star fa-lg';
            star.style.color = '#FFD700'; // Color amarillo para estrellas completas
            starContent.appendChild(star);
        }

        // Mostrar estrellas incompletas
        for (let i = 0; i < 5 - numberOfStars; i++) {
            const star = document.createElement('i');
            star.className = 'fa-solid fa-star fa-lg';
            star.style.color = '#ccc'; // Color gris para estrellas incompletas
            starContent.appendChild(star);
        }

     
    }
});

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


@stop