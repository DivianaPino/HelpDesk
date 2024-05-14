<x-guest-layout>

    <!-- <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        <a href="{{ route('/') }}" class=" btn txt-btn " ><i class="fa-solid fa-arrow-left fa-lg"></i> Página principal</a>
    </div> -->
    
        <x-jet-authentication-card >

            <x-slot name="logo">
                <x-jet-authentication-card-logo />
            </x-slot>

            <x-jet-validation-errors class="mb-4" />

            @if (session('status'))
                <div class="mb-4 font-medium text-sm text-green-600 ">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}" >
                @csrf

                <div>
                    <x-jet-label for="email" value="{{ __('Correo:') }}" class="label texto-formulario" />
                    <x-jet-input id="email" class="block mt-1 w-full " type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <div class="mt-4">
                    <x-jet-label for="password" value="{{ __('Contraseña:') }}" class="label texto-formulario" />
                    <x-jet-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="current-password" />
                </div>

                <div class="contenedor-remember">
                    <div >
                        <label for="remember_me" class="flex items-center">
                            <x-jet-checkbox id="remember_me" name="remember" />
                            <span class=" texto-recuerdame margin-derecho ">{{ __('Recordarme') }}</span>
                        </label>
                    </div>

                    <div class="texto-noregister">
                    @if (Route::has('register'))
                            <a class="underline" href="{{ route('register') }}">
                                {{ __('¿No tienes cuenta?') }}
                            </a>
                    @endif
                    </div>
                </div>

                <div class="contenedor-btnform">
                    <x-jet-button class="btn-form" >
                    {{ __('Iniciar sesión') }} 
                    </x-jet-button>
                </div>
            </form>
            
        </x-jet-authentication-card>
    
</x-guest-layout>
