import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="promociones-editor">
				<h3>
					{ __(
						'Aquí se mostrará el botón de compra del producto y/o enlaces alternativos',
						'makistyle'
					) }
				</h3>
			</div>
		</div>
	);
}
