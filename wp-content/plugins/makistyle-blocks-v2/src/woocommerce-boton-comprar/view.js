/**
 * Lógica de frontend para el bloque woocommerce-boton-comprar
 */
document.addEventListener( 'DOMContentLoaded', function () {
	const addToCartButtons = document.querySelectorAll(
		'.makistyle-add-to-cart-btn'
	);

	addToCartButtons.forEach( function ( button ) {
		button.addEventListener( 'click', function ( e ) {
			e.preventDefault();

			if ( button.classList.contains( 'loading' ) ) {
				return;
			}

			const productId = button.getAttribute( 'data-product-id' );
			const feedbackSpan = button.querySelector( '.cart-feedback' );
			let currentQty = parseInt(
				button.getAttribute( 'data-qty' ) || '0',
				10
			);

			// Añadir estado de carga
			button.classList.add( 'loading' );
			button.style.opacity = '0.7';
			button.style.cursor = 'wait';

			// Utilizar Fetch API (Vanilla JS) para la llamada de red
			if (typeof wc_add_to_cart_params !== 'undefined') {
				const formData = new FormData();
				formData.append('product_id', productId);
				formData.append('quantity', 1);

				const ajaxUrl = wc_add_to_cart_params.wc_ajax_url
					.toString()
					.replace('%%endpoint%%', 'add_to_cart');

				fetch(ajaxUrl, {
					method: 'POST',
					body: formData,
				})
					.then((response) => response.json())
					.then((data) => {
						if (!data || data.error) {
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

						// Actualizar los fragmentos manualmente (Vanilla JS) para asegurar que funciona
						// independientemente de si jQuery / cart-fragments.js está activo
						if (data.fragments) {
							Object.keys(data.fragments).forEach((selector) => {
								const elements = document.querySelectorAll(selector);
								elements.forEach((el) => {
									el.outerHTML = data.fragments[selector];
								});
							});
						}

						// Obligatorio: Disparar evento de jQuery para que el ecosistema 
						// nativo de WooCommerce (cart-fragments.js) se entere y actualice la cabecera.
						if (typeof jQuery !== 'undefined') {
							jQuery(document.body).trigger('added_to_cart', [
								data.fragments,
								data.cart_hash,
								button,
							]);
						}
					})
					.catch((error) => {
						console.error('Error al añadir al carrito:', error);
						button.classList.remove('loading');
						button.style.opacity = '1';
						button.style.cursor = 'pointer';
					});
			} else {
				// Fallback si los parámetros de WC no están disponibles
				window.location.href = `/?add-to-cart=${productId}`;
			}
		});
	});
});
