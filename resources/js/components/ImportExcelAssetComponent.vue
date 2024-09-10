<script setup>
import { ref } from "vue";
import axios from "axios";

const file = ref("");
const errors = ref([]);
const errorsImport = ref([]);
const loading = ref(false);
const headersFile = ref([]);
const dataFile = ref([]);

const importAsset = async (e) => {
    errors.value = [];
    try {
        if (!file.value) {
            errors.value.push("El archivo es obligatorio.");
        }
        if (errors.value.length > 0) {
            return;
        }
        loading.value = true;
        const formData = new FormData();
        formData.append("file", file.value);
        const response = await axios.post("/api/import-excel", formData, {
            headers: {
                "Content-Type": "multipart/form-data",
            },
        });
        headersFile.value = response.data.headers;
        dataFile.value = response.data.data;
        document.getElementById("company-modal").classList.add("modal-lg");
        file.value = "";
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

//unir headers y data, convertir en array de objetos
const sendData = async () => {
    try {
        let data = [];
        loading.value = true;
        dataFile.value.forEach((row) => {
            let obj = {};
            headersFile.value.forEach((header, index) => {
                if (header === "estado") {
                    obj[header] =
                        row[index] == "B" ? "Bueno" : "M" ? "Malo" : "Regular";
                } else if (header === "funcionamiento") {
                    obj[header] =
                        row[index].toLowerCase() == "si"
                            ? "Activo"
                            : "Inactivo";
                } else {
                    obj[header] = row[index];
                }
            });
            data.push(obj);
        });
        const response = await axios.post("/api/import", {
            data: data,
        });
        if (response.data.errors.length > 0) {
            errorsImport.value = response.data.errors;
            Swal.fire("¡Éxito!", "Datos importados.", "success");
        } else {
            errorsImport.value = [];
            headersFile.value = [];
            dataFile.value = [];
            errors.value = [];
            file.value = "";
            $(companyModal).modal("hide");
            Swal.fire(
                "¡Éxito!",
                "Datos importados correctamente.",
                "success"
            ).then((result) => {
                location.reload();
            });
        }
        loading.value = false;
    } catch (error) {
        loading.value = false;
        console.log(error);
    }
};

const closeModal = () => {
    document.getElementById("company-modal").classList.remove("modal-lg");
    headersFile.value = [];
    dataFile.value = [];
    errorsImport.value = [];
    errors.value = [];
    file.value = "";
    loading.value = false;
    $(companyModal).modal("hide");
};

const finalize = () => {
    document.getElementById("company-modal").classList.remove("modal-lg");
    headersFile.value = [];
    dataFile.value = [];
    errorsImport.value = [];
    errors.value = [];
    file.value = "";
    loading.value = false;
    $(companyModal).modal("hide");
    Swal.fire("¡Éxito!", "Datos importados correctamente.", "success").then(
        (result) => {
            location.reload();
        }
    );
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
            @click="closeModal"
            :disabled="loading"
        >
            <span aria-hidden="true">&times;</span>
        </button>
    </div>
    <div
        class="modal-body"
        v-if="headersFile.length == 0 && dataFile.length == 0"
    >
        <div v-if="errors.length > 0">
            <span>
                <b>Por favor, corrija el(los) siguiente(s) error(es):</b>
                <ul>
                    <li v-for="error in errors">{{ error }}</li>
                </ul>
            </span>
        </div>
        <div class="form-group">
            <label for="name">Archivo</label>
            <div class="row">
                <div class="col-8">
                    <input
                        type="file"
                        class="form-control"
                        style="height: 40px"
                        id="file"
                        name="file"
                        @change="file = $event.target.files[0]"
                    />
                </div>
                <div class="col-3 d-flex flex-row align-items-center">
                    <button
                        type="submit"
                        @click="importAsset"
                        class="btn btn-primary m-0"
                        :disabled="loading"
                    >
                        <span
                            v-if="loading"
                            class="spinner-grow spinner-grow-sm"
                            role="status"
                            aria-hidden="true"
                        ></span>
                        <span>importar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
    <div
        class="modal-body overflow-scroll"
        v-if="headersFile.length > 0 && dataFile.length > 0"
    >
        <div v-if="errorsImport.length > 0">
            <span>
                <b>Errores en la importación:</b>
                <ul>
                    <li v-for="error in errorsImport">{{ error }}</li>
                </ul>
            </span>
        </div>
        <table v-if="errorsImport.length == 0" class="table table-bordered">
            <thead>
                <tr>
                    <th v-for="header in headersFile">{{ header }}</th>
                </tr>
            </thead>
            <tbody>
                <tr v-for="row in dataFile">
                    <td v-for="cell in row">{{ cell }}</td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="modal-footer">
        <button
            id="company-modal-button-cancel"
            type="button"
            class="btn btn-secondary"
            data-dismiss="modal"
            :disabled="loading"
            @click="closeModal"
            v-if="errorsImport.length == 0"
        >
            Cancelar
        </button>
        <button
            id="company-modal-button-cancel"
            type="button"
            class="btn btn-success"
            data-dismiss="modal"
            @click="sendData"
            :disabled="loading"
            v-if="
                headersFile.length > 0 &&
                dataFile.length > 0 &&
                errorsImport.length == 0
            "
        >
            <span
                v-if="loading"
                class="spinner-grow spinner-grow-sm"
                role="status"
                aria-hidden="true"
            ></span>
            <span>Importar datos</span>
        </button>
        <button
            id="company-modal-button-cancel"
            type="button"
            class="btn btn-info"
            data-dismiss="modal"
            :disabled="loading"
            @click="finalize"
            v-if="errorsImport.length > 0"
        >
            Finalizar
        </button>
    </div>
</template>
