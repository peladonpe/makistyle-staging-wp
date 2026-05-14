import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';

export default function Edit() {
	// Obtener los meta del post actual
	const { meta } = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return {
			meta: getEditedPostAttribute( 'meta' ),
		};
	}, [] );
	const {
		makistyle_cmb2_tienda_precio: precioMeta,
		makistyle_cmb2_tienda_enlace_compra: enlaceCompraMeta,
	} = meta;

	// Obtener función para actualizar el post
	const { editPost } = useDispatch( 'core/editor' );

	// Handler para cambios en el precio
	const handlePrecioChange = ( value ) => {
		// Convertir el string a número (float para permitir decimales)
		let numericValue;

		if ( value === '' || value === null || value === undefined ) {
			numericValue = null;
		} else {
			const parsed = parseFloat( value );
			numericValue = isNaN( parsed ) ? null : parsed;
		}

		editPost( {
			meta: {
				...meta,
				makistyle_cmb2_tienda_precio: numericValue,
			},
		} );
	};

	// Handler para cambios en el enlace de compra
	const handleEnlaceCompraChange = ( value ) => {
		editPost( {
			meta: {
				...meta,
				makistyle_cmb2_tienda_enlace_compra: value,
			},
		} );
	};

	return (
		<div { ...useBlockProps() }>
			<div className="precio-field">
				<TextControl
					label={ __( 'Precio', 'makistyle' ) }
					value={ precioMeta !== '' ? String( precioMeta ) : '' }
					onChange={ handlePrecioChange }
					type="number"
					step="0.1"
					__next40pxDefaultSize={ true }
					__nextHasNoMarginBottom={ true }
				/>
			</div>
			<TextControl
				label={ __( 'Enlace de compra', 'makistyle' ) }
				value={ enlaceCompraMeta }
				help={ __(
					'P. ej https://payhip.com/b/KGQOX pasa a https://payhip.com/buy?link=KGQOX (https://payhip.com/buy?link= + id_payhip)',
					'makistyle'
				) }
				onChange={ handleEnlaceCompraChange }
				type="url"
				__next40pxDefaultSize={ true }
				__nextHasNoMarginBottom={ true }
			/>
		</div>
	);
}
