import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';

export default function Edit() {
	// Obtener el término de la taxonomía asociado al post actual
	const taxonomyTerm = useSelect( ( select ) => {
		const { getCurrentPostId } = select( 'core/editor' );
		const { getEntityRecord } = select( 'core' );

		const postId = getCurrentPostId();
		const post = getEntityRecord( 'postType', 'tienda_pt', postId );

		if (
			! post ||
			! post.tipo_recurso_taxonomia ||
			post.tipo_recurso_taxonomia.length === 0
		) {
			return null;
		}

		// Obtener el primer término asignado
		const termId = post.tipo_recurso_taxonomia[ 0 ];
		return getEntityRecord( 'taxonomy', 'tipo_recurso_taxonomia', termId );
	}, [] );
	const displayText =
		taxonomyTerm?.meta
			?.makistyle_cmb2_tipo_recurso_taxonomia_nombre_singular ||
		taxonomyTerm?.name ||
		__( 'CATEGORIA', 'makistyle' );

	return (
		<div { ...useBlockProps() }>
			<div className="categoria__badge">{ displayText }</div>
		</div>
	);
}
