import "./bootstrap";
import { createApp } from "vue";
import CreateCompanyComponent from "./components/CreateCompanyComponent.vue";
import EditCompanyComponent from "./components/EditCompanyComponent.vue";
import CreateAssetComponent from "./components/CreateAssetComponent.vue";
import EditAssetComponent from "./components/EditAssetComponent.vue";
import CreateUserComponent from "./components/CreateUserComponent.vue";
import EditUserComponent from "./components/EditUserComponent.vue";
import ImportExcelAssetComponent from "./components/ImportExcelAssetComponent.vue";
import CreateHeadquarterComponent from "./components/CreateHeadquarterComponent.vue";
import EditHeadquarterComponent from "./components/EditHeadquarterComponent.vue";

import Alpine from "alpinejs";

window.Alpine = Alpine;

Alpine.start();
createApp({})
    .component("create-company-component", CreateCompanyComponent)
    .component("edit-company", EditCompanyComponent)
    .component("create-asset", CreateAssetComponent)
    .component("edit-asset", EditAssetComponent)
    .component("create-user", CreateUserComponent)
    .component("edit-user", EditUserComponent)
    .component("import-excel-asset", ImportExcelAssetComponent)
    .component("create-headquarter", CreateHeadquarterComponent)
    .component("edit-headquarter", EditHeadquarterComponent)
    .mount("#app");
