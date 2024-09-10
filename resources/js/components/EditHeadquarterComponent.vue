<script setup>
import { ref } from "vue";
import axios from "axios";

const name = ref("");
const address = ref("");
const company_id = ref("");
const errors = ref([]);
const loading = ref(false);

const props = defineProps({
    headquarter: {
        type: Object,
    },
    companies: {
        type: Array
    },
    auth: {
        type: Object,
    },
});

name.value = props.headquarter.name;
address.value = props.headquarter.address;
company_id.value = props.headquarter.company_id;

const editHeadquarter = async (e) => {
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
        await axios.patch(
            `/api/sedes/${props.headquarter.id}`,
            {
                name: name.value,
                address: address.value,
                company_id: company_id.value
            }
        );
        loading.value = false;
    } catch (error) {
        loading.value = false;
        if (error.response.status === 400 || error.response.status === 422) {
            errors.value.push(error.response.data.message);
        } else {
            errors.value.push(
                "Ha ocurrido un error al intentar guardar la sede."
            );
        }
    }
};
</script>

<template>
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <p class="mb-0">Perfil de empresa</p>
            <button
                @click="editHeadquarter"
                class="btn btn-primary btn-sm ms-auto"
                :disabled="loading"
            >
                <span
                    v-if="loading"
                    class="spinner-grow spinner-grow-sm"
                    role="status"
                    aria-hidden="true"
                ></span>
                Editar
            </button>
        </div>
    </div>
    <div class="card-body">
        <p class="text-uppercase text-sm">Información general</p>
        <div  v-if="errors.length > 0">
            <p>
                <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </p>
        </div>
        <div class="row">
            <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Nombre</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="name"
                        @change="name = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Dirección</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="address"
                        @change="address = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="company_id">Empresa</label>
                    <select
                        class="form-control"
                        id="company_id"
                        name="company_id"
                        required
                        :disabled="loading || auth.includes('company')"
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
        </div>
    </div>
</template>
