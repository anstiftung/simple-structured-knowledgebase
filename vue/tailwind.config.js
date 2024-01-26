/** @type {import('tailwindcss').Config} */

export default {
  content: ['./index.html', './src/**/*.{js,vue}'],
  theme: {
    extend: {
      height: {
        header: '100px',
      },
      spacing: {
        header: '100px',
      },
      fontFamily: {
        sans: ['Inter', 'system-ui'],
        rubik: ['Rubik', 'system-ui'],
      },
      colors: {
        dark: '#010044',
        blue: {
          200: '#5879b1',
          DEFAULT: '#103f8f',
          400: '#007FFF',
          600: '#0F3F8F',
        },
        orange: '#ff7f5f',
        yellow: '#FFE24B',
        red: 'red',
        green: '#07b87e',
        gray: {
          100: '#f4f4f4',
          200: '#cbcbcb',
          300: '#666',
          400: '#828282',
          800: '#272727',
        },
      },
    },
  },
  plugins: [require('@tailwindcss/typography')],
}
