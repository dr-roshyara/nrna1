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
            fontFamily: {
                sans:  ['Inter', 'Nunito', 'ui-sans-serif', 'system-ui', '-apple-system', 'BlinkMacSystemFont', 'Segoe UI', 'Roboto', 'Helvetica Neue', 'Arial', 'sans-serif'],
                serif: ['Cormorant Garamond', 'Georgia', 'Times New Roman', 'serif'],
                mono:  ['DM Mono', 'JetBrains Mono', 'Courier New', 'monospace'],
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
