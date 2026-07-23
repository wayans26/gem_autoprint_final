<style>
:root {
    --animate-duration: 300ms;
    --animate-delay: 0.9s;
}

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
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
}

.loader {
    width: 50px;
    aspect-ratio: 1;
    display: grid;
    border: 4px solid #0000;
    border-radius: 50%;
    border-color: #858585 #0000;
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
</style>

<template>
    <div id="wrapper" class="toggled">
        <admin-header></admin-header>
        <div class="content-wrapper" style="min-height: 99vh">
            <div class="container-fluid">
                <router-view>
                </router-view>
            </div>
        </div>

        <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i>
        </a>

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
};
</script>
