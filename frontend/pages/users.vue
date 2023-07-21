<script setup>
definePageMeta({
    middleware: ['auth']
});
</script>
<template>
    <NuxtLayout name="portal-layout">
        <div class="container" v-if="permissions.includes('export-all-vouchers')">
            <button type="button" class="btn btn-info" @click="exportVoucher">Export All</button>
        </div>

        <div class="list-group p-4">
            <a v-for="user in users" href="#" class="list-group-item list-group-item-action"
                @click="loadVoucher(user.vouchers)" data-bs-toggle="modal" data-bs-target="#voucherModal">
                <div class="d-flex w-100 justify-content-between">
                    <h5 class="mb-1">{{ user.name }}</h5>
                </div>
                <p class="mb-1">{{ user.email }}</p>
            </a>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="voucherModal" tabindex="-1" aria-labelledby="voucherModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Vouchers</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <ul class="list-group">
                            <li class="list-group-item" v-for="item in vouchers">{{ item.code }}</li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
    </NuxtLayout>
</template>

<script>
import axios from "axios";
export default {
    data() {
        return {
            permissions: useCookie('permissionsCookie').value,
            runtimeConfig: useRuntimeConfig(),
            users: null,
            vouchers: null
        }
    },
    methods: {
        loadVoucher(vouchers) {
            this.vouchers = vouchers;
        },
        getUsers() {
            let $this = this;
            axios.get(
                this.runtimeConfig.public.baseUrl + '/api/users',
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.users = value.data
            })
        },
        exportVoucher() {
            let $this = this;
            axios.get(
                this.runtimeConfig.public.baseUrl + '/api/export/all/users',
                {
                    headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` },
                    responseType: 'blob'
                }
            ).then(function (value) {
                const href = URL.createObjectURL(value.data);

                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'user-voucher.csv');
                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        }
    },
    mounted() {
        this.getUsers()
    }
}
</script>
