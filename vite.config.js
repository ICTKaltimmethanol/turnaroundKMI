import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

import tailwindcss from '@tailwindcss/vite'


export default defineConfig({
    theme: {
        extend: {
          fontFamily: {
            roboto: ['Roboto', 'sans-serif'], // Define 'roboto' as a custom font family
          },
        },
      },
    plugins: [
        tailwindcss(),
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
});
