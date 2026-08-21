<template>
    <aside id="sidebar-wrapper" class="admin-sidebar" data-simplebar="" data-simplebar-auto-hide="true"
        aria-label="Main navigation">
        <div class="brand-logo">
            <span class="brand-mark">
                <img :src="'/logontt.png'" class="logo-icon" alt="Visitor Registration logo" />
            </span>
            <span class="brand-copy">
                <span class="logo-text">Visitor</span>
                <small>Administration</small>
            </span>
        </div>
        <nav class="sidebar-navigation">
            <ul class="sidebar-menu do-nicescrol">
                <li class="sidebar-header">WORKSPACE</li>
                <li v-for="(item, index) in globalNavigation" :key="index" class="sidebar-parent"
                    :class="{ active: isActiveParent(item), open: isOpenParent(item, index) }">
                    <a href="#" class="waves-effect sidebar-parent-link" :aria-expanded="isOpenParent(item, index)"
                        @click.prevent="toggle_parent(index)">
                        <i :class="item.icon"></i>
                        <span>{{ item.parent }}</span>
                        <i class="fa fa-angle-left ms-auto sidebar-chevron"></i>
                    </a>
                    <ul class="sidebar-submenu" :class="{ 'menu-open': isOpenParent(item, index) }">
                        <li v-for="(sub, indexsub) in item.child" :key="indexsub"
                            :class="{ active: isActive(sub.page_name) }">
                            <router-link :to="{ name: sub.page_name }" class="waves-effect"
                                @click="close_sidebar_mobile">
                                <i class="fa fa-arrow-right"></i>
                                <span>{{ sub.name }}</span>
                            </router-link>
                        </li>
                    </ul>
                </li>
                <li class="sidebar-logout">
                    <a @click="confimLogout" href="javascript:void(0)" class="waves-effect">
                        <i class="fa fa-power-off"></i>
                        <span>Logout</span>
                    </a>
                </li>
            </ul>
        </nav>
        <div class="sidebar-footer-note">
            <span class="status-dot"></span>
            <span>System ready</span>
        </div>
    </aside>
</template>

<script>
import Swal from 'sweetalert2';

export default {
    created() { },
    data() {
        return {
            items: [],
            user: {},
            open_parent: null,
        };
    },
    methods: {
        logout() {
            localStorage.removeItem('token');
            location.href = '/auth/login';
        },
        isActive(routeName) {
            return this.$route.name === routeName;
        },
        isActiveParent(item) {
            return Array.isArray(item?.child) && item.child.some((c) => c.page_name === this.$route.name);
        },
        isOpenParent(item, index) {
            if (this.open_parent === null) {
                return this.isActiveParent(item);
            }

            return this.open_parent === index;
        },
        toggle_parent(index) {
            this.open_parent = this.open_parent === index ? -1 : index;
        },
        close_sidebar_mobile() {
            if (window.innerWidth < 992) {
                document.getElementById('wrapper')?.classList.remove('toggled');
            }
        },
        isMandatoryPage(routeName) {
            const mandatory = [
                'welcome'
            ];

            return mandatory.includes(this.$route.name);
        },
        confimLogout() {
            const vm = this;
            Swal.fire({
                icon: "warning",
                title: "Warning",
                allowOutsideClick: false,
                allowEscapeKey: false,
                text: "Are you sure you want to logout!",
                confirmButtonColor: "#3085d6",
                confirmButtonText: "Yes!",
                cancelButtonText: "No, cancel!",
                showCancelButton: true,
                didOpen: () => {
                    Swal.showLoading();
                    setTimeout(() => { Swal.hideLoading() }, 1000)
                }
            }).then((result) => {
                $(".confirm").attr('disabled', 'disabled');
                if (result.isConfirmed) {
                    vm.logout();
                }
            });
        }
    },
    watch: {
        '$route.name'() {
            this.open_parent = null;
            this.close_sidebar_mobile();
        }
    }
};
</script>
