/**
 * Lógica de frontend para el bloque woocommerce-boton-comprar
 */
document.addEventListener('DOMContentLoaded', function () {
	const addToCartButtons = document.querySelectorAll(
		'.makistyle-add-to-cart-btn'
	);

	addToCartButtons.forEach(function (button) {
		button.addEventListener('click', function (e) {
			e.preventDefault();

			if (button.classList.contains('loading')) {
				return;
			}

			const productId = button.getAttribute('data-product-id');
			const feedbackSpan = button.querySelector('.cart-feedback');
			let currentQty = parseInt(button.getAttribute('data-qty') || '0', 10);

			// Añadir estado de carga
			button.classList.add('loading');
			button.style.opacity = '0.7';
			button.style.cursor = 'wait';

			// Utilizar jQuery para la llamada AJAX de WooCommerce,
			// ya que el ecosistema de WooCommerce depende fuertemente de él
			// para el refresco de fragmentos del carrito (wc_fragments).
			if (typeof jQuery !== 'undefined' && typeof wc_add_to_cart_params !== 'undefined') {
				jQuery.post(
					wc_add_to_cart_params.wc_ajax_url
						.toString()
						.replace('%%endpoint%%', 'add_to_cart'),
					{
						product_id: productId,
						quantity: 1,
					},
					function (response) {
						if (!response || response.error) {
							button.classList.remove('loading');
							button.style.opacity = '1';
							button.style.cursor = 'pointer';
							return;
						}

						// Actualizar estado del botón
						currentQty++;
						button.setAttribute('data-qty', currentQty);

						// Mostrar feedback visual
						if (feedbackSpan) {
							feedbackSpan.textContent = `(${currentQty} en carrito)`;
							feedbackSpan.style.display = 'inline-block';
						}

						// Restaurar apariencia
						button.classList.remove('loading');
						button.style.opacity = '1';
						button.style.cursor = 'pointer';

						// Disparar evento para que el mini-carrito del header se actualice
						jQuery(document.body).trigger('added_to_cart', [
							response.fragments,
							response.cart_hash,
							button,
						]);
					}
				);
			} else {
				// Fallback si jQuery o los parámetros de WC no están disponibles
				window.location.href = `/?add-to-cart=${productId}`;
			}
		});
	});
});
