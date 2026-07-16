<div x-data="{
    open: false,
    scale: parseInt(localStorage.getItem('nc-font-scale') || '100'),
    updateScale() {
        document.documentElement.style.fontSize = this.scale + '%';
        localStorage.setItem('nc-font-scale', this.scale);
    },
    resetScale() {
        this.scale = 100;
        this.updateScale();
    }
}" class="nc-accessibility-widget">
    <!-- Botón Activador Flotante -->
    <button @click="open = !open" 
            class="nc-accessibility-btn" 
            :aria-expanded="open.toString()" 
            aria-label="Ajustar tamaño de fuente de la página"
            title="Ajustar tamaño de fuente">
        <i class="bi bi-type-size"></i>
    </button>

    <!-- Panel de Control Deslizante (Tipo Volumen) -->
    <div x-show="open" 
         @click.outside="open = false" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 translate-y-2 scale-95"
         x-transition:enter-end="opacity-100 translate-y-0 scale-100"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0 scale-100"
         x-transition:leave-end="opacity-0 translate-y-2 scale-95"
         class="nc-accessibility-panel shadow border"
         style="display: none;">
        
        <div class="d-flex justify-content-between align-items-center mb-2">
            <span class="small fw-bold text-dark text-nowrap">Escala de fuente</span>
            <span class="badge bg-light text-dark font-monospace" x-text="scale + '%'"></span>
        </div>

        <div class="d-flex align-items-center gap-2">
            <!-- Botón Reducir -->
            <button @click="if(scale > 100) { scale = Math.max(100, parseInt(scale) - 5); updateScale(); }" 
                    class="btn btn-sm btn-light p-1 border-0" 
                    title="Reducir fuente" 
                    aria-label="Reducir fuente">
                <i class="bi bi-dash-lg" style="font-size: 0.8rem;"></i>
            </button>
            
            <!-- Barra Deslizante (Slider) -->
            <input type="range" 
                   min="100" 
                   max="200" 
                   step="5" 
                   x-model="scale" 
                   @input="updateScale()" 
                   class="form-range flex-grow-1" 
                   aria-label="Barra de volumen para el tamaño de fuente"
                   style="height: 6px;">

            <!-- Botón Aumentar -->
            <button @click="if(scale < 200) { scale = Math.min(200, parseInt(scale) + 5); updateScale(); }" 
                    class="btn btn-sm btn-light p-1 border-0" 
                    title="Aumentar fuente" 
                    aria-label="Aumentar fuente">
                <i class="bi bi-plus-lg" style="font-size: 0.8rem;"></i>
            </button>
        </div>

        <hr class="my-2">

        <!-- Botón Restablecer -->
        <button @click="resetScale()" class="btn btn-outline-dark btn-sm w-100 py-1" style="font-size: 0.75rem;">
            Restablecer (100%)
        </button>
    </div>
</div>
