import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { RangeControl } from '@wordpress/components';

export default function Edit( { attributes, setAttributes } ) {
	const { numeroArticulosDestacados } = attributes;

	return (
		<div { ...useBlockProps() }>
			<p className="listado-descripcion">
				{ __(
					'Aquí se mostrarán los artículos destacados del blog',
					'makistyle'
				) }
			</p>
			<RangeControl
				label="Numero de items a mostrar como máximo"
				value={ numeroArticulosDestacados }
				onChange={ ( newValue ) =>
					setAttributes( { numeroArticulosDestacados: newValue } )
				}
				min={ 1 }
				max={ 8 }
			/>
		</div>
	);
}
