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
	const { makistyle_cmb2_tienda_autores: autoresMeta } = meta;

	// Obtener función para actualizar el post
	const { editPost } = useDispatch( 'core/editor' );

	// Handler para cambios en los autores
	const handleAutoresChange = ( value ) => {
		editPost( {
			meta: {
				...meta,
				makistyle_cmb2_tienda_autores: value,
			},
		} );
	};

	return (
		<div { ...useBlockProps() }>
			<TextControl
				label={ __( 'Autores', 'makistyle' ) }
				value={ autoresMeta || '' }
				onChange={ handleAutoresChange }
				__next40pxDefaultSize={ true }
				__nextHasNoMarginBottom={ true }
			/>
		</div>
	);
}
