<script setup>
definePageMeta({
    middleware: ['auth']
});
</script>

<template>
    <div class="container ">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div class="d-flex justify-content-center">
                                <h3 for="">Voucher Portal</h3>
                            </div>
                            <div class="mt-4">
                                <label for="">E-mail</label>
                                <input type="email" class="form-control" v-model="overview.email">
                                <span v-for="item in error.email" class="text-danger">{{ item }}</span>
                            </div>
                            <div class="mt-2">
                                <label for="">Password</label>
                                <input type="password" class="form-control" v-model="overview.password">
                                <span v-for="item in error.password" class="text-danger">{{ item }}</span>
                            </div>
                            <div class="mt-4">
                                <span v-if="error.message" class="text-danger">{{ error.message }}</span>
                                <button class="btn btn-primary w-100 fw-bold shadow-sm" @click="login">Login</button>
                            </div>
                            <div>
                                <label for="">Do not an account? <NuxtLink to="/registration">Register Here</NuxtLink></label>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios';

export default {
    data() {
        return {
            runtimeConfig: useRuntimeConfig(),
            overview: {
                email: null,
                password: null
            },
            error: {
                email: null,
                password: null,
                message: null,
            }
        };
    },
    methods: {
        login() {
            let $this = this;
            axios.post(this.runtimeConfig.public.baseUrl + '/api/login', this.overview)
                .then(function (value) {
                    const permissionsCookie = useCookie('permissionsCookie')
                    const roleCookie = useCookie('roleCookie')
                    const tokenCookie = useCookie('tokenCookie')
                    const bearerToken = useCookie('bearer_token')

                    permissionsCookie.value = value.data.permissions;
                    roleCookie.value = value.data.role;
                    tokenCookie.value = value.data.token;
                    bearerToken.value = value.data.token;

                    navigateTo('/portal')
                })
                .catch(function (value) {
                    if (value.response.status == 422) {
                        $this.error = value.response.data.errors
                    } else {
                        $this.error.message = value.response.data.message;
                    }
                });
        }
    },
    mounted() {

    }
}
</script>
