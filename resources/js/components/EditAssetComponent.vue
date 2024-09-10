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
    asset: {
        type: Object,
    },
    categories: {
        type: Array
    },
    companies: {
        type: Array
    },
    auth: {
        type: Array,
    },
});

code.value = props.asset.code;
name.value = props.asset.name;
area.value = props.asset.area;
quantity.value = props.asset.quantity;
brand.value = props.asset.brand;
model.value = props.asset.model;
serie.value = props.asset.serie;
state.value = props.asset.state;
category_id.value = props.asset.category_id;
company_id.value = props.asset.company_id;
status.value = props.asset.status;
performance.value = props.asset.performance;
usefulLife.value = props.asset.usefulLife;
amount.value = props.asset.amount;
age.value = props.asset.age;
headquarter_id.value = props.asset.headquarter_id == null ? 0 : props.asset.headquarter_id;
if (company_id.value != 0) {
    console.log(company_id.value);
    let hqs = props.companies.filter((c) => c.id == company_id.value)[0].headquarters;    
    headquarters.value = hqs;
}

let salv = 0;
let OneR = 0;
let EV = 0;
let BC = 0;
let OneD = 0;
let VA = 0;

const setValues = () => {
    salv = performance.value / 1000;
    OneR = parseFloat(1 - salv).toFixed(2);
    EV = age.value / usefulLife.value;
    BC = parseFloat((1 - salv) * EV).toFixed(4);
    OneD = parseFloat(1 - BC).toFixed(4);
    VA = parseFloat(amount.value * OneD).toFixed(0);
}

setValues();

const editAsset = async (e) => {
    errors.value = [];
    if (code.value == "") {
        errors.value.push("El código es obligatorio y debe ser un número.");
    }
    if (!name.value || name.value.length < 3) {
        errors.value.push("El nombre es obligatorio.");
    }
    if (!area.value) {
        errors.value.push("La area es obligatoria.");
    }
    if (!headquarter_id.value || headquarter_id.value == 0) {
        errors.value.push("La sede es obligatoria.");
    }
    if (!quantity.value) {
        errors.value.push("La cantidad es obligatoria.");
    }
    if (!company_id.value) {
        errors.value.push("La empresa es obligatoria.");
    }
    if (!category_id.value) {
        errors.value.push("La categoria es obligatoria.");
    }
    // Estado
    if (state.value == 0) {
        errors.value.push("El estado es obligatorio.");
    }
    if (!performance.value || isNaN(performance.value) || performance.value.toString().includes('.') || performance.value.toString().includes(',') || performance.value > 100) {
        errors.value.push("El rendimiento es obligatorio y debe ser un número no mayor a 100.");
    }
    if (!age.value || isNaN(age.value) || age.value.toString().includes('.') || age.value.toString().includes(',')) {
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
    if (!amount.value || isNaN(amount.value) || amount.value.toString().includes('.') || amount.value.toString().includes(',')) {
        errors.value.push("El valor es obligatorio y debe ser un número.");
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
        loading.value = true;
        const response = await axios.patch(
            `/api/activos/${props.asset.id}`,
            {
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
            }
        );
        setValues();
        loading.value = false;
    } catch (error) {
        loading.value = false;
        if (error.response.status === 400 || error.response.status === 422) {
            errors.value.push(error.response.data.message);
        } else {
            errors.value.push(
                "Ha ocurrido un error al intentar guardar el activo"
            );
        }
    }
};

const toggleStatusAsset = async (e) => {
    try {
        loading.value = true;
        const response = await axios.patch(
            `/api/activos/estado/${props.asset.id}`,
            {
            status: status.value == 'Activo' ? 'Inactivo' : 'Activo',
            }
        );
        window.location.reload();
    } catch (error) {
        loading.value = false;
        if (error.response.status === 400 || error.response.status === 422) {
            errors.value.push(error.response.data.message);
        } else {
            errors.value.push(
                "Ha ocurrido un error al intentar guardar el activo"
            );
        }
    }
};

//Select headquarter
const selectCompany = (e) => {
    company_id.value = e.target.value;
    let hqs = props.companies.filter((c) => c.id == e.target.value)[0].headquarters;
    headquarters.value = hqs;
    
}

const uploadFile = (e) => {
    console.log(e.target.files[0]);
    const file = e.target.files[0];
    const formData = new FormData();
    formData.append("file", file);
    loading.value = true;
    // Add asset id to form data
    formData.append("asset_id", props.asset.id);
    axios
        .post(`/api/subir-archivo`, formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        })
        .then((response) => {
            window.location.reload();
        })
        .catch((error) => {
            loading.value = false;
            console.log(error);
        });
};
</script>

<template>
    <div class="card-header pb-0">
        <div class="d-flex align-items-center justify-content-between">
            <p class="mb-0">Perfil de activo</p>
            <div class="d-flex gap-3">
                <button
                @click="editAsset"
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
                <button
                v-if="status == 'Activo'"
                    @click="toggleStatusAsset"
                    class="btn btn-danger btn-sm ms-auto"
                    :disabled="loading"
                >
                    <span
                        v-if="loading"
                        class="spinner-grow spinner-grow-sm"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    Dar de baja
                </button>
                <!-- Input para Subir archivo -->
                <div class="btn btn-success btn-sm ms-auto "
                    :disabled="loading"
                >
                    <span
                        v-if="loading"
                        class="spinner-grow spinner-grow-sm"
                        role="status"
                        aria-hidden="true"
                    ></span>
                    <input :disabled="loading" type="file" id="file" style="display: none" @change="uploadFile"/>
                    <label for="file" class="text-white m-0">
                        Subir archivo
                    </label>
                </div>
            </div>
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
            <div class="col-xs-6 col-md-4">
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
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="code" class="form-control-label"
                        >Código</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="code"
                        @change="code = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Cantidad</label
                    >
                    <input
                        class="form-control"
                        type="number"
                        :disabled="loading"
                        v-model="quantity"
                        @change="quantity = $event.target.value"
                    />
                    <span style="font-size: 12px;">Ingrese un valor sin comas ni puntos.</span>
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="area" class="form-control-label"
                        >Area</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="area"
                        @change="area = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="category_id" class="form-control-label"
                        >Categoria</label
                    >
                    <select
                        class="form-control"
                        id="category_id"
                        name="category_id"
                        required
                        :disabled="loading"
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
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="category_id" class="form-control-label"
                        >Empresa</label>
                    <select
                        class="form-control"
                        id="company_id"
                        name="company_id"
                        required
                        :disabled="loading || auth.includes('company')"
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
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="category_id" class="form-control-label"
                        >Sede</label
                    >
                    <select
                        class="form-control"
                        id="headquarter_id"
                        name="headquarter_id"
                        required
                        :disabled="loading"
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
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="brand" class="form-control-label"
                        >Marca</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="brand"
                        @change="brand = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="model" class="form-control-label"
                        >Modelo</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="model"
                        @change="model = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >Serie</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="serie"
                        @change="serie = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="category_id" class="form-control-label"
                        >Estado</label
                    >
                    <select
                        class="form-control"
                        id="state"
                        name="state"
                        required
                        :disabled="loading"
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
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="performance" class="form-control-label"
                        >Rendimiento</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="performance"
                        @change="performance = $event.target.value"
                    />
                    <span style="font-size: 12px;">Ingrese un valor sin comas ni puntos.</span>
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="age" class="form-control-label"
                        >Edad</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="age"
                        @change="age = $event.target.value"
                    />
                    <span style="font-size: 12px;">Ingrese un valor sin comas ni puntos.</span>
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="usefulLife" class="form-control-label"
                        >Vida util</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="usefulLife"
                        @change="usefulLife = $event.target.value"
                    />
                    <span style="font-size: 12px;">Ingrese un valor sin comas ni puntos.</span>
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="amount" class="form-control-label"
                        >Valor</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        :disabled="loading"
                        v-model="amount"
                        @change="amount = $event.target.value"
                    />
                    <span style="font-size: 12px;">Ingrese un valor sin comas ni puntos.</span>
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >Porcentaje de salvamento</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        disabled
                        v-model="salv"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >1-R</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        disabled
                        v-model="OneR"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >E/V</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        disabled
                        v-model="EV"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >B*C</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        disabled
                        v-model="BC"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >1-D</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        disabled
                        v-model="OneD"
                    />
                </div>
            </div>
            <div class="col-xs-6 col-md-4">
                <div class="form-group">
                    <label for="serie" class="form-control-label"
                        >Valor actual - Depresiado</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        disabled
                        v-model="VA"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
