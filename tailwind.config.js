module.exports = {
    content: [
        './themes/opatrimoine/templates/**/*.php',
        './themes/opatrimoine/**/*.php',
        './themes/opatrimoine/*.php',
    ],
    theme: {
        colors: {
            white: '#ffffff',
            black: '#000',
            transparent: 'transparent',
            main: 'var(--color_main)',
            second: 'var(--color_second)',
            third: 'var(--color_third)',

            greyLight: 'rgb(203 213 225)',
            grey: 'rgb(100, 116, 139)',
            greyDark: 'rgb(51 65 85)',

        },
        fontFamily: {
            'sans': ['Helvetica', 'Arial', 'sans-serif'],
        },
    },
    plugins: []
}
