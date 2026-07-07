export default {
    content: [
        './resources/**/*.blade.php',
        './resources/**/*.js',
        './resources/**/*.ts',
        './resources/**/*.vue',
        './packages/simone-bianco/vue-ui-components/src/**/*.{js,ts,vue}',
    ],
    theme: {
        extend: {
            colors: {
                ui: {
                    background: 'rgb(var(--ui-background) / <alpha-value>)',
                    foreground: 'rgb(var(--ui-foreground) / <alpha-value>)',
                    card: 'rgb(var(--ui-card) / <alpha-value>)',
                    border: 'rgb(var(--ui-border) / <alpha-value>)',
                    primary: 'rgb(var(--ui-primary) / <alpha-value>)',
                    'primary-foreground': 'rgb(var(--ui-primary-foreground) / <alpha-value>)',
                    'primary-hover': 'rgb(var(--ui-primary-hover) / <alpha-value>)',
                    secondary: 'rgb(var(--ui-secondary) / <alpha-value>)',
                    'secondary-foreground': 'rgb(var(--ui-secondary-foreground) / <alpha-value>)',
                    'secondary-hover': 'rgb(var(--ui-secondary-hover) / <alpha-value>)',
                    muted: 'rgb(var(--ui-muted) / <alpha-value>)',
                    'muted-foreground': 'rgb(var(--ui-muted-foreground) / <alpha-value>)',
                    input: 'rgb(var(--ui-input) / <alpha-value>)',
                    ring: 'rgb(var(--ui-ring) / <alpha-value>)',
                    error: 'rgb(var(--ui-error) / <alpha-value>)',
                    destructive: 'rgb(var(--ui-destructive) / <alpha-value>)',
                    'destructive-foreground': 'rgb(var(--ui-destructive-foreground) / <alpha-value>)',
                    'destructive-hover': 'rgb(var(--ui-destructive-hover) / <alpha-value>)',
                    warning: 'rgb(var(--ui-warning) / <alpha-value>)',
                    'warning-foreground': 'rgb(var(--ui-warning-foreground) / <alpha-value>)',
                },
            },
        },
    },
    plugins: [require('@tailwindcss/forms')],
};
