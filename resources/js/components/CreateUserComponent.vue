<script setup>
import { ref } from "vue";
import axios from "axios";

const name = ref("");
const password = ref("");
const email = ref("");
const role = ref(0);
const company = ref(0);
const errors = ref([]);
const loading = ref(false);

const props = defineProps({
    roles: {
        type: Array,
    },
    companies: {
        type: Array,
    },
    role: {
        type: Object,
    },
    company: {
        type: Object,
    },
});

if (props.company) {
    role.value = 2;
    company.value = props.company.id;
}

const createUser = async (e) => {
    errors.value = [];
    if (!name.value || name.value.length < 3) {
        errors.value.push("El nombre es obligatorio.");
    }
    if (!password.value) {
        errors.value.push("La contraseña es obligatoria.");
    }
    if (!email.value || !email.value.includes("@")) {
        errors.value.push(
            "El correo es obligatorio y debe ser un correo válido."
        );
    }
    if (role.value === 0) {
        errors.value.push("El rol es obligatorio.");
    }
    if (role.value === 2) {
        if (company.value === 0) {
            errors.value.push("La empresa es obligatoria.");
        }
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
        loading.value = true;
        const response = await axios.post("/api/usuarios", {
            name: name.value,
            password: password.value,
            email: email.value,
            role_id: role.value,
            company_id: company.value,
        });
        window.location.reload();
        document.getElementById("company-modal-button-close").click();
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

const closeModal = () => {
    name.value = "";
    password.value = "";
    email.value = "";
    role.value = 0;
    company.value = 0;
    errors.value = [];
    $(userModal).modal("hide");
};
</script>

<template>
    <div class="modal-header">
        <h5 class="modal-title">Usuario</h5>
        <button
            id="company-modal-button-close"
            type="button"
            class="close"
            @click="closeModal"
            data-dismiss="modal"
            aria-label="Close"
            :disabled="loading"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div class="modal-body">
        <div v-if="errors.length > 0">
            <span>
                <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </span>
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
        <div class="form-group" v-if="props.roles != null">
            <label for="role">Rol</label>
            <select
                class="form-control"
                id="role"
                name="role"
                required
                v-model="role"
                @change="role = $event.target.value"
            >
                <option value="0">Seleccione una opción</option>
                <option v-for="role in props.roles" :value="role.id">
                    {{
                        role.name == "admin"
                            ? "Administrador"
                            : role.name == "regularUser"
                            ? "Usuario"
                            : "Cliente"
                    }}
                </option>
            </select>
        </div>
        <div class="form-group" v-if="role == 2 && props.companies != null">
            <label for="company">Empresa</label>
            <select
                class="form-control"
                id="company"
                name="company"
                required
                v-model="company"
                @change="company = $event.target.value"
            >
                <option value="0">Seleccione una opción</option>
                <option v-for="company in props.companies" :value="company.id">
                    {{ company.name }}
                </option>
            </select>
        </div>
        <div class="form-group">
            <label for="password">Contraseña</label>
            <input
                type="text"
                class="form-control"
                id="password"
                name="password"
                required
                v-model="password"
                @change="password = $event.target.value"
            />
        </div>
    </div>
    <div class="modal-footer">
        <button
            id="company-modal-button-cancel"
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            @click="closeModal"
            :disabled="loading"
        >
            Cancelar
        </button>
        <button
            type="submit"
            @click="createUser"
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
