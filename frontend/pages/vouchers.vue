<script setup>
definePageMeta({
    middleware: ['auth']
});
</script>

<template>
    <NuxtLayout name="portal-layout">
        <div class="mb-3">
            <button type="button" class="btn btn-success" v-if="permissions.includes('create-vouchers')"
                @click="storeVoucher">Generate New Voucher</button>
        </div>
        <ul class="list-group">
            <li class="list-group-item d-flex justify-content-between" v-if="vouchers" v-for="voucher in vouchers.data">
                <label>{{ voucher.code }}</label>
                <button type="button" class="btn btn-sm btn-danger" v-if="permissions.includes('delete-vouchers')"
                    @click="deleteVoucher(voucher.id)">Delete</button>
            </li>
        </ul>
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li v-bind:class="{ 'disabled': page.active}" class="page-item" v-if="pagination"
                    v-for="page in pagination.meta.links">
                    <a href="#" class="page-link" @click='getVouchers(page.url)' v-html="page.label">
                    </a>
                </li>
            </ul>
        </nav>
    </NuxtLayout>
</template>

<script>
import axios from 'axios'
export default {
    data() {
        return {
            permissions: useCookie('permissionsCookie').value,
            runtimeConfig: useRuntimeConfig(),
            vouchers: null,
            pagination: null
        };
    },
    methods: {
        getVouchers(page = this.runtimeConfig.public.baseUrl + `/api/vouchers`) {
            let $this = this;
            axios.get(
                page,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.vouchers = value.data
                $this.pagination = value.data
            });
        },
        storeVoucher() {
            let $this = this;
            axios.post(
                this.runtimeConfig.public.baseUrl + '/api/vouchers',
                [],
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getVouchers()
            }).catch(function(value){
                alert(value.response.data.message)
            });
        },
        deleteVoucher(id) {
            let $this = this;
            axios.delete(
                this.runtimeConfig.public.baseUrl + '/api/vouchers/' + id,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getVouchers()
            });
        }
    },
    mounted() {
        this.getVouchers();
    }
}
</script>

