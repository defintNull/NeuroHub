import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/css/createtest.css',
                'resources/css/interviewdetail.css',
                'resources/css/selectwithsearch.css',
                'resources/css/testadministration.css',
                'resources/css/testcompilation.css',
                'resources/css/testdetail.css',
                'resources/css/testscore.css',
                'resources/js/admingraph.js',
                'resources/js/bootstrap.js',
                'resources/js/interviewdetail.js',
                'resources/js/medgraph.js',
                'resources/js/selectwithsearch.js',
                'resources/js/testadministration.js',
                'resources/js/testcompilation.js',
                'resources/js/testcreation.js',
                'resources/js/testdetail.js',
                'resources/js/testscore.js'
            ],
            refresh: true,
        }),
    ],
    build: {
        target: ['chrome128', 'edge128' , 'firefox130', 'safari18', 'esnext'],
    },
});
