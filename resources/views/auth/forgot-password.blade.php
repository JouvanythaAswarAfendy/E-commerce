<x-app-layout>
    <div class="min-h-[80vh] flex flex-col justify-center items-center py-12 px-4 sm:px-6 lg:px-8 bg-stone-50">
        <div class="w-full max-w-md bg-white p-8 rounded-lg shadow-sm border border-stone-200">
            <div class="text-center mb-8">
                <h1 class="text-2xl font-bold text-stone-900 mb-2">Lupa Password</h1>
                <p class="text-sm text-stone-500">
                    Lupa password? Tenang saja. Masukkan alamat email kamu dan kami akan mengirimkan link reset password untuk membuat password baru.
                </p>
            </div>

            <!-- Session Status -->
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf

                <!-- Email Address -->
                <div>
                    <label for="email" class="block text-sm font-semibold text-stone-900 mb-2">Email</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-stone-400">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-5 h-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21.75 6.75v10.5a2.25 2.25 0 0 1-2.25 2.25h-15a2.25 2.25 0 0 1-2.25-2.25V6.75m19.5 0A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25m19.5 0v.243a2.25 2.25 0 0 1-1.07 1.916l-7.5 4.615a2.25 2.25 0 0 1-2.36 0L3.32 8.91a2.25 2.25 0 0 1-1.07-1.916V6.75" /></svg>
                        </span>
                        <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus autocomplete="username" placeholder="your@email.com" @class([
                            'w-full pl-10 pr-4 py-3 border rounded-lg text-sm focus:border-primary focus:ring-primary',
                            'border-red-500' => $errors->has('email'),
                            'border-stone-200' => !$errors->has('email'),
                        ])>
                    </div>
                    <x-input-error :messages="$errors->get('email')" class="mt-2 text-xs text-red-500" />
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full flex justify-center py-3.5 px-4 border border-transparent rounded-lg shadow-sm font-bold uppercase tracking-[0.1em] text-white bg-primary hover:bg-primary/90 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        Kirim Link Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
