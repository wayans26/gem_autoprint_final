<template>
    <div class="loader-container" v-if="globalLoader.show" role="status" aria-live="polite">
        <div class="loader"></div>
        <span class="visually-hidden">Loading...</span>
    </div>
    <div id="wrapper" class="auth-layout">
        <div class="auth-layout__main">
            <aside class="auth-showcase">
                <div class="auth-showcase__content">
                    <span class="auth-showcase__eyebrow">Visitor Management</span>
                    <h1>Manage every visitor journey with confidence.</h1>
                    <p>One secure workspace for a smoother registration and event experience.</p>

                    <ul class="auth-showcase__features">
                        <li><i class="fa fa-check" aria-hidden="true"></i> Streamlined visitor registration</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Reliable event operations</li>
                        <li><i class="fa fa-check" aria-hidden="true"></i> Secure team access</li>
                    </ul>
                </div>
                <div class="auth-showcase__decoration" aria-hidden="true"></div>
            </aside>

            <main class="auth-form-panel">
                <div class="auth-form-panel__header">
                    <span><i class="fa fa-lock" aria-hidden="true"></i> Secure access</span>
                </div>
                <div class="auth-form-panel__content">
                    <router-view>
                    </router-view>
                </div>
            </main>
        </div>

        <a href="#app" class="back-to-top" aria-label="Back to top"><i class="fa fa-angle-double-up"></i>
        </a>
        <landing-footer v-if="this.$route.name !== 'notFound'"></landing-footer>
    </div>
</template>

<script>
import landingFooter from "../components/landing_footer.vue";


export default {
    components: {
        landingFooter
    },
};
</script>
