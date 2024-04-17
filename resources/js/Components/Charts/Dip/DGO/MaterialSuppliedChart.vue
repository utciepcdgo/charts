<script setup>
import {usePage} from "@inertiajs/vue3";

const props = {
    chart: usePage().props.chart
}
</script>

<template>

    <apexchart :width="props.chart.width" :height="props.chart.height" :type="props.chart.type"
               :options="props.chart.options"
               :series="props.chart.series"></apexchart>

</template>

<style scoped>

</style>
