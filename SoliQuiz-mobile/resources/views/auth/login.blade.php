@extends('components.layout.app')

@section('content')
    <div class="h-[44px] w-full shrink-0"></div> <!-- iOS Safe Area -->

    <main class="w-full max-w-md mx-auto p-6 flex flex-col justify-center flex-1 animate-in fade-in duration-1000">
        <div class="bg-white border border-slate-100 rounded-[2.5rem] shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="p-8 sm:p-12">
                <div class="text-center mb-10">
                    <div
                        class="size-20 bg-primary-500 rounded-[2rem] flex items-center justify-center shadow-2xl shadow-primary-500/30 mx-auto mb-6 transition-transform hover:scale-105">
                        <svg class="text-white size-10" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                            stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <h1 class="text-4xl font-heading font-extrabold text-slate-900 tracking-tighter">Soli<span
                            class="text-primary-500">Quiz</span></h1>
                    <p class="mt-2 text-xs text-slate-400 font-bold uppercase tracking-widest">
                        Portail d'évaluation mobile
                    </p>
                </div>

                <div class="mt-8">
                    <form x-data="{
                        email: '',
                        password: '',
                        error: null,
                        loading: false,
                        async handleLogin() {
                            this.loading = true;
                            this.error = null;
                            try {
                                const response = await fetch(`${Alpine.store('config').apiBaseUrl}/login`, {
                                    method: 'POST',
                                    headers: {
                                        'Content-Type': 'application/json',
                                        'Accept': 'application/json'
                                    },
                                    body: JSON.stringify({
                                        email: this.email,
                                        password: this.password,
                                        device_name: 'MobileApp'
                                    })
                                });

                                const data = await response.json();

                                if (!response.ok) {
                                    throw new Error(data.message || 'Échec de la connexion');
                                }

                                // Store authentication data
                                Alpine.store('config').setAuth(data.token, data.user);

                                // Redirect based on role
                                if (data.user.type_profil === 'formateur') {
                                    window.location.href = '/formateur/qcms';
                                } else if (data.user.type_profil === 'admin') {
                                    window.location.href = '/admin/dashboard'; // Assuming this exists or falls back
                                } else {
                                    window.location.href = '/student/dashboard';
                                }
                            } catch (e) {
                                this.error = e.message;
                            } finally {
                                this.loading = false;
                            }
                        }
                    }" @submit.prevent="handleLogin" class="grid gap-y-6">
                        
                        <!-- Error Alert -->
                        <div x-show="error" x-cloak class="p-4 bg-red-50 border border-red-100 rounded-2xl text-[10px] font-black text-red-600 uppercase tracking-widest text-center" x-text="error"></div>

                        <!-- Input Email -->
                        <div>
                            <label for="email"
                                class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em] mb-3">Email
                                Solicode</label>
                            <div class="relative group">
                                <input type="email" id="email" name="email" x-model="email"
                                    class="py-4.5 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                                    placeholder="prenom@solicode.co" required>
                            </div>
                        </div>

                        <!-- Input Password -->
                        <div>
                            <div class="flex justify-between items-center mb-3">
                                <label for="password"
                                    class="block text-[10px] font-black text-slate-400 uppercase tracking-[0.2em]">Mot
                                    de
                                    passe</label>
                                <!-- <a class="text-[10px] text-primary-600 font-black uppercase tracking-widest" href="#">Oublié
                                    ?</a> -->
                            </div>
                            <div class="relative group">
                                <input type="password" id="password" name="password" x-model="password"
                                    class="py-4.5 px-6 block w-full border-slate-100 bg-slate-50 rounded-2xl text-sm font-bold focus:border-primary-500 focus:ring-4 focus:ring-primary-500/5 transition-all outline-none"
                                    placeholder="••••••••" required>
                            </div>
                        </div>

                        <!-- Checkbox Remember Me -->
                        <div class="flex items-center mb-2">
                            <div class="flex">
                                <input id="remember-me" name="remember-me" type="checkbox"
                                    class="shrink-0 size-4 mt-0.5 border-slate-200 rounded-md text-primary-600 focus:ring-primary-500 transition-all">
                            </div>
                            <div class="ms-3">
                                <label for="remember-me"
                                    class="text-xs font-bold text-slate-500 uppercase tracking-tight">Se souvenir de
                                    moi</label>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" :disabled="loading"
                            class="w-full h-16 inline-flex justify-center items-center gap-x-2 text-xs font-black rounded-2xl border border-transparent bg-slate-950 text-white shadow-xl shadow-slate-900/10 hover:bg-slate-900 active:scale-[0.98] transition-all uppercase tracking-[0.2em] disabled:opacity-70 disabled:pointer-events-none">
                            <span x-show="!loading">Connexion</span>
                            <span x-show="loading" class="flex items-center gap-2" x-cloak>
                                <svg class="animate-spin h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Connexion en cours...
                            </span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </main>
@endsection