
<template>
    <div class="container ">
        <div class="row justify-content-center align-items-center vh-100">
            <div class="col-md-4">
                <div class="card shadow">
                    <div class="card-body">
                        <div class="d-flex flex-column">
                            <div>
                                <h3>Registration Form</h3>
                            </div>
                            <div class="mt-2">
                                <label for="">Fullname</label>
                                <input type="text" class="form-control" v-model="overview.name">
                                <label v-if="errors.name" v-for="error in errors.name" class="text-danger">{{ error
                                }}</label>
                            </div>
                            <div class="mt-2">
                                <label for="">E-mail</label>
                                <input type="email" class="form-control" v-model="overview.email">
                                <label v-if="errors.email" v-for="error in errors.email" class="text-danger">{{ error
                                }}</label>
                            </div>
                            <div class="mt-2">
                                <label for="">Password</label>
                                <input type="password" class="form-control" v-model="overview.password">
                                <label v-if="errors.password" v-for="error in errors.password" class="text-danger">{{ error
                                }}</label>
                            </div>
                            <div class="mt-2">
                                <label for="">Confirm Password</label>
                                <input type="password" class="form-control" v-model="overview.password_confirmation">
                                <label v-if="errors.password_confirmation" v-for="error in errors.password_confirmation"
                                    class="text-danger">{{ error }}</label>
                            </div>
                            <div class="mt-2">
                                <button type="button" class="btn btn-primary w-100" @click="register">Register</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            runtimeConfig: useRuntimeConfig(),
            overview: {
                'name': null,
                'email': null,
                'password': null,
                'password_confirmation': null
            },
            errors: {
                'name': null,
                'email': null,
                'password': null,
                'password_confirmation': null
            }
        };
    },
    methods: {
        register() {
            let $this = this;
            axios.post(this.runtimeConfig.public.baseUrl + '/api/register', $this.overview)
                .then(function (value) {
                    alert('You have been registered!')
                    navigateTo('/')
                })
                .catch(function (value) {
                    $this.errors = value.response.data.errors
                });
        }
    }
}
</script>

