<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
import GaugeChart from "@/Components/Charts/GaugeChart.vue";
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
}

</script>

<template>
    <AppLayout title="Pueblo Nuevo">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Pueblo Nuevo
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
                    <circle-chart ref="msc" :series="[materialSupplied.series.progress]" :width="300"
                                  :key="2"></circle-chart>
                </div>
                <div
                    class="flex flex-col w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <div class="shrink w-full md:ml-3 grid grid-cols-1 justify-center text-center lg:text-left">
                        <p class="font-bold text-gray-500">Paquetes recibidos en CME (Jornada Electoral)</p>
                        <p class="font-medium text-gray-400">{{ packetsReceived.series.received }} de
                            {{ packetsReceived.series.expected }}</p>
                    </div>
                    <circle-chart :series="[packetsReceived.series.progress]" :width="300" :key="1"></circle-chart>
                </div>
                <div
                    class="flex flex-col w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <div class="shrink w-full md:ml-3 grid grid-cols-1 justify-center text-center lg:text-left">
                        <p class="font-bold text-gray-500">AEC registradas</p>
                        <p class="font-medium text-gray-400">{{ aecRegistration.series.received }} de
                            {{ aecRegistration.series.expected }}</p>
                    </div>
                    <circle-chart :series="[aecRegistration.series.progress]" :width="300" :key="3"></circle-chart>
                </div>
            </div>
            <h5 class="my-5 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Cómputos Electorales.</h5>
            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-2 justify-center gap-4 my-10">
                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-2 items-center gap-4 my-10">
                    <GaugeChart :title="'Recuento'" :series="[recountPackets.series.expected - (recountPackets.series.received), recountPackets.series.received]"/>
                    <div
                        class="w-full p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <img src="../../../images/icons/Files_23.svg" alt="Icono de archivo Excel"
                                 width="60">
                            <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Bitácora de recepción de Paquetes Electorales</h5>
                        </div>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Registros
                            correspondientes a la recepción de los paquetes electorales que contienen la
                            documentación electoral utilizada en las casillas.</p>
                        <div class="flex justify-end">
                            <Link href=""
                                  class="inline-flex space-x-2 items-center px-3 py-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12v5"/>
                                    <path d="M13 16h-7a2 2 0 0 0 -2 2"/>
                                    <path d="M15 19l3 3l3 -3"/>
                                    <path d="M18 22v-9"/>
                                </svg>
                                <span>Descargar</span>
                            </Link>
                        </div>
                    </div>
                </div>
                <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-2 items-center gap-4 my-10">
                    <GaugeChart :title="'Cotejo'" :series="[collatedPackets.series.expected - (collatedPackets.series.received), collatedPackets.series.received]"/>
                    <div
                        class="w-full p-6 dark:bg-gray-800 dark:border-gray-700">
                        <div class="flex items-center space-x-4">
                            <img src="../../../images/icons/Files_23.svg" alt="Icono de archivo Excel"
                                 width="60">
                            <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                Bitácora de recepción de Paquetes Electorales</h5>
                        </div>
                        <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Registros
                            correspondientes a la recepción de los paquetes electorales que contienen la
                            documentación electoral utilizada en las casillas.</p>
                        <div class="flex justify-end">
                            <Link href=""
                                  class="inline-flex space-x-2 items-center px-3 py-2 text-sm font-medium text-center text-white bg-purple-600 rounded-lg hover:bg-purple-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                     viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                     stroke-linecap="round" stroke-linejoin="round">
                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"/>
                                    <path d="M12 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12v5"/>
                                    <path d="M13 16h-7a2 2 0 0 0 -2 2"/>
                                    <path d="M15 19l3 3l3 -3"/>
                                    <path d="M18 22v-9"/>
                                </svg>
                                <span>Descargar</span>
                            </Link>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

<style scoped>

</style>
