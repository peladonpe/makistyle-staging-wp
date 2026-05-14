<?php

// use Symfony\Component\Translation\Loader\CsvFileLoader;

/**
 * POSTS
 */

function makistyle_cmb2_tipo_recurso_taxonomia()
{
	$prefix = 'makistyle_cmb2_tipo_recurso_taxonomia_';
	$makistyle_cmb2_tipo_recurso_taxonomia = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__(
			'Campos de la taxonomia tipo recurso tienda',
			'cmb2',
		),
		'object_types' => ['term'], // Post type
		'taxonomies' => ['tipo_recurso_taxonomia'],
	]);
	$makistyle_cmb2_tipo_recurso_taxonomia->add_field([
		'name' => esc_html__('Nombre SINGULAR', 'cmb2'),
		'desc' => esc_html__('Nombre del tipo de recurso en singular', 'cmb2'),
		'id' => $prefix . 'nombre_singular',
		'type' => 'text',
	]);
}
add_action('cmb2_admin_init', 'makistyle_cmb2_tipo_recurso_taxonomia');

function makistyle_cmb2_category()
{
	$prefix = 'makistyle_cmb2_category_';
	$makistyle_cmb2_category = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__('Campos de las categorías del blog', 'cmb2'),
		'object_types' => ['term'],
		'taxonomies' => ['category'],
	]);
	$makistyle_cmb2_category->add_field([
		'name' => esc_html__('Nombre SINGULAR', 'cmb2'),
		'desc' => esc_html__('Nombre de la categoría en singular', 'cmb2'),
		'id' => $prefix . 'nombre_singular',
		'type' => 'text',
	]);
}
add_action('cmb2_admin_init', 'makistyle_cmb2_category');
function es_hijo_de_descuentos($cmb)
{
	if (!isset($_GET['tag_ID'])) {
		return false; // No mostrar en términos nuevos
	}

	$term_id = absint($_GET['tag_ID']);
	$term = get_term($term_id, 'promociones_taxonomia');

	if (!$term || is_wp_error($term)) {
		return false;
	}

	// Si el término tiene padre, verificar si es hijo de "descuentos"
	if ($term->parent) {
		$parent = get_term($term->parent, 'promociones_taxonomia');
		if ($parent && $parent->slug === 'descuentos') {
			return true;
		}
	}

	return false;
}

// Función para verificar si el término es hijo de "cupones"
function es_hijo_cupones($cmb)
{
	if (!isset($_GET['tag_ID'])) {
		return false; // No mostrar en términos nuevos
	}

	$term_id = absint($_GET['tag_ID']);
	$term = get_term($term_id, 'promociones_taxonomia');

	if (!$term || is_wp_error($term)) {
		return false;
	}

	// Si el término tiene padre, verificar si es hijo de "cupones"
	if ($term->parent) {
		$parent = get_term($term->parent, 'promociones_taxonomia');
		if ($parent && $parent->slug === 'cupones') {
			return true;
		}
	}

	return false;
}

// Función para verificar si el término es hijo de "descuentos" O "cupones"
function es_hijo_descuentos_o_cupones($cmb)
{
	return es_hijo_de_descuentos($cmb) || es_hijo_cupones($cmb);
}

// Callback para establecer la fecha actual como valor por defecto
function makistyle_fecha_actual_default()
{
	return time();
}

// Validación de fechas en el cliente (JavaScript)
function makistyle_validar_fechas_script()
{
	$screen = get_current_screen();
	if (!$screen || $screen->taxonomy !== 'promociones_taxonomia') {
		return;
	}?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var form = document.getElementById('edittag');
            if (!form) return;

            // Función para convertir fecha en formato d-m-Y a timestamp
            function convertirFechaATimestamp(fechaString) {
                if (!fechaString) return null;
                var partes = fechaString.split('-');
                if (partes.length !== 3) return null;

                var dia = parseInt(partes[0], 10);
                var mes = parseInt(partes[1], 10) - 1; // Los meses en JavaScript van de 0 a 11
                var anio = parseInt(partes[2], 10);

                return new Date(anio, mes, dia).getTime();
            }

            form.addEventListener('submit', function(e) {
                var fechaInicio = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_fecha_inicio"]');
                var fechaFinal = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_fecha_final"]');

                if (!fechaInicio || !fechaFinal) return;

                var valorInicio = fechaInicio.value;
                var valorFinal = fechaFinal.value;

                if (valorInicio && valorFinal) {
                    var timestampInicio = convertirFechaATimestamp(valorInicio);
                    var timestampFinal = convertirFechaATimestamp(valorFinal);

                    if (timestampInicio && timestampFinal && timestampFinal < timestampInicio) {
                        e.preventDefault();
                        alert('Error: La fecha final no puede ser anterior a la fecha de inicio.');

                        // Resaltar el campo con error
                        fechaFinal.style.borderColor = 'red';
                        fechaFinal.focus();

                        return false;
                    }
                }
            });
        });
    </script>
<?php
}
add_action('admin_footer', 'makistyle_validar_fechas_script');

function makistyle_cmb2_promociones_taxonomia()
{
	$prefix = 'makistyle_cmb2_promociones_taxonomia_';
	$makistyle_cmb2_promociones_taxonomia = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__(
			'Campos de la taxonomia promociones tienda',
			'cmb2',
		),
		'object_types' => ['term'], // Post type
		'taxonomies' => ['promociones_taxonomia'],
	]);
	$makistyle_cmb2_promociones_taxonomia->add_field([
		'name' => esc_html__('NOMBRE de la promoción', 'cmb2'),
		'desc' => esc_html__(
			'Nombre de la promoción aplicable, p. ej. 20% descuento, promoción Navidad, etc.',
			'cmb2',
		),
		'id' => $prefix . 'nombre_promocion',
		'type' => 'text',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
		'show_on_cb' => 'es_hijo_descuentos_o_cupones',
	]);

	$makistyle_cmb2_promociones_taxonomia->add_field([
		'name' => esc_html__('Código de descuento', 'cmb2'),
		'desc' => esc_html__(
			'Código de cupón para aplicar el descuento',
			'cmb2',
		),
		'id' => $prefix . 'codigo_descuento',
		'type' => 'text',
		'show_on_cb' => 'es_hijo_cupones',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);
	$makistyle_cmb2_promociones_taxonomia->add_field([
		'name' => esc_html__('Porcentaje de descuento', 'cmb2'),
		'desc' => esc_html__(
			'Porcentaje de descuento aplicable (número entero del 0 al 100)',
			'cmb2',
		),
		'id' => $prefix . 'porcentaje_descuento',
		'type' => 'text_small',
		'show_on_cb' => 'es_hijo_descuentos_o_cupones',
		'required' => true,
		'attributes' => [
			'type' => 'number',
			'pattern' => '\d*',
			'min' => '0',
			'max' => '100',
			'step' => '1',
			'required' => 'required',
		],
	]);
	$makistyle_cmb2_promociones_taxonomia->add_field([
		'name' => esc_html__('Fecha de inicio', 'cmb2'),
		'desc' => esc_html__('Fecha de inicio de la promoción', 'cmb2'),
		'id' => $prefix . 'fecha_inicio',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
		'show_on_cb' => 'es_hijo_descuentos_o_cupones',
		'default_cb' => 'makistyle_fecha_actual_default',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$makistyle_cmb2_promociones_taxonomia->add_field([
		'name' => esc_html__('Fecha final', 'cmb2'),
		'desc' => esc_html__('Fecha final de la promoción', 'cmb2'),
		'id' => $prefix . 'fecha_final',
		'type' => 'text_date_timestamp',
		'date_format' => 'd-m-Y',
		'show_on_cb' => 'es_hijo_descuentos_o_cupones',
		'required' => true,
		'attributes' => [
			'required' => 'required',
		],
	]);

	$makistyle_cmb2_promociones_taxonomia->add_field([
		'name' => esc_html__('PROMOCIÓN ACTIVA', 'cmb2'),
		'desc' => esc_html__('Desmarcar para desactivar la promoción', 'cmb2'),
		'id' => $prefix . 'promocion_activa',
		'type' => 'checkbox',
		'show_on_cb' => 'es_hijo_descuentos_o_cupones',
	]);
}
add_action('cmb2_admin_init', 'makistyle_cmb2_promociones_taxonomia');

// JavaScript para manejar la lógica de promoción activa/inactiva
function makistyle_promocion_activa_script()
{
	$screen = get_current_screen();
	if (!$screen || $screen->taxonomy !== 'promociones_taxonomia') {
		return;
	}?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var checkbox = document.getElementById('makistyle_cmb2_promociones_taxonomia_promocion_activa');
            var inputNombre = document.querySelector('#name');
            var inputSlug = document.querySelector('#slug');
            var inputParent = document.querySelector('#parent');
            var textareaDescription = document.querySelector('#description');
            var inputNombrePromocion = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_nombre_promocion"]');
            var inputPorcentajeDescuento = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_porcentaje_descuento"]');
            var inputCodigoDescuento = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_codigo_descuento"]');
            var inputFechaInicio = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_fecha_inicio"]');
            var inputFechaFinal = document.querySelector('input[name="makistyle_cmb2_promociones_taxonomia_fecha_final"]');

            if (!checkbox) {
                return;
            }
            // Función para deshabilitar un campo
            function deshabilitarCampo(campo) {
                if (!campo) return;
                campo.style.opacity = '0.6';
                campo.style.pointerEvents = 'none';
                campo.style.backgroundColor = '#f0f0f0';
                campo.style.borderRadius = '4px';
                // campo.disabled = true;
            }

            // Función para habilitar un campo
            function habilitarCampo(campo) {
                if (!campo) return;
                campo.style.opacity = '';
                campo.style.pointerEvents = '';
                campo.style.backgroundColor = '';
                campo.style.borderRadius = '';
                // campo.disabled = false;
            }

            function actualizarEstadoCampos() {

                // Cuando NO está checked (promoción desactivada), deshabilitar los campos
                if (!checkbox.checked) {
                    deshabilitarCampo(inputNombre);
                    deshabilitarCampo(inputSlug);
                    deshabilitarCampo(inputParent);
                    deshabilitarCampo(textareaDescription);
                    deshabilitarCampo(inputNombrePromocion);
                    deshabilitarCampo(inputPorcentajeDescuento);
                    deshabilitarCampo(inputCodigoDescuento);
                    deshabilitarCampo(inputFechaInicio);
                    deshabilitarCampo(inputFechaFinal);
                } else {
                    // Restaurar estado normal cuando está activa
                    habilitarCampo(inputNombre);
                    habilitarCampo(inputSlug);
                    habilitarCampo(inputParent);
                    habilitarCampo(textareaDescription);
                    habilitarCampo(inputNombrePromocion);
                    habilitarCampo(inputPorcentajeDescuento);
                    habilitarCampo(inputCodigoDescuento);
                    habilitarCampo(inputFechaInicio);
                    habilitarCampo(inputFechaFinal);
                }
            }

            // Ejecutar al cargar
            actualizarEstadoCampos();

            // Ejecutar cuando cambie el checkbox
            checkbox.addEventListener('change', actualizarEstadoCampos);
        });
    </script>
<?php
}
add_action('admin_footer', 'makistyle_promocion_activa_script');

/**
 * SEO
 */
function makistyle_cmb2_seo()
{
	$prefix = 'makistyle_cmb2_seo_';

	$pv_cmb2_seo = new_cmb2_box([
		'id' => $prefix . 'metaboxes',
		'title' => esc_html__('Campos seo', 'cmb2'),
		'object_types' => ['page', 'tienda_pt', 'post'], // Post type
		'context' => 'normal',
		'priority' => 'high',
		'show_names' => true, // Show field names on the left
	]);
	$pv_cmb2_seo->add_field([
		'name' => esc_html__('Meta description', 'cmb2'),
		'desc' => esc_html__('Descripción meta, 120-320 caracteres', 'cmb2'),
		'id' => $prefix . 'description',
		'type' => 'textarea',
	]);
}
add_action('cmb2_admin_init', 'makistyle_cmb2_seo');

// JavaScript para mostrar el contador de caracteres en el metabox de SEO
function makistyle_cmb2_seo_contador_script()
{
	$screen = get_current_screen();
	if (
		!$screen ||
		!in_array($screen->post_type, ['page', 'tienda_pt', 'post'])
	) {
		return;
	}?>
    <script type="text/javascript">
        document.addEventListener('DOMContentLoaded', function() {
            var textarea = document.querySelector('#makistyle_cmb2_seo_description');
            if (!textarea) return;

            var contador = document.createElement('div');
            contador.style.marginTop = '5px';
            contador.style.fontWeight = 'bold';
            textarea.parentNode.appendChild(contador);

            function actualizarContador() {
                var longitud = textarea.value.length;
                contador.textContent = longitud + ' caracteres';

                if (longitud >= 120 && longitud <= 150) {
                    contador.style.color = '#46b450'; // verde wp-admin
                } else if (longitud >= 151 && longitud <= 320) {
                    contador.style.color = '#ffb900'; // naranja wp-admin
                } else if (longitud > 320) {
                    contador.style.color = '#dc3232'; // rojo wp-admin
                } else {
                    contador.style.color = ''; // por defecto
                }
            }

            textarea.addEventListener('input', actualizarContador);
            actualizarContador(); // inicializa valor actual
        });
    </script>
<?php
}
add_action('admin_footer', 'makistyle_cmb2_seo_contador_script');
