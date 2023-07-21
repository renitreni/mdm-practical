<template>
    <nav class="navbar navbar-expand-lg navbar-light bg-light">
        <div class="container-fluid container">
            <a class="navbar-brand" href="/">PORTAL</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent"
                aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <!-- User -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button"
                            data-bs-toggle="dropdown" aria-expanded="false">
                            Menu
                        </a>
                        <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                            <li>
                                <NuxtLink class="dropdown-item" to="/vouchers" v-if="permissions.includes('view-vouchers')">
                                    Vouchers</NuxtLink>
                            </li>
                            <li>
                                <NuxtLink class="dropdown-item" to="/my-group"
                                    v-if="permissions.includes('view-my-groups')">My Group</NuxtLink>
                            </li>
                            <li>
                                <NuxtLink class="dropdown-item" to="/users" v-if="permissions.includes('view-users')">Users
                                </NuxtLink>
                            </li>
                            <li>
                                <NuxtLink class="dropdown-item" to="/groups" v-if="permissions.includes('view-groups')">
                                    Groups</NuxtLink>
                            </li>
                        </ul>
                    </li>
                </ul>
                <form class="d-flex">
                    <button class="btn btn-outline-warning" type="button" @click="logout">Log Out</button>
                </form>
            </div>
        </div>
    </nav>
    <section>
        <div class="container">
            <div class="card shadow mt-5 p-2">
                <div class="card-body">
                    <slot />
                </div>
            </div>
        </div>
    </section>
</template>

<script>
export default {
    data() {
        return {
            role: useCookie('roleCookie').value,
            permissions: useCookie('permissionsCookie').value
        }
    },
    methods: {
        logout() {
            let token = useCookie('tokenCookie')
            token.value = undefined;
            return navigateTo('/')
        }
    }
}
</script>
