/** @type {import('tailwindcss').Config} */
export default {
  content: ['./index.html', './src/**/*.{js,vue}'],
  theme: {
    extend: {
      fontFamily: {
        sans: ['Roboto Condensed', 'system-ui'],
        rubik: ['Rubik', 'system-ui'],
      },
      colors: {
        dark: '#010044',
        blue: '#103f8f',
        orange: '#ff7f5f',
        green: '#07b87e',
        gray: {
          100: '#f4f4f4',
          200: '#cbcbcb',
          400: '#828282',
          800: '#272727',
        },
      },
    },
  },
  plugins: [require('@tailwindcss/typography')],
}
