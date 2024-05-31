<script setup>

import NavLink from "@/Components/NavLink.vue";
import SidebarLinkItem from "@/Components/Modules/SidebarLinkItem.vue";

const municipalities = [
    {id: '5', name: 'Durango', route: 'stats.durango'},
    {id: '33', name: 'Santiago Papasquiaro', route: 'stats.santiago'},
    {id: '8', name: 'Guadalupe Victoria', route: 'stats.guadalupe'},
    {id: '13', name: 'Mapimí', route: 'stats.mapimi'},
    {id: '7', name: 'Gómez Palacio', route: 'stats.gomez'},
    {id: '12', name: 'Lerdo', route: 'stats.lerdo'},
    {id: '4', name: 'Cuencamé', route: 'stats.cuencame'},
    {id: '24', name: 'Pueblo Nuevo', route: 'stats.pueblonuevo'},

];

</script>

<template>
    <ul class="space-y-4 font-medium" v-for="municipality in municipalities" :key="municipality.id">
        <li class="mb-2">
            <SidebarLinkItem :to="municipality.route" :districtName="municipality.name" :key="municipality.id"/>
        </li>
    </ul>
</template>

<style scoped>

</style>
