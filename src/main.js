// Bootstrap
import "@/styles/bootstrap.css";
import "bootstrap/dist/js/bootstrap.js";
import "bootstrap-icons/font/bootstrap-icons.css";

// Toast
import Toast from "vue-toastification";
import "vue-toastification/dist/index.css";

// Vue
import { createApp } from "vue";
import App from "./App.vue";

const app = createApp(App);

//Toast Config
const options = {
	position: "top-right",
	timeout: 5000,
	closeOnClick: true,
	pauseOnFocusLoss: true,
	pauseOnHover: true,
	draggable: true,
	draggablePercent: 0.6,
	showCloseButtonOnHover: false,
	hideProgressBar: false,
	closeButton: "button",
	icon: true,
	rtl: false,
};

app.use(Toast, options);

app.mount("#app");
