import { __ } from '@wordpress/i18n';
import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	
	const onChangeContent = ( newContent ) => {
		setAttributes( { descriptionContent: newContent } );
	};

	return (
		<div { ...blockProps }>
			<RichText
				tagName="div"
				className="wp-block-makistyle-blocks-v2-descripcion-post-content"
				value={ attributes.descriptionContent }
				onChange={ onChangeContent }
				placeholder={ __( 'Escribe la descripción del post...', 'makistyle' ) }
			/>
		</div>
	);
}
