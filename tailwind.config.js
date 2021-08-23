const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

const allColors = {
    ...colors,
    ...{
        "cat": '#293042',
        "cat-light": '#343A4B',
        "cat-dark": '#202634',
        "cat-blue": '#366DC7',
        "cat-blue-light": '#3B82EC',
        "cat-gray": '#C8C8C8'
    }

};

module.exports = {
    mode: 'jit',
    purge: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './vendor/laravel/jetstream/**/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
        './resources/js/**/*.vue',
    ],
    theme: {
        extend: {
            "fontFamily": {
                "sans": ['Nunito', ...defaultTheme.fontFamily.sans],
            },
        },
        colors:allColors
    },

    variants: {
        extend: {
            opacity: ['disabled'],
        },
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
