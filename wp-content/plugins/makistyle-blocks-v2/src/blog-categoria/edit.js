import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

export default function Edit() {
	// Obtener el término de la taxonomía asociado al post actual
	const taxonomyTerm = useSelect( ( select ) => {
		const { getCurrentPostId } = select( 'core/editor' );
		const { getEntityRecord } = select( 'core' );

		const postId = getCurrentPostId();
		const post = getEntityRecord( 'postType', 'post', postId );

		if ( ! post || ! post.categories || post.categories.length === 0 ) {
			return null;
		}

		// Obtener el primer término asignado
		const termId = post.categories[ 0 ];
		return getEntityRecord( 'taxonomy', 'category', termId );
	}, [] );
	const displayText =
		taxonomyTerm?.meta?.makistyle_cmb2_category_nombre_singular ||
		taxonomyTerm?.name ||
		__( 'CATEGORIA', 'makistyle' );

	return (
		<div { ...useBlockProps() }>
			<div className="categoria__badge">{ displayText }</div>
		</div>
	);
}
