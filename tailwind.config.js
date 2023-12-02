/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./**/*.{html,js,php}"],
  theme: {
    extend: {
      container: {
        screens:{
          '2xl': '1400px',
        },
        center: true,
      }
    },
  },
  plugins: [],
}