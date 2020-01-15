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
		<div class="mb-3">
			<h3 class="mb-0">
				<span>Editar producto #<?php echo isset($product['id']) ? $product['id'] : '' ?></span>
			</h3>
		</div>
		<div class="card bg-white shadow">
			<div class="card-body">
				<form id="new_product" method="post" action="<?php echo base_url('admin/products/edit_product_validation') ?>">
					<input type="hidden" name="<?= $csrf['name']; ?>" value="<?= $csrf['hash']; ?>"/>
					<input type="hidden" name="id" value="<?php echo isset($product['id']) ? $product['id'] : '' ?>">
					<div class="form-group">
						<label>Nombre</label>
						<input
							autocomplete="off"
							type="text"
							class="form-control<?php echo isset($errors['name']) ? ' is-invalid' : '' ?>"
							name="name"
							value="<?php echo $name ?>">
						<div class="invalid-feedback">
							<?php echo isset($errors['name']) ? $errors['name'] : '' ?>
						</div>
					</div>
					<div class="form-group">

						<label>Precio unitario</label>
						<div class="input-group">
							<div class="input-group-prepend">
								<span class="input-group-text">$</span>
							</div>
							<input
								autocomplete="off"
								type="text"
								class="money form-control<?php echo isset($errors['unit_price']) ? ' is-invalid' : '' ?>"
								name="unit_price"
								value="<?php echo $unit_price ?>">
							<div class="input-group-append">
								<span class="input-group-text" id="basic-addon2">MXN</span>
							</div>
						</div>
						<div class="invalid-feedback d-block">
							<?php echo isset($errors['unit_price']) ? $errors['unit_price'] : '' ?>
						</div>

					</div>
					<div class="form-group">
						<label>Descripción</label>
						<textarea
							autocomplete="off"
							type="text"
							class="form-control<?php echo isset($errors['description']) ? ' is-invalid' : '' ?>"
							name="description"
							rows="5"><?php echo $description ?></textarea>
						<div class="invalid-feedback">
							<?php echo isset($errors['description']) ? $errors['description'] : '' ?>
						</div>
					</div>
					<div class="d-flex justify-content-between">
						<a href="<?php echo base_url('admin/productos') ?>"
						   class="btn btn-lg btn-secondary">Cancelar</a>
						<button id="submit_btn" type="submit" class="btn btn-lg btn-success">Guardar cambios
						</button>
					</div>
				</form>
			</div>
		</div>
	</div>
</div>
