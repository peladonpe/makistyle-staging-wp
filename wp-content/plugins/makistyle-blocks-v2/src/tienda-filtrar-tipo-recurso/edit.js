import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';

export default function Edit() {
	return (
		<div { ...useBlockProps() }>
			<div className="lanzador-filtrar__div seccion">
				<a
					id="lanzador-modal-filtrar"
					className="lanzador-filtrar__a"
					href="javascript:void(0)"
				>
					Filtrar
				</a>

				<div id="btn-reset-filtro" className="lanzador-filtrar__reset">
					<a href="javascript:void(0)">X</a>
				</div>
			</div>
		</div>
	);
}
