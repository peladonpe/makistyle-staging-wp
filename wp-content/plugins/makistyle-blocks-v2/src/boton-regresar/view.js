document.addEventListener( 'DOMContentLoaded', function () {
	const btn = document.querySelector( '.boton-regresar__historial' );
	if ( btn ) {
		btn.addEventListener( 'click', function () {
			history.back();
		} );
	}
} );
