<?php
if (isset($product)) {
    $product = (array)$product;
}
$errors = $this->session->flashdata('errors');
$old = $this->session->flashdata('old');
$csrf = array(
    'name' => $this->security->get_csrf_token_name(),
    'hash' => $this->security->get_csrf_hash()
);
if (isset($old['name'])) {
    $name = $old['name'];
} else if (isset($product['nombre'])) {
    $name = $product['nombre'];
} else {
    $name = '';
}
if (isset($old['unit_price'])) {
    $unit_price = $old['unit_price'];
} else if (isset($product['precio_unitario'])) {
    $unit_price = $product['precio_unitario'];
} else {
    $unit_price = '';
}
if (isset($old['description'])) {
    $description = $old['description'];
} else if (isset($product['descripcion'])) {
    $description = $product['descripcion'];
} else {
    $description = '';
}
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
        <h1 class="mb-2 h3">Editar producto #<?php echo isset($product['id']) ? $product['id'] : '' ?></h1>
        <p class="mb-2 font-weight-bold">Los campos marcados con <i class="fas fa-asterisk text-danger"></i> son obligatorios</p>
        <div class="card bg-white shadow">
            <div class="card-body p-3">
                <form id="productForm"
                      method="post"
                      action="<?php echo base_url('admin/products/edit_product_validation') ?>">
                    <input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
                    <input type="hidden" name="id" value="<?php echo isset($product['id']) ? $product['id'] : '' ?>">
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Nombre</label>
                        <input autocomplete="off"
                               type="text"
                               class="form-control"
                               name="name"
                               value="<?php echo $name ?>"
                               placeholder="Ej. Lápices de Colores">
                    </div>
                    <div class="form-group">
                        <label class="font-weight-bold"><i class="fas fa-asterisk text-danger"></i>&nbsp;Precio unitario</label>
                        <input id="unitPrice"
                               autocomplete="off"
                               type="text"
                               class="money form-control"
                               name="unit_price"
                               value="<?php echo $unit_price ?>"
                               placeholder="Ej. 249.33">
                    </div>
                    <div class="form-group">
                        <label>Descripción</label>
                        <textarea autocomplete="off"
                                  type="text"
                                  class="form-control<?php echo isset($errors['description']) ? ' is-invalid' : '' ?>"
                                  name="description"
                                  rows="5"
                                  placeholder='48 lápices de colores "Arcoíris" para dibujo'><?php echo $description ?></textarea>
                    </div>
                    <div class="action-buttons">
                        <a href="<?php echo base_url('admin/productos') ?>"
                           class="btn btn-lg btn-secondary cancel-btn">Regresar</a>
                        <button type="submit"
                                class="btn btn-lg btn-success ok-btn">Guardar
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
