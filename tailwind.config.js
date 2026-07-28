/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                ermis: {
                    navy: '#0f172a',
                    blue: '#1e3a8a',
                    teal: '#0d9488',
                    tealdark: '#0f766e',
                },
            },
        },
    },
    plugins: [],
};
