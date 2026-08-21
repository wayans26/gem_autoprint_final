<template>
    <div class="card card-authentication1 auth-card border-0">
        <div class="card-body">
            <div class="card-content">
                <div class="auth-card__brand text-center">
                    <img :src="'/logontt.png'" class="auth-logo" alt="Gem Indonesia">
                </div>
                <div class="auth-card__heading text-center">
                    <span class="auth-card__eyebrow">Welcome back</span>
                    <h2 id="login-title" class="card-title">Sign in to your account</h2>
                    <p>Enter your account details to continue.</p>
                </div>
                <form method="post" @submit="login" aria-labelledby="login-title">
                    <div class="mb-3">
                        <label for="exampleInputUsername" class="form-label">Username</label>
                        <div class="position-relative auth-input-wrap">
                            <i class="icon-user auth-input-icon" aria-hidden="true"></i>
                            <input type="text" id="exampleInputUsername" class="form-control input-shadow auth-control"
                                placeholder="Enter Username" required v-model="username" autocomplete="username"
                                :disabled="loading">
                        </div>
                    </div>
                    <div class="mb-2">
                        <label for="exampleInputPassword" class="form-label">Password</label>
                        <div class="position-relative auth-input-wrap">
                            <i class="icon-lock auth-input-icon" aria-hidden="true"></i>
                            <input type="password" id="exampleInputPassword" class="form-control input-shadow auth-control"
                                placeholder="Enter Password" required v-model="password" autocomplete="current-password"
                                :disabled="loading">
                        </div>
                    </div>

                    <div class="text-end mb-4">
                        <router-link class="auth-link" :to="{ name: 'forgot_password' }">Forgot Password?</router-link>
                    </div>

                    <button type="submit" :disabled="loading" class="btn btn-primary auth-submit w-100"
                        :aria-busy="loading">
                        <i :class="{
                            'fa fa-spinner fa-spin': loading,
                            'fa fa-sign-in': !loading,
                        }" aria-hidden="true"></i>
                        <span>{{ loading ? 'Signing in...' : 'Login' }}</span>
                    </button>
                </form>

                <div class="auth-card__security text-center">
                    <i class="fa fa-shield" aria-hidden="true"></i>
                    Authorized users only
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import errorMessage from '../components/errorMessage.vue'
export default {
    components: {
        errorMessage
    },
    data() {
        return {
            username: '',
            password: '',
            errors: '',
            loading: false
        }
    },
    methods: {
        login(e) {
            e.preventDefault();
            const vm = this;
            this.loading = true;
            if (!this.username) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Username Cant Be Empty!",
                });
                this.loading = false;
                return;
            }
            if (!this.password) {
                this.$swal({
                    icon: "error",
                    title: "Oops...",
                    text: "Password Cant Be Empty!",
                });
                this.loading = false;
                return;
            }
            vm.globalLoader.show = true;
            $.ajax({
                url: "/api/v1/web/auth/login",
                type: "POST",
                data: {
                    username: this.username,
                    password: this.password,
                },
                success: function (data) {
                    if (data.status === 1) {
                        localStorage.setItem("token", data.data.token);
                        vm.$swal({
                            icon: "success",
                            title: "Login Success",
                            text: "Selamat Datang",
                        });
                        location.href = "/?token=" + localStorage.getItem('token');
                    } else {
                        vm.$swal({
                            icon: "info",
                            title: "Information",
                            text: data.message,
                        });
                        vm.globalLoader.show = false;
                        vm.loading = false;
                    }
                },
                error: function (err) {
                    vm.$swal({
                        icon: "error",
                        title: "Error",
                        text: "Internal Server Error",
                    });
                    vm.globalLoader.show = false;
                    vm.loading = false;
                }
            });
        },
        check_login() {
            const vm = this;
            if (localStorage.getItem('token')) {
                $.ajax({
                    url: "/api/v1/web/auth/login/check",
                    type: "POST",
                    data: {
                        token: localStorage.getItem('token')
                    },
                    success: function (data) {
                        if (data.status === 1) {
                            location.href = "/?token=" + localStorage.getItem('token');
                        }
                        else {
                            vm.globalLoader.show = false;
                            localStorage.removeItem('token');
                        }
                    },
                    error: function (err) {
                        vm.globalLoader.show = false;
                        vm.$swal({
                            icon: "error",
                            title: "Error",
                            text: "Internal Server Error",
                        });
                    }
                });
            }
            else {
                vm.globalLoader.show = false;
            }
        }
    },
    mounted() {
        this.check_login();
    }
}
</script>
