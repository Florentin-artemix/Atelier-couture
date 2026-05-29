/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './resources/views/**/*.blade.php',
        './resources/js/**/*.js',
    ],
    theme: {
        extend: {
            colors: {
                'ivoire': '#FAF7F2',
                'sable': '#F5F0E8',
                'lin': '#E8E0D4',
                'terracotta': {
                    50: '#FDF5F0',
                    100: '#FAE8DD',
                    200: '#F5CCBA',
                    300: '#EDA88A',
                    400: '#E07D54',
                    500: '#C75E35',
                    600: '#A84A2A',
                    700: '#8B3B22',
                    800: '#6E2F1C',
                    900: '#5A2718',
                },
                'charbon': '#2D2926',
                'cendre': '#6B6560',
                'dore': '#C8A96B',
            },
            fontFamily: {
                'display': ['"Cormorant Garamond"', 'serif'],
                'body': ['"Inter"', 'sans-serif'],
            },
            borderRadius: {
                'couture': '0.375rem',
            },
        },
    },
    plugins: [
        require('@tailwindcss/forms'),
    ],
};
