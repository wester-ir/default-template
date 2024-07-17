const colors = require('tailwindcss/colors');

/** @type {import('tailwindcss').Config} */
module.exports = {
  important: true,
  content: ["./views/**/*.{html,js,php}"],
  safelist: [
    'w-3',
    'h-3',
  ],
  theme: {
    extend: {
        colors: {
            success: colors.green[500],
            primary: colors.sky[500],
            'success-hovered': colors.green[600],
            danger: colors.red[500],
            'danger-hovered': colors.red[600],
            light: colors.neutral[50],
            secondary: colors.neutral[100],
        },
        screens: {
            // 'slg': '1135px',
            'xs': '480px',
        },
    },
},
  plugins: [],
}
