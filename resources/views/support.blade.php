<x-dynamic-component :component="auth()->check() ? 'app-layout' : 'public-layout'">
    <div class="container py-4">
        {{-- Banner Principal --}}
        <div class="p-4 p-md-5 mb-4 rounded-3 text-white shadow-sm" style="background: linear-gradient(135deg, var(--nc-primary) 0%, #2d6a4f 100%);">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <span class="badge bg-white text-dark mb-2 px-3 py-2 fw-semibold"><i class="bi bi-headset me-1"></i> Atenci&oacute;n al Cliente</span>
                    <h1 class="display-6 fw-bold mb-2">Centro de Ayuda y Soporte</h1>
                    <p class="lead mb-0 opacity-90">&iquest;Tienes preguntas o necesitas asistencia? Estamos aqu&iacute; para ayudarte a aprovechar NutriChef al m&aacute;ximo.</p>
                </div>
                <div class="col-lg-4 text-center text-lg-end mt-3 mt-lg-0">
                    <i class="bi bi-patch-question opacity-25" style="font-size: 5rem;"></i>
                </div>
            </div>
        </div>

        {{-- Canales de Contacto Directo --}}
        <div class="row g-4 mb-5">
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-envelope-at fs-3 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Correo Electr&oacute;nico</h5>
                    <p class="text-muted small mb-3">Respuesta garantizada en menos de 24 horas.</p>
                    <a href="mailto:soporte@nutrichef.com" class="fw-semibold text-decoration-none text-dark">soporte@nutrichef.com</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-whatsapp fs-3 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-1">WhatsApp &amp; Tel&eacute;fono</h5>
                    <p class="text-muted small mb-3">Atenci&oacute;n inmediata de nuestro equipo.</p>
                    <a href="https://wa.me/51987654321" target="_blank" class="fw-semibold text-decoration-none text-dark">+51 987 654 321</a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100 text-center p-4">
                    <div class="rounded-circle bg-light d-inline-flex align-items-center justify-content-center mx-auto mb-3" style="width: 60px; height: 60px;">
                        <i class="bi bi-clock-history fs-3 text-success"></i>
                    </div>
                    <h5 class="fw-bold mb-1">Horario de Atenci&oacute;n</h5>
                    <p class="text-muted small mb-1">Lunes a Viernes</p>
                    <span class="fw-semibold text-dark">8:00 AM - 6:00 PM (UTC-5)</span>
                </div>
            </div>
        </div>

        <div class="row g-4">
            {{-- Formulario de Mensaje al Soporte --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4" x-data="{ sent: false, loading: false }">
                        <h5 class="fw-bold mb-3"><i class="bi bi-send me-2 text-success"></i>Env&iacute;anos un Mensaje</h5>
                        <p class="text-muted small mb-4">Completa el formulario y un representante de soporte se pondr&aacute; en contacto contigo.</p>

                        <div x-show="sent" class="alert alert-success border-0 shadow-sm mb-0">
                            <i class="bi bi-check-circle-fill me-2"></i>
                            <strong>&iexcl;Mensaje enviado con &eacute;xito!</strong> Gracias por contactarnos, te responderemos a la brevedad.
                        </div>

                        <form x-show="!sent" @submit.prevent="loading = true; setTimeout(() => { loading = false; sent = true; }, 800)">
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Nombre Completo</label>
                                <input type="text" class="form-control" value="{{ auth()->user()?->name }}" required placeholder="Tu nombre">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Correo de Contacto</label>
                                <input type="email" class="form-control" value="{{ auth()->user()?->email }}" required placeholder="ejemplo@correo.com">
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Motivo de Consulta</label>
                                <select class="form-select" required>
                                    <option value="">Selecciona un motivo</option>
                                    <option value="tecnico">Problema t&eacute;cnico o error</option>
                                    <option value="nutricion">Consulta sobre asesor&iacute;a nutricional</option>
                                    <option value="cuenta">Gesti&oacute;n de cuenta o acceso</option>
                                    <option value="sugerencia">Sugerencias o comentarios</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-semibold">Detalle del Mensaje</label>
                                <textarea class="form-control" rows="4" required placeholder="Describe tu consulta con detalle..."></textarea>
                            </div>
                            <button type="submit" class="btn btn-dark w-100 py-2" :disabled="loading">
                                <span x-show="!loading"><i class="bi bi-send me-1"></i> Enviar Mensaje a Soporte</span>
                                <span x-show="loading" style="display: none;"><span class="spinner-border spinner-border-sm me-1"></span> Enviando...</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Preguntas Frecuentes (FAQ) --}}
            <div class="col-lg-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body p-4">
                        <h5 class="fw-bold mb-3"><i class="bi bi-question-circle me-2 text-success"></i>Preguntas Frecuentes</h5>
                        <p class="text-muted small mb-4">Respuestas r&aacute;pidas a las dudas m&aacute;s comunes de nuestros usuarios.</p>

                        <div class="accordion accordion-flush" id="faqAccordion">
                            <div class="accordion-item border-bottom">
                                <h2 class="accordion-header" id="faqHeadingOne">
                                    <button class="accordion-button collapsed fw-semibold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseOne">
                                        &iquest;C&oacute;mo consulto a un nutricionista?
                                    </button>
                                </h2>
                                <div id="faqCollapseOne" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Puedes acceder a la secci&oacute;n de <strong>Mensajes</strong> desde el men&uacute; lateral para iniciar una conversaci&oacute;n con un nutricionista asignado y recibir recomendaciones personalizadas.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item border-bottom">
                                <h2 class="accordion-header" id="faqHeadingTwo">
                                    <button class="accordion-button collapsed fw-semibold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseTwo">
                                        &iquest;C&oacute;mo generar mi lista de compras?
                                    </button>
                                </h2>
                                <div id="faqCollapseTwo" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Al visualizar cualquier receta, encontrar&aacute;s el bot&oacute;n para agregar los ingredientes a tu lista de compras. Tambi&eacute;n puedes crear y editar tus listas en la secci&oacute;n <strong>Lista de Compras</strong>.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item border-bottom">
                                <h2 class="accordion-header" id="faqHeadingThree">
                                    <button class="accordion-button collapsed fw-semibold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseThree">
                                        &iquest;C&oacute;mo armar mi men&uacute; semanal?
                                    </button>
                                </h2>
                                <div id="faqCollapseThree" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Ingresa a <strong>Men&uacute; Semanal</strong> y haz clic en crear o editar. Podr&aacute;s seleccionar tus recetas favoritas organizadas por d&iacute;as y tipos de comida (desayuno, almuerzo, cena, postre, snacks).
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeadingFour">
                                    <button class="accordion-button collapsed fw-semibold text-dark small" type="button" data-bs-toggle="collapse" data-bs-target="#faqCollapseFour">
                                        &iquest;Qu&eacute; hago si encuentro un error en una receta?
                                    </button>
                                </h2>
                                <div id="faqCollapseFour" class="accordion-collapse collapse" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body text-muted small">
                                        Puedes dejar un comentario en la vista de la receta o enviarnos un reporte directo usando el formulario de soporte en esta misma p&aacute;gina.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-dynamic-component>
