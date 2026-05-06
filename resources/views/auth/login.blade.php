<x-app-layout>
    <div class="min-h-[80vh] flex flex-col items-center justify-center py-12 px-4 sm:px-6 lg:px-8 bg-stone-50">
        <div class="w-full max-w-md bg-white border border-stone-200 rounded-lg p-8 shadow-sm">
            {{-- Header --}}
            <div class="text-center mb-8">
                <h1 class="text-3xl font-bold text-stone-900 mb-2">Masuk</h1>
                <p class="text-stone-500 text-sm">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="text-primary font-bold hover:underline">
                        Daftar di sini
                    </a>
                </p>
            </div>

            {{-- Session Status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('login') }}" class="space-y-5">
                @csrf
                
                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-stone-900 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                            {{-- Email Envelope Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" />
                            </svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" @class([
                            'w-full pl-10 pr-4 py-3 border rounded-lg text-sm focus:border-primary focus:ring-primary',
                            'border-red-500' => $errors->has('email'),
                            'border-stone-200' => !$errors->has('email'),
                        ])>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-2">
                        <label for="password" class="block text-sm font-semibold text-stone-900">Password</label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-xs text-stone-500 hover:text-stone-900">Lupa password?</a>
                        @endif
                    </div>
                    <div class="relative" x-data="{ show: false }">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                            {{-- Padlock Icon --}}
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                              <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" />
                            </svg>
                        </span>
                        <input id="password" :type="show ? 'text' : 'password'" name="password" required autocomplete="current-password" @class([
                            'w-full pl-10 pr-10 py-3 border rounded-lg text-sm focus:border-primary focus:ring-primary',
                            'border-red-500' => $errors->has('password'),
                            'border-stone-200' => !$errors->has('password'),
                        ])>
                        
                        <button type="button" @click="show = !show" class="absolute right-3 top-1/2 -translate-y-1/2 text-stone-400 hover:text-primary transition-colors focus:outline-none">
                            <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                            </svg>
                            <svg x-show="show" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5" x-cloak>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.98 8.223A10.477 10.477 0 0 0 1.934 12C3.226 16.338 7.244 19.5 12 19.5c.993 0 1.953-.138 2.863-.395M6.228 6.228A10.451 10.451 0 0 1 12 4.5c4.756 0 8.773 3.162 10.065 7.498a10.522 10.522 0 0 1-4.293 5.774M6.228 6.228 3 3m3.228 3.228 3.65 3.65m7.894 7.894L21 21m-3.228-3.228-3.65-3.65m0 0a3 3 0 1 0-4.243-4.243m4.242 4.242L9.88 9.88" />
                            </svg>
                        </button>
                    </div>
                    <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
                </div>

                {{-- Remember Me --}}
                <div class="flex items-center pt-1">
                    <input id="remember_me" type="checkbox" name="remember" class="w-4 h-4 text-primary bg-white border-stone-300 rounded focus:ring-primary cursor-pointer">
                    <label for="remember_me" class="ml-2 text-sm text-stone-500 cursor-pointer">Ingat saya</label>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm text-sm font-bold uppercase tracking-[0.1em] text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Masuk
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
