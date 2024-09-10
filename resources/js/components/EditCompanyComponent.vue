<script setup>
import { ref } from "vue";
import axios from "axios";

const nit = ref("");
const name = ref("");
const address = ref("");
const phone = ref("");
const email = ref("");
const errors = ref([]);
const loading = ref(false);

const props = defineProps({
    company: {
        type: Object,
    },
});

name.value = props.company.name;
nit.value = props.company.nit;
address.value = props.company.address;
phone.value = props.company.phone;
email.value = props.company.email;

const editCompany = async (e) => {
    errors.value = [];
    if (nit.value == "" || isNaN(nit.value) || nit.value.length < 8) {
        errors.value.push("El nit es obligatorio y debe ser un número.");
    }
    if (!name.value || name.value.length < 3) {
        errors.value.push("El nombre es obligatorio.");
    }
    if (!address.value) {
        errors.value.push("La dirección es obligatoria.");
    }
    if (!phone.value) {
        errors.value.push("El teléfono es obligatorio.");
    }
    if (!email.value || !email.value.includes("@")) {
        errors.value.push(
            "El correo es obligatorio y debe ser un correo válido."
        );
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
        loading.value = true;
        const response = await axios.patch(
            `/api/empresas/${props.company.id}`,
            {
                nit: nit.value,
                name: name.value,
                address: address.value,
                phone: phone.value,
                email: email.value,
            }
        );
        loading.value = false;
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
</script>

<template>
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <p class="mb-0">Perfil de empresa</p>
            <button
                @click="editCompany"
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
                        >Nit</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        value="lucky.jesse"
                        :disabled="loading"
                        v-model="nit"
                        @change="nit = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Correo</label
                    >
                    <input
                        class="form-control"
                        type="email"
                        value="jesse@example.com"
                        :disabled="loading"
                        v-model="email"
                        @change="email = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Teléfono</label
                    >
                    <input
                        class="form-control"
                        type="text"
                        value="Jesse"
                        :disabled="loading"
                        v-model="phone"
                        @change="phone = $event.target.value"
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
                        value="Lucky"
                        :disabled="loading"
                        v-model="address"
                        @change="address = $event.target.value"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
