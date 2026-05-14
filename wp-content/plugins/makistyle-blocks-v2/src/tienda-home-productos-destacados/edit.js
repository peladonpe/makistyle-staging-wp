import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { RangeControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { numeroProductosDestacados } = attributes;

	return (
		<div { ...useBlockProps() }>
			<p className="listado-descripcion">
				{ __(
					'Aquí se mostrarán los productos destacados de la tienda',
					'makistyle'
				) }
			</p>
			<RangeControl
				label="Numero de items a mostrar como máximo"
				value={ numeroProductosDestacados }
				onChange={ ( newValue ) =>
					setAttributes( { numeroProductosDestacados: newValue } )
				}
				min={ 1 }
				max={ 8 }
			/>
		</div>
	);
}
