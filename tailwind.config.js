import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        //'./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        //'./storage/framework/views/*.php',
        //'./resources/views/**/*.blade.php',

        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],

    theme: {
        extend: {
            /*fontFamily: {
                sans: ['Figtree', ...defaultTheme.fontFamily.sans],
            },*/
            fontFamily: {
                sans: ['Inter var', ...defaultTheme.fontFamily.sans],
              }
        },
        keyframes: {
            progress: {
                '0%': { width: '0%' },
                '100%': { width: '100%' }
            },
            spin: {
                '0%': { transform: 'rotate(0deg)' },
                '100%': { transform: 'rotate(360deg)' },
            }
        },
        animation: {
            'progress': 'progress 2s ease-in-out infinite',
            'spin': 'spin 1s linear infinite',
        }
    },

    plugins: [forms],

    safelist: [
        'border-purple-200',
        'border-purple-500',
        'border-t-purple-500',
        'border-r-purple-500'
    ],
};
