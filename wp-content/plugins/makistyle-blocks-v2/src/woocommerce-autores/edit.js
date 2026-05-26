import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="autores-editor">
				<h3>
					{ __(
						'Aquí se mostrarán los autores (WooCommerce)',
						'makistyle'
					) }
				</h3>
			</div>
		</div>
	);
}
