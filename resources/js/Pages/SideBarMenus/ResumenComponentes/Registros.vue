<script setup>
import { ref, onMounted } from 'vue';
import { watch } from 'vue';

import { FilterMatchMode } from 'primevue/api';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import InputText from 'primevue/inputtext';
import InputNumber from 'primevue/inputnumber';
import Toolbar from 'primevue/toolbar';
import Button from 'primevue/button';
import Dialog from 'primevue/dialog';
import Dropdown from 'primevue/dropdown';
import { useToast } from 'primevue/usetoast';
import Toast from 'primevue/toast';
import { Inertia } from '@inertiajs/inertia'
import Textarea from 'primevue/textarea';

const props = defineProps({
    registrosResumen: {
        type: Array,
        required: true
    },
    registrosTransversales: {
        type: Array,
        required: true
    },
    totalAcreditacion: {
        type: Number,
    },
    totalCertificacion: {
        type: Number,
    },
    totalEvento: {
        type: Number,
    },
    totalInvestigacion: {
        type: Number,
    },
    totalMaterialEducativo: {
        type: Number,
    },
    totalPlanEstudios: {
        type: Number,
    },
    totalProyecto: {
        type: Number,
    },
    totalOtro: {
        type: Number,
    },
    total: {
        type: Number,
    },
});

const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});



const programas_educativos_lista = ref([
    { nombre: 'Lic. en Administración y Gestión Empresarial', value: 'Lic. en Administración y Gestión Empresarial' },
    { nombre: 'Ing. en Tecnología Automotriz', value: 'Ing. en Tecnología Automotriz' },
    { nombre: 'Ing. en Tecnologías de Manufactura', value: 'Ing. en Tecnologías de Manufactura' },
    { nombre: 'Ing.en Mecatrónica', value: 'Ing.en Mecatrónica' },
    { nombre: 'Lic. en Negocios Internacionales', value: 'Lic. en Negocios Internacionales' },
    { nombre: 'Ing. en Redes y Telecomunicaciones', value: 'Ing. en Redes y Telecomunicaciones' },
    { nombre: 'Ing. En Sistemas Computacionales', value: 'Ing. En Sistemas Computacionales' },
    { nombre: 'Maestría Ing. Administrativa', value: 'Maestría Ing. Administrativa' },
    { nombre: 'Maestría Ing. Sistemas Productivos e Ind. 4.0', value: 'Maestría Ing. Sistemas Productivos e Ind. 4.0' },
    { nombre: 'Coordinación Académica', value: 'Coordinación Académica' },
    { nombre: 'Posgrado', value: 'Posgrado' },
]);

const selectedProduct = ref();
const editingRows = ref([]);
const dt = ref();
const productDialog = ref(false);
const actTransversalDialog = ref(false);
const verActsDialog = ref(false);
const registroActTransversalDialog = ref(false);
const deleteProductsDialog = ref(false);

const toast = useToast();
const noDataMessage = "No se encontraron datos";

// variables para el dialog de nuevo registro
const programaEducativo = ref(null);
const acreditacion = ref(0);
const capacitacion = ref(0);
const certificacion_competencias = ref(0);
const evento = ref(0);
const investigacion = ref(0);
const material_educativo = ref(0);
const plan_estudios = ref(0);
const proyecto = ref(0);
const otro = ref(0);
const total = ref(0);
const programa_educativo_act_transversal = ref(null);
// variables para el dialog de registrar act transversal
const nombre_evento = ref(null);
const persona_acargo = ref(null);
const nueva_act_transversal = ref(null);
const id_para_asignar_programa_actividad = ref(null);
const actividadesTransversales = ref([]);


const onRowSelect = (event) => {
    const selectedRowData = event.data;
};
const onRowUnselect = (event) => {
    // alert('Producto deseleccionado')
}

const openDialogNuevo = () => {
    productDialog.value = true;
};

const openDialogActTransversal = (id_para_asignar) => {
    id_para_asignar_programa_actividad.value = id_para_asignar;
    actTransversalDialog.value = true;
};

const openVerActsDialog = (id) => {
    verActsDialog.value = true;
    Inertia.post(`/ver-acts-transversales/${id}`, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: (page) => {
            actividadesTransversales.value = page.props.actividadesTransversalesArray;
        },
    });
};



const openDialogRegistrarActTransversal = () => {
    registroActTransversalDialog.value = true;
};

// metodo registrarActTransversal que recibe el  slotProps.data.programa_educativo
const asignarActTransversal = (id) => {
    const data = {
        nombre_evento: nombre_evento.value,
        id: id_para_asignar_programa_actividad.value,
    }
    Inertia.post("/asignar-act-transversal", data, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            actTransversalDialog.value = false;
            nombre_evento.value = null;
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Asignado exitosamente",
                life: 3000,
            });
        },
    });
}


const ocultarNuevoRegistro = () => {

    productDialog.value = false;
};

const openEliminarDialog = () => {
    deleteProductsDialog.value = true;
};
const eliminarProductos = () => {
    const data = {
        id: selectedProduct.value.map((item) => item.id)
    }
    Inertia.post("/eliminar-resumen", data, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            deleteProductsDialog.value = false;
            selectedProduct.value = null;
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Eliminado exitosamente",
                life: 3000,
            });
        },
    });

};

const registrarNuevo = () => {
    // validar que los campos no esten vacios
    if (programaEducativo.value == null || acreditacion.value < 0 ||
        certificacion_competencias.value < 0 || evento.value < 0 ||
        investigacion.value < 0 || material_educativo.value < 0 ||
        plan_estudios.value < 0 || proyecto.value < 0 ||
        otro.value < 0 || acreditacion.value < 0) {
        toast.add({
            severity: "error",
            summary: "Error",
            detail: "Todos los campos son obligatorios",
            life: 3000,
        });
        return;
    } else {
        const data = {
            programa_educativo: programaEducativo.value,
            acreditacion: acreditacion.value,
            certificacion_competencias: certificacion_competencias.value,
            evento: evento.value,
            investigacion: investigacion.value,
            material_educativo: material_educativo.value,
            plan_estudios: plan_estudios.value,
            proyecto: proyecto.value,
            otro: otro.value,
            // total es la suma de todos los campos menos el programa educativo
            total: parseInt(acreditacion.value) + parseInt(certificacion_competencias.value) + parseInt(evento.value) + parseInt(investigacion.value) + parseInt(material_educativo.value) + parseInt(plan_estudios.value) + parseInt(proyecto.value) + parseInt(otro.value),
        }
        Inertia.post("/registro-resumen", data, {
            preserveState: true,
            preserveScroll: true,
            onSuccess: () => {
                productDialog.value = false;
                toast.add({
                    severity: "success",
                    summary: "Exito",
                    detail: "Registrado correctamente",
                    life: 3000,
                });
            },
        });
    }


}

const onRowEditSave = (event) => {
    let { newData } = event;

    const data = {
        id: newData.id,
        programa_educativo: newData.programa_educativo,
        acreditacion: newData.acreditacion,
        certificacion_competencias: newData.certificacion_competencias,
        evento: newData.evento,
        investigacion: newData.investigacion,
        material_educativo: newData.material_educativo,
        plan_estudios: newData.plan_estudios,
        proyecto: newData.proyecto,
        otro: newData.otro,
        total: newData.total,
        actividades_transversales: newData.actividades_transversales,
        persona_acargo: newData.persona_acargo,
    }
    Inertia.post(`/editar-resumen/${newData.id}`, data, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Editado correctamente",
                life: 3000,
            });
        },
    });
};


const exportCSV = () => {
    dt.value.exportCSV();
};


</script>

<template>
    <div class="max-w-[99.9%] m-auto p-2">
        <Toolbar class="mb-4">
            <template #start>
                <Button label="Registrar" icon="pi pi-plus" severity="success" class="!mr-3" @click="openDialogNuevo" />
                <Button label="Eliminar" icon="pi pi-trash" severity="danger" @click="openEliminarDialog"
                    :disabled="!selectedProduct || !selectedProduct.length" />
            </template>

            <template #end>

                <Button label="Excel" icon="pi pi-upload" severity="help" @click="exportCSV($event)" />
            </template>
        </Toolbar>
        <!-- seccion para mostrar el totalAcreditacion etc -->
        <div class="totales flex justify-center items-center flex-wrap gap-3">
            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalAcreditacion }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Acreditación
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalCertificacion }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Certificación
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalEvento }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Evento
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalInvestigacion }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Investigación
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalMaterialEducativo }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Material Educativo
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalPlanEstudios }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Plan de Estudios
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalProyecto }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Proyecto
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ totalOtro }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total Otro
                </p>
            </div>

            <div class="relative block rounded-sm border-t-4 border-pink-600 p-4 shadow-xl sm:p-6 lg:p-8 min-w-[210px]">
                <div class="flex items-center gap-4">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-pink-600 sm:h-8 sm:w-8" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 10V3L4 14h7v7l9-11h-7z" />
                    </svg>

                    <h3 class="text-3xl font-bold sm:text-4xl">{{ total }}</h3>
                </div>

                <p class="mt-4 font-medium text-gray-500">
                   Total
                </p>
            </div>



        </div>

        <!-- toast para que aparescan las alertas mensajes -->
        <Toast />
        <div class="contendorTabla">
            <DataTable :value="registrosResumen" ref="dt" class="p-datatable-sm" showGridlines stripedRows paginator
                :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]" v-model:selection="selectedProduct" @rowSelect="onRowSelect"
                @rowUnselect="onRowUnselect" 
                :filters="filters" :emptyMessage="noDataMessage"
                v-model:editingRows="editingRows" editMode="row" @row-edit-save="onRowEditSave">

                <template #header>
                    <div class="flex flex-wrap align-items-center justify-between gap-2">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Buscar" />
                        </span>
                    </div>
                </template>

                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>

                <Column field="id" header="ID" hidden>
                </Column>

                <Column field="programa_educativo" header="Programa Educativo">
                    <template #editor="{ data, field }">
                        <Dropdown v-model="data[field]" :options="programas_educativos_lista" optionLabel="nombre"
                            optionValue="value">
                        </Dropdown>
                    </template>
                </Column>

                <Column field="acreditacion" header="Acreditación">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="certificacion_competencias" header="Certificación/Competencias">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="evento" header="Evento">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="investigacion" header="Investigación">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="material_educativo" header="Material Educativo">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="plan_estudios" header="Plan estudios">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="proyecto" header="Proyecto">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="otro" header="Otro">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="total" header="Total">
                    <template #editor="{ data, field }">
                        <InputNumber v-model="data[field]" mode="decimal" :min="0" :max="10000" :showButtons="true" />
                    </template>
                </Column>

                <Column field="actividades_transversales" header="Act.Transversales">
                    <template #editor="{ data, field }">
                        <Textarea v-model="data[field]" rows="5" cols="30" />
                    </template>
                </Column>

                <Column field="persona_acargo" header="Acargo">
                    <template #editor="{ data, field }">
                        <Textarea v-model="data[field]" rows="5" cols="30" />
                    </template>
                </Column>

                <Column header="Asignar">
                    <template #body="slotProps">
                        <div class="flex justify-center gap-3 p-4">
                            <Button icon="pi pi-plus" class="p-button-rounded p-button-success"
                                @click="openDialogActTransversal(slotProps.data.id)" />
                        </div>
                    </template>
                </Column>

                <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center">
                </Column>

                <!-- mensaje de no hay datos -->
                <template #empty>
                    <div class="flex justify-center align-middle text-xl">
                        <h2>No se encontraron datos</h2>
                    </div>
                </template>

            </DataTable>

        </div>


        <!-- Dialog para REGISTRAR nuevo -->
        <Dialog v-model:visible="productDialog" :style="{ width: '650px' }" header="Registro nuevo" :modal="true"
            class="p-fluid">

            <label for="carrera" class="mb-3">Programa Educativo</label>
            <Dropdown id="carrera" v-model="programaEducativo" :options="programas_educativos_lista" optionLabel="nombre"
                optionValue="value" placeholder="Programa Educativo" />

            <label for="acreditacion" class="mb-3">Acreditación</label>
            <InputNumber id="acreditacion" v-model="acreditacion" mode="decimal" :min="0" :max="10000"
                :showButtons="true" />

            <label for="capacitacion" class="mb-3">Capacitación</label>
            <InputNumber id="capacitacion" v-model="capacitacion" mode="decimal" :min="0" :max="10000"
                :showButtons="true" />

            <label for="certificacion_competencias" class="mb-3">Certificación/Competencias</label>
            <InputNumber id="certificacion_competencias" v-model="certificacion_competencias" mode="decimal" :min="0"
                :max="10000" :showButtons="true" />

            <label for="evento" class="mb-3">Evento</label>
            <InputNumber id="evento" v-model="evento" mode="decimal" :min="0" :max="10000" :showButtons="true" />

            <label for="investigacion" class="mb-3">Investigación</label>
            <InputNumber id="investigacion" v-model="investigacion" mode="decimal" :min="0" :max="10000"
                :showButtons="true" />

            <label for="material_educativo" class="mb-3">Material Educativo</label>
            <InputNumber id="material_educativo" v-model="material_educativo" mode="decimal" :min="0" :max="10000"
                :showButtons="true" />

            <label for="plan_estudios" class="mb-3">Plan estudios</label>
            <InputNumber id="plan_estudios" v-model="plan_estudios" mode="decimal" :min="0" :max="10000"
                :showButtons="true" />

            <label for="proyecto" class="mb-3">Proyecto</label>
            <InputNumber id="proyecto" v-model="proyecto" mode="decimal" :min="0" :max="10000" :showButtons="true" />

            <label for="otro" class="mb-3">Otro</label>
            <InputNumber id="otro" v-model="otro" mode="decimal" :min="0" :max="10000" :showButtons="true" />


            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="ocultarNuevoRegistro" />
                <Button label="Registrar" icon="pi pi-check" text @click="registrarNuevo" />
            </template>
        </Dialog>

        <!-- dialog para asignar ACTIVIDAD TRANSVERSAL a un grupo -->
        <Dialog v-model:visible="actTransversalDialog" :style="{ width: '400px' }" header="Registrar Act. Transversal"
            :modal="true">

            <div class="flex flex-col">
                <!-- dropdown con las opciones de evento que estan en el arrray registrosTransversales que llego como props -->
                <Dropdown id="nombre_evento" v-model="nombre_evento" :options="registrosTransversales"
                    optionLabel="nombre_evento" optionValue="nombre_evento" placeholder="Evento" />

            </div>

            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="actTransversalDialog = false" />
                <Button label="Registrar" icon="pi pi-check" text @click="asignarActTransversal" />
            </template>

        </Dialog>

        <!-- dialog para VER las actividades transversales de una fila -->
        <Dialog v-model:visible="verActsDialog" :style="{ width: '400px' }" header="Act. Transversales Registradas"
            :modal="true">

            <div class="flex flex-col">
                <pre>
                            {{ actividadesTransversalesArray }}
                        </pre>
            </div>

            <template #footer>
                <Button label="Cerrar" icon="pi pi-check" text @click="verActsDialog = false" />
            </template>

        </Dialog>



        <!-- Dialog para ELIMINAR -->
        <Dialog v-model:visible="deleteProductsDialog" :style="{ width: '400px' }" header="Confirmar" :modal="true">
            <div class="confirmation-content flex justify-center items-center">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>¿Esta seguro de eliminar los registro(s) seleccionado(s)?</span>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="deleteProductsDialog = false" />
                <Button label="Eliminar" icon="pi pi-check" text @click="eliminarProductos" />
            </template>
        </Dialog>



    </div>
</template>

<style scoped></style>