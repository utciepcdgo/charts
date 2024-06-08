import axios from 'axios';
import {useLoading} from 'vue-loading-overlay'
import 'vue-loading-overlay/dist/css/index.css';
import {usePage} from "@inertiajs/vue3";

const $loading = useLoading({
    canCancel: false,
    isFullPage: true,
    opacity: 0.7,
    color: "#7723ec",
});

export default {
    // Function to download a file
    getCollatedRecountPacketsList: function (typeOfRecords) {
        const loader = $loading.show({});
        console.log(usePage().props.municipio_id);
        axios.get('/export/aec-collates-recounts/', {
            params: {
                municipio_id: usePage().props.municipio_id,
                type_of_records: typeOfRecords
            },
            responseType: 'blob'
        }).then(response => {
            console.log(response);
            const url = window.URL.createObjectURL(new Blob([response.data]));
            const link = document.createElement('a');
            link.href = url;
            // Getting name from laravel's response header
            const filename = response.headers.get("content-disposition").split("filename=")[1];

            link.setAttribute('download', filename);
            document.body.appendChild(link);
            link.click();

        }).then(() => {
            loader.hide();
        });
    },
    municipalitiesList: [
        {id: '5', name: 'Durango', route: 'stats.durango'},
        {id: '33', name: 'Santiago Papasquiaro', route: 'stats.santiago'},
        {id: '8', name: 'Guadalupe Victoria', route: 'stats.guadalupe'},
        {id: '13', name: 'Mapimí', route: 'stats.mapimi'},
        {id: '7', name: 'Gómez Palacio', route: 'stats.gomez'},
        {id: '12', name: 'Lerdo', route: 'stats.lerdo'},
        {id: '4', name: 'Cuencamé', route: 'stats.cuencame'},
        {id: '24', name: 'Pueblo Nuevo', route: 'stats.pueblonuevo'},
    ]
}
