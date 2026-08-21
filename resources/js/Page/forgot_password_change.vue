<template>
    <div class="card card-authentication1 auth-card border-0">
        <div class="card-body">
            <div class="card-content">
                <div class="auth-card__brand text-center">
                    <img :src="'/logontt.png'" class="auth-logo" alt="Gem Indonesia">
                </div>
                <div class="auth-card__heading text-center">
                    <span class="auth-card__eyebrow">Create new credentials</span>
                    <h2 id="change-password-title" class="card-title">Choose a new password</h2>
                    <p>Use at least 6 characters and make sure both passwords match.</p>
                </div>
                <Form @submit="changePassword" :validation-schema="validate" aria-labelledby="change-password-title">
                    <div class="mb-3">
                        <label for="input-1" class="form-label">New Password</label>
                        <div class="position-relative auth-input-wrap">
                            <i class="icon-lock auth-input-icon" aria-hidden="true"></i>
                            <Field name="password" type="password" class="form-control input-shadow auth-control" id="input-1"
                                placeholder="New Password" v-model="password" autocomplete="new-password"
                                :disabled="loading">
                            </Field>
                        </div>
                        <ErrorMessage class="auth-error" name="password" />
                    </div>
                    <div class="mb-4">
                        <label for="input-1" class="form-label">Confirm New Password</label>
                        <div class="position-relative auth-input-wrap">
                            <i class="icon-lock auth-input-icon" aria-hidden="true"></i>
                            <Field name="confirm_password" type="password" class="form-control input-shadow auth-control"
                                id="input-1" placeholder="Confirm New Password" v-model="confirm_password"
                                autocomplete="new-password" :disabled="loading">
                            </Field>
                        </div>
                        <ErrorMessage class="auth-error" name="confirm_password" />
                    </div>
                    <button type="submit" :disabled="loading" class="btn btn-primary auth-submit w-100"
                        :aria-busy="loading">
                        <i :class="{
                            'fa fa-spinner fa-spin': loading,
                            'fa fa-sign-in': !loading,
                        }" aria-hidden="true"></i>
                        <span>{{ loading ? 'Updating password...' : 'Reset Password' }}</span>
                    </button>
                </Form>
            </div>
        </div>
    </div>
</template>

<script>
import { Form, Field, ErrorMessage } from 'vee-validate';
import * as yup from 'yup';
import swalNotif from '../Utils/swalNotif.js';
import axios from 'axios';
export default {
    components: {
        Form,
        Field,
        ErrorMessage
    },
    data() {
        return {
            password: '',
            confirm_password: '',
            token: this.$route.query.token,
            loading: false,
            validate: yup.object({
                password: yup.string().required("New Password is required").min(6, "Password must be at least 6 characters"),
                confirm_password: yup.string().required("Confirm New Password is required").min(6, "Password must be at least 6 characters").oneOf([yup.ref("password"), null], "Passwords must match"),
            }),
        }
    },
    methods: {
        changePassword() {
            const vm = this;
            this.loading = true;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/auth/reset/password/change", {
                password: this.password,
                confirm_password: this.confirm_password,
                token: this.token
            }).then(res => {
                if (res.data.status === 1) {
                    swalNotif.success(res.data.message);
                    vm.$router.push({ name: 'login' });
                } else {
                    swalNotif.info(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Failed to Change password!");
            }).finally(() => {
                this.loading = false;
                vm.globalLoader.show = false;
            });
        },
        tokenCheck() {
            const vm = this;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/auth/reset/password/check", {
                token: this.token
            }).then(res => {
                if (res.data.status === 1) {
                    vm.globalLoader.show = false;
                } else {
                    swalNotif.error(res.data.message);
                    vm.$router.push({ name: 'login' });
                }
            }).catch(err => {
                vm.$router.push({ name: 'login' });
            });
        }
    },
    mounted() {
        this.tokenCheck();
    }
}
</script>
