import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { tipoRecursoInicio } = attributes;

	return (
		<div { ...useBlockProps() }>
			<p className="listado-descripcion">
				{ __(
					'Aquí se mostrarán los productos de la tienda, agrupados por tipo de recurso',
					'makistyle'
				) }
			</p>
			<TextControl
				label={ __( 'Tipo de recurso al inicio', 'makistyle' ) }
				help={ __(
					'Introduce el slug del tipo de recurso (taxonomía) que quieres mostrar en la primera posición del carrusel. Déjalo vacío para usar el orden por defecto.',
					'makistyle'
				) }
				value={ tipoRecursoInicio }
				onChange={ ( value ) =>
					setAttributes( { tipoRecursoInicio: value } )
				}
				__next40pxDefaultSize={ true }
				__nextHasNoMarginBottom={ true }
			/>
		</div>
	);
}
