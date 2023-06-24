module.exports = {
    content: [
        './public/themes/opatrimoine/templates/**/*.php',
        './public/themes/opatrimoine/**/*.php',
        './public/themes/opatrimoine/*.php',
    ],
    theme: {
        colors: {
            white: '#ffffff',
            grey: '#d6d6d6',
            black: '#000',
            transparent: 'transparent',
            main: 'var(--color_main)',
            second: 'var(--color_second)',
            third: 'var(--color_third)',
            fourth: 'var(--color_fourth)',
        },
        fontFamily: {
            'sans': ['Helvetica', 'Arial', 'sans-serif'],
        },
    },
    plugins: []
}
