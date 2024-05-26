<script lang="ts">
import {defineComponent, defineExpose} from 'vue'
import ApexCharts from "vue3-apexcharts";

export default defineComponent({
    components: {
        ApexCharts,
    },
    props: {
        series: {
            type: Object,
            required: true
        },
        width: {
            default: 180
        },
        labels: {
            type: Array,
            size: 1,
            default: ['Avance']
        }
    },
    data() {
        return {
            id: 6,
            options: {
                colors: ['#6200EA'],
                chart: {
                    type: 'radialBar',
                },
                plotOptions: {
                    radialBar: {
                        hollow: {
                            margin: 0,
                            size: '70%',
                            background: '#fff',
                            image: undefined,
                            imageOffsetX: 0,
                            imageOffsetY: 0,
                            position: 'front',
                        },
                        track: {
                            background: '#EDE7F6',
                            strokeWidth: '70%',
                            margin: 0, // margin is in pixels
                        },

                        dataLabels: {
                            show: true,
                            name: {
                                offsetY: -5,
                                show: true,
                                color: '#888',
                                fontSize: '12px'
                            },
                            value: {
                                formatter: function (val) {
                                    return val + "%";
                                },
                                offsetY: 0,
                                color: '#111',
                                fontSize: '16px',
                                show: true,
                            }
                        }
                    }
                },
                fill: {
                    type: 'solid',
                    gradient: {
                        shade: 'dark',
                        type: 'horizontal',
                        shadeIntensity: 0.5,
                        //gradientToColors: ['#680bf8'],
                        inverseColors: true,
                        opacityFrom: 1,
                        opacityTo: 1,
                        stops: [0, 100]
                    }
                },
                stroke: {
                    lineCap: 'round'
                },
                labels: this.labels
            },
        }
    }
})
defineExpose({
    apexChart: ApexCharts
})
</script>

<template>

    <apexchart :series="this.series" type="radialBar" :width="this.width" :options="this.options"></apexchart>

</template>
