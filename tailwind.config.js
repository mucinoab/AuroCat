const defaultTheme = require('tailwindcss/defaultTheme');
const colors = require('tailwindcss/colors');

const allColors = {
    ...colors,
    ...{
        "auro": {
            "light": '#343A4B',
            "DEFAULT": '#293042',
            "dark": '#202634'
          },
          "auro_blue":{
              "DEFAULT": '#366DC7',
              "blue_light": '#3B82EC'
          },
          "auro_gray":{
              "DEFAULT": '#C8C8C8'
          }
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
