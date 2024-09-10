<script setup>
import { ref } from "vue";
import axios from "axios";

const name = ref("");
const role = ref(0);
const password = ref("");
const email = ref("");
const company_id = ref(0);
const errors = ref([]);
const loading = ref(false);

const props = defineProps({
    user: {
        type: Object,
    },
    roles: {
        type: Array,
    },
    auth: {
        type: Object
    },
    companies: {
        type: Array
    }
});

if (props.auth.company_id !== null) {
    company_id.value = props.auth.company_id
}

name.value = props.user.name;
role.value = props.user.roles.length > 0 ? props.user.roles[0].id : 0;
email.value = props.user.email;
if (role.value === 2) {
    company_id.value = props.user.company_id;
}

const editUser = async (e) => {
    errors.value = [];
    if (role.value == 0) {
        errors.value.push("El rol es obligatorio.");
    }
    if (!name.value || name.value.length < 3) {
        errors.value.push("El nombre es obligatorio.");
    }
    if (!email.value || !email.value.includes("@")) {
        errors.value.push(
            "El correo es obligatorio y debe ser un correo válido."
        );
    }
    // Password when it is not empty
    if (password.value && password.value.length < 8) {
        errors.value.push(
            "La contraseña es obligatoria y debe tener al menos 8 caracteres."
        );
    }
    if (errors.value.length > 0) {
        return;
    }
    try {
        loading.value = true;
        const response = await axios.patch(
            `/api/usuarios/${props.user.id}`,
            {
                role: role.value,
                name: name.value,
                password: password.value,
                company_id: company_id.value,
                email: email.value,
            }
        );
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
</script>

<template>
    <div class="card-header pb-0">
        <div class="d-flex align-items-center">
            <p class="mb-0">Perfil de {{ user.name }}</p>
            <button
                @click="editUser"
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
        <p class="text-uppercase text-sm">Información personal</p>
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
            <div class="col-md-6" v-if="props.auth.company_id === null">
                <div class="form-group" >
                    <label for="role" class="form-control-label">Rol</label>
                    <select
                        class="form-control"
                        id="role"
                        name="role"
                        required
                        :disabled="loading"
                        v-model="role"
                        @change="role = $event.target.value"
                    >
                        <option value="0">Seleccione un rol</option>
                        <option
                            v-for="rol in roles"
                            :key="rol.id"
                            :value="rol.id"
                        >
                            {{ 
    rol.name == 'admin' ? 'Administrador' :
        rol.name == 'regularUser' ? 'Usuario' : 'Cliente'
                            }}
                        </option>
                    </select>
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group" >
                    <label for="company_id" class="form-control-label">Empresa</label>
                    <select
                        class="form-control"
                        id="company_id"
                        name="company_id"
                        required
                        :disabled="loading || props.auth.company_id !== null || role != 2"
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
            <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Correo</label
                    >
                    <input
                        class="form-control"
                        type="email"
                        :disabled="loading"
                        v-model="email"
                        @change="email = $event.target.value"
                    />
                </div>
            </div>
            <div class="col-md-6">
                <div class="form-group">
                    <label for="example-text-input" class="form-control-label"
                        >Contraseña</label
                    >
                    <input
                        class="form-control"
                        type="password"
                        autocomplete="new-password"
                        :disabled="loading"
                        v-model="password"
                        @change="password = $event.target.value"
                    />
                </div>
            </div>
        </div>
    </div>
</template>
