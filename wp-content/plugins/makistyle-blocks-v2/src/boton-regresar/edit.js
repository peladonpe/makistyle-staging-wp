import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<a className="boton-regresar__enlace" href="#">
				<span className="boton-regresar__icono" aria-hidden="true">
					&#8592;
				</span>
				<span className="boton-regresar__texto">
					{ __( 'Regresar', 'makistyle' ) }
				</span>
			</a>
		</div>
	);
}
