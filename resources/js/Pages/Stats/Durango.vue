<script lang="ts">

import AppLayout from "@/Layouts/AppLayout.vue";

export default {

}


</script>

<template>
    <AppLayout title="Dashboard">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Durango
            </h2>
        </template>

        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="text-3xl font-medium text-gray-900 mb-6">
                Durango
            </h1>
            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-3 gap-4 mb-4">
                <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <apexchart ref="cpr" width="180" class="flex-none grow-0" type="radialBar"
                               :options="chart_test_dgo.options"
                               :series="chart_test_dgo.series" :key="chart_test_dgo.id"></apexchart>
                    <div class="shrink w-full ml-8">
                        <p class="font-bold text-gray-500">Entrega de Material</p>
                        <p class="font-medium text-gray-400">{{ c[1] }} de {{ c[0] }}</p>
                    </div>
                </div>
                <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <apexchart ref="cpr" width="180" class="flex-none grow-0" type="radialBar"
                               :options="chart_test_dgo.options"
                               :series="chart_test_dgo.series" :key="chart_test_dgo.id"></apexchart>
                    <div class="shrink w-full ml-8">
                        <p class="font-bold text-gray-500">Entrega de Material</p>
                        <p class="font-medium text-gray-400">{{ c[1] }} de {{ c[0] }}</p>
                    </div>
                </div>
                <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded shadow-lg">
                    <apexchart ref="cpr" width="180" class="flex-none grow-0" type="radialBar"
                               :options="chart_test_dgo.options"
                               :series="chart_test_dgo.series" :key="chart_test_dgo.id"></apexchart>
                    <div class="shrink w-full ml-8">
                        <p class="font-bold text-gray-500">Entrega de Material</p>
                        <p class="font-medium text-gray-400">{{ c[1] }} de {{ c[0] }}</p>
                    </div>
                </div>
            </div>
        </div>
    </AppLayout>

</template>

<style scoped>

</style>
