<style>   
    .dropdown-menu {
        max-width: 50px; /* Ajusta este valor según sea necesario */
    }
   .dropdown-item {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        width: 100%; /* Asegura que el elemento ocupe todo el ancho disponible */
    }
   .dropdown-item i,.dropdown-item span {
        display: block; /* Hace que el ícono y la fecha sean bloques para aplicarles el ancho */
        width: 100%; /* Aplica el ancho completo al ícono y la fecha */
        white-space: nowrap; /* Evita que el texto se envuelva */
        overflow: hidden; /* Oculta cualquier contenido que exceda el ancho definido */
        text-overflow: ellipsis; /* Muestra puntos suspensivos si el contenido excede el ancho */
    }
</style>


{{-- Navbar notification --}}

<li class="{{ $makeListItemClass() }}" id="{{ $id }}">

    {{-- Link --}}
    <a @if($enableDropdownMode) href="" @endif {{ $attributes->merge($makeAnchorDefaultAttrs()) }}>

        {{-- Icon --}}
        <i class="{{ $makeIconClass() }}"></i>

        {{-- Badge --}}
        <span class="{{ $makeBadgeClass() }}"> 
            @if(auth()->check() && auth()->user()->unreadNotifications->isNotEmpty())
                {{ $notificacionesNoLeidas->count() }}
            @else
            <span>0</span>
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
                    if ($notificacion->type === 'App\Notifications\ComentarioNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['comentario_ticketId'])->first();
                        $id_usuario_Ticket=$ticket->user_id;
                        $usuario=App\Models\User::where('id',$id_usuario_Ticket)->first();
                        $usuarioNombre=$usuario->name;

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['comentario_ticketId']}/resp/{$notificacion->data['comentario_respuestaId']}");
                    
                    } elseif ($notificacion->type === 'App\Notifications\TicketNotification') {

                        $usuario=App\Models\User::where('id',$notificacion->data['ticket_userId'] )->first();
                        $usuarioNombre=$usuario->name;

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['ticket_id']}");

                    }elseif ($notificacion->type === 'App\Notifications\MasInfoNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['masInfo_ticketId'])->first();
                        $tecnicoSop=$ticket->asignado_a;
                       
                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['masInfo_ticketId']}/masInfo");
                        
                    }elseif ($notificacion->type === 'App\Notifications\RespMasInfoNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['respMasInfo_ticketId'])->first();
                        $usuario=App\Models\User::where('id',$ticket->user_id)->first();
                        $usuarioNombre=$usuario->name;

                     

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['respMasInfo_ticketId']}/respmasInfo");

                    }elseif ($notificacion->type === 'App\Notifications\RespuestaNotification') {

                        $ticket=App\Models\Ticket::where('id',$notificacion->data['respuesta_ticketId'])->first();
                        $tecnicoSop=$ticket->asignado_a;

                        $url = url("/notificacion/{$notificacion->id}/ticket/{$notificacion->data['respuesta_ticketId']}/respuesta");
                    }



                @endphp


                <a href="{{ $url }}" class="dropdown-item" style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">

                        @if($notificacion->type ==='App\Notifications\ComentarioNotification')
                            <i class="fas fa-envelope mr-4"></i>{{ $notificacion->data['comentario_mensaje'] }}
                            <span class="float-right text-muted text-sm">Usuario: {{ $usuarioNombre }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>

                        @elseif($notificacion->type ==='App\Notifications\TicketNotification')
                            <i class="fas fa-ticket-alt mr-4"></i> {{ $notificacion->data['ticket_asunto'] }}
                            <span class="float-right text-muted text-sm">Usuario: {{ $usuarioNombre }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>

                        @elseif($notificacion->type ==='App\Notifications\MasInfoNotification')
                            <i class="fas fa-info-circle mr-4"></i>  {{ $notificacion->data['masInfo_mensaje'] }}
                            <span class="float-right text-muted text-sm">Técnico de soporte: {{ $tecnicoSop }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>

                        @elseif($notificacion->type ==='App\Notifications\RespMasInfoNotification')
                            <i class="fas fa-info-circle mr-4"></i>  {{ $notificacion->data['respMasInfo_mensaje'] }}
                            <span class="float-right text-muted text-sm">Usuario: {{ $usuarioNombre }}</span>
                            <span class="float-right text-muted text-sm">{{ $notificacion->created_at->diffForHumans() }}</span>

                        @elseif($notificacion->type ==='App\Notifications\RespuestaNotification')
                            <i class="fas fa-check mr-4"></i>  {{ $notificacion->data['respuesta_mensaje'] }}
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
