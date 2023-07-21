<script setup>
definePageMeta({
    middleware: ['auth']
});
</script>

<template>
    <NuxtLayout name="portal-layout">
        <h2 v-if="mygroup">My Group is <i>{{ mygroup.group.group_name }}</i></h2>
        <h2 v-else="mygroup">No Group Yet</h2>
    </NuxtLayout>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            permissions: useCookie('permissionsCookie').value,
            runtimeConfig: useRuntimeConfig(),
            mygroup: null
        };
    },
    methods: {
        getMyGroup() {
            let $this = this;
            axios.get(
                this.runtimeConfig.public.baseUrl + '/api/my-group',
                { headers: { Authorization: `Bearer ${useCookie('bearer_token').value}` } }
            ).then(function (value) {
                $this.mygroup = value.data
            });
        }
    },
    mounted() {
        this.getMyGroup();
    }
}
</script>

