<script lang="ts">
import RadialProgress, {StrokeLinecap} from "vue3-radial-progress";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import ApexCharts from "vue3-apexcharts";
import {usePage} from "@inertiajs/vue3";
import {PropType, toRef} from 'vue'
import {ApexOptions} from "apexcharts";


interface Chart extends ApexCharts {
    series: Array<any>,
    options?: ApexOptions,
}

export default {
    emits: ['update:chartDataRo'],
    components: {
        PrimaryButton,
        RadialProgress,
        ApexCharts
    },
    props: {
        x_chart_packages_dgo: ApexCharts as PropType<Chart>,
        x_chart_packets_received_dgo: ApexCharts as PropType<Chart>,
        x_chart_aec_registration_dgo: ApexCharts as PropType<Chart>,
        // ETAPA CÓMPUTOS
        x_chart_collated_packets_dgo: ApexCharts as PropType<Chart>,
        x_chart_recount_packets_dgo: ApexCharts as PropType<Chart>,
        chartData: Array<any>
    },
    setup(props, {emit}) {
        const chartDataRo = toRef(props, 'chartData');
        const updateChartData = (data) => emit('update:chartDataRo', data);
        // return {props}
    },
    data() {
        return {
            completedSteps: 2,
            totalSteps: 6,
            diameter: 320,
            strokeWidth: 10,
            innerStrokeWidth: 20,
            strokeLinecap: "round" as StrokeLinecap,
            startColor: "#0071ff",
            stopColor: "#ea00f6",
            innerStrokeColor: '#ECEFF1',
            timingFunc: "linear",
            isClockwise: true,
            animateSpeed: 1000,
            chart_data: [],
            // GRAFICA AVANCE DE PAQUETES
            chart_packages_dgo: {
                id: 1,
                series: usePage().props.x_chart_packets_received_dgo.series,
                options: {
                    colors: ['#5e5e5e', '#680bf8'],
                    title: {
                        text: "Documentación y Material",
                        align: 'left',
                        margin: 10,
                    },
                    subtitle: {
                        text: "Entregados a los CAE",
                        align: 'left',
                        margin: 0,
                    },
                    labels: ['Pendiente', 'Entregado'],
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        formatter:function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0)
                                        }
                                    }
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
                id: 2,
                series: usePage().props.x_chart_packets_received_dgo.series,
                options: {
                    colors: ['#5e5e5e', '#680bf8'],
                    title: {
                        text: "Paquetes Recibidos",
                        align: 'left',
                        margin: 10,
                    },
                    labels: ['Pendiente', 'Recibidos'],
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        formatter:function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0)
                                        }
                                    }
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
            // GRAFICA AEC REGISTRADAS
            chart_aec_registration_dgo: {
                id: 3,
                series: usePage().props.x_chart_aec_registration_dgo.series,
                options: {
                    colors: ['#5e5e5e', '#680bf8'],
                    title: {
                        text: "AEC Registradas",
                        align: 'left',
                        margin: 10,
                    },
                    labels: ['Pendiente', 'Registradas'],
                    plotOptions: {
                        pie: {
                            startAngle: -90,
                            endAngle: 90,
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        formatter:function(w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return a + b
                                            }, 0)
                                        }
                                    }
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
            // GRAFICA PAQUETES COTEJO
            chart_collated_packets_dgo: {
                id: 4,
                series: usePage().props.x_chart_collated_packets_dgo.series,
                options: {
                    colors: ['#5e5e5e', '#680bf8'],
                    title: {
                        text: "Paquetes Cotejo",
                        align: 'left',
                        margin: 10,
                    },
                    labels: ['Pendiente', 'Cotejados'],
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
                        enabled: true
                    },
                    noData: {
                        text: "Sin datos",
                        align: 'center',
                        verticalAlign: 'middle',
                    }
                },
            },
            // GRAFICA PAQUETES RECUENTO
            chart_recount_packets_dgo: {
                id: 5,
                series: usePage().props.x_chart_recount_packets_dgo.series,
                options: {
                    colors: ['#5e5e5e', '#680bf8'],
                    title: {
                        text: "Paquetes Recuento",
                        align: 'left',
                        margin: 10,
                    },
                    labels: ['Pendiente', 'Recontados'],
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
                        enabled: true
                    },
                    noData: {
                        text: "Sin datos",
                        align: 'center',
                        verticalAlign: 'middle',
                    }
                },
            },
        }
    },
    // REALTIME DATA
    mounted() {
        this.listenForPacketsReceived();
        console.log('Series: ', this.chart_packets_received_dgo.series)

    },
    methods: {
        listenForPacketsReceived() {
            window.Echo.channel('packets-received-dgo')
                .listen('PacketsReceivedEvent', e => {
                    // console.log(e.data.received)
                    this.chartData = e?.data?.received;
                });
        },
        // window.Echo.channel('packages')
        //     .listen('PackageEvent', (e) => {
        //         this.chart_packages_dgo.series = e.data;
        //     });
        // window.Echo.channel('packets-received-dgo')
        //     .listen('PacketReceivedEvent', (e) => {
        //         console.log(e.data)
        //         // this.chart_packets_received_dgo.series = e.data;
        //     });
        // window.Echo.channel('packages')
        //     .listen('AecRegistrationEvent', (e) => {
        //         this.chart_aec_registration_dgo.series = e.data;
        //     });
        // window.Echo.channel('packages')
        //     .listen('CollatedPacketEvent', (e) => {
        //         this.chart_collated_packets_dgo.series = e.data;
        //     });
        // window.Echo.channel('packages')
        //     .listen('RecountPacketEvent', (e) => {
        //         this.chart_recount_packets_dgo.series = e.data;
        //     });
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

        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-3 gap-6 lg:gap-8 p-6 lg:p-8">

            <div>
                {{ chartData }}
                <apexchart width="500" type="donut" :options="chart_packages_dgo.options"
                           :series="chart_packages_dgo.series" :key="chart_packages_dgo.id"></apexchart>
            </div>

            <div>
                <apexchart width="500" type="donut" :options="chart_packets_received_dgo.options"
                           :series="chart_packets_received_dgo.series" :key="chart_packets_received_dgo.id"></apexchart>
            </div>

            <div>
                <apexchart width="500" type="donut" :options="chart_aec_registration_dgo.options"
                           :series="chart_aec_registration_dgo.series" :key="chart_aec_registration_dgo.id"></apexchart>
            </div>

            <!--            <div class="flex justify-center items-center">
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
                        </div>-->
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
                <apexchart width="500" type="donut" :options="chart_collated_packets_dgo.options"
                           :series="chart_collated_packets_dgo.series" :key="chart_collated_packets_dgo.id"></apexchart>
            </div>

            <div>
                <apexchart width="500" type="donut" :options="chart_recount_packets_dgo.options"
                           :series="chart_recount_packets_dgo.series" :key="chart_recount_packets_dgo.id"></apexchart>
            </div>

            <div>

            </div>

            <div>

            </div>
        </div>
    </div>
</template>
