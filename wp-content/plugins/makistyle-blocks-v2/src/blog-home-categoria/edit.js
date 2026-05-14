import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { categoriaInicio } = attributes;

	return (
		<div { ...useBlockProps() }>
			<p className="listado-descripcion">
				{ __(
					'Aquí se mostrarán los artículos del blog, agrupados por categoría',
					'makistyle'
				) }
			</p>
			<TextControl
				label={ __( 'Categoría al inicio', 'makistyle' ) }
				help={ __( 'Introduce el slug de la categoría que quieres mostrar en la primera posición del carrusel. Déjalo vacío para usar el orden por defecto.', 'makistyle' ) }
				value={ categoriaInicio }
				onChange={ ( value ) => setAttributes( { categoriaInicio: value } ) }
				__next40pxDefaultSize={ true }
				__nextHasNoMarginBottom={ true }
			/>
		</div>
	);
}
