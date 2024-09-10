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

const createCompany = async (e) => {
    errors.value = [];
    if (nit.value == "" || isNaN(nit.value) || nit.value.length < 8) {
        errors.value.push('El nit es obligatorio y debe ser un número.');
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
        errors.value.push("El correo es obligatorio y debe ser un correo válido.");
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
    loading.value = true;
 const response = await axios
        .post("/api/empresas", {
            nit: nit.value,
            name: name.value,
            address: address.value,
            phone: phone.value,
            email: email.value,
        })   
        window.location.reload();
        document.getElementById("company-modal-button-close").click();
        loading.value = false;
    
} catch (error) {
    loading.value = false;
    if (error.response.status === 400 || error.response.status === 422) {
        errors.value.push(error.response.data.message);
    } else {
        errors.value.push("Ha ocurrido un error al intentar guardar la empresa.");
    }
}
};
</script>

<template>
    <div class="modal-header">
        <h5 class="modal-title">Empresa</h5>
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
            <label for="nit">Nit</label>
            <input
                type="text"
                class="form-control"
                id="nit"
                name="nit"
                required
                v-model="nit"
                @change="nit = $event.target.value"
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
            <label for="phone">Teléfono</label>
            <input
                type="text"
                class="form-control"
                id="phone"
                name="phone"
                required
                v-model="phone"
                @change="phone = $event.target.value"
            />
        </div>
        <div class="form-group">
            <label for="email">Correo</label>
            <input
                type="text"
                class="form-control"
                id="email"
                name="email"
                required
                v-model="email"
                @change="email = $event.target.value"
            />
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
        <button type="submit" @click="createCompany" class="btn btn-primary" :disabled="loading">
            <span 
            v-if="loading"
            class="spinner-grow spinner-grow-sm" role="status" aria-hidden="true"></span>
            <span>Guardar</span>
        </button>
    </div>
</template>
