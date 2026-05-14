import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<a className="breadcrumbs__enlace" href="#">
				<span className="breadcrumbs__texto">
					{ __( 'Breadcrumbs', 'makistyle' ) }
				</span>
			</a>
		</div>
	);
}
