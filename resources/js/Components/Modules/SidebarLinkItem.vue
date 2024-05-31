<script setup>
import NavLink from "@/Components/NavLink.vue";

const props = defineProps({
    to: String,
    districtName: String,
    icon: {
        type: String,
        default: 'home'
    }
});
</script>

<template>
    <NavLink :href="route(props.to)" :active="route().current(props.to)" class="py-3">
        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
             fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
             stroke-linejoin="round">
            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
            <path d="M12 3v5m4 4h5"/>
            <path d="M12 12m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0"/>
            <path d="M12 12m-9 0a9 9 0 1 0 18 0a9 9 0 1 0 -18 0"/>
        </svg>
        <span class="ms-3">{{ props.districtName }}</span>
    </NavLink>
</template>

<style scoped>

</style>
