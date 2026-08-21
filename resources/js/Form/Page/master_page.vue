<template>
    <div class="regular-form-shell">
        <admin-header></admin-header>

        <main class="registration-main">
            <div class="container registration-content">
                <router-view>
                </router-view>
            </div>
        </main>

        <button v-show="show_back_to_top" type="button" class="back-to-top" aria-label="Back to top"
            @click="scroll_to_top">
            <i class="fa fa-angle-up"></i>
        </button>

        <admin-footer></admin-footer>
    </div>
</template>

<script>
import adminHeader from "./header.vue";
import adminFooter from "./footer.vue";

export default {
    components: {
        adminHeader,
        adminFooter,
    },
    data() {
        return {
            show_back_to_top: false,
        };
    },
    methods: {
        handle_scroll() {
            this.show_back_to_top = window.scrollY > 360;
        },
        scroll_to_top() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        },
    },
    mounted() {
        this.handle_scroll();
        window.addEventListener('scroll', this.handle_scroll, { passive: true });
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handle_scroll);
    },
};
</script>
