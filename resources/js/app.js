import "./bootstrap";

// Alpine.js — mantener para modales y componentes interactivos
import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();

// Bootstrap 5 JS — disponible globalmente para modales, dropdowns, etc.
import * as bootstrap from "bootstrap";
window.bootstrap = bootstrap;

// Chart.js — disponible globalmente para gráficos
import { Chart, registerables } from "chart.js";
Chart.register(...registerables);
window.Chart = Chart;
