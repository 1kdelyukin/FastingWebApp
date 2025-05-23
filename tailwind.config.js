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
            colors: {
                customDarkGray: '#434738',
                customGray: '#58626E',
                customGreen: '#B5D43B',
                customLightGreen:  '#D9EF82',
                customGray2: '#D9D9D9',
                customGreen2: '#007705',
                customEgg: '#E6EEB6',
              },
              fontFamily: {
                sans: ['"Inria Sans"', 'sans-serif'],
              },
        },
    },

    
    plugins: [forms],
};
