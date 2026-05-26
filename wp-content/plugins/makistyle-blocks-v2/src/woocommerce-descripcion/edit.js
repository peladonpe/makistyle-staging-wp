import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<div className="descripcion-editor">
				<h3>
					{ __(
						'Aquí se mostrará la descripción larga del producto (WooCommerce)',
						'makistyle'
					) }
				</h3>
			</div>
		</div>
	);
}
