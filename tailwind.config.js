import forms from '@tailwindcss/forms';

const olive = {
    DEFAULT: '#554F13',
    50: '#FAF8EF',
    100: '#F1EDD7',
    200: '#DDD6AA',
    300: '#C3BA73',
    400: '#9F9544',
    500: '#7B7125',
    600: '#554F13',
    700: '#413B0E',
    800: '#2F2A09',
};

/**
 * Brand theme, compiled by Vite (previously injected at runtime by the
 * Tailwind CDN script). Note `green` is intentionally remapped to the olive
 * palette, so `green-*` utilities render in brand olive.
 *
 * @type {import('tailwindcss').Config}
 */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
        './app/**/*.php',
    ],

    theme: {
        extend: {
            colors: {
                'instagram-pink': '#E4405F',
                'instagram-purple': '#8134AF',
                'instagram-blue': '#0095F6',
                'instagram-gradient-start': '#F56040',
                'instagram-gradient-middle': '#F77737',
                'instagram-gradient-end': '#FCAF45',
                'brand-pink': '#FF6B9D',
                'brand-purple': '#C77DFF',
                'brand-gold': '#FFD700',
                'brand-cream': '#FFF8DC',
                'brand-charcoal': '#36454F',
                'brand-green': '#554F13',
                'brand-green-deep': '#413B0E',
                'brand-green-soft': '#8D8540',
                beige: {
                    50: '#FBF7F1',
                    100: '#F2EBDD',
                },
                nude: '#F5E6D3',
                rose: '#E8C4D4',
                olive,
                green: olive,
            },
            fontFamily: {
                serif: ['Ahsing', 'Poppins', 'serif'],
                sans: ['Poppins', 'sans-serif'],
                display: ['Ahsing', 'Poppins', 'serif'],
                instagram: ['Ahsing', 'Poppins', 'serif'],
            },
            backgroundImage: {
                'instagram-gradient': 'linear-gradient(45deg, #f09433 0%, #e6683c 25%, #dc2743 50%, #cc2366 75%, #bc1888 100%)',
                'brand-gradient': 'linear-gradient(135deg, #554F13 0%, #7B7125 55%, #9F9544 100%)',
            },
        },
    },

    plugins: [forms],
};
