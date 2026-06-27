const colors = require('tailwindcss/colors')

export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],

    theme: {
        extend: {
            colors: {
                primary: colors.blue,
                accent:  colors.amber,
                success: colors.green,
                danger:  colors.red,
                warning: colors.amber,
                neutral: colors.slate,
                'brand-gold': {
                    50:  '#fdf8ed',
                    100: '#faf0d9',
                    200: '#f5e1b3',
                    300: '#f0d27d',
                    400: '#ebc347',
                    500: '#d4af37',  // Primary brand gold
                    600: '#c4a530',
                    700: '#b49b28',
                    800: '#a49120',
                    900: '#8a7818',
                },
            },
            fontFamily: {
                sans:  ['system-ui', 'sans-serif'],
                serif: ['Georgia', 'serif'],
                mono:  ['monospace'],
            },
            animation: {
                'pulse-slow': 'pulse-slow 3s ease-in-out infinite',
                'shimmer': 'shimmer 2s infinite',
            },
            keyframes: {
                'pulse-slow': {
                    '0%, 100%': { opacity: '0.6', width: '5rem' },
                    '50%': { opacity: '1', width: '7rem' },
                },
                'shimmer': {
                    '100%': { transform: 'translateX(100%)' },
                },
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
            },
        },
    },

    plugins: [],
};
