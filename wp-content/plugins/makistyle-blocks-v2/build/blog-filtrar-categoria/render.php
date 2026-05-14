<?php

$homeUrl = esc_url(home_url());
$mostrar_busqueda = is_home();
$terminos = get_terms([
	'taxonomy'   => 'category',
	'hide_empty' => true,
]);
$termino_actual = get_queried_object();
if (!$termino_actual || !isset($termino_actual->slug) || !$termino_actual->slug) {
	$termino_actual = (object) ['slug' => 'todo', 'name' => 'Todo'];
}
?>
<div <?php echo get_block_wrapper_attributes(); ?>>

	<div class="lanzador-filtrar__div seccion">
		<?php if ($mostrar_busqueda) : ?>
			<div class="lanzador-buscar__wrap">
				<div class="lanzador-buscar__input-wrap">
					<input
						type="text"
						class="lanzador-buscar__input"
						placeholder="<?php esc_attr_e('Buscar...', 'makistyle'); ?>"
						aria-label="<?php esc_attr_e('Buscar', 'makistyle'); ?>" />
				</div>
				<button
					type="button"
					class="lanzador-buscar__btn"
					aria-label="<?php esc_attr_e('Buscar', 'makistyle'); ?>"
					data-home-url="<?php echo $homeUrl; ?>">
					<svg xmlns="http://www.w3.org/2000/svg" width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
						<circle cx="11" cy="11" r="8" />
						<line x1="21" y1="21" x2="16.65" y2="16.65" />
					</svg>
				</button>
			</div>
		<?php endif; ?>
		<button id="lanzador-modal-filtrar" class="lanzador-filtrar__a" type="button">
			<?php esc_html_e('Filtrar', 'makistyle'); ?>
		</button>

		<div id="btn-reset-filtro" class="lanzador-filtrar__reset">
			<a type="button" href="javascript:void(0)">X</a>
		</div>
	</div>

	<div
		class="mkv2-modal-overlay oculto"
		id="modalTipoRecurso"
		role="dialog"
		aria-modal="true"
		aria-labelledby="modalTipoRecursoLabel">
		<div class="mkv2-modal-dialog">
			<form id="formulario-filtro">

				<div class="mkv2-modal-header">
					<h2 class="mkv2-modal-title" id="modalTipoRecursoLabel">
						<?php esc_html_e('ELIGE UNA CATEGORIA', 'makistyle'); ?>
					</h2>
					<button
						type="button"
						id="boton-cerrar-modal"
						class="mkv2-modal-close"
						aria-label="<?php esc_attr_e('Cerrar', 'makistyle'); ?>"
						style="position:relative;"></button>
				</div>

				<div class="mkv2-modal-body">
					<select
						id="select-categoria"
						class="mkv2-select"
						aria-label="<?php esc_attr_e('Selecciona una categoría', 'makistyle'); ?>"
						data-categoria-name="tipo-recurso-tienda"
						data-categoria-value="<?php echo $termino_actual->name; ?>"
						data-categoria-slug="<?php echo $termino_actual->slug; ?>">
						<option value="todo" selected><?php esc_html_e('Mostrar todo', 'makistyle'); ?></option>
						<?php
						if ($terminos && ! is_wp_error($terminos)) :
							foreach ($terminos as $termino) :							if ($termino->slug === 'sin-categoria') continue;						?>
								<option value="<?php echo esc_attr($termino->slug); ?>"><?php echo esc_html($termino->name); ?></option>
						<?php
							endforeach;
						endif;
						?>
					</select>


				</div>

				<div class="mkv2-modal-footer">
					<button type="button" class="mkv2-btn mkv2-btn-cancelar" id="btn-cancelar-filtro">
						<?php esc_html_e('Cancelar', 'makistyle'); ?>
					</button>
					<button
						type="submit"
						id="btn-submit-filtrar"
						class="mkv2-btn mkv2-btn-filtrar"
						data-home-url="<?php echo $homeUrl; ?>">
						<?php esc_html_e('Filtrar', 'makistyle'); ?>
					</button>
				</div>

			</form>
		</div>
	</div>

</div>