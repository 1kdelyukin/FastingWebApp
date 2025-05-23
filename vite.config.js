

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/css/jsCalendar.css', 
                'resources/js/app.js',
                'resources/js/notes.js',
                'resources/js/jsCalendar.js',
                'resources/js/barGraph.js',
                'resources/js/timer.js',
                'resources/js/foodHistory.js',
                'resources/js/statsBox.js',
                'resources/js/notesHistory.js'
            ],
            refresh: true,
        }),
    ],
});