<x-app-layout>
    <div class="min-h-[80vh] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-stone-50">
        <div class="w-full max-w-md">
            <!-- Logo Section -->
            <div class="flex flex-col items-center mb-8">
                <a href="{{ url('/') }}" class="flex flex-col items-center gap-3 group text-center">
                    <x-logo size="xl" />
                    <div>
                        <h1 class="text-2xl font-bold text-stone-900 group-hover:text-primary transition-colors uppercase tracking-[0.2em] font-primary">
                            Gdo Tinoel Craft
                        </h1>
                        <p class="text-[10px] text-stone-500 font-bold uppercase tracking-widest font-primary mt-1">Kerajinan Akrilik & Manik-manik</p>
                    </div>
                </a>
            </div>

            <div class="bg-white p-8 rounded-xl shadow-sm border border-stone-200">
                <div class="text-center mb-8">
                    <h1 class="text-2xl font-bold text-stone-900 mb-2">Reset Password</h1>
                    <p class="text-sm text-stone-500">
                        Silakan masukkan password baru Anda di bawah ini.
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('password.store') }}" class="space-y-6">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route()->parameter('token') }}">

                    <!-- Email Address -->
                    <div>
                        <label for="email" class="block text-sm font-semibold text-stone-900 mb-2">Email</label>
                        <div class="relative text-left">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                            </span>
                            <input id="email" type="email" name="email" value="{{ old('email', $request->email) }}" required autofocus autocomplete="username" placeholder="your@email.com" @class([
                                'w-full pl-10 pr-4 py-3 border rounded-lg text-sm focus:border-primary focus:ring-primary',
                                'border-red-500' => $errors->has('email'),
                                'border-stone-200' => !$errors->has('email'),
                            ])>
                        </div>
                        <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
                    </div>

                    <!-- Password -->
                    <div>
                        <label for="password" class="block text-sm font-semibold text-stone-900 mb-2">Password Baru</label>
                        <div class="relative text-left">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                            </span>
                            <input id="password" type="password" name="password" required autocomplete="new-password" placeholder="••••••••" @class([
                                'w-full pl-10 pr-4 py-3 border rounded-lg text-sm focus:border-primary focus:ring-primary',
                                'border-red-500' => $errors->has('password'),
                                'border-stone-200' => !$errors->has('password'),
                            ])>
                        </div>
                        <x-input-error :messages="$errors->get('password')" class="mt-2 text-xs text-red-500" />
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-semibold text-stone-900 mb-2">Konfirmasi Password Baru</label>
                        <div class="relative text-left">
                            <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z" /></svg>
                            </span>
                            <input id="password_confirmation" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="••••••••" @class([
                                'w-full pl-10 pr-4 py-3 border rounded-lg text-sm focus:border-primary focus:ring-primary',
                                'border-red-500' => $errors->has('password_confirmation'),
                                'border-stone-200' => !$errors->has('password_confirmation'),
                            ])>
                        </div>
                        <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-xs text-red-500" />
                    </div>

                    <div class="pt-4">
                        <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm font-bold uppercase tracking-[0.1em] text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            {{ __('Reset Password') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
