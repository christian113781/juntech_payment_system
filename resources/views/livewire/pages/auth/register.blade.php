<?php

use App\Models\User;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Livewire\Attributes\Layout;
use Livewire\Volt\Component;

new #[Layout('layouts.guest')] class extends Component {
    public string $name = '';
    public string $email = '';
    public string $password = '';
    public string $password_confirmation = '';

    public function register(): void
    {
        $validated = $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'lowercase', 'email', 'max:255', 'unique:' . User::class],
            'password' => ['required', 'string', 'confirmed', Rules\Password::defaults()],
        ]);

        $validated['password'] = Hash::make($validated['password']);

        event(new Registered(($user = User::create($validated))));

        Auth::login($user);

        $this->redirect(route('dashboard', absolute: false), navigate: true);
    }
}; ?>

<div class="min-h-[100dvh] flex items-center justify-center px-4 py-10" x-data="{
    showPw: false,
    showPwConfirm: false,
    password: '',
    agreed: false,
    get isDark() { return document.documentElement.classList.contains('dark'); },
    toggleDark() {
        document.documentElement.classList.toggle('dark');
        localStorage.setItem('sm-dark', document.documentElement.classList.contains('dark') ? 'dark' : 'light');
    },
    get strength() {
        const p = this.password;
        if (!p) return 0;
        let score = 0;
        if (p.length >= 8) score++;
        if (/[A-Z]/.test(p)) score++;
        if (/[0-9]/.test(p)) score++;
        if (/[^A-Za-z0-9]/.test(p)) score++;
        return score;
    },
    get strengthLabel() {
        return ['', 'Weak', 'Fair', 'Good', 'Strong'][this.strength];
    },
    get strengthColor() {
        return ['', 'text-red-500', 'text-amber-500', 'text-blue-500', 'text-emerald-500'][this.strength];
    },
    get strengthBars() {
        return [
            this.strength >= 1 ? ['', 'bg-red-400', 'bg-amber-400', 'bg-blue-400', 'bg-emerald-400'][this.strength] : 'bg-gray-200 dark:bg-gray-700',
            this.strength >= 2 ? ['', 'bg-red-400', 'bg-amber-400', 'bg-blue-400', 'bg-emerald-400'][this.strength] : 'bg-gray-200 dark:bg-gray-700',
            this.strength >= 3 ? ['', 'bg-red-400', 'bg-amber-400', 'bg-blue-400', 'bg-emerald-400'][this.strength] : 'bg-gray-200 dark:bg-gray-700',
            this.strength >= 4 ? ['', 'bg-red-400', 'bg-amber-400', 'bg-blue-400', 'bg-emerald-400'][this.strength] : 'bg-gray-200 dark:bg-gray-700',
        ];
    },
}">

    <main id="main" tabindex="-1" class="w-full max-w-md">

        {{-- Dark mode toggle --}}
        <div class="flex justify-end mb-4">
            <button type="button" data-theme-toggle class="dark-toggle text-gray-500 hover:bg-gray-100 dark:hover:bg-white/10"
                aria-label="Switch to dark mode">
                <svg class="icon-sun w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364-6.364l-.707.707M6.343 17.657l-.707.707M17.657 17.657l-.707-.707M6.343 6.343l-.707-.707M16 12a4 4 0 11-8 0 4 4 0 018 0z" />
                </svg>
                <svg class="icon-moon w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20.354 15.354A9 9 0 018.646 3.646 9.003 9.003 0 0012 21a9.003 9.003 0 008.354-5.646z" />
                </svg>
            </button>
        </div>

        {{-- Logo --}}
        <div class="flex flex-col items-center gap-3 mb-8">
            <div class="w-12 h-12 rounded-2xl bg-brand-600 flex items-center justify-center flex-shrink-0"
                aria-hidden="true">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                    aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
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
                <h1 class="text-xl font-bold text-gray-900 dark:text-gray-100">Create an account</h1>
                <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Start managing your inventory today</p>
            </div>

            <form wire:submit="register" novalidate class="space-y-4">

                {{-- Name --}}
                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Full name') }}
                    </label>
                    <input wire:model="name" id="name" type="text" class="field @error('name') error @enderror"
                        placeholder="e.g. Juan dela Cruz" autocomplete="name" aria-describedby="name-error"
                        @error('name') aria-invalid="true" @enderror>
                    @error('name')
                        <p id="name-error" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Email --}}
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Email address') }}
                    </label>
                    <input wire:model="email" id="email" type="email"
                        class="field @error('email') error @enderror" placeholder="e.g. juan@stockmaster.ph"
                        autocomplete="email" aria-describedby="email-error"
                        @error('email') aria-invalid="true" @enderror>
                    @error('email')
                        <p id="email-error" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Password --}}
                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Password') }}
                    </label>
                    <div class="relative">
                        <input wire:model="password" :type="showPw ? 'text' : 'password'" id="password"
                            class="field pe-11 @error('password') error @enderror"
                            placeholder="Create a strong password" autocomplete="new-password" x-model="password"
                            aria-describedby="password-strength password-error"
                            @error('password') aria-invalid="true" @enderror>
                        <button type="button" @click="showPw = !showPw"
                            class="absolute end-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors"
                            :aria-label="showPw ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPw" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPw" x-cloak class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>

                    {{-- Strength meter — always shown --}}
                    <div id="password-strength" class="mt-2">
                        <div class="flex gap-1 mb-1" aria-hidden="true">
                            <template x-for="(bar, i) in strengthBars" :key="i">
                                <div class="h-1 flex-1 rounded-full transition-colors duration-300"
                                    :class="bar"></div>
                            </template>
                        </div>
                        <p class="text-xs font-medium transition-colors duration-300"
                            :class="password.length ? strengthColor : 'text-gray-400'">
                            Password strength: <span x-text="password.length ? strengthLabel : 'None'"></span>
                        </p>
                    </div>

                    @error('password')
                        <p id="password-error" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Confirm Password --}}
                <div>
                    <label for="password_confirmation"
                        class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">
                        {{ __('Confirm password') }}
                    </label>
                    <div class="relative">
                        <input wire:model="password_confirmation" :type="showPwConfirm ? 'text' : 'password'"
                            id="password_confirmation"
                            class="field pe-11 @error('password_confirmation') error @enderror"
                            placeholder="Re-enter your password" autocomplete="new-password"
                            aria-describedby="password-confirm-error"
                            @error('password_confirmation') aria-invalid="true" @enderror>
                        <button type="button" @click="showPwConfirm = !showPwConfirm"
                            class="absolute end-2.5 top-1/2 -translate-y-1/2 p-1.5 rounded-lg text-gray-400 hover:text-gray-600 dark:hover:text-gray-200 hover:bg-gray-100 dark:hover:bg-white/10 transition-colors"
                            :aria-label="showPwConfirm ? 'Hide password' : 'Show password'">
                            <svg x-show="!showPwConfirm" class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="showPwConfirm" x-cloak class="w-4 h-4" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.542 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21" />
                            </svg>
                        </button>
                    </div>
                    @error('password_confirmation')
                        <p id="password-confirm-error" class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Terms agreement --}}
                <div class="flex items-start gap-2.5 pt-1">
                    <input id="agreed" type="checkbox" x-model="agreed"
                        class="w-4 h-4 mt-0.5 rounded border-gray-300 text-brand-600 focus:ring-brand-400 flex-shrink-0">
                    <label for="agreed" class="text-sm text-gray-600 dark:text-gray-300 leading-snug">
                        {{ __('I agree to the') }}
                        <a href="#"
                            class="font-semibold text-brand-600 hover:text-brand-700">{{ __('Terms of Service') }}</a>
                        {{ __('and') }}
                        <a href="#"
                            class="font-semibold text-brand-600 hover:text-brand-700">{{ __('Privacy Policy') }}</a>
                    </label>
                </div>

                <button type="submit" :disabled="!agreed"
                    class="w-full flex items-center justify-center gap-2 px-4 py-2.5 text-white text-sm font-semibold rounded-xl transition-all shadow-sm min-h-[44px]"
                    :style="agreed
                        ?
                        'background-color: #4f46e5; cursor: pointer;' :
                        'background-color: #a5b4fc; cursor: not-allowed; opacity: 0.7;'"
                    wire:loading.attr="disabled" wire:target="register">

                    {{-- Spinner: only visible while loading --}}
                    <svg wire:loading wire:target="register" class="animate-spin w-4 h-4 shrink-0" fill="none"
                        viewBox="0 0 24 24" aria-hidden="true">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                            stroke-width="4" />
                        <path class="opacity-75" fill="currentColor"
                            d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4z" />
                    </svg>

                    {{-- Text: swaps between idle and loading --}}
                    <span wire:loading.remove wire:target="register">{{ __('Create Account') }}</span>
                    <span wire:loading wire:target="register">{{ __('Creating account…') }}</span>

                </button>

            </form>

            <p class="text-center text-sm text-gray-500 dark:text-gray-400 mt-6">
                {{ __('Already have an account?') }}
                <a href="{{ route('login') }}" wire:navigate
                    class="font-semibold text-brand-600 hover:text-brand-700">
                    {{ __('Sign in') }}
                </a>
            </p>
        </div>

        <p class="text-center text-xs text-gray-400 dark:text-gray-500 mt-6">
            <a href="{{ route('dashboard') }}" wire:navigate class="hover:text-brand-600 transition-colors">
                ← {{ __('Back to dashboard') }}
            </a>
        </p>

    </main>
</div>
