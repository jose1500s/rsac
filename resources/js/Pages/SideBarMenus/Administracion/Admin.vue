<script setup>
import { ref, onMounted } from 'vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from '@/Components/InputError.vue';
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


const props = defineProps({
    users: {
        type: Array,
        required: true
    },
});

const roles = [
    { name: 'Administrador', value: 'Administrador' },
    { name: 'Automotriz', value: 'Automotriz' },
    { name: 'Manufactura', value: 'Manufactura' },
    { name: 'Redes', value: 'Redes' },
    { name: 'LAGE', value: 'LAGE' },
    { name: 'Mecatronica', value: 'Mecatronica' },
    { name: 'Negocios', value: 'Negocios' },
    { name: 'Posgrado', value: 'Posgrado' },
    { name: 'Sistemas', value: 'Sistemas' },
    { name: 'Coordinacion', value: 'Coordinacion' },
    { name: 'Resumen', value: 'Resumen' },
    { name: 'Rector', value: 'Rector' },
    { name: 'SecretarioAcademico', value: 'SecretarioAcademico' },
];


const emptyMessage = 'No hay registros';
const rolesDropdown = ref(null);
const nuevoUsuarioDialog = ref(false);
const deleteProductsDialog = ref(false);
const selectedProduct = ref();
const toast = useToast();

const nombre = ref('');
const email = ref('');
const password = ref('');
const filters = ref({
    'global': { value: null, matchMode: FilterMatchMode.CONTAINS },
});

const closeDialogNuevo = () => {
    nuevoUsuarioDialog.value = false;
}

const openEliminarDialog = () => {
    deleteProductsDialog.value = true;
};

const openDialogNuevo = () => {
    nuevoUsuarioDialog.value = true;
}

const submit = () => {

    const data = {
        name: nombre.value,
        email: email.value,
        password: password.value,
    }
    Inertia.post("/registrar-nuevo-usuario", data, {
        onSuccess: () => {
            nuevoUsuarioDialog.value = false;
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Registrado exitosamente",
                life: 3000,
            });
        },
    });
}

const asignarRol = (event) => {
    let { newData } = event;
    const data = {
        id: newData.id,
        roles: newData.roles
    }
    Inertia.post(`/asignar-rol/${newData.id}`, data, {
        onSuccess: () => {
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Rol asignado exitosamente",
                life: 3000,
            });
        },
    });
}

const eliminarUsuario = () => {
    const data = {
        id: selectedProduct.value.map((item) => item.id)
    }
    Inertia.post("/eliminar-usuario", data, {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
            deleteProductsDialog.value = false;
            selectedProduct.value = null;
            toast.add({
                severity: "success",
                summary: "Exito",
                detail: "Eliminado Exitosamente",
                life: 3000,
            });
        },
    });
}

const editingRows = ref([]);
</script>

<template>
    <AppLayout title="Administración">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                Administración
            </h2>
            <Toast />
        </template>

        <div class="max-w-[99.9%] m-auto p-2">
            <Toolbar class="mb-4">
                <template #start>
                    <Button label="Registrar" icon="pi pi-plus" severity="success" class="!mr-3" @click="openDialogNuevo" />
                    <Button label="Eliminar" icon="pi pi-trash" severity="danger" @click="openEliminarDialog"
                        :disabled="!selectedProduct || !selectedProduct.length" />
                </template>
            </Toolbar>
            <!-- tabla con los registros de los usuarios -->
            <DataTable :value="users" v-model:editingRows="editingRows" editMode="row" @row-edit-save="asignarRol"
                :paginator="true" :rows="10" :rowsPerPageOptions="[5, 10, 20]" :responsive="true" :filters="filters"
                v-model:selection="selectedProduct" :emptyMessage="emptyMessage" class="p-datatable-gridlines">

                <template #header>
                    <div class="flex flex-wrap align-items-center justify-between gap-2">
                        <span class="p-input-icon-left">
                            <i class="pi pi-search" />
                            <InputText v-model="filters['global'].value" placeholder="Buscar" />
                        </span>
                    </div>
                </template>


                <Column selectionMode="multiple" headerStyle="width: 3rem"></Column>
                <Column field="id" header="ID" sortable hidden />
                <Column field="name" header="Nombre" sortable />
                <Column field="email" header="Correo" sortable />
                <Column field="created_at" header="Fecha de creación" sortable />
                <Column field="roles" header="Rol" sortable>
                    <template #body="slotProps">
                        <div class="flex justify-center gap-3 p-4">
                            <span v-for="role in slotProps.data.roles" :key="role.id"
                                class="p-tag p-tag-rounded p-tag-info">
                                {{ role.name }}
                            </span>
                        </div>
                    </template>
                    <template #editor="{ data, field }">
                        <Dropdown v-model="data[field]" :options="roles" optionLabel="name" optionValue="value">
                        </Dropdown>
                    </template>
                </Column>

                <Column :rowEditor="true" style="width: 10%; min-width: 8rem" bodyStyle="text-align:center"></Column>
            </DataTable>

        </div>

        <!-- Dialog para registrar un nuevo usuario -->
        <Dialog v-model:visible="nuevoUsuarioDialog" :style="{ width: '650px' }" header="Registro nuevo" :modal="true"
            class="p-fluid">
            <form @submit.prevent="submit">
                <div class="p-field">
                    <label for="name">Nombre</label>
                    <InputText id="name" v-model="nombre" />

                </div>

                <div class="p-field">
                    <label for="email">Correo</label>
                    <InputText type="email" id="email" v-model="email" />
                </div>

                <div class="p-field">
                    <label for="password">Contraseña</label>
                    <InputText type="password" id="password" v-model="password" />
                </div>

                <div class="flex justify-end items-center max-w-[100%] mt-4 gap-4">
                    <Button type="submit" class="w-auto" label="Registrar" icon="pi pi-check" />
                </div>
            </form>
        </Dialog>

        <!-- Dialog para eliminar -->
        <Dialog v-model:visible="deleteProductsDialog" :style="{ width: '400px' }" header="Confirmar" :modal="true">
            <div class="confirmation-content flex justify-center items-center">
                <i class="pi pi-exclamation-triangle mr-3" style="font-size: 2rem" />
                <span>¿Esta seguro de eliminar los usuarios seleccionados?</span>
            </div>
            <template #footer>
                <Button label="Cancelar" icon="pi pi-times" text @click="deleteProductsDialog = false" />
                <Button label="Eliminar" icon="pi pi-check" text @click="eliminarUsuario" />
            </template>
        </Dialog>




    </AppLayout>>
</template>

