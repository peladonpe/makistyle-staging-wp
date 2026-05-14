import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect, useDispatch } from '@wordpress/data';

export default function Edit() {
	// Obtener el meta del post actual
	const { meta } = useSelect( ( select ) => {
		const { getCurrentPostId, getEditedPostAttribute } =
			select( 'core/editor' );
		// const { getEditedEntityRecord } = select( 'core' );

		const currentPostId = getCurrentPostId();
		// const post = getEditedEntityRecord(
		// 	'postType',
		// 	'tienda_pt',
		// 	currentPostId
		// );
		// const META_KEY = 'makistyle_cmb2_tienda_fecha_lanzamiento2';

		// return {
		// 	fechaLanzamientoMeta:
		// 		post?.meta?.makistyle_cmb2_tienda_fecha_lanzamiento2,
		// 	postId: currentPostId,
		// };
		return {
			meta: getEditedPostAttribute( 'meta' ),
			postId: currentPostId,
		};
	}, [] );
	const { makistyle_cmb2_tienda_fecha_lanzamiento2: fechaLanzamientoMeta } =
		meta;

	// Obtener función para actualizar el post
	// const { editEntityRecord } = useDispatch( 'core' );
	const { editPost } = useDispatch( 'core/editor' );

	// Convertir timestamp unix a formato YYYY-MM-DD para el input
	const dateValue = fechaLanzamientoMeta
		? new Date( fechaLanzamientoMeta * 1000 )
				.toISOString()
				.split( 'T' )[ 0 ]
		: '';
	const handleDateChange = ( event ) => {
		const selectedDate = event.target.value;
		if ( selectedDate ) {
			// Convertir fecha YYYY-MM-DD a timestamp unix (segundos)
			const timestamp = Math.floor(
				new Date( selectedDate ).getTime() / 1000
			);

			// Actualizar el meta del post
			// editEntityRecord( 'postType', 'tienda_pt', postId, {
			// 	meta: {
			// 		makistyle_cmb2_tienda_fecha_lanzamiento2: timestamp,
			// 	},
			// } );
			editPost( {
				meta: {
					...meta,
					makistyle_cmb2_tienda_fecha_lanzamiento2: timestamp,
				},
			} );
		} else {
			// Actualizar el meta del post a null
			// editEntityRecord( 'postType', 'tienda_pt', postId, {
			// 	meta: {
			// 		makistyle_cmb2_tienda_fecha_lanzamiento2: null,
			// 	},
			// } );
			editPost( {
				meta: {
					...meta,
					makistyle_cmb2_tienda_fecha_lanzamiento2: null,
				},
			} );
		}
	};

	return (
		<div { ...useBlockProps() }>
			<div className="fecha-lanzamiento-editor">
				<label htmlFor="fecha-lanzamiento-input">
					{ __( 'Fecha de lanzamiento', 'makistyle' ) }
				</label>
				<input
					id="fecha-lanzamiento-input"
					type="date"
					value={ dateValue }
					onChange={ handleDateChange }
				/>
			</div>
		</div>
	);
}
