<script setup>
import {computed, defineComponent, onMounted, ref} from 'vue';
import {Head, Link, router, usePage} from '@inertiajs/vue3';
import ApplicationMark from '@/Components/ApplicationMark.vue';
import Banner from '@/Components/Banner.vue';
import NavLink from '@/Components/NavLink.vue';
import {useLoading} from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css';
import {
    initDrawers,
    initDropdowns,
    initModals,

} from 'flowbite';
import SidebarLinks from "@/Components/SidebarLinks.vue";

onMounted(() => {
    initDrawers();
    initDropdowns();
    initModals();
})

defineProps({
    title: String,
    municipio_id: String
});

const $loading = useLoading({
    canCancel: false,
    isFullPage: true,
    opacity: 0.7,
    color: "#7723ec",
});

const page = usePage()
const municipio_id = computed(() => page.props.municipio_id)

const switchToTeam = (team) => {
    router.put(route('current-team.update'), {
        team_id: team.id,
    }, {
        preserveState: false,
    });
};

const getMaterialSuppliedLog = () => {
    const loader = $loading.show({});
    axios.get('/export/material-supplied/', {
        params: {
            municipio_id: municipio_id.value
        },
        responseType: 'blob'
    }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'entregas_cae_sel.xlsx');
        document.body.appendChild(link);
        link.click();

    }).then(() => {
        loader.hide();
    });
}

const getAECRecordsLog = () => {
    const loader = $loading.show();
    axios.get('/export/aec-records/', {
        params: {
            municipio_id: municipio_id.value
        },
        responseType: 'blob'
    }).then(response => {
        const url = window.URL.createObjectURL(new Blob([response.data]));
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('download', 'bitacora-recepcion-' + municipio_id.value + '_' + Date.now() + '.csv');
        document.body.appendChild(link);
        link.click();

    }).then(() => {
        loader.hide();
    });
}

const logout = () => {
    router.post(route('logout'));
};
</script>

<template>
    <div>
        <Head :title="title"/>

        <Banner/>

        <div class="min-h-screen bg-gray-100">

            <nav
                class="fixed top-0 z-50 w-full bg-white border-b border-gray-200 dark:bg-gray-800 dark:border-gray-700">
                <div class="px-3 py-3 lg:px-5 lg:pl-3">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center justify-start rtl:justify-end">
                            <button data-drawer-target="logo-sidebar" data-drawer-toggle="logo-sidebar"
                                    aria-controls="logo-sidebar" type="button"
                                    class="inline-flex items-center p-2 text-sm text-gray-500 rounded-lg sm:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600">
                                <span class="sr-only">Open sidebar</span>
                                <svg class="w-6 h-6" aria-hidden="true" fill="currentColor" viewBox="0 0 20 20"
                                     xmlns="http://www.w3.org/2000/svg">
                                    <path clip-rule="evenodd" fill-rule="evenodd"
                                          d="M2 4.75A.75.75 0 012.75 4h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 4.75zm0 10.5a.75.75 0 01.75-.75h7.5a.75.75 0 010 1.5h-7.5a.75.75 0 01-.75-.75zM2 10a.75.75 0 01.75-.75h14.5a.75.75 0 010 1.5H2.75A.75.75 0 012 10z"></path>
                                </svg>
                            </button>
                            <div class="shrink-0 flex items-center">
                                <Link :href="route('dashboard')">
                                    <ApplicationMark class="block w-auto"/>
                                </Link>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <div class="flex items-center ms-3">
                                <div>
                                    <button type="button"
                                            class="flex text-sm bg-gray-800 rounded-full focus:ring-4 focus:ring-gray-300 dark:focus:ring-gray-600"
                                            aria-expanded="false" data-dropdown-toggle="dropdown-user">
                                        <span class="sr-only">Open user menu</span>
                                        <img class="w-8 h-8 rounded-full"
                                             src="../../images/avatar.jpg"
                                             alt="user photo">
                                    </button>
                                </div>
                                <div
                                    class="z-50 hidden my-4 text-base list-none bg-white divide-y divide-gray-100 rounded shadow dark:bg-gray-700 dark:divide-gray-600"
                                    id="dropdown-user">
                                    <div class="px-4 py-3" role="none">
                                        <p class="text-sm text-gray-900 dark:text-white" role="none">
                                            {{ $page.props.auth.user.name }}
                                        </p>
                                        <p class="text-sm font-medium text-gray-900 truncate dark:text-gray-300"
                                           role="none">
                                            {{ $page.props.auth.user.email }}
                                        </p>
                                    </div>
                                    <ul class="py-1" role="none">
                                        <li>
                                            <form @submit.prevent="logout">
                                                <button
                                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 dark:text-gray-300 dark:hover:bg-gray-600 dark:hover:text-white"
                                                    role="menuitem">Salir
                                                </button>
                                            </form>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </nav>


            <aside id="logo-sidebar"
                   class="fixed top-0 left-0 z-40 w-64 h-screen pt-20 transition-transform -translate-x-full bg-white border-r border-gray-200 sm:translate-x-0 dark:bg-gray-800 dark:border-gray-700"
                   aria-label="Sidebar">
                <div class="h-full px-3 pb-4 overflow-y-auto bg-white dark:bg-gray-800">
                    <SidebarLinks/>
                </div>
            </aside>

            <!-- Page Content -->
            <main class="sm:p-4 sm:ml-64">
                <div class="sm:p-4 rounded-lg dark:border-gray-700 mt-14">
                    <!-- Page Heading -->
                    <header v-if="$slots.header" class="bg-white shadow flex justify-between items-center">
                        <div class="flex items-center space-x-5 max-w-7xl mt-5 px-4 sm:px-6 lg:px-8">
                            <img :src="'/images/' + municipio_id + '.png'" alt="Escudo" width="60">
                            <slot name="header"/>
                        </div>
                    </header>
                    <Transition mode="out-in" name="page">
                        <div :key="$page.url">
                            <slot/>
                            <h5 class="my-5 text-2xl font-bold tracking-tight text-gray-900 dark:text-white">Formatos
                                descargables.</h5>
                            <div class="grid md:grid-cols-2 sm:grid-cols-1 lg:md:grid-cols-3 gap-4 mb-4 mt-5">
                                <div
                                    class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                    <div class="flex items-center space-x-4">
                                        <img src="../../images/icons/Files_15.svg" alt="Icono de archivo Excel"
                                             width="60">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            Bitácora de entrega de Documentación Electoral.</h5>
                                    </div>
                                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Formato que contiene
                                        los
                                        detalles de las entregas realizadas a los CAEL/SEL sobre la Documentación
                                        Electoral.</p>
                                    <div class="flex justify-end">
                                        <Link @click="getMaterialSuppliedLog"
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
                                <div
                                    class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                    <div class="flex items-center space-x-4">
                                        <img src="../../images/icons/Files_15.svg" alt="Icono de archivo Excel"
                                             width="60">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            Bitácora de Entrada/Salida de Bodega Electoral.</h5>
                                    </div>
                                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Formato que contiene
                                        las
                                        entradas y salidas de paquetes de la Bodega Electoral, mismo que especifica la
                                        hora,
                                        motivo de entrada/salida y el paquete correspondiente.</p>
                                    <div class="flex justify-end">
                                        <!--                                    <Link @click="alert('Recurso no disponible')"  class="inline-flex space-x-2 items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">-->
                                        <!--                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
                                        <!--                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>-->
                                        <!--                                            <path d="M12 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12v5"/>-->
                                        <!--                                            <path d="M13 16h-7a2 2 0 0 0 -2 2"/>-->
                                        <!--                                            <path d="M15 19l3 3l3 -3"/>-->
                                        <!--                                            <path d="M18 22v-9"/>-->
                                        <!--                                        </svg>-->
                                        <!--                                        <span>Descargar</span>-->
                                        <!--                                    </Link>-->
                                        <span
                                            class="text-sm text-gray-500">Recurso no disponible por el momento...</span>
                                    </div>
                                </div>
                                <div
                                    class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                    <div class="flex items-center space-x-4">
                                        <img src="../../images/icons/Files_23.svg" alt="Icono de archivo Excel"
                                             width="60">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            Bitácora de recepción de Paquetes Electorales</h5>
                                    </div>
                                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Registros
                                        correspondientes a la recepción de los paquetes electorales que contienen la
                                        documentación electoral utilizada en las casillas.</p>
                                    <div class="flex justify-end">
                                        <Link @click="getAECRecordsLog"
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
                                <div
                                    class="w-full p-6 bg-white border border-gray-200 rounded-lg shadow dark:bg-gray-800 dark:border-gray-700">
                                    <div class="flex items-center space-x-4">
                                        <img src="../../images/icons/Files_15.svg" alt="Icono de archivo Excel"
                                             width="60">
                                        <h5 class="mb-2 text-xl font-bold tracking-tight text-gray-900 dark:text-white">
                                            Resultados Preliminares.</h5>
                                    </div>
                                    <p class="mb-3 font-normal text-gray-700 dark:text-gray-400">Resultados de los
                                        Partidos
                                        Políticos y Coaliciones Electorales en desglose por sección y casilla por el
                                        Principio de Mayoría Relativa y Representación Proporcional.</p>
                                    <div class="flex justify-end">
                                        <!--                                    <Link @click="alert('Recurso no disponible')"  class="inline-flex space-x-2 items-center px-3 py-2 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300 dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">-->
                                        <!--                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">-->
                                        <!--                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"/>-->
                                        <!--                                            <path d="M12 20h-6a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12v5"/>-->
                                        <!--                                            <path d="M13 16h-7a2 2 0 0 0 -2 2"/>-->
                                        <!--                                            <path d="M15 19l3 3l3 -3"/>-->
                                        <!--                                            <path d="M18 22v-9"/>-->
                                        <!--                                        </svg>-->
                                        <!--                                        <span>Descargar</span>-->
                                        <!--                                    </Link>-->
                                        <span
                                            class="text-sm text-gray-500">Recurso no disponible por el momento...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </Transition>
                </div>
            </main>

        </div>
    </div>
</template>
