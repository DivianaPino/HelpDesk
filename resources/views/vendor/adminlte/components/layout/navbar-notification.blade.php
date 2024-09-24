<style>   
    .dropdown-menu {
        max-width: 50px; /* Ajusta este valor según sea necesario */
        max-height: 500px;
        overflow-y: auto;
    }
    
   .dropdown-item {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%; /* Asegura que el elemento ocupe todo el ancho disponible */
        border-bottom: solid 2px #f2f4f5;
    }
   .dropdown-item i,.dropdown-item span {
        display: block; /* Hace que el ícono y la fecha sean bloques para aplicarles el ancho */
        width: 100%; /* Aplica el ancho completo al ícono y la fecha */
        white-space: nowrap; /* Evita que el texto se envuelva */
        overflow: hidden; /* Oculta cualquier contenido que exceda el ancho definido */
        text-overflow: ellipsis; /* Muestra puntos suspensivos si el contenido excede el ancho */
    }

    .dropdown-item i{
        width: 10% !important;
    }


    .iconStyle{
        margin-bottom:10px;
      
    }
    .titleNoti{
        font-size: 14px !important;
        font-family: Georgia, 'Times New Roman', Times, serif; 
        font-weight: 600;
    }

    .msjStyle{
        color: #5f5d5d !important;
        font-weight: 600;
        font-size: 15px !important;
    }
</style>


{{-- Navbar notification --}}

<li class="{{ $makeListItemClass() }}" id="{{ $id }}">

    {{-- Link --}}
    <a @if($enableDropdownMode) href="" @endif {{ $attributes->merge($makeAnchorDefaultAttrs()) }}>
        {{-- Icon --}}
        <i class="{{ $makeIconClass() }}"></i>

        {{-- Badge --}}
        <span id="badge" class="{{ $makeBadgeClass() }}">
            @if(auth()->check())
                <span id="notificationCount">{{ $notificacionesNoLeidas->count() }}</span>
            @else
                0
            @endif
        </span>
    </a>



   

    {{-- Dropdown Menu --}}
    @if($enableDropdownMode)

        <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">

        @if(auth()->check() && auth()->user()->unreadNotifications->count() > 0)
            @foreach(auth()->user()->unreadNotifications as $notificacion)

                <!-- Determinar el tipo de notificacion, y dependiendo de este mostrar su url correspondiente -->
                @php
                    $url = '';
                    if ($notificacion->type === 'App\Notifications\CalificacionNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['calificacion_idTicket'])->first();
                        $id_usuario_Ticket=$ticket->user_id;
                        $usuario=App\Models\User::where('id',$id_usuario_Ticket)->first();
                        $usuarioNombre=$usuario->name;


                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['calificacion_idTicket']}/mensajeTecnico");
                    
                    } elseif ($notificacion->type === 'App\Notifications\TicketNotification') {

                        $usuario=App\Models\User::where('id',$notificacion->data['ticket_userId'] )->first();
                        $usuarioNombre=$usuario->name;

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['ticket_id']}");
                        
                    }elseif ($notificacion->type === 'App\Notifications\MensajeClienteNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['mensaje_ticketId'])->first();
                        $usuario=App\Models\User::where('id',$ticket->user_id)->first();
                        $usuarioNombre=$usuario->name;

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['mensaje_ticketId']}/mensajeTecnico");


                    }elseif ($notificacion->type === 'App\Notifications\MensajeTecnicoNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['mensaje_ticketId'])->first();
                        $tecnicoSop=$ticket->asignado_a;

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['mensaje_ticketId']}/mensajeCliente");
                    }



                @endphp


                <a href="{{ $url }}" class="dropdown-item" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">

                        @if($notificacion->type ==='App\Notifications\CalificacionNotification')
                            <i class="fas fa-star mr-4  iconStyle "> Calificación - Ticket #{{ $notificacion->data['calificacion_idTicket'] }} </i>
                            <span class="float-right text-muted text-sm">{{ $notificacion->data['calificacion_satisfaccion'] }}</span>
                            <span class="float-right text-muted text-sm">Usuario: {{ $usuarioNombre }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>
        
                        @elseif($notificacion->type ==='App\Notifications\TicketNotification')
                            <div class="d-flex align-items-center">
                                <i class="fas fa-ticket-alt  iconStyle"></i>
                                <h6 class="titleNoti">Nuevo Ticket</h6>
                            </div>
                            <span class="float-right text-muted text-sm">{{ $notificacion->data['ticket_asunto'] }}</span>
                            <span class="float-right text-muted text-sm">Cliente: {{ $usuarioNombre }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>

                        @elseif($notificacion->type ==='App\Notifications\MensajeClienteNotification')
                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope iconStyle"></i>
                                <h6 class="titleNoti">Mensaje cliente - Ticket #{{ $notificacion->data['mensaje_ticketId'] }}</h6>
                            </div>
                            <span class="float-right text-muted text-sm msjStyle">{{ $notificacion->data['mensaje_mensaje'] }}</span>
                            <span class="float-right text-muted text-sm">Cliente: {{ $usuarioNombre }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>

                        @elseif($notificacion->type ==='App\Notifications\MensajeTecnicoNotification')

                            <div class="d-flex align-items-center">
                                <i class="fas fa-envelope iconStyle"></i>
                                <h6 class="titleNoti">Mensaje técnico - Ticket #{{ $notificacion->data['mensaje_ticketId'] }}</h6>
                            </div>
                            <span class="float-right text-muted text-sm msjStyle">{{ $notificacion->data['mensaje_mensaje'] }}</span>
                            <span class="float-right text-muted text-sm">Técnico de soporte: {{ $tecnicoSop }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>
                        
                        @endif
                 
                </a>
            @endforeach

        @else
         <p class="text-sm text-center" >Sin notificaciones</p>

        @endif
<!-- 
            {{-- Custom dropdown content provided by external source --}}
            <div class="adminlte-dropdown-content"></div>

            {{-- Dropdown divider --}}
            <div class="dropdown-divider"></div>

            {{-- Dropdown footer with link --}}
            <a href="{{ $attributes->get('href') }}" class="dropdown-item dropdown-footer">
                @isset($dropdownFooterLabel)
                    {{ $dropdownFooterLabel }}
                @else
                    <i class="fas fa-lg fa-search-plus"></i>
                @endisset
            </a> -->

        </div>

    @endif

</li>

{{-- If required, update the notification periodically --}}

@if (! is_null($makeUpdateUrl()) && $makeUpdatePeriod() > 0)
@push('js')





<!-- <script>
$(document).ready(function() {
    $.ajax({
       
        success: function(response) {
            console.log(response);
            // Aquí puedes procesar la respuesta según sea necesario
        },
        error: function(xhr, status, error) {
            console.error('Error al obtener la calificación del ticket:', status, error);
            // Aquí puedes agregar más detalles de error si es necesario
        }
    });
});
</script> -->
<!-- <script>
document.addEventListener('DOMContentLoaded', function() {
    const badgeElement = document.getElementById('badge');
    const originalCount = parseInt(badgeElement.textContent);

    function updateBadge(count) {
        badgeElement.textContent = count;
    }

    // Event listener para actualizar el contador cuando se marca una notificación como leída
    window.addEventListener('message', function(event) {
        if (event.data.type === 'notificationRead') {
            updateBadge(originalCount - 1);
        }
    });
});
</script> -->

<!-- <script>
    document.addEventListener('DOMContentLoaded', function() {
    updateNotificationCount();

    // Actualiza cada 30 segundos
    setInterval(updateNotificationCount, 30000);
});

function updateNotificationCount() {
    fetch('mensajeClienteNoti')
        .then(response => response.json())
        .then(data => {
            document.getElementById('badge').textContent = data.count;
        })
        .catch(error => console.error('Error:', error));
}

</script> -->

<script>

    $(() => {

        // Method to get new notification data from the configured url.

        let updateNotification = (nLink) =>
        {
            // Make an ajax call to the configured url. The response should be
            // an object with the new data. The supported properties are:
            // 'label', 'label_color', 'icon_color' and 'dropdown'.

            $.ajax({
                url: "{{ $makeUpdateUrl() }}"
            })
            .done((data) => {
                nLink.update(data);
            })
            .fail(function(jqXHR, textStatus, errorThrown) {
                console.log(jqXHR, textStatus, errorThrown);
            });
        };

        // First load of the notification data.

        let nLink = new _AdminLTE_NavbarNotification("{{ $id }}");
        updateNotification(nLink);

        // Periodically update the notification.

        setInterval(updateNotification, {{ $makeUpdatePeriod() }}, nLink);
    })

</script>
@endpush
@endif

{{-- Register Javascript utility class for this component --}}

@once
@push('js')
<script>

    class _AdminLTE_NavbarNotification {

        /**
         * Constructor.
         *
         * target: The id of the target notification link.
         */
        constructor(target)
        {
            this.target = target;
        }

        /**
         * Update the notification link.
         *
         * data: An object with the new data.
         */
        update(data)
        {
            // Check if target and data exists.

            let t = $(`li#${this.target}`);

            if (t.length <= 0 || ! data) {
                return;
            }

            let badge = t.find(".navbar-badge");
            let icon = t.find(".nav-link > i");
            let dropdown = t.find(".adminlte-dropdown-content");

            // Update the badge label.

            if (data.label && data.label > 0) {
                badge.html(data.label);
            } else {
                badge.empty();
            }

            // Update the badge color.

            if (data.label_color) {
                badge.removeClass((idx, classes) => {
                    return (classes.match(/(^|\s)badge-\S+/g) || []).join(' ');
                }).addClass(`badge-${data.label_color} badge-pill`);
            }

            // Update the icon color.

            if (data.icon_color) {
                icon.removeClass((idx, classes) => {
                    return (classes.match(/(^|\s)text-\S+/g) || []).join(' ');
                }).addClass(`text-${data.icon_color}`);
            }

            // Update the dropdown content.

            if (data.dropdown && dropdown.length > 0) {
                dropdown.html(data.dropdown);
            }
        }
    }

</script>
@endpush
@endonce
