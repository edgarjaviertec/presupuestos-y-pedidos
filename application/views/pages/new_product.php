<?php
if (isset($user)) {
    $user = (array)$user;
}
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
?>
<div class="row justify-content-center">
    <div class="col-12 col-md-12 col-lg-10 col-xl-8">
        <?php if (!empty($errors)): ?>
            <div class="alert alert-danger shadow">
                <p class="mb-2">
                    <strong>Hay algunos errores, por favor corríjalos y vuelva a intentarlo:</strong>
                </p>
                <ul class="mb-0">
                    <?php foreach ($errors as $error): ?>
                        <li><?php echo $error; ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        <?php endif; ?>
        <h1 class="mb-2 h3">Nuevo producto</h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son
            obligatorios</p>
        <div class="card bg-white shadow">
            <div class="card-body p-3">
                <form id="productForm" method="post"
                      action="<?php echo base_url('admin/products/new_product_validation') ?>">
                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Nombre</label>
                        <input autocomplete="off"
                               type="text"
                               class="form-control"
                               name="name"
                               value="<?php echo isset($old['name']) ? $old['name'] : '' ?>"
                               placeholder="Ej. Lápices de Colores">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Precio unitario</label>
                        <input id="unitPrice"
                               autocomplete="off"
                               type="text"
                               class="money form-control"
                               name="unit_price"
                               value="<?php echo isset($old['unit_price']) ? $old['unit_price'] : '' ?>"
                               placeholder="Ej. 249.33">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea autocomplete="off"
                                  type="text"
                                  class="form-control"
                                  name="description"
                                  rows="5"
                                  placeholder='48 lápices de colores "Arcoíris" para dibujo'><?php echo isset($old['description']) ? $old['description'] : '' ?></textarea>
                    </div>
                    <div class="action-buttons">
                        <a href="<?php echo base_url('admin/productos') ?>" class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
                        <button type="submit" class="btn btn-lg btn-success ok-btn">Guardar</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>