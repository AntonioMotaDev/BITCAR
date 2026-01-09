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
                sans: ['Montserrat', ...defaultTheme.fontFamily.sans],
            },
            colors: {
            'altivus': {
                'light-blue': '#6B99C5',
                'median-blue': '#4A85B8',
                'dark-blue': '#2C346C',
                'light-green': '#B4CA68',
                'median-green': '#AEC44C',
                'dark-green': '#1B8946',
                'terracota': '#DA6829',
                'pink': '#CB2864',
                'dark-gray': '#333333',
            }
        },
        },
    },

    plugins: [forms],
};
