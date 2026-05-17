import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';
import { useSelect, useDispatch } from '@wordpress/data';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	
	// Obtener los meta del post actual
	const { meta } = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return {
			meta: getEditedPostAttribute( 'meta' ),
		};
	}, [] );
	
	const { makistyle_cmb2_descripcion_post: descriptionContentMeta } = meta || {};

	// Obtener función para actualizar el post
	const { editPost } = useDispatch( 'core/editor' );
	
	const onChangeContent = ( newContent ) => {
		editPost( {
			meta: {
				...meta,
				makistyle_cmb2_descripcion_post: newContent,
			},
		} );
	};

	return (
		<div { ...blockProps }>
			<RichText
				tagName="div"
				className="wp-block-makistyle-blocks-v2-descripcion-post-content"
				value={ descriptionContentMeta || '' }
				onChange={ onChangeContent }
				placeholder={ __( 'Escribe la descripción del post...', 'makistyle' ) }
			/>
		</div>
	);
}
