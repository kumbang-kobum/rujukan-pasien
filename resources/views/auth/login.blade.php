<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf

        <!-- Email Address -->
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Password -->
        <div class="mt-4">
            <x-input-label for="password" :value="__('Password')" />

            <div class="relative mt-1 flex w-full">
                <x-text-input
                    id="password"
                    class="block w-full pr-12"
                    type="password"
                    name="password"
                    required
                    autocomplete="current-password"
                />

                {{-- tombol show/hide --}}
                <div class="flex items-center justify-end">  
                    <div class="flex absolute">
                        <button
                            type="button"
                            id="togglePassword"
                            class="p-2"
                            aria-label="Show password"
                            aria-pressed="false">
                            {{-- eye (show) --}}
                            <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.07.21.07.434 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z"/>
                                <circle cx="12" cy="12" r="3" stroke-width="1.5" stroke="currentColor"></circle>
                            </svg>
                            {{-- eye-off (hide) --}}
                            <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                d="M3 3l18 18M10.584 10.59A2 2 0 0012 14a2 2 0 001.414-.586M9.88 4.64A9.71 9.71 0 0112 4c4.64 0 8.577 2.51 9.964 6.678.07.21.07.434 0 .644-.51 1.49-1.39 2.8-2.53 3.85M6.11 6.11C4.38 7.2 3.03 8.77 2.036 11.678a1.012 1.012 0 000 .644C3.423 16.49 7.36 19 12 19c1.16 0 2.27-.17 3.3-.49" />
                            </svg>
                        </button>
                    </div>    
                </div>
            </div>

            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>


        <!-- Remember Me -->
        <div class="block mt-4">
            <label for="remember_me" class="inline-flex items-center">
                <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
                <span class="ms-2 text-sm text-gray-600">{{ __('Remember me') }}</span>
            </label>
        </div>

        <div class="flex items-center justify-end mt-4">
            @if (Route::has('password.request'))
                <a
                    class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                    href="{{ route('password.request') }}"
                >
                    {{ __('Forgot your password?') }}
                </a>
            @endif

            <x-primary-button class="ms-3">
                {{ __('Log in') }}
            </x-primary-button>
        </div>
    </form>
    {{-- script --}}
    <script>
        (function(){
        const input = document.getElementById('password');
        const btn   = document.getElementById('togglePassword');
        const eye   = document.getElementById('icon-eye');
        const eyeOff= document.getElementById('icon-eye-off');
        if(!input || !btn) return;

        btn.addEventListener('click', () => {
            const showing = input.type === 'text';
            input.type = showing ? 'password' : 'text';
            btn.setAttribute('aria-pressed', String(!showing));
            btn.setAttribute('aria-label', showing ? 'Show password' : 'Hide password');
            eye.classList.toggle('hidden', !showing);
            eyeOff.classList.toggle('hidden', showing);
        });
        })();
    </script>

    <!-- Kredit & WhatsApp -->
    <div class="mt-6 text-center text-sm text-gray-600">
        <strong>@chandra irawan</strong>
        <div class="mt-2">
            <a
                href="https://wa.me/6281373936006?text=Halo%20Chandra,%20saya%20tertarik%20dengan%20aplikasi%20{{ rawurlencode(config('app.name')) }}"
                target="_blank"
                rel="noopener"
                class="inline-flex items-center px-3 py-2 rounded-md border border-gray-300 hover:bg-gray-50"
                style="gap:8px"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.9 11.9 0 0 0 12.06 0C5.43.03.1 5.37.12 12a11.85 11.85 0 0 0 1.7 6.13L.02 24l6.02-1.76a11.95 11.95 0 0 0 6.03 1.63h.01c6.63-.02 11.96-5.36 11.96-12a11.88 11.88 0 0 0-3.52-8.39ZM12.07 22.1h-.01a9.9 9.9 0 0 1-5.04-1.38l-.36-.21-3.57 1.04 1.05-3.48-.24-.36a9.9 9.9 0 0 1-1.5-5.24C2.4 6.47 6.5 2.36 11.99 2.34h.01c2.66 0 5.15 1.04 7.04 2.93a9.86 9.86 0 0 1 2.92 7.04c0 5.5-4.49 9.99-9.89 9.99Zm5.6-7.41c-.31-.16-1.86-.92-2.15-1.02-.29-.11-.5-.16-.72.16s-.83 1.02-1.02 1.24-.37.23-.68.08a8.12 8.12 0 0 1-2.39-1.48 8.92 8.92 0 0 1-1.66-2.05c-.17-.29 0-.45.13-.61.13-.16.29-.37.43-.56.14-.19.19-.31.29-.52.1-.21.05-.39-.02-.55-.16-.16-.72-1.77-.98-2.42-.26-.65-.52-.56-.72-.57h-.62c-.21 0-.55.08-.84.39-.29.31-1.11 1.08-1.11 2.64 0 1.56 1.14 3.07 1.3 3.28.16.21 2.24 3.41 5.43 4.66.76.33 1.35.52 1.81.67.76.24 1.45.2 2 .12.61-.09 1.86-.76 2.12-1.49.26-.73.26-1.36.18-1.49-.08-.13-.29-.21-.6-.37Z"/></svg>
                WhatsApp
            </a>
        </div>
    </div>
</x-guest-layout>