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
        "cat-gray": '#C8C8C8',
        "cat-dark-light": '#293042',
        "cat-logo":'#042f69',

        "cat-hard-1":'#293042',
        "cat-hard-2":'#8096CF',
        "cat-hard-3":'#59688F',
        "cat-hard-4":'#313A4F',
        "cat-hard-5":'#202533',

        "cat-hard-secundary-1":'#4A5E8F',
        "cat-hard-secundary-2":'#67728F',
        "cat-hard-secundary-3":'#8F773C',
        "cat-hard-secundary-4":'#423B29',

        "cat-hard-blue-1":'#043069',
        "cat-hard-blue-2":'#0A70F5',
        "cat-hard-blue-3":'#0753B5',
        "cat-hard-blue-4":'#053575',
        "cat-hard-blue-5":'#03244F',

        "cat-hard-colors-1":'#60A366',
        "cat-hard-colors-2":'#77AD66',
        "cat-hard-colors-3":'#A9AD66',
        "cat-hard-colors-4":'#A39E60',
        "cat-hard-colors-5":'#809660',

        "cat-light-1":'#F9FAFB',
        "cat-light-2":'#B8B9BA',
        "cat-light-3":'#797A7A',
        "cat-light-4":'#3A3A3B',
        "cat-light-5":'#DEDFE0',

        "cat-light-secundary-1":'#9AA4AD',
        "cat-light-secundary-2":'#ADA089',
        "cat-light-secundary-3":'#FAF9F7',

        "cat-light-blue-1":'#54677A',
        "cat-light-blue-2":'#62AEFB',
        "cat-light-blue-3":'#ACD3FA',
        "cat-light-blue-4":'#30557A',
        "cat-light-blue-5":'#89A8C7',

        "cat-light-colors-1":'#99D654',
        "cat-light-colors-2":'#D0E058',
        "cat-light-colors-3":'#E0C958',
        "cat-light-colors-4":'#D6B354',
        "cat-light-colors-5":'#C9C259',

        "cat-gray-1":'#C8C8C8',
        "cat-gray-2":'#878787',
        "cat-gray-3":'#474747',
        "cat-gray-4":'#D4D4D4',
        "cat-gray-5":'#ADADAD',
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
    darkMode:'class',
    theme: {
        extend: {
            fontFamily: {
                'font-cat':'Roboto'
            },
        },
        colors:allColors
    },
    variants: {
        extend: {
            opacity: ['disabled']
        }
    },

    plugins: [require('@tailwindcss/forms'), require('@tailwindcss/typography')],
};
