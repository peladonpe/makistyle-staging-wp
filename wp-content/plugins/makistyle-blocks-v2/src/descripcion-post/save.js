import { useBlockProps, RichText } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	return (
		<div { ...useBlockProps.save() }>
			<RichText.Content
				tagName="div"
				className="wp-block-makistyle-blocks-v2-descripcion-post-content"
				value={ attributes.descriptionContent }
			/>
		</div>
	);
}
