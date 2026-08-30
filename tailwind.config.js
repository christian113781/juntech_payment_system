import defaultTheme from 'tailwindcss/defaultTheme';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',   // ← add this; Breeze may already have it
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/Livewire/**/*.php',       // ← add this for Volt/Livewire classes
        './app/View/Components/**/*.php',
    ],
    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans], // replaces Inter if Breeze set it
            },
            colors: {
                brand: {
                    50:  '#f0f4ff',
                    100: '#e0eaff',
                    200: '#c7d7fd',
                    400: '#818cf8',
                    500: '#6366f1',
                    600: '#4f46e5',
                    700: '#4338ca',
                    900: '#1e1b4b',
                },
            },
            boxShadow: {
                card: '0 1px 4px 0 rgba(99,102,241,.08), 0 4px 16px 0 rgba(99,102,241,.06)',
            },
        },
    },
    plugins: [],
};
