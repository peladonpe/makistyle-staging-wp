import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

export default function Edit() {
	// Obtener el término de la taxonomía asociado al post actual
	const taxonomyTerm = useSelect( ( select ) => {
		const { getCurrentPostId } = select( 'core/editor' );
		const { getEntityRecord } = select( 'core' );

		const postId = getCurrentPostId();
		const post = getEntityRecord( 'postType', 'product', postId );

		if (
			! post ||
			! post.product_cat ||
			post.product_cat.length === 0
		) {
			return null;
		}

		// Obtener el primer término asignado
		const termId = post.product_cat[ 0 ];
		return getEntityRecord( 'taxonomy', 'product_cat', termId );
	}, [] );

	const displayText =
		taxonomyTerm?.cmb2?.makistyle_cmb2_woocommerce_categorias_metaboxes?.makistyle_cmb2_woocommerce_categorias_nombre_singular ||
		taxonomyTerm?.meta?.makistyle_cmb2_woocommerce_categorias_nombre_singular ||
		taxonomyTerm?.name ||
		__( 'CATEGORIA', 'makistyle' );

	return (
		<div { ...useBlockProps() }>
			<div className="categoria__badge">{ displayText }</div>
		</div>
	);
}
