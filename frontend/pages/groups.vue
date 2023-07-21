<script setup>
definePageMeta({
    middleware: ['auth']
});
</script>
<template>
    <NuxtLayout name="portal-layout">
        <div class="p-4">
            <div class="d-flex justify-content-end mb-3">
                <button type="button" class="btn btn-success" v-if="permissions.includes('create-groups')"
                    data-bs-toggle="modal" data-bs-target="#groupFormModal" @click="resetOverview">
                    Add Group
                </button>
            </div>

            <div class="list-group">
                <a v-for="group in groups" href="#"
                    class="list-group-item list-group-item-action d-flex justify-content-between">
                    <div>
                        <div class="d-flex w-100 justify-content-between">
                            <h5 class="mb-1">{{ group.group_name }}</h5>
                        </div>
                        <p class="mb-1">Admin: {{ group.owner }} / {{ group.owner_email }}</p>
                    </div>

                    <div>
                        <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                            <button type="button" class="btn btn-info" v-if="permissions.includes('export-vouchers')"
                                @click="exportVoucher(group.members)">
                                Export
                            </button>
                            <button type="button" class="btn btn-warning" v-if="permissions.includes('assign-user-groups')"
                                @click="loadMember(group.members, group.group_id)" data-bs-toggle="modal"
                                data-bs-target="#memberModal">
                                Assign
                            </button>
                            <button type="button" class="btn btn-primary" v-if="permissions.includes('update-groups')"
                                data-bs-toggle="modal" data-bs-target="#groupFormModal" @click="overview = group">
                                Update
                            </button>
                            <button type="button" class="btn btn-danger" v-if="permissions.includes('delete-groups')"
                                @click="deleteGroup(group.group_id)">Delete</button>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="memberModal" tabindex="-1" aria-labelledby="memberModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Members</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div v-if="permissions.includes('assign-user-groups')">
                            <label for="">Users</label>
                            <select class="form-select" type="text" v-model="overview.user_id">
                                <option v-for="user in userList" v-bind:value="user.user_id">{{ user.name }}</option>
                            </select>
                        </div>
                        <ul class="list-group mt-2">
                            <li class="list-group-item d-flex justify-content-between" v-for="item in members">
                                <div>
                                    <label for="">{{ item.name }}</label>
                                    <div>
                                        <ol class="list-group list-group-numbered">
                                            <li v-for="voucher in item.vouchers" class="list-group-item">
                                                {{ voucher.code }}
                                            </li>
                                        </ol>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-sm btn-danger"
                                    v-if="permissions.includes('remove-user-groups')" @click="removeMember(item.member_id)">
                                    remove
                                </button>
                            </li>
                        </ul>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary" @click="assignUser">
                            Assign User</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="groupFormModal" tabindex="-1" aria-labelledby="groupFormModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Group Form</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div>
                                <label for="">Group Name</label>
                                <input class="form-control" type="text" v-model="overview.group_name" />
                            </div>
                            <div class="mt-2">
                                <label for="">Group Admin</label>
                                <select class="form-select" type="text" v-model="overview.owner_id">
                                    <option v-for="user in groupAdmins" v-bind:value="user.user_id">{{ user.name }}</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-success" data-bs-dismiss="modal" @click="store">Save</button>
                    </div>
                </div>
            </div>
        </div>
    </NuxtLayout>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            permissions: useCookie('permissionsCookie').value,
            runtimeConfig: useRuntimeConfig(),
            groups: null,
            members: null,
            groupAdmins: null,
            userList: null,
            overview: {
                user_id: null,
                group_name: null,
                group_id: null,
            }
        };
    },
    methods: {
        resetOverview() {
            this.overview = {
                user_id: null,
                group_name: null,
                group_id: null,
            };
        },
        loadMember(members, id) {
            this.members = members;
            this.overview.group_id = id;
        },
        store() {
            let $this = this;
            axios.post(
                this.runtimeConfig.public.baseUrl + '/api/groups',
                $this.overview,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getGroups()
                $this.getGroupAdmins()
                $this.getUserList()
            });
        },
        getGroups() {
            let $this = this;
            axios.get(
                this.runtimeConfig.public.baseUrl + '/api/groups',
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.groups = value.data
            });
        },
        getGroupAdmins() {
            let $this = this;
            axios.get(
                this.runtimeConfig.public.baseUrl + '/api/group-admins',
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.groupAdmins = value.data
            });
        },
        getUserList() {
            let $this = this;
            axios.get(
                this.runtimeConfig.public.baseUrl + '/api/user-list',
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.userList = value.data
            });
        },
        removeUser() {
            let $this = this;
            axios.post(
                this.runtimeConfig.public.baseUrl + '/api/remove/member',
                $this.overview,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getGroups()
                $this.getGroupAdmins()
                $this.getUserList()
            });
        },
        deleteGroup(id) {
            let $this = this;
            axios.delete(
                this.runtimeConfig.public.baseUrl + '/api/groups/' + id,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getGroups()
                $this.getGroupAdmins()
                $this.getUserList()
            });
        },
        removeMember(member_id, group_id) {
            let $this = this;
            axios.delete(
                this.runtimeConfig.public.baseUrl + '/api/remove/member/' + member_id,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getGroups()
                $this.getGroupAdmins()
                $this.getUserList()
                $this.members = value.data.data
            });
        },
        assignUser() {
            let $this = this;
            axios.post(
                this.runtimeConfig.public.baseUrl + '/api/assign/member',
                $this.overview,
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.getGroups()
                $this.getGroupAdmins()
                $this.getUserList()
                $this.members = value.data.data
            });
        },
        exportVoucher(members) {
            let $this = this;
            axios.post(
                this.runtimeConfig.public.baseUrl + '/api/export/user',
                members,
                {
                    headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` },
                    responseType: 'blob'
                }
            ).then(function (value) {
                const href = URL.createObjectURL(value.data);

                const link = document.createElement('a');
                link.href = href;
                link.setAttribute('download', 'specific-user-voucher.csv');
                document.body.appendChild(link);
                link.click();

                document.body.removeChild(link);
                URL.revokeObjectURL(href);
            })
        }
    },
    mounted() {
        console.log(this.permissions)
        this.getGroups()
        this.getGroupAdmins()
        this.getUserList()
    }
}
</script>
