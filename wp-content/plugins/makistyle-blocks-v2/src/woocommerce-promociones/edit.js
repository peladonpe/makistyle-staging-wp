import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="promociones-editor">
				<h3>
					{ __(
						'Aquí se mostrará el descuento activo del producto (WooCommerce)',
						'makistyle'
					) }
				</h3>
				<p style={{ marginTop: '10px', fontSize: '14px', opacity: 0.8 }}>
					{ __(
						'Si hay varios descuentos activos, únicamente se aplicará y mostrará el de mayor cantidad.',
						'makistyle'
					) }
				</p>
			</div>
		</div>
	);
}
