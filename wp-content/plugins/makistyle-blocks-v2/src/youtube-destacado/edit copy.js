import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, TextControl, PanelBody } from '@wordpress/components';
import { useSelect, useDispatch } from '@wordpress/data';

export default function Edit( { attributes, setAttributes } ) {
	const { autoplay = true } = attributes;

	// Obtener los meta del post actual
	const { meta } = useSelect( ( select ) => {
		const { getEditedPostAttribute } = select( 'core/editor' );
		return {
			meta: getEditedPostAttribute( 'meta' ),
		};
	}, [] );
	const { makistyle_cmb2_todos_pt_id_youtube_destacado: idYoutubeMeta } =
		meta;

	// Obtener función para actualizar el post
	const { editPost } = useDispatch( 'core/editor' );

	// Handler para cambios en el ID de YouTube
	const handleIdYoutubeChange = ( value ) => {
		editPost( {
			meta: {
				...meta,
				makistyle_cmb2_todos_pt_id_youtube_destacado: value,
			},
		} );
	};

	return (
		<div { ...useBlockProps() }>
			<InspectorControls>
				<PanelBody
					title={ __( 'Configuración', 'makistyle' ) }
					initialOpen={ true }
				>
					<ToggleControl
						label={ __( 'Autoplay', 'makistyle' ) }
						checked={ autoplay }
						onChange={ ( value ) =>
							setAttributes( { autoplay: value } )
						}
						__nextHasNoMarginBottom={ true }
					/>
				</PanelBody>
			</InspectorControls>
			<div className="youtube-destacado-editor">
				<h3>
					{ __(
						'Aquí se mostrará el video destacado de Youtube',
						'makistyle'
					) }
				</h3>

				<TextControl
					label={ __( 'ID del video de YouTube', 'makistyle' ) }
					value={ idYoutubeMeta || '' }
					onChange={ handleIdYoutubeChange }
					help={ __(
						'P. ej https://www.youtube.com/watch?v=Mb46adPgmDc&t=4s (el codigo que viene después de v=, hasta el &, sin incluir este último)',
						'makistyle'
					) }
					__next40pxDefaultSize={ true }
					__nextHasNoMarginBottom={ true }
				/>
			</div>
		</div>
	);
}
