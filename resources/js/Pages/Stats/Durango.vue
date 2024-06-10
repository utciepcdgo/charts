<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import GaugeChart from "@/Components/Charts/GaugeChart.vue";
import Files from '@/Functions/Files.js';
import {FwbProgress} from 'flowbite-vue'
import {Link} from '@inertiajs/vue3';
</script>

<script lang="ts">
import CircleChart from "@/Components/Charts/CircleChart.vue";
import type {PropType} from 'vue'
import ApexCharts from "vue3-apexcharts";

interface Chart {
    series: {
        received: number,
        expected: number,
        progress: number,
        by_district: {
            district: number,
            amount: number,
            progress: number,
        }
    }
}

export default {
    components: {
        CircleChart: ApexCharts
    },
    props: {
        materialSupplied: {
            type: Object as PropType<Chart>,
            required: true,
        },
        packetsReceived: {
            type: Object as PropType<Chart>,
            required: true
        },
        aecRegistration: {
            type: Object as PropType<Chart>,
            required: true
        },
        collatedPackets: {
            type: Object as PropType<Chart>,
            required: true
        },
        recountPackets: {
            type: Object as PropType<Chart>,
            required: true
        },
    },
    mounted() {
        this.updateMaterialSupplied();
    },
    methods: {
        async updateMaterialSupplied() {
            const data = await fetch('/api/material-supplied').then((res) => res.json());
        }
    },
}

const toFixed = (n, fixed) => ~~(Math.pow(10, fixed) * n) / Math.pow(10, fixed);

</script>

<template>
    <AppLayout title="Durango">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Durango
            </h2>
            <!--            <button @click="updateMaterialSupplied">Actualizar</button>-->
        </template>

        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h5 class="my-5 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Jornada Electoral.</h5>
            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-3 gap-4 mb-4">
                <div
                    class="flex flex-col w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <div class="shrink w-full md:ml-3 grid grid-cols-1 justify-center text-center lg:text-left">
                        <p class="font-bold text-gray-500">Entrega de Documentación y Material Electoral a CAEL/SEL</p>
                        <p class="font-medium text-gray-400">{{ materialSupplied.series.received }} de
                            {{ materialSupplied.series.expected }}</p>
                    </div>
                    <CircleChart ref="msc" :series="[materialSupplied.series.progress]" :width="300"
                                 :key="2"></CircleChart>
                </div>
                <div
                    class="flex flex-col w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <div class="shrink w-full md:ml-3 grid grid-cols-1 justify-center text-center lg:text-left">
                        <p class="font-bold text-gray-500">Paquetes recibidos en CME (Jornada Electoral)</p>
                        <p class="font-medium text-gray-400">{{ packetsReceived.series.received }} de
                            {{ packetsReceived.series.expected }}</p>
                    </div>
                    <CircleChart :series="[packetsReceived.series.progress]" :width="300" :key="1"></CircleChart>
                </div>
                <div
                    class="flex flex-col w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <div class="shrink w-full md:ml-3 grid grid-cols-1 justify-center text-center lg:text-left">
                        <p class="font-bold text-gray-500">AEC registradas</p>
                        <p class="font-medium text-gray-400">{{ aecRegistration.series.received }} de
                            {{ aecRegistration.series.expected }}</p>
                    </div>
                    <CircleChart :series="[aecRegistration.series.progress]" :width="300" :key="3"></CircleChart>
                </div>
            </div>
            <!--SECCION 2 - COMPUTOS ELECTORALES-->
            <h5 class="my-5 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Cómputos Electorales.</h5>

            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-2 justify-center gap-4 my-10">
                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-2 items-center gap-4 my-10">
                    <GaugeChart :title="'Recuento'"
                                :series="[recountPackets.series.expected - (recountPackets.series.received), recountPackets.series.received]"/>
                    <div class="w-full p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex flex-col justify-start">

                            <div class="block mb-6 relative" v-for="district in recountPackets.series.by_district">
                                <span class="absolute">Distrito {{ district.district }}:</span>
                                <fwb-progress :progress="toFixed((district.progress / district.amount * 100), 2)" size="lg" label-position="outside" label-progress color="purple"/>
                            </div>

                            <div class="flex space-x-3.5">
                                <img src="../../../images/icons/Files_23.svg" alt="Icono de archivo Excel" width="60">
                                <div class="flex flex-col">
                                    <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                        Listado de Casillas en Recuento
                                    </h5>
                                    <Link @click.prevent="Files.getCollatedRecountPacketsList(2)" href="#"
                                          class="inline-flex space-x-2 items-center text-sm font-medium text-center text-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-csv">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                            <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                            <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"/>
                                            <path d="M7 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0"/>
                                            <path d="M10 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75"/>
                                            <path d="M16 15l2 6l2 -6"/>
                                        </svg>
                                        <span>Descargar</span>
                                    </Link>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-2 items-center gap-4 my-10">
                    <GaugeChart :title="'Cotejo'"
                                :series="[collatedPackets.series.expected - (collatedPackets.series.received), collatedPackets.series.received]"/>
                    <div
                        class="w-full p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <img src="../../../images/icons/Files_23.svg" alt="Icono de archivo Excel"
                                 width="60">
                            <div class="flex flex-col">
                                <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                    Listado de Casillas en Cotejo
                                </h5>
                                <Link @click.prevent="Files.getCollatedRecountPacketsList(1)" href="#"
                                      class="inline-flex space-x-2 items-center text-sm font-medium text-center text-purple-700 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-file-type-csv">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                        <path d="M14 3v4a1 1 0 0 0 1 1h4"/>
                                        <path d="M5 12v-7a2 2 0 0 1 2 -2h7l5 5v4"/>
                                        <path d="M7 16.5a1.5 1.5 0 0 0 -3 0v3a1.5 1.5 0 0 0 3 0"/>
                                        <path d="M10 20.25c0 .414 .336 .75 .75 .75h1.25a1 1 0 0 0 1 -1v-1a1 1 0 0 0 -1 -1h-1a1 1 0 0 1 -1 -1v-1a1 1 0 0 1 1 -1h1.25a.75 .75 0 0 1 .75 .75"/>
                                        <path d="M16 15l2 6l2 -6"/>
                                    </svg>
                                    <span>Descargar</span>
                                </Link>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

<style scoped>

</style>
