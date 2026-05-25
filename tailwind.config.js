import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Poppins', ...defaultTheme.fontFamily.sans],
                orbitron: ['Orbitron', 'sans-serif'],
            },
            colors: {
                primary: '#22c55e',
                background: '#0f0f0f',
                surface: '#181818',
                card: '#1f1f1f',
                'secondary-text': '#bdbdbd',
                danger: '#ef4444',
                success: '#22c55e',
            },
            boxShadow: {
                'neon-green': '0 0 10px #22c55e, 0 0 20px #22c55e',
            },
        },
    },

    plugins: [forms],
};
