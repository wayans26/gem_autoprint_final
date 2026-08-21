<template>
    <div class="card card-authentication1 auth-card border-0">
        <div class="card-body">
            <router-link :to="{ name: 'login' }" class="btn btn-outline-secondary btn-sm auth-back">
                <i class="fa fa-arrow-left" aria-hidden="true"></i> Back to sign in
            </router-link>
            <div class="card-content">
                <div class="auth-card__brand text-center">
                    <img :src="'/logontt.png'" class="auth-logo" alt="Gem Indonesia">
                </div>
                <div class="auth-card__heading text-center">
                    <span class="auth-card__eyebrow">Account recovery</span>
                    <h2 id="forgot-password-title" class="card-title">Reset your password</h2>
                    <p>We will send password reset instructions to your registered email address.</p>
                </div>
                <Form @submit="forgot_password" :validation-schema="validate" aria-labelledby="forgot-password-title">
                    <div class="mb-4">
                        <label for="input-1" class="form-label">Email</label>
                        <div class="position-relative auth-input-wrap">
                            <i class="icon-envelope auth-input-icon" aria-hidden="true"></i>
                            <Field name="email" type="email" class="form-control input-shadow auth-control" id="input-1"
                                placeholder="Email Address" v-model="email" autocomplete="email" :disabled="loading">
                            </Field>
                        </div>
                        <ErrorMessage class="auth-error" name="email" />
                    </div>
                    <button type="submit" :disabled="loading" class="btn btn-primary auth-submit w-100"
                        :aria-busy="loading">
                        <i :class="{
                            'fa fa-spinner fa-spin': loading,
                            'fa fa-sign-in': !loading,
                        }" aria-hidden="true"></i>
                        <span>{{ loading ? 'Sending instructions...' : 'Reset Password' }}</span>
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
            email: '',
            loading: false,
            validate: yup.object({
                email: yup.string().email("Email is invalid").required("Email is required"),
            }),
        }
    },
    methods: {
        forgot_password() {
            const vm = this;
            this.loading = true;
            vm.globalLoader.show = true;
            axios.post("/api/v1/web/auth/reset/password", {
                email: this.email
            }).then(res => {
                if (res.data.status === 1) {
                    swalNotif.success(res.data.message);
                    vm.$router.push({ name: 'login' });
                } else {
                    swalNotif.info(res.data.message);
                }
            }).catch(err => {
                swalNotif.error("Failed to reset password!");
            }).finally(() => {
                this.loading = false;
                vm.globalLoader.show = false;
            });
        }
    },
    mounted() {
        this.globalLoader.show = false;
    }
}
</script>
