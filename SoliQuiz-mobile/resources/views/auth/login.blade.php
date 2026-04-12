@extends('components.layout.app')

@section('content')
<div class="h-full flex flex-col relative overflow-hidden">

    <div class="h-[44px] w-full shrink-0"></div>

    <main class="w-full max-w-md mx-auto p-6 flex flex-col justify-center flex-1">
        <div class="mt-7 bg-white border border-slate-200 rounded-xl shadow-sm">
            <div class="p-4 sm:p-7">
                <div class="text-center">
                    <div class="size-16 bg-primary-500 rounded-2xl flex items-center justify-center shadow-lg shadow-primary-500/20 transition-transform mx-auto mb-4">
                        <svg class="text-white size-8" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z" />
                            <polyline points="14 2 14 8 20 8" />
                            <path d="m9 15 2 2 4-4" />
                        </svg>
                    </div>
                    <h1 class="text-3xl font-heading font-bold text-slate-900 tracking-tight">Soli<span class="text-primary-500">Quiz</span></h1>
                    <p class="mt-2 text-sm text-slate-500 font-medium">Connectez-vous pour commencer.</p>
                </div>

                <div class="mt-8">
                    <form x-data="loginForm()" @submit.prevent="handleLogin">
                        <div class="grid gap-y-4">
                            <div>
                                <label for="email" class="block text-sm mb-2 font-medium">Email Solicode</label>
                                <div class="relative">
                                    <input type="email" id="email" name="email" x-model="email"
                                        class="py-4 px-5 block w-full border-slate-200 rounded-2xl text-sm focus:border-primary-500 focus:ring-primary-500 transition-all outline-none"
                                        placeholder="prenom@solicode.co" required>
                                </div>
                            </div>

                            <div>
                                <div class="flex justify-between items-center">
                                    <label for="password" class="block text-xs font-black text-slate-400 uppercase tracking-widest mb-2 italic">Mot de passe</label>
                                    <a class="text-xs text-primary-600 font-bold uppercase tracking-wider mb-2" href="#">Oublié ?</a>
                                </div>
                                <div class="relative">
                                    <input type="password" id="password" name="password" x-model="password"
                                        class="py-4 px-5 block w-full border-slate-200 rounded-2xl text-sm focus:border-primary-500 focus:ring-primary-500 transition-all outline-none"
                                        placeholder="••••••••" required>
                                </div>
                            </div>

                            <div class="flex items-center">
                                <div class="flex">
                                    <input id="remember-me" name="remember-me" type="checkbox" class="shrink-0 mt-0.5 border-slate-200 rounded text-primary-600 focus:ring-primary-500">
                                </div>
                                <div class="ms-3">
                                    <label for="remember-me" class="text-sm">Se souvenir de moi</label>
                                </div>
                            </div>

                            <button type="submit" class="w-full py-4 px-4 inline-flex justify-center items-center gap-x-2 text-xs font-black rounded-2xl border border-transparent bg-primary-500 text-white hover:bg-primary-600 shadow-xl shadow-primary-500/30 active:scale-[0.98] transition-all uppercase tracking-widest">
                                Connexion
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </main>
</div>

<script>
function loginForm() {
    return {
        email: '',
        password: '',
        handleLogin() {
            const email = this.email.toLowerCase();
            if (email.includes('formateur')) {
                window.location.href = '/formateur/qcms';
            } else {
                window.location.href = '/student/dashboard';
            }
        }
    }
}
</script>
@endsection