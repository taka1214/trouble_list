const defaultTheme = require('tailwindcss/defaultTheme');

/** @type {import('tailwindcss').Config} */
module.exports = {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                'default': '#c8ad85',
                'default-50': 'rgba(200, 173, 133, 0.5)', // 50% opacity version of default
                'default-10': 'rgba(200, 173, 133, 0.1)', // 80% opacity version of default
                'alert': '#b03b3b',
                'alert-50': 'rgba(176, 59, 59, 0.5)', // 50% opacity version of alert
            },
        },
    },

    plugins: [require('@tailwindcss/forms')],
};
