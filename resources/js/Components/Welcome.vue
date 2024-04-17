<script lang="ts">
import RadialProgress, {StrokeLinecap} from "vue3-radial-progress";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ApexCharts from "vue3-apexcharts";
import {usePage} from "@inertiajs/vue3";
import type {PropType} from 'vue'
import {toRefs} from "vue";
import {ApexOptions} from "apexcharts";


interface Chart extends ApexCharts {
    series: Array<any>,
    options?: ApexOptions,
}

export default {
    components: {
        PrimaryButton,
        RadialProgress,
        ApexCharts
    },
    props: {
        x_chart_packages_dgo: ApexCharts as PropType<Chart>,
        x_chart_packets_received_dgo: ApexCharts as PropType<Chart>,
        x_chart_collated_packets_dgo: ApexCharts as PropType<Chart>,
        x_chart_aec_registration_dgo: ApexCharts as PropType<Chart>,
    },
    setup(props) {
        return props
    },
    data() {
        return {
            completedSteps: 2,
            totalSteps: 6,
            diameter: 300,
            strokeWidth: 10,
            innerStrokeWidth: 20,
            strokeLinecap: "round" as StrokeLinecap,
            startColor: "#0071ff",
            stopColor: "#ea00f6",
            innerStrokeColor: '#ECEFF1',
            timingFunc: "linear",
            isClockwise: true,
            animateSpeed: 1000,
            // GRAFICA AVANCE DE PAQUETES
            chart_packages_dgo: {
                series: [44, 55, 13, 33],
                options: {
                    labels: ['Apple', 'Mango', 'Orange', 'Watermelon'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                }
                            },
                            expandOnClick: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                },
            },
            // GRAFICA PAQUETES RECIBIDOS
            chart_packets_received_dgo: {
                series: usePage().props.x_chart_packets_received_dgo.series,
                options: {
                    title: {
                        text: "Paquetes Recibidos",
                        align: 'left',
                        margin: 10,
                        offsetX: 0,
                        offsetY: 0,
                        floating: true,
                        style: {
                            fontSize: '14px',
                            fontWeight: 'bold',
                            fontFamily: undefined,
                            color: '#263238'
                        },
                    },
                    labels: ['Pendiente', 'Recibidos'],
                    plotOptions: {
                        pie: {
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                }
                            },
                            expandOnClick: false
                        }
                    },
                    dataLabels: {
                        enabled: false
                    },
                },
            },
        }
    },
}


const toFixed = (n, fixed) => ~~(Math.pow(10, fixed) * n) / Math.pow(10, fixed);
</script>

<template>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <!--            <ApplicationLogo class="block h-12 w-auto"/>-->

            <h1 class="mt-8 text-3xl font-medium text-gray-900">
                Durango.
            </h1>

            <p class="mt-2 text-gray-500 leading-relaxed">
                Estadísticas de paquetes recibidos y paquetes enviados en la ciudad de Durango, comprendida por los
                Distritos Locales I al VI.
            </p>
            <div>

            </div>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <!--                <apexchart :width="props.chart_packages_dgo.width" :height="props.chart_packages_dgo.height"-->
                <!--                           :type="props.chart_packages_dgo.type" :options="props.chart_packages_dgo.options"-->
                <!--                           :series="props.chart_packages_dgo.series"></apexchart>-->
                <!--                <p class="mt-2 text-sm">-->
                <!--                    {{ props.chart_packages_dgo.series[0] }} de-->
                <!--                    {{ props.chart_packages_dgo.series[0] + props.chart_packages_dgo.series[1] }}-->
                <!--                    Paquetes entregados, para un avance del {{-->
                <!--                        toFixed(((props.chart_packages_dgo.series[0]) / (props.chart_packages_dgo.series[0] + props.chart_packages_dgo.series[1]) * 100), 4)-->
                <!--                    }}%-->
                <!--                </p>-->

                <div>
                    <apexchart width="500" type="donut" :options="chart_packages_dgo.options"
                               :series="chart_packages_dgo.series"></apexchart>

                    <apexchart width="500" type="donut" :options="chart_packets_received_dgo.options"
                               :series="chart_packets_received_dgo.series"></apexchart>
                </div>
            </div>

            <div>
                <!--                <apexchart :width="props.chart_packets_received_dgo.width"-->
                <!--                           :height="props.chart_packets_received_dgo.height"-->
                <!--                           :type="props.chart_packets_received_dgo.type"-->
                <!--                           :options="props.chart_packets_received_dgo.options"-->
                <!--                           :series="props.chart_packets_received_dgo.series"></apexchart>-->

                <!--                <p class="mt-2 text-sm">{{ props.chart_packets_received_dgo.series[1] }} de-->
                <!--                    {{ (props.chart_packets_received_dgo.series[0] + props.chart_packets_received_dgo.series[1]) }}-->
                <!--                    Paquetes recibidos, para un avande del {{-->
                <!--                        toFixed(((props.chart_packets_received_dgo.series[1]) / (props.chart_packets_received_dgo.series[0] + props.chart_packets_received_dgo.series[1]) * 100), 4)-->
                <!--                    }}%</p>-->
            </div>

            <div>
                <!--                <apexchart :width="props.chart_aec_registration_dgo.width"-->
                <!--                           :height="props.chart_aec_registration_dgo.height"-->
                <!--                           :type="props.chart_aec_registration_dgo.type"-->
                <!--                           :options="props.chart_aec_registration_dgo.options"-->
                <!--                           :series="props.chart_aec_registration_dgo.series">-->
                <!--                </apexchart>-->
            </div>

            <div class="flex justify-center items-center">
                <RadialProgress
                    :diameter="diameter"
                    :total-steps="totalSteps"
                    :completed-steps="completedSteps"
                    :animate-speed="animateSpeed"
                    :stroke-width="strokeWidth"
                    :inner-stroke-width="innerStrokeWidth"
                    :stroke-linecap="strokeLinecap"
                    :start-color="startColor"
                    :stop-color="stopColor"
                    :inner-stroke-color="innerStrokeColor"
                    :timing-func="timingFunc"
                    :is-clockwise="isClockwise"
                >
                    {{ completedSteps }}/{{ totalSteps }}
                    <p class="text-sm">Avance</p>
                </RadialProgress>

                <PrimaryButton class="mt-4" :disabled="completedSteps >= totalSteps"
                               @click.prevent="completedSteps++">Actualizar
                </PrimaryButton>

            </div>
        </div>

        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <h1 class="mt-8 text-2xl font-medium text-gray-900">
                Etapa de Cómputos.
            </h1>

            <div>


            </div>
        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">
            <div>
                <!--                <apexchart :width="props.chart_packages_dgo.width" :height="props.chart_packages_dgo.height"-->
                <!--                           :type="props.chart_packages_dgo.type" :options="props.chart_packages_dgo.options"-->
                <!--                           :series="props.chart_packages_dgo.series"></apexchart>-->
                <!--                <p class="mt-2 text-sm">-->
                <!--                    {{ props.chart_packages_dgo.series[0] }} de-->
                <!--                    {{ props.chart_packages_dgo.series[0] + props.chart_packages_dgo.series[1] }}-->
                <!--                    Paquetes entregados, para un avance del {{-->
                <!--                        toFixed(((props.chart_packages_dgo.series[0]) / (props.chart_packages_dgo.series[0] + props.chart_packages_dgo.series[1]) * 100), 4)-->
                <!--                    }}%-->
                <!--                </p>-->
            </div>

            <div>
                <!--                <apexchart :width="props.chart_packets_received_dgo.width"-->
                <!--                           :height="props.chart_packets_received_dgo.height"-->
                <!--                           :type="props.chart_packets_received_dgo.type"-->
                <!--                           :options="props.chart_packets_received_dgo.options"-->
                <!--                           :series="props.chart_packets_received_dgo.series"></apexchart>-->

                <!--                <p class="mt-2 text-sm">{{ props.chart_packets_received_dgo.series[1] }} de-->
                <!--                    {{ (props.chart_packets_received_dgo.series[0] + props.chart_packets_received_dgo.series[1]) }}-->
                <!--                    Paquetes recibidos, para un avande del {{-->
                <!--                        toFixed(((props.chart_packets_received_dgo.series[1]) / (props.chart_packets_received_dgo.series[0] + props.chart_packets_received_dgo.series[1]) * 100), 4)-->
                <!--                    }}%</p>-->
            </div>

            <div>
                <!--                <apexchart :width="props.chart_packets_received_dgo.width"-->
                <!--                           :height="props.chart_packets_received_dgo.height"-->
                <!--                           :type="props.chart_packets_received_dgo.type"-->
                <!--                           :options="props.chart_packets_received_dgo.options"-->
                <!--                           :series="props.chart_packets_received_dgo.series">-->
                <!--                </apexchart>-->
            </div>

            <div>

            </div>
        </div>
    </div>
</template>
