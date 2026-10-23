import { defineConfig } from "vite";
import laravel from "laravel-vite-plugin";
import vue from "@vitejs/plugin-vue";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                "resources/js/Master/index.js",
                "resources/js/Master/index_login.js",
                // template Page
                "resources/js/Form/Master/index_reguler.js",
                "resources/js/Form/Master/index_vip.js",
                "resources/js/Form/Master/index_busworld.js",
            ],
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
    resolve: {
        alias: {
            vue: "vue/dist/vue.esm-bundler.js",
        },
    },
    build: {
        target: "esnext",
        sourcemap: false,
        cssCodeSplit: true,
        rollupOptions: {
            output: {
                entryFileNames: "assets/[hash].js",
                chunkFileNames: "assets/[hash].js",
                assetFileNames: "assets/[hash][extname]",
                manualChunks: {
                    vue: ["vue", "vue-router"],
                    sweetalert: ["sweetalert2"],
                    apexcharts: ["apexcharts", "vue3-apexcharts"],
                    vendor: [
                        "js-file-download",
                        "vue3-carousel",
                        "@chenfengyuan/vue-barcode",
                        "lodash.debounce",
                        "yup",
                        "vee-validate",
                    ],
                },
            },
        },
    },
    esbuild: {
        drop: ["console", "debugger"], // optional, kecilkan bundle
    },
});
