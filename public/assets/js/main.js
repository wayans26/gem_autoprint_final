'use strict';

document.documentElement.classList.replace('no-js', 'js');

document.addEventListener('DOMContentLoaded', () => {
    initializeAos();
    initializeThemeToggle();
});

function initializeAos() {
    if (typeof AOS === 'undefined') {
        return;
    }

    AOS.init({
        duration: 900,
        once: true,
        offset: 50,
        easing: 'ease-out-cubic',
        disable: () => window.matchMedia('(prefers-reduced-motion: reduce)').matches
    });
}

function initializeThemeToggle() {
    const toggleButton = document.getElementById('themeToggle');
    const toggleText = document.getElementById('themeToggleText');

    if (!toggleButton) {
        return;
    }

    const getCurrentTheme = () => {
        return document.documentElement.getAttribute('data-theme') === 'dark' ?
            'dark' :
            'light';
    };

    const updateToggleUi = (theme) => {
        const isDark = theme === 'dark';

        toggleButton.setAttribute('aria-pressed', String(isDark));
        toggleButton.setAttribute(
            'aria-label',
            isDark ? 'Switch to light mode' : 'Switch to dark mode'
        );
        toggleButton.setAttribute(
            'title',
            isDark ? 'Switch to light mode' : 'Switch to dark mode'
        );

        if (toggleText) {
            toggleText.textContent = isDark ? 'Dark' : 'Light';
        }
    };

    const applyTheme = (theme, savePreference = true) => {
        document.documentElement.setAttribute('data-theme', theme);
        updateToggleUi(theme);

        if (savePreference) {
            localStorage.setItem('gem-theme', theme);
        }
    };

    updateToggleUi(getCurrentTheme());

    toggleButton.addEventListener('click', () => {
        const nextTheme = getCurrentTheme() === 'dark' ? 'light' : 'dark';
        applyTheme(nextTheme);
    });

    const systemThemeQuery = window.matchMedia('(prefers-color-scheme: dark)');

    systemThemeQuery.addEventListener('change', (event) => {
        if (localStorage.getItem('gem-theme')) {
            return;
        }

        applyTheme(event.matches ? 'dark' : 'light', false);
    });
    applyTheme("dark");
}
