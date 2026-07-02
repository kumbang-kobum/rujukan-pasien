<x-guest-layout>
    <div>
        <div class="text-center">
            <h1 class="text-2xl font-semibold tracking-normal text-slate-950">Masuk</h1>
            <p class="mt-2 text-sm leading-6 text-slate-500">
                Gunakan akun rumah sakit Anda.
            </p>
        </div>

        <x-auth-session-status class="mt-6 rounded-lg border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm text-emerald-800" :status="session('status')" />

        <form method="POST" action="{{ route('login') }}" class="mt-7 space-y-5">
            @csrf

            <div>
                <x-input-label for="email" value="Email" class="text-slate-700" />
                <x-text-input
                    id="email"
                    class="mt-2 block w-full rounded-lg border-slate-200 bg-white px-4 py-3 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-emerald-600"
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
                <div class="flex items-center justify-between gap-3">
                    <x-input-label for="password" value="Password" class="text-slate-700" />
                    @if (Route::has('password.request'))
                        <a class="text-sm font-medium text-emerald-700 transition hover:text-emerald-900" href="{{ route('password.request') }}">
                            Lupa password?
                        </a>
                    @endif
                </div>

                <div class="relative mt-2">
                    <x-text-input
                        id="password"
                        class="block w-full rounded-lg border-slate-200 bg-white px-4 py-3 pr-12 text-slate-900 shadow-sm transition placeholder:text-slate-400 focus:border-emerald-600 focus:ring-emerald-600"
                        type="password"
                        name="password"
                        required
                        autocomplete="current-password"
                        placeholder="Masukkan password"
                    />
                    <button
                        type="button"
                        id="togglePassword"
                        class="absolute inset-y-0 right-0 flex w-12 items-center justify-center rounded-r-lg text-slate-500 transition hover:text-slate-800 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-emerald-600"
                        aria-label="Tampilkan password"
                        aria-pressed="false"
                    >
                        <svg id="icon-eye" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M2.036 12.322a1.012 1.012 0 010-.644C3.423 7.51 7.36 5 12 5c4.64 0 8.577 2.51 9.964 6.678.07.21.07.434 0 .644C20.577 16.49 16.64 19 12 19c-4.64 0-8.577-2.51-9.964-6.678Z" />
                            <circle cx="12" cy="12" r="3" stroke-width="1.7" />
                        </svg>
                        <svg id="icon-eye-off" xmlns="http://www.w3.org/2000/svg" class="hidden h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M3 3l18 18M10.584 10.59A2 2 0 0012 14a2 2 0 001.414-.586M9.88 4.64A9.71 9.71 0 0112 4c4.64 0 8.577 2.51 9.964 6.678.07.21.07.434 0 .644-.51 1.49-1.39 2.8-2.53 3.85M6.11 6.11C4.38 7.2 3.03 8.77 2.036 11.678a1.012 1.012 0 000 .644C3.423 16.49 7.36 19 12 19c1.16 0 2.27-.17 3.3-.49" />
                        </svg>
                    </button>
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-emerald-700 shadow-sm focus:ring-emerald-600" name="remember">
                    <span class="ms-2 text-sm text-slate-600">Ingat saya</span>
                </label>
            </div>

            <button
                type="submit"
                class="inline-flex w-full items-center justify-center rounded-lg bg-emerald-700 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-emerald-800 focus:outline-none focus:ring-2 focus:ring-emerald-600 focus:ring-offset-2"
            >
                Masuk ke Dashboard
            </button>
        </form>
    </div>

    <script>
        (function () {
            const input = document.getElementById('password');
            const button = document.getElementById('togglePassword');
            const eye = document.getElementById('icon-eye');
            const eyeOff = document.getElementById('icon-eye-off');

            if (!input || !button || !eye || !eyeOff) return;

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
