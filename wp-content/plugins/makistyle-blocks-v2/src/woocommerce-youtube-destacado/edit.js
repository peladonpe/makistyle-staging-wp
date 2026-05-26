import { __ } from '@wordpress/i18n';
import { useBlockProps, InspectorControls } from '@wordpress/block-editor';
import { ToggleControl, PanelBody } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { autoplay = true } = attributes;

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
						'Aquí se mostrará el video destacado de Youtube (WooCommerce)',
						'makistyle'
					) }
				</h3>
			</div>
		</div>
	);
}
