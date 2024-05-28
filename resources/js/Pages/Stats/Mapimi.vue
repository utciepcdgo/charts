<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
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
    <AppLayout title="Mapimí">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Mapimí
            </h2>
        </template>

        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
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
        </div>
    </AppLayout>

</template>

<style scoped>

</style>
