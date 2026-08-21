<template>
    <header class="registration-header">
        <nav class="navbar registration-navbar" aria-label="Visitor registration header">
            <div class="container registration-navbar__inner">
                <div class="registration-brand">
                    <span class="registration-brand__mark">
                        <img src="/assets/images/logo-gem-indonesia.png" alt="GEM Indonesia">
                    </span>
                    <span class="registration-brand__copy">
                        <strong>Visitor Registration</strong>
                        <small>PT Global Expo Management</small>
                    </span>
                </div>

                <div class="registration-header__actions">
                    <span class="registration-secure d-none d-md-inline-flex">
                        <i class="fa fa-shield"></i>
                        Secure registration
                    </span>
                    <button type="button" class="registration-theme-toggle"
                        :title="is_dark ? 'Use light mode' : 'Use dark mode'"
                        :aria-label="is_dark ? 'Use light mode' : 'Use dark mode'" :aria-pressed="is_dark"
                        @click="toggle_theme">
                        <i :class="is_dark ? 'fa fa-sun-o' : 'fa fa-moon-o'"></i>
                        <span class="d-none d-sm-inline">{{ is_dark ? 'Light' : 'Dark' }} mode</span>
                    </button>
                </div>
            </div>
        </nav>
    </header>
</template>

<script>
export default {
    data() {
        return {
            is_dark: false,
        };
    },
    methods: {
        get_system_theme() {
            return window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        },
        get_preferred_theme() {
            try {
                const saved_theme = localStorage.getItem('theme');

                if (saved_theme === 'light' || saved_theme === 'dark') {
                    return saved_theme;
                }
            } catch (error) {
                return this.get_system_theme();
            }

            return this.get_system_theme();
        },
        apply_theme(theme, save_theme = false) {
            const selected_theme = theme === 'dark' ? 'dark' : 'light';

            document.documentElement.setAttribute('data-bs-theme', selected_theme);
            document.documentElement.setAttribute('data-theme', selected_theme);
            document.documentElement.style.colorScheme = selected_theme;
            document.querySelector('meta[name="theme-color"]')?.setAttribute(
                'content',
                selected_theme === 'dark' ? '#0b1220' : '#f4f7fb'
            );
            this.is_dark = selected_theme === 'dark';

            if (save_theme) {
                try {
                    localStorage.setItem('theme', selected_theme);
                } catch (error) {
                    return;
                }
            }
        },
        toggle_theme() {
            this.apply_theme(this.is_dark ? 'light' : 'dark', true);
        },
    },
    mounted() {
        this.apply_theme(this.get_preferred_theme());
    },
};
</script>
