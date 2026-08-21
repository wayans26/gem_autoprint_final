<template>
    <footer class="registration-footer">
        <div class="container registration-footer__inner">
            <p class="mb-0">
                &copy; {{ current_year }}
                <a href="https://gem-indonesia.net/" target="_blank" rel="noopener noreferrer">
                    PT Global Expo Management
                </a>
            </p>
            <p class="mb-0 registration-footer__note">Professional exhibition services</p>
        </div>
    </footer>
</template>

<script>
export default {
    data() {
        return {
            current_year: new Date().getFullYear(),
        };
    },
};
</script>
