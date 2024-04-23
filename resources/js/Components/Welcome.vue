<script lang="ts">
import ApexCharts from "vue3-apexcharts";
import {usePage} from "@inertiajs/vue3";
import {computed, PropType, ref, toRef, toRefs} from 'vue'
import {ApexOptions} from "apexcharts";


interface Chart extends ApexCharts {
    series: Array<any>,
    options?: ApexOptions,
}

export default {
    components: {
        ApexCharts
    },
    props: {
        x_chart_packages_dgo: ApexCharts as PropType<Chart>,
        x_chart_packets_received_dgo: ApexCharts as PropType<Chart>,
        x_chart_aec_registration_dgo: ApexCharts as PropType<Chart>,
        // ETAPA CÓMPUTOS
        x_chart_collated_packets_dgo: ApexCharts as PropType<Chart>,
        x_chart_recount_packets_dgo: ApexCharts as PropType<Chart>,
    },
    setup(props, {emit}) {
        const c = ref(usePage().props.x_chart_packets_received_dgo.series)
        return {
            props,
            toFixed,
            c
        }
    },
    data() {
        return {
            // GRAFICA AVANCE DE PAQUETES
            chart_packages_dgo: {
                id: 1,
                series: usePage().props.x_chart_packets_received_dgo.series,
                options: {
                    stroke: {
                        show: false
                    },
                    legend: {
                        show: false
                    },
                    colors: ['#CFD8DC', '#680bf8'],
                    title: {
                        text: "Documentación y Material",
                        align: 'left',
                        margin: 10,
                    },
                    subtitle: {
                        text: "Entregado a los CAE",
                        align: 'left',
                        margin: 0,
                    },
                    dataLabels: { // Etiquetas de datos dentro de la gráfica
                        enabled: false,
                    },
                    labels: ['Pendiente', 'Entregado'],
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
                                        label: 'Entregado/Pendientes',
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
            },
            // GRAFICA PAQUETES RECIBIDOS
            chart_packets_received_dgo: {
                id: 2,
                series: usePage().props.x_chart_packets_received_dgo.series,
                options: {
                    legend: {
                        show: false
                    },
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
                            customScale: 0.9,
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return (b + " de " + a)
                                            })
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
                    legend: {
                        show: false
                    },
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
                            customScale: 0.9,
                            donut: {
                                size: '80%',
                                labels: {
                                    show: true,
                                    total: {
                                        show: true,
                                        showAlways: true,
                                        formatter: function (w) {
                                            return w.globals.seriesTotals.reduce((a, b) => {
                                                return (b + " de " + a)
                                            })
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
            // TEST
            chart_test_dgo: {
                id: 6,
                series: [toFixed((this.c[1] / this.c[0] * 100), 2)],
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
                    labels: ['Avance'],
                },
            },
        }
    },
    // REALTIME DATA
    mounted() {
        this.listenForPacketsReceived();
    },
    methods: {
        listenForPacketsReceived() {
            window.Echo.channel('packets-received-dgo')
                .listen('PacketsReceivedEvent', e => {
                    // Update series just if the received data is different from the current series
                    if (this.$refs.chart_packages_dgo.series !== e.received) {
                        this.c = e.received;
                        this.$refs.cpr.updateSeries([toFixed((e.received[1] / e.received[0] * 100), 2)], true);
                        this.$refs.chart_packages_dgo.updateSeries(e.received, true);
                    }
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

<style>
.apexcharts-inner.apexcharts-graphical {
    transform: translate(0, 0) !important;
}

.vue-apexcharts, .apexcharts-radialbar {
    width: 100px !important;
}

.apexcharts-canvas {
    width: 100%;
    height: 100%;
}

.apexcharts-svg > foreignObject, .apexcharts-svg g {
    width: 100px;
    height: 100px;
}
</style>

<template>

    <div class="flex gap-4 p-8">
        <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded">
            <apexchart ref="cpr" width="180" class="flex-none grow-0" type="radialBar" :options="chart_test_dgo.options"
                       :series="chart_test_dgo.series" :key="chart_test_dgo.id"></apexchart>
            <div class="shrink w-full ml-8">
                <p class="font-bold text-gray-500">Entrega de Material</p>
                <p class="font-medium text-gray-400">{{ c[1] }} de {{ c[0] }}</p>
            </div>
        </div>
        <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded">
            <apexchart width="180" type="radialBar" :options="chart_test_dgo.options"
                       :series="chart_test_dgo.series" :key="chart_test_dgo.id"></apexchart>
            <div class="shrink w-full ml-8">
                <p class="font-bold text-gray-500">Entrega de Material</p>
                <p class="font-medium text-gray-400">233 de 599</p>
            </div>
        </div>
        <div class="flex w-full items-center border border-gray-200 px-3 py-2 rounded">
            <apexchart width="180" type="radialBar" :options="chart_test_dgo.options"
                       :series="chart_test_dgo.series" :key="chart_test_dgo.id"></apexchart>
            <div class="shrink w-full ml-8">
                <p class="font-bold text-gray-500 text-sm">Entrega de Material</p>
                <p class="font-medium text-gray-400 text-xs">233 de 599</p>
            </div>
        </div>
    </div>
    <div>
        <div class="p-6 lg:p-8 bg-white border-b border-gray-200">
            <!--            <ApplicationLogo class="block h-12 w-auto"/>-->

            <h1 class="text-3xl font-medium text-gray-900">
                Durango.
            </h1>

            <p class="mt-2 text-gray-500 leading-relaxed">
                Estadísticas de paquetes recibidos y paquetes enviados en la ciudad de Durango, comprendida por los
                Distritos Locales I al VI.
            </p>

        </div>

        <div class="bg-gray-200 bg-opacity-25 grid grid-cols-1 md:grid-cols-2 gap-6 lg:gap-8 p-6 lg:p-8">

            <div>
                <apexchart ref="chart_packages_dgo" width="500" type="donut" :options="chart_packages_dgo.options"
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
