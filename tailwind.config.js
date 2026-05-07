/** @type {import('tailwindcss').Config} */
export default {
  content: [
    './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
    './storage/framework/views/*.php',
    './resources/views/**/*.blade.php',
    './resources/js/**/*.js',
  ],
  theme: {
    extend: {
      colors: {
        primary: {
          50: '#fff8f3',
          100: '#ffeddc',
          200: '#ffd3b5',
          300: '#ffb27f',
          400: '#f48a47',
          500: '#d96a2b',
          600: '#b4531f',
          700: '#8f3f16',
          800: '#742f12',
          900: '#5a240e',
        },
      },
    },
  },
  plugins: [],
}

