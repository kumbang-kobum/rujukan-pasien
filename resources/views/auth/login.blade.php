<x-guest-layout>
    <div class="space-y-6">
        <div class="text-center">
            <div class="mx-auto flex h-14 w-14 items-center justify-center rounded-2xl bg-emerald-50 text-emerald-700 ring-1 ring-emerald-100">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M12 6v6l4 2m5-2a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
            <h1 class="mt-4 text-2xl font-bold tracking-tight text-gray-900">Rujukan Pasien</h1>
            <p class="mt-2 text-sm leading-6 text-gray-600">
                Portal koordinasi rujukan, konsultasi dokter, SOAP, dan berkas medis antar rumah sakit.
            </p>
        </div>

        <div class="grid grid-cols-3 gap-2 text-center text-xs text-gray-600">
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-2 py-3">
                <div class="font-semibold text-gray-900">Multi RS</div>
                <div class="mt-1">Data terpisah</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-2 py-3">
                <div class="font-semibold text-gray-900">Klinis</div>
                <div class="mt-1">SOAP & berkas</div>
            </div>
            <div class="rounded-xl border border-gray-200 bg-gray-50 px-2 py-3">
                <div class="font-semibold text-gray-900">Konsul</div>
                <div class="mt-1">Antar dokter</div>
            </div>
        </div>

        <x-auth-session-status class="mb-4" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="space-y-5">
            @csrf

            <div>
                <x-input-label for="email" value="Email pengguna" />
                <x-text-input
                    id="email"
                    class="mt-1 block w-full"
                    type="email"
                    name="email"
                    :value="old('email')"
                    required
                    autofocus
                    autocomplete="username"
                    placeholder="nama@rumahsakit.id"
                />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <div class="flex items-center justify-between">
                    <x-input-label for="password" value="Password" />
                    @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-emerald-700 hover:text-emerald-900" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="relative mt-1">
                    <x-text-input
                        id="password"
                        class="block w-full pr-11"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                    />
                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex w-11 items-center justify-center text-gray-500 hover:text-gray-800"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.07.21.07.434 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678z" />
                            <circle cx="12" cy="12" r="3" stroke-width="1.6" />
                        </svg>
                        <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.6" d="M3 3l18 18M10.584 10.59A2 2 0 0012 14a2 2 0 001.414-.586M9.88 4.64A9.71 9.71 0 0112 4c4.64 0 8.577 2.51 9.964 6.678.07.21.07.434 0 .644-.51 1.49-1.39 2.8-2.53 3.85M6.11 6.11C4.38 7.2 3.03 8.77 2.036 11.678a1.012 1.012 0 000 .644C3.423 16.49 7.36 19 12 19c1.16 0 2.27-.17 3.3-.49" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-gray-300 text-emerald-600 shadow-sm focus:ring-emerald-500" name="remember">
                    <span class="ms-2 text-sm text-gray-600">Ingat saya</span>
                </label>
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-md bg-emerald-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-500 focus:ring-offset-2"
            >
                Masuk ke Dashboard
            </button>
        </form>

        <div class="rounded-xl border border-amber-200 bg-amber-50 px-4 py-3 text-xs leading-5 text-amber-900">
            Gunakan akun yang diberikan super admin atau admin rumah sakit. Setiap akses akan mengikuti role dan rumah sakit pengguna.
        </div>
    </div>

    <script>
        (function () {
            const input = document.getElementById('password');
            const button = document.getElementById('togglePassword');
            const eye = document.getElementById('icon-eye');
            const eyeOff = document.getElementById('icon-eye-off');

            if (!input || !button) return;

            button.addEventListener('click', () => {
                const showing = input.type === 'text';
                input.type = showing ? 'password' : 'text';
                button.setAttribute('aria-pressed', String(!showing));
                button.setAttribute('aria-label', showing ? 'Tampilkan password' : 'Sembunyikan password');
                eye.classList.toggle('hidden', !showing);
                eyeOff.classList.toggle('hidden', showing);
            });
        })();
    </script>
</x-guest-layout>
