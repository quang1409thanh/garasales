import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { viteStaticCopy } from 'vite-plugin-static-copy';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
            // Thêm base path để đảm bảo tài nguyên được phục vụ qua HTTPS
            base: process.env.APP_URL || 'https://garasales-1027992830683.asia-east2.run.app/', // Thay your-domain.com bằng tên miền của bạn
        }),
        viteStaticCopy({
            targets: [
                // Styles
                {
                    src: [
                        'node_modules/@tabler/core/dist/css/tabler.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-flags.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-payments.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-vendors.min.css',
                        'node_modules/@tabler/core/dist/css/demo.min.css',
                        'node_modules/@tabler/core/dist/css/tabler-social.min.css',
                    ],
                    dest: '../dist/css',
                },
                // Scripts
                {
                    src: [
                        'node_modules/@tabler/core/dist/js/demo-theme.min.js',
                        'node_modules/@tabler/core/dist/js/tabler.min.js',
                        'node_modules/@tabler/core/dist/js/demo.min.js',
                    ],
                    dest: '../dist/js',
                },
                // libraries
                {
                    src: 'node_modules/@tabler/core/dist/libs/*',
                    dest: '../dist/libs',
                },
                // Images
                {
                    src: 'node_modules/@tabler/core/dist/img/*',
                    dest: '../dist/img',
                },
            ],
        }),
    ],
});
