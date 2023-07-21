<script setup>
definePageMeta({
    middleware: ['auth']
});
</script>

<template>
    <NuxtLayout name="portal-layout">
        <div class="d-flex justify-content-center">
            <h1>Welcome Back!</h1>
        </div>
    </NuxtLayout>
</template>

<script>
export default {

}
</script>

