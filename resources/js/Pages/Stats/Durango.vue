<script setup lang="ts">
import AppLayout from '@/Layouts/AppLayout.vue';
</script>

<script lang="ts">
import CircleChart from "@/Components/Charts/CircleChart.vue";

interface Chart extends Object {
    series: {
        received: number,
        expected: number,
        progress: number
    }
}

export default {
    components: {
        CircleChart
    },
    props: {
        materialSupplied: {
            type: Object as () => Chart,
        },
        packetsReceived: {
            type: Object as () => Chart,
            coerce: (value: string) => {
                return JSON.parse(value)
            }
        },
        aecRegistration: {
            type: Object as () => Chart,
        },
        collatedPackets: {
            type: Object as () => Chart,
        },
        recountPackets: {
            type: Object as () => Chart,
        },
    },
    computed: {
        packetsReceived: function () {
            return JSON.parse(this.packetsReceived)
        }
    },
    mounted() {
        console.log(this.packetsReceived)
    }
}

</script>

<template>
    <AppLayout title="Durango">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Durango
            </h2>
        </template>

        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-3 gap-4 mb-4">
                <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <circle-chart :series="[packetsReceived.series.progress]" :key="1"></circle-chart>
                    <div class="shrink w-full ml-8">
                        <p class="font-bold text-gray-500">Entrega de Material</p>
                        <p class="font-medium text-gray-400">{{ packetsReceived.series.received }} de
                            {{ packetsReceived.series.expected }}</p>
                    </div>
                </div>
                <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <circle-chart :series="[packetsReceived.series.progress]" :key="2"></circle-chart>
                    <div class="shrink w-full ml-8">
                        <p class="font-bold text-gray-500">Entrega de Material</p>
                        <p class="font-medium text-gray-400">{{ packetsReceived.series.received }} de
                            {{ packetsReceived.series.expected }}</p>
                    </div>
                </div>
                <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <circle-chart :series="[packetsReceived.series.progress]" :key="3"></circle-chart>
                    <div class="shrink w-full ml-8">
                        <p class="font-bold text-gray-500">Entrega de Material</p>
                        <p class="font-medium text-gray-400">{{ packetsReceived.series.received }} de
                            {{ packetsReceived.series.expected }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

<style scoped>

</style>
