<template>
    <header class="topbar-nav">
        <nav class="navbar fixed-top admin-topbar">
            <div class="container-fluid admin-topbar-inner">
                <div class="d-flex align-items-center min-width-0">
                    <button type="button" class="nav-link toggle-menu admin-icon-button" aria-label="Toggle navigation"
                        aria-controls="sidebar-wrapper" @click="toggle_sidebar">
                        <i class="icon-menu menu-icon"></i>
                    </button>
                    <div class="admin-page-heading">
                        <span class="title-dinas">Visitor Registration</span>
                        <small>Administration panel</small>
                    </div>
                </div>
                <div class="d-flex align-items-center topbar-actions">
                    <span class="topbar-status d-none d-lg-flex">
                        <span class="status-dot"></span>
                        Online
                    </span>
                    <button type="button" class="theme-toggle" :title="is_dark ? 'Use light mode' : 'Use dark mode'"
                        :aria-label="is_dark ? 'Use light mode' : 'Use dark mode'" @click="toggle_theme">
                        <i :class="is_dark ? 'fa fa-sun-o' : 'fa fa-moon-o'"></i>
                        <span class="theme-toggle-label d-none d-sm-inline">
                            {{ is_dark ? 'Light mode' : 'Dark mode' }}
                        </span>
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
        get_preferred_theme() {
            try {
                const saved_theme = localStorage.getItem('theme');

                if (saved_theme === 'light' || saved_theme === 'dark') {
                    return saved_theme;
                }
            } catch (error) {
                return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
            }

            return window.matchMedia('(prefers-color-scheme: dark)').matches ? 'dark' : 'light';
        },
        apply_theme(theme, save_theme = false) {
            const selected_theme = theme === 'dark' ? 'dark' : 'light';

            document.documentElement.setAttribute('data-bs-theme', selected_theme);
            document.documentElement.setAttribute('data-theme', selected_theme);
            document.documentElement.style.colorScheme = selected_theme;
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
        toggle_sidebar() {
            document.getElementById('wrapper')?.classList.toggle('toggled');
        },
        close_sidebar_on_escape(event) {
            if (event.key === 'Escape' && window.innerWidth < 992) {
                document.getElementById('wrapper')?.classList.remove('toggled');
            }
        }
    },
    mounted() {
        this.apply_theme(this.get_preferred_theme());
        document.addEventListener('keydown', this.close_sidebar_on_escape);
    },
    beforeUnmount() {
        document.removeEventListener('keydown', this.close_sidebar_on_escape);
    }
}
</script>
