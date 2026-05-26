import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="fecha-lanzamiento-editor">
				<h3>
					{ __(
						'Aquí se mostrará la fecha de lanzamiento (WooCommerce)',
						'makistyle'
					) }
				</h3>
			</div>
		</div>
	);
}
