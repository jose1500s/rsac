<script setup>
import { defineProps, onMounted, ref } from 'vue';
import { watch } from 'vue';

const datos = ref([]);
let series = ref([]);
let categories = ref([]);

const props = defineProps({
    datos: {
        type: Array,
    },
});


const formatCategories = (categories) => {
    return categories.map((item) =>
        item.proyecto_actividad + '  -  ' + item.categoria + '  -  ' + item.estatus
    );
};

onMounted(() => {

});

series = [
    {
        name: 'hombres',
        data: props.datos.map((item) => item.hombres1 + item.hombres2)
    },
    {
        name: 'mujeres',
        data: props.datos.map((item) => item.mujeres1 + item.mujeres2)
    }
];

const options = {
    chart: {
        id: 'vuechart-example',
        animations: {
            enabled: true,
            easing: 'easeinout',
            speed: 800,
            animateGradually: {
                enabled: true,
                delay: 150
            },
            dynamicAnimation: {
                enabled: true,
                speed: 350
            }
        },

    },
    xaxis: {
        categories: formatCategories(props.datos),
        position: 'bottom',
        axisBorder: {
            show: false
        },
        crosshairs: {
            fill: {
                type: 'gradient',
                gradient: {
                    colorFrom: '#D8E3F0',
                    colorTo: '#BED1E6',
                    stops: [0, 100],
                    opacityFrom: 0.4,
                    opacityTo: 0.5,
                }
            }
        },
    },
    title: {
        text: 'Reporte de Proyectos y Actividades',
        align: 'bottom',
        style: {
            fontSize: '16px',
            color: '#666'
        }
    },
    plotOptions: {
        bar: {
            borderRadius: 10,
            dataLabels: {
                position: 'center', // top, center, bottom
            },
        }
    },
    dataLabels: {
        enabled: true,
        formatter: function (val) {
            return val;
        },
        offsetY: 5,
        style: {
            fontSize: '18px',
            colors: ["#fff"]
        }
    }

};

</script>

<template>
    <apexchart width="100%" height="550px" class="m-auto" type="bar" :options="options" :series="series"></apexchart>
</template>

<style scoped></style>
