import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { ToggleControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { esPaginado } = attributes;

	const onChangeEsPaginado = ( newValue ) => {
		setAttributes( { esPaginado: newValue } );
	};

	return (
		<div { ...useBlockProps() }>
			<p className="listado-descripcion">
				{ __(
					'Aquí se mostrarán los resultados de la búsqueda de productos tienda',
					'makistyle'
				) }
			</p>
			<ToggleControl
				__nextHasNoMarginBottom
				label={ __( 'Paginacion', 'makistyle' ) }
				checked={ esPaginado }
				onChange={ onChangeEsPaginado }
			/>
		</div>
	);
}
