const cleanup = Symbol('subExhibitionMenuCleanup');

export default {
    mounted(field) {
        const card = field.closest('.registration-card');
        const header = field.closest('.regular-form-shell')?.querySelector('.registration-header');
        const viewport = window.visualViewport;
        const dropup = 'registration-sub-exhibition-up';
        const available_height = '--sub-exhibition-menu-available-height';

        const update_position = () => {
            const select = field.querySelector('.registration-event-select.vs--open');
            const menu = select?.querySelector('.vs__dropdown-menu');
            field.classList.remove(dropup);
            if (!menu) {
                return;
            }

            // Measure the usual downward placement before applying the available height.
            const scroll_top = menu.scrollTop;
            menu.style.removeProperty(available_height);
            const menu_rect = menu.getBoundingClientRect();
            const card_rect = card.getBoundingClientRect();
            if (menu_rect.bottom > card_rect.bottom) {
                const select_rect = select.getBoundingClientRect();
                const gap = menu_rect.top - select_rect.bottom;
                const top_boundary = Math.max(card_rect.top, viewport?.offsetTop ?? 0,
                    header?.getBoundingClientRect().bottom ?? 0);
                const space_above = Math.max(0, select_rect.top - gap - top_boundary);
                field.classList.add(dropup);
                menu.style.setProperty(available_height, `${space_above}px`);
            }
            menu.scrollTop = scroll_top;
        };

        const observer = new MutationObserver(update_position);
        observer.observe(field, { childList: true, subtree: true, characterData: true });
        window.addEventListener('resize', update_position);
        window.addEventListener('scroll', update_position, { passive: true });
        viewport?.addEventListener('resize', update_position);

        field[cleanup] = () => {
            observer.disconnect();
            window.removeEventListener('resize', update_position);
            window.removeEventListener('scroll', update_position);
            viewport?.removeEventListener('resize', update_position);
        };
    },
    beforeUnmount(field) {
        field[cleanup]();
    }
};
