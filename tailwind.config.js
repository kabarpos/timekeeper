import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './node_modules/preline/dist/*.js',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
                manrope: ['Manrope', ...defaultTheme.fontFamily.sans],
            },
            colors: {
                brand: {
                    primary: '#fe7211',
                    secondary: '#ef5707',
                    light: '#ffedd4',
                    white: '#ffffff',
                    gray: '#f9f9f9',
                },
            },
        },
    },

    plugins: [forms],
};
