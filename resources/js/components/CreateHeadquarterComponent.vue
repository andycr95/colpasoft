<script setup>
import { ref } from "vue";
import axios from "axios";

const name = ref("");
const address = ref("");
const company_id = ref(0);
const errors = ref([]);
const loading = ref(false);

const props = defineProps({
    user: {
        type: Object,
    },
    companies: {
        type: Array,
    },
});

const createSede = async (e) => {
    errors.value = [];
    if (!name.value || name.value.length < 3) {
        errors.value.push("El nombre es obligatorio.");
    }
    if (!address.value) {
        errors.value.push("La dirección es obligatoria.");
    }
    if (!company_id.value || company_id.value === 0) {
        errors.value.push("La empresa es obligatoria.");
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
        loading.value = true;
        await axios.post("/api/sedes", {
            name: name.value,
            address: address.value,
            company_id: company_id.value,
        })   
        window.location.reload();
    } catch (error) {
        loading.value = false;
        if (error.response.status === 400 || error.response.status === 422) {
            errors.value.push(error.response.data.message);
        } else {
            errors.value.push("Ha ocurrido un error al intentar guardar la sede.");
        }
    }
};
</script>

<template>
    <div class="modal-header">
        <h5 class="modal-title">Sede</h5>
        <button
            id="company-modal-button-close"
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            :disabled="loading"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div  v-if="errors.length > 0">
            <p>
                <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </p>
        </div>
        <div class="form-group">
            <label for="name">Nombre</label>
            <input
                type="text"
                class="form-control"
                id="name"
                name="name"
                v-model="name"
                @change="name = $event.target.value"
                required
            />
        </div>
        <div class="form-group">
            <label for="address">Dirección</label>
            <input
                type="text"
                class="form-control"
                id="address"
                name="address"
                required
                v-model="address"
                @change="address = $event.target.value"
            />
        </div>
        <div class="form-group">
            <label for="company_id">Empresa</label>
            <select
                class="form-control"
                id="company_id"
                name="company_id"
                required
                v-model="company_id"
                @change="company_id = $event.target.value"
            >
                <option value="0">Seleccione una empresa</option>
                <option
                    v-for="company in companies"
                    :key="company.id"
                    :value="company.id"
                >
                    {{ company.name }}
                </option>
            </select>
        </div>
    </div>
    <div class="modal-footer">
        <button
            id="company-modal-button-cancel"
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            :disabled="loading"
        >
            Cancelar
        </button>
        <button type="submit" @click="createSede" class="btn btn-primary" :disabled="loading">
            <span 
            v-if="loading"
            class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            <span>Guardar</span>
        </button>
    </div>
</template>
