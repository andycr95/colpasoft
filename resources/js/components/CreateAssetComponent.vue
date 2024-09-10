<script setup>
import { ref } from "vue";
import axios from "axios";

const code = ref("");
const name = ref("");
const area = ref("");
const quantity = ref("");
const brand = ref("");
const model = ref("");
const serie = ref("");
const state = ref(0);
const category_id = ref(0);
const company_id = ref(0);
const headquarter_id = ref(0);
const status = ref(0);
const performance = ref("");
const usefulLife = ref("");
const amount = ref("");
const age = ref("");
const errors = ref([]);
const headquarters = ref([]);
const loading = ref(false);


const props = defineProps({
    categories: {
        type: Array,
    },
    companies: {
        type: Array,
    },
    company: {
        type: Object,
    },
    role: {
        type: Array,
    },
    auth: {
        type: Object,
    }
});

if (props.role.includes('company') ) {
    company_id.value = props.auth.company_id;
    let hqs = props.companies.filter((c) => c.id == company_id.value)[0].headquarters;    
    headquarters.value = hqs;
}

const createAsset = async (e) => {
    errors.value = [];
    if (code.value == "" || isNaN(code.value)) {
        errors.value.push("El código es obligatorio y debe ser un número.");
    }
    if (!name.value || name.value.length < 3) {
        errors.value.push("El nombre es obligatorio.");
    }
    if (!area.value) {
        errors.value.push("La area es obligatoria.");
    }
    if (!quantity.value) {
        errors.value.push("La cantidad es obligatoria.");
    }
    if (!company_id.value) {
        errors.value.push("La empresa es obligatoria.");
    }
    if (!headquarter_id.value) {
        errors.value.push("La sede es obligatoria.");
    }
    if (!category_id.value) {
        errors.value.push("La categoria es obligatoria.");
    }
    // Estado
    if (state.value == 0) {
        errors.value.push("El estado es obligatorio.");
    }
    // Estado
    if (status.value == 0) {
        errors.value.push("El funcionamiento es obligatorio.");
    }
    if (!performance.value || isNaN(performance.value)) {
        errors.value.push("El rendimiento es obligatorio y debe ser un número.");
    }
    if (!age.value || isNaN(age.value)) {
        errors.value.push("La edad es obligatoria y debe ser un número.");
    }
    if (!brand.value) {
        errors.value.push("La marca es obligatoria.");
    }
    if (!model.value) {
        errors.value.push("El modelo es obligatorio.");
    }
    if (!serie.value) {
        errors.value.push("La serie es obligatoria.");
    }
    if (!usefulLife.value) {
        errors.value.push("La vida util es obligatoria y debe ser un número.");
    }
    if (!amount.value || isNaN(amount.value)) {
        errors.value.push("El valor es obligatorio y debe ser un número.");
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
        loading.value = true;
        const response = await axios.post("/api/activos", {
            code: code.value,
            name: name.value,
            area: area.value,
            quantity: quantity.value,
            brand: brand.value,
            model: model.value,
            serie: serie.value,
            state: state.value,
            category_id: category_id.value,
            company_id: company_id.value,
            headquarter_id: headquarter_id.value,
            status: status.value,
            performance: performance.value,
            usefulLife: usefulLife.value,
            amount: amount.value,
            age: age.value,
        });
        window.location.reload();
    } catch (error) {
        loading.value = false;
        if (error.response.status === 400 || error.response.status === 422) {
            errors.value.push(error.response.data.message);
        } else {
            errors.value.push(
                "Ha ocurrido un error al intentar guardar la empresa."
            );
        }
    }
};

// Funcion para cerrar el modal
const closeModal = () => {
    $(companyModal).modal('hide');
};

//Select headquarter
const selectCompany = (e) => {
    let hqs = props.companies.filter((c) => c.id == e.target.value)[0].headquarters;
    headquarters.value = hqs;
    
}

</script>

<template>
    <div class="modal-header">
        <h5 class="modal-title">Activo</h5>
        <button
            id="company-modal-button-close"
            type="button"
            class="close"
            data-dismiss="modal"
            aria-label="Close"
            @click="closeModal()"
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
        <div class="d-flex flex-row justify-content-between gap-3">
            <div class="w-100">
                <div class="form-group">
                    <label for="code">Código</label>
                    <input
                        type="text"
                        class="form-control"
                        id="code"
                        name="code"
                        required
                        v-model="code"
                        @change="code = $event.target.value"
                    />
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
                    <label for="category_id">Categoria</label>
                    <select
                        class="form-control"
                        id="category_id"
                        name="category_id"
                        required
                        v-model="category_id"
                        @change="category_id = $event.target.value"
                    >
                        <option value="0">Seleccione una categoria</option>
                        <option
                            v-for="category in categories"
                            :key="category.id"
                            :value="category.id"
                        >
                            {{ category.name }}
                        </option>
                    </select>
                </div>
                <div class="form-group" v-if="!role.includes('company')">
                    <label for="company_id">Empresa</label>
                    <select
                        class="form-control"
                        id="company_id"
                        name="company_id"
                        required
                        v-model="company_id"
                        @change="selectCompany"
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
                <div class="form-group">
                    <label for="headquarter_id">Sede</label>
                    <select
                        class="form-control"
                        id="headquarter_id"
                        name="headquarter_id"
                        required
                        v-model="headquarter_id"
                        @change="headquarter_id = $event.target.value"
                    >
                        <option value="0">Seleccione una sede</option>
                        <option
                            v-for="headquarter in headquarters"
                            :key="headquarter.id"
                            :value="headquarter.id"
                        >
                            {{ headquarter.name }}
                        </option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="area">Area</label>
                    <input
                        type="text"
                        class="form-control"
                        id="area"
                        name="area"
                        required
                        v-model="area"
                        @change="area = $event.target.value"
                    />
                </div>
            </div>
            <div class="w-100">
                <div class="form-group">
                    <label for="quantity">Cantidad</label>
                    <input
                        type="text"
                        class="form-control"
                        id="quantity"
                        name="quantity"
                        required
                        v-model="quantity"
                        @change="quantity = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="brand">Marca</label>
                    <input
                        type="text"
                        class="form-control"
                        id="brand"
                        name="brand"
                        required
                        v-model="brand"
                        @change="brand = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="model">Modelo</label>
                    <input
                        type="text"
                        class="form-control"
                        id="model"
                        name="model"
                        required
                        v-model="model"
                        @change="model = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="serie">Serie</label>
                    <input
                        type="text"
                        class="form-control"
                        id="serie"
                        name="serie"
                        required
                        v-model="serie"
                        @change="serie = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="state">Estado</label>
                    <select
                        class="form-control"
                        id="state"
                        name="state"
                        required
                        v-model="state"
                        @change="state = $event.target.value"
                    >
                        <option value="0">Seleccione un estado</option>
                        <option value="Bueno">Bueno</option>
                        <option value="Regular">Regular</option>
                        <option value="Malo">Malo</option>
                    </select>
                </div>
            </div>
            <div class="w-100">
                <div class="form-group">
                    <label for="status">Funcionamiento</label>
                    <select
                        class="form-control"
                        id="status"
                        name="status"
                        required
                        v-model="status"
                        @change="status = $event.target.value"
                    >
                        <option value="0">Seleccione una opción</option>
                        <option value="Activo">Activo</option>
                        <option value="Inactivo">Inactivo</option>
                    </select>
                </div>
                <div class="form-group">
                    <label for="performance">Rendimiento</label>
                    <input
                        type="text"
                        class="form-control"
                        id="performance"
                        name="performance"
                        required
                        v-model="performance"
                        @change="performance = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="age">Edad</label>
                    <input
                        type="text"
                        class="form-control"
                        id="age"
                        name="age"
                        required
                        v-model="age"
                        @change="age = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="usefulLife">Vida Util</label>
                    <input
                        type="text"
                        class="form-control"
                        id="usefulLife"
                        name="usefulLife"
                        required
                        v-model="usefulLife"
                        @change="usefulLife = $event.target.value"
                    />
                </div>
                <div class="form-group">
                    <label for="amount">Valor</label>
                    <input
                        type="text"
                        class="form-control"
                        id="amount"
                        name="amount"
                        required
                        v-model="amount"
                        @change="amount = $event.target.value"
                    />
                    <span style="font-size: 12px;">El valor debe ser sin comas ni puntos.</span>
                </div>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button
            id="company-modal-button-cancel"
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            :disabled="loading"
            @click="closeModal"
        >
            Cancelar
        </button>
        <button
            type="submit"
            @click="createAsset"
            class="btn btn-primary"
            :disabled="loading"
        >
            <span
                v-if="loading"
                class="spinner-grow spinner-grow-sm"
                role="status"
                aria-hidden="true"
            ></span>
            <span>Guardar</span>
        </button>
    </div>
</template>
