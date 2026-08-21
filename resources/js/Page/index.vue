<style>
/* contoh tambahkan ini di style scoped di komponen atau di css globalmu */
.loader-container {
    position: fixed;
    z-index: 999999;
    background: rgb(255 255 255 / 58%);
    width: 100vw;
    height: 100vh;
    text-align: -webkit-center;
    align-content: center;
    color: black;
}

.loader {
    width: 50px;
    aspect-ratio: 1;
    display: grid;
    border: 4px solid #0000;
    border-radius: 50%;
    border-color: #ccc #0000;
    animation: l16 1s infinite linear;
}

.loader::before,
.loader::after {
    content: "";
    grid-area: 1/1;
    margin: 2px;
    border: inherit;
    border-radius: 50%;
}

.loader::before {
    border-color: #f03355 #0000;
    animation: inherit;
    animation-duration: .5s;
    animation-direction: reverse;
}

.loader::after {
    margin: 8px;
}

@keyframes l16 {
    100% {
        transform: rotate(1turn)
    }
}

.v-select {
    padding: 0;
}

.v-select .vs__dropdown-toggle {
    border: 1px solid #ced4da;
    /* bootstrap input border */
    height: calc(2.25rem + 2px);
    /* tinggi input bootstrap */
    border-radius: 0.375rem;
    /* rounded bootstrap */
    font-size: 1rem;
}

.vs__selected-options {
    padding-left: 0.75rem;
}

.vs__search {
    margin: 0;
    padding: 0;
}
</style>
<template>
    <div class="loader-container" v-if="globalLoader.show" role="status" aria-live="polite">
        <div class="loader"></div>
        <span class="visually-hidden">Loading...</span>
    </div>
    <div id="wrapper" :class="{ 'admin-shell': this.$route.name !== 'notFound' }" style="user-select: text;">
        <landing-navigation v-if="this.$route.name !== 'notFound'"></landing-navigation>
        <landing-header v-if="this.$route.name !== 'notFound'"></landing-header>
        <main :class="{ 'content-wrapper': this.$route.name !== 'notFound' }">
            <div :class="{ 'container-fluid admin-content-container': this.$route.name !== 'notFound' }">
                <router-view>
                </router-view>
            </div>
        </main>

        <button type="button" class="back-to-top" :class="{ 'is-visible': show_back_to_top }"
            aria-label="Back to top" @click="scroll_to_top">
            <i class="fa fa-angle-up"></i>
        </button>
        <landing-footer v-if="this.$route.name !== 'notFound'"></landing-footer>
        <button v-if="this.$route.name !== 'notFound'" type="button" class="sidebar-backdrop"
            aria-label="Close navigation" @click="close_sidebar"></button>
    </div>
</template>

<script>
import landingHeader from "../components/landing_header.vue";
import landingFooter from "../components/landing_footer.vue";
import landingNavigation from "./index_navigation.vue"


export default {
    components: {
        landingHeader,
        landingFooter,
        landingNavigation,
    },
    data() {
        return {
            show_back_to_top: false,
        };
    },
    methods: {
        check_login() {
            if (localStorage.getItem('token') == null) {
                location.href = '/login';
            }

            $.ajax({
                url: "/api/v1/auth/login/check",
                type: "POST",
                data: {
                    token: localStorage.getItem('token')
                },
                success: function (data) {
                    if (data.status === 1) {

                    }
                    else {
                        localStorage.removeItem('token');
                        location.href = '/login';
                    }
                },
                error: function (err) {
                    $.swal({
                        icon: "error",
                        title: "Error",
                        text: "Internal Server Error",
                    });
                }
            });
        },
        handle_scroll() {
            this.show_back_to_top = window.scrollY > 300;
        },
        scroll_to_top() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth',
            });
        },
        close_sidebar() {
            document.getElementById('wrapper')?.classList.remove('toggled');
        }
    },
    mounted() {
        // this.check_login();
        window.addEventListener('scroll', this.handle_scroll, { passive: true });
        this.handle_scroll();
    },
    beforeUnmount() {
        window.removeEventListener('scroll', this.handle_scroll);
    }
};
</script>
