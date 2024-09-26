<!-- resources/views/partials/service-modal.blade.php -->
<div class="modal fade" id="connectionModal" tabindex="-1" role="dialog" aria-labelledby="connectionModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="connectionModalLabel">Probar Conectividad del Servicio</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>URL del servicio: <strong id="modal-service-url"></strong></p>
                
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
                            <button type="button" class="btn btn-success btn-add-param">+</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                <button type="button" id="test-connection-confirm" class="btn btn-primary">Probar Conexión</button>
            </div>
        </div>
    </div>
</div>
