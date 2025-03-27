<x-guest-layout>
    
    <!-- <div class="hidden fixed top-0 right-0 px-6 py-4 sm:block">
        <a href="{{ route('/') }}" class=" btn txt-btn " ><i class="fa-solid fa-arrow-left fa-lg"></i> Página principal</a>
    </div> -->
    <x-authentication-card>
        <x-slot name="logo">
            <x-authentication-card-logo />
        </x-slot>

        <x-validation-errors class="mb-4" />

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div>
                <x-label for="name" value="{{ __('Nombre y Apellido:') }}" class="texto-formulario" />
                <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" />
            </div>

            <div class="mt-4">
                <x-label for="email" value="{{ __('Correo:') }}"  class="texto-formulario" />
                <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required />
            </div>

            <div class="mt-4">
                <x-label for="telegram_id" value="{{ __('Telegram id:') }}"  class="texto-formulario" />
                <x-input id="telegram_id" class="block mt-1 w-full" type="text" name="telegram_id" :value="old('telegram_id')" required />
            </div>

            <div class="mt-4">
                <x-label for="password" value="{{ __('Contraseña:') }}" class="texto-formulario" />
                <x-input id="password" class="block mt-1 w-full" type="password" name="password" required autocomplete="new-password" />
            </div>

            <div class="mt-4">
                <x-label for="password_confirmation" value="{{ __('Confirmar contraseña:') }}" class="texto-formulario" />
                <x-input id="password_confirmation" class="block mt-1 w-full" type="password" name="password_confirmation" required autocomplete="new-password" />
            </div>

            @if (Laravel\Jetstream\Jetstream::hasTermsAndPrivacyPolicyFeature())
                <div class="mt-4">
                    <x-label for="terms">
                        <div class="flex items-center">
                            <x-checkbox name="terms" id="terms"/>

                            <div class="ml-2">
                                {!! __('I agree to the :terms_of_service and :privacy_policy', [
                                        'terms_of_service' => '<a target="_blank" href="'.route('terms.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Terms of Service').'</a>',
                                        'privacy_policy' => '<a target="_blank" href="'.route('policy.show').'" class="underline text-sm text-gray-600 hover:text-gray-900">'.__('Privacy Policy').'</a>',
                                ]) !!}
                            </div>
                        </div>
                    </x-label>
                </div>
            @endif

            <div class="  ">
                <a class="underline  estarRegistrado" href="{{ route('login') }}">
                    {{ __('¿Ya estás registrado?') }}
                </a>

                <x-button class="btn-form ">
                    {{ __('REGISTRARSE') }}
                </x-button>
            </div>
        </form>
    </x-authentication-card>
</x-guest-layout>
