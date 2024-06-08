<script setup>
import SidebarLinkItem from "@/Components/Modules/SidebarLinkItem.vue";
import {municipalitiesList} from '@/Functions/Districts.js';

</script>

<template>
    <ul class="space-y-4 font-medium" v-for="municipality in municipalitiesList" :key="municipality.id">
        <li class="mb-2" @click.stop>
            <SidebarLinkItem :to="municipality.route" :districtName="municipality.name" :key="municipality.id"/>
        </li>
    </ul>
</template>

<style scoped>

</style>
