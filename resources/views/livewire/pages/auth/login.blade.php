<?php

use App\Livewire\Forms\LoginForm;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component
{
    public LoginForm $form;

    public function login(): void
    {
        $this->validate();
        $this->form->authenticate();
        Session::regenerate();
        $this->redirectIntended(default: route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-[100dvh] flex items-center justify-center px-4 py-10"
     x-data="{
         showPw: false,
         toast: { show: false, msg: '' },
         toastTimer: null,
         showToast(msg) {
             this.toast.msg = msg;
             this.toast.show = true;
             clearTimeout(this.toastTimer);
             this.toastTimer = setTimeout(() => this.toast.show = false, 3500);
         },
     }">

    {{-- Toast --}}
    <div id="toast" role="status" aria-live="polite" aria-atomic="true"
         :class="{ show: toast.show }">
        <svg class="w-4 h-4 text-emerald-400 flex-shrink-0" fill="none" stroke="currentColor"
             viewBox="0 0 24 24" aria-hidden="true">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"/>
        </svg>
        <span x-text="toast.msg"></span>
    </div>

    <main id="main" tabindex="-1" class="w-full max-w-md">

        {{-- Dark mode toggle --}}
        <div class="flex justify-end mb-4">
            <button type="button" data-theme-toggle
                    class="dark-toggle text-gray-500 hover:bg-gray-100 dark:hover:bg-white/10"
                    aria-label="Switch to dark mode">
                <svg class="icon-sun w-5 h-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                </svg>
                <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z"/>
                </svg>
            </button>
        </div>

        {{-- Logo --}}
        <div class="flex flex-col items-center gap-3 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-brand-600 flex items-center justify-center flex-shrink-0"
                 aria-hidden="true">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor"
                     viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                </svg>
            </div>
            <div class="text-center">
                <p class="text-lg font-bold text-gray-900 dark:text-gray-100 leading-tight">StockMaster</p>
                <p class="text-[11px] text-gray-400 font-medium tracking-wide uppercase">Inventory Pro</p>
            </div>
        </div>

        {{-- Card --}}
        <div class="bg-white dark:bg-[#1e2133] rounded-2xl shadow-card p-6 sm:p-8">
            <div class="mb-6 text-center">
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Welcome back</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Sign in to your account to continue</p>
            </div>

            {{-- Session status --}}
            <x-auth-session-status class="mb-4" :status="session('status')" />

            <form wire:submit="login" novalidate class="space-y-4">

                {{-- Email --}}
                <div>
                    <label for="email"
                           class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Email address') }}
                    </label>
                    <input wire:model="form.email"
                           id="email"
                           type="email"
                           class="field @error('form.email') error @enderror"
                           placeholder="e.g. juan@stockmaster.ph"
                           autocomplete="email"
                           aria-describedby="email-error"
                           @error('form.email') aria-invalid="true" @enderror>
                    @error('form.email')
                        <p id="email-error" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <div class="flex items-center justify-between mb-1.5">
                        <label for="password"
                               class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            {{ __('Password') }}
                        </label>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}"
                               wire:navigate
                               class="text-xs font-semibold text-brand-600 hover:text-brand-700">
                                {{ __('Forgot password?') }}
                            </a>
                        @endif
                    </div>
                    <div class="relative">
                        <input wire:model="form.password"
                               :type="showPw ? 'text' : 'password'"
                               id="password"
                               class="field pe-11 @error('form.password') error @enderror"
                               placeholder="Enter your password"
                               autocomplete="current-password"
                               aria-describedby="password-error"
                               @error('form.password') aria-invalid="true" @enderror>
                        <button type="button"
                                @click="showPw = !showPw"
                                class="absolute end-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors"
                                :aria-label="showPw ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor"
                                 viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none"
                                 stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                      d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                        </button>
                    </div>
                    @error('form.password')
                        <p id="password-error" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Remember me --}}
                <div class="flex items-center gap-2">
                    <input wire:model="form.remember"
                           id="remember"
                           type="checkbox"
                           class="w-4 h-4 rounded border-gray-300 text-brand-600 focus:ring-brand-400">
                    <label for="remember" class="text-sm text-gray-600 dark:text-gray-300">
                        {{ __('Remember me for 30 days') }}
                    </label>
                </div>

                {{-- Submit --}}
                <button type="submit"
                        class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-brand-600 hover:bg-brand-700 text-white text-sm font-semibold rounded-xl transition-colors shadow-sm min-h-[44px]">
                    <span wire:loading.remove wire:target="login">{{ __('Sign In') }}</span>
                    <span wire:loading wire:target="login" class="flex items-center gap-2">
                        <svg class="animate-spin w-4 h-4" fill="none" viewBox="0 0 24 24" aria-hidden="true">
                            <circle class="opacity-25" cx="12" cy="12" r="10"
                                    stroke="currentColor" stroke-width="4"/>
                            <path class="opacity-75" fill="currentColor"
                                  d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z"/>
                        </svg>
                        {{ __('Signing in…') }}
                    </span>
                </button>
            </form>

            {{-- Divider --}}
            <div class="flex items-center gap-3 my-6">
                <div class="flex-1 h-px bg-gray-100 dark:bg-[#2d3148]"></div>
                <span class="text-xs text-gray-400 font-medium">OR</span>
                <div class="flex-1 h-px bg-gray-100 dark:bg-[#2d3148]"></div>
            </div>

            {{-- Google (UI only — not connected) --}}
            <button type="button"
                    @click="showToast('Google sign-in is not available yet.')"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 border border-gray-200 dark:border-[#374151] text-gray-700 dark:text-gray-200 text-sm font-semibold rounded-xl hover:bg-gray-50 dark:hover:bg-white/5 transition-colors min-h-[44px]">
                <svg class="w-4 h-4 flex-shrink-0" viewBox="0 0 24 24" aria-hidden="true">
                    <path fill="#4285F4" d="M23.52 12.27c0-.85-.08-1.67-.22-2.45H12v4.64h6.47c-.28 1.5-1.13 2.78-2.4 3.63v3.02h3.88c2.27-2.09 3.58-5.17 3.58-8.84z"/>
                    <path fill="#34A853" d="M12 24c3.24 0 5.96-1.07 7.95-2.9l-3.88-3.02c-1.08.72-2.45 1.15-4.07 1.15-3.13 0-5.78-2.11-6.73-4.96H1.26v3.12A11.997 11.997 0 0012 24z"/>
                    <path fill="#FBBC05" d="M5.27 14.27A7.2 7.2 0 014.9 12c0-.79.14-1.56.37-2.27V6.61H1.26A11.997 11.997 0 000 12c0 1.94.46 3.77 1.26 5.39l4.01-3.12z"/>
                    <path fill="#EA4335" d="M12 4.77c1.76 0 3.35.61 4.6 1.8l3.44-3.44C17.95 1.19 15.24 0 12 0 7.31 0 3.26 2.69 1.26 6.61l4.01 3.12C6.22 6.88 8.87 4.77 12 4.77z"/>
                </svg>
                {{ __('Continue with Google') }}
            </button>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                {{ __("Don't have an account?") }}
                <a href="{{ route('register') }}" wire:navigate
                   class="font-semibold text-brand-600 hover:text-brand-700">
                    {{ __('Create one') }}
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-6">
            <a href="{{ route('dashboard') }}" wire:navigate
               class="hover:text-brand-600 transition-colors">
                ← {{ __('Back to dashboard') }}
            </a>
        </p>

    </main>
</div>
