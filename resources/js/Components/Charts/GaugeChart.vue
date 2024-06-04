<script lang="ts">
import {defineComponent, defineExpose} from 'vue'
import ApexCharts from "vue3-apexcharts";

export default defineComponent({
    components: {
        ApexCharts,
    },
    props: {
        series: Object,
        title: {
            type: String,
            default: "",
            required: true
        },
    },
    data() {
        return {
            id: 7,
            options: {
                stroke: {
                    show: false
                },
                legend: {
                    show: false
                },
                colors: ['#CFD8DC', '#680bf8'],
                title: {
                    text: this.title,
                    align: 'left',
                    margin: 10,
                },
                dataLabels: { // Etiquetas de datos dentro de la gráfica
                    enabled: false,
                },
                labels: ['Pendiente', 'Cotejados'],
                tooltip: {
                    enabled: false
                },
                plotOptions: {
                    pie: {
                        startAngle: -90,
                        endAngle: 90,
                        customScale: 0.9,
                        donut: {
                            size: '80%',
                            labels: {
                                show: true,
                                name: {
                                    show: true,
                                    fontSize: '22px',
                                    fontFamily: 'Helvetica, Arial, sans-serif',
                                    fontWeight: 600,
                                    color: '#37474F',
                                    offsetY: -10,
                                },
                                total: {
                                    label: 'Realizado/Pendientes',
                                    show: true,
                                    showAlways: false,
                                    color: '#37474F',
                                    formatter: function (w) {
                                        return w.globals.seriesTotals.reduce((a, b) => {
                                            return (b + " de " + (a - b))
                                        })
                                    }
                                }
                            }
                        },
                        expandOnClick: false
                    }
                },
            },
        }
    },
})
</script>

<template>
    <apexchart width="400" type="donut" :title="this.title" :options="this.options" :series="this.series" :key="this.id"></apexchart>
</template>
