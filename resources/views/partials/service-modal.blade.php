<!-- resources/views/partials/service-modal.blade.php -->
<div class="modal fade" id="connectionModal" tabindex="-1" role="dialog" aria-labelledby="connectionModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectionModalLabel"><i class="fas fa-wifi"></i> Probar Conectividad del
                    Servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p><i class="fas fa-link"></i> URL del servicio: <strong id="modal-service-url"></strong></p>

                <!-- Inputs estilo Postman -->
                <div id="params-container">
                    <div class="form-group row">
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Key" name="key[]" />
                        </div>
                        <div class="col-md-5">
                            <input type="text" class="form-control" placeholder="Value" name="value[]" />
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-success btn-add-param"><i
                                    class="fas fa-plus"></i></button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i>
                    Cerrar</button>
                <button type="button" id="test-connection-confirm" class="btn btn-primary"><i class="fas fa-plug"></i>
                    Probar Conexión</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para mostrar la respuesta JSON -->
<div class="modal fade" id="jsonResponseModal" tabindex="-1" role="dialog" aria-labelledby="jsonResponseModalLabel"
    aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document"> <!-- Ancho ajustado -->
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="jsonResponseModalLabel">Respuesta JSON del Servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <pre id="jsonResponseContent" class="pre-scrollable"
                    style="background-color: #f5f5f5; padding: 10px; border-radius: 5px; max-height: 400px; overflow:auto;"></pre>
            </div>
            <div class="modal-footer">
                <!-- Botón para copiar el JSON -->
                <button id="copyJsonBtn" class="btn btn-info">
                    <i class="fas fa-copy"></i> Copiar JSON
                </button>
                <!-- Botón para exportar JSON a otros formatos -->
                <div class="dropdown">
                    <button class="btn btn-secondary dropdown-toggle" type="button" id="exportDropdown"
                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <i class="fas fa-file-export"></i> Exportar
                    </button>
                    <div class="dropdown-menu" aria-labelledby="exportDropdown">
                        <a class="dropdown-item" href="#" id="exportToCSV"><i class="fas fa-file-csv"></i>
                            Exportar a CSV</a>
                        <a class="dropdown-item" href="#" id="exportToXML"><i class="fas fa-file-code"></i>
                            Exportar a XML</a>
                    </div>
                </div>
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
