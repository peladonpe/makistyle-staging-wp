import { __ } from '@wordpress/i18n';
import { useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { useState } from '@wordpress/element';

export default function Edit() {
	const [ copiedId, setCopiedId ] = useState( null );

	// Obtener los términos de la taxonomía promociones_taxonomia asociados al post actual
	const promociones = useSelect( ( select ) => {
		const { getCurrentPostId } = select( 'core/editor' );
		const { getEntityRecord } = select( 'core' );

		const postId = getCurrentPostId();
		const post = getEntityRecord( 'postType', 'tienda_pt', postId );

		if (
			! post ||
			! post.promociones_taxonomia ||
			post.promociones_taxonomia.length === 0
		) {
			return [];
		}

		// Obtener todos los términos asignados
		const termIds = post.promociones_taxonomia;
		const terms = termIds.map( ( termId ) =>
			getEntityRecord( 'taxonomy', 'promociones_taxonomia', termId )
		);

		// Filtrar términos que ya estén cargados y enriquecer con parentSlug
		const loadedTerms = terms.filter(
			( term ) => term !== null && term !== undefined
		);

		return loadedTerms.map( ( term ) => {
			if ( term.parent && term.parent !== 0 ) {
				const parentTerm = getEntityRecord(
					'taxonomy',
					'promociones_taxonomia',
					term.parent
				);
				return { ...term, parentSlug: parentTerm?.slug || null };
			}
			return { ...term, parentSlug: null };
		} );
	}, [] );

	const ahora = Math.floor( Date.now() / 1000 );

	const esActiva = ( p ) => {
		const activa =
			p?.meta?.makistyle_cmb2_promociones_taxonomia_promocion_activa ===
			'on';
		const inicio =
			p?.meta?.makistyle_cmb2_promociones_taxonomia_fecha_inicio;
		const final = p?.meta?.makistyle_cmb2_promociones_taxonomia_fecha_final;
		return (
			activa &&
			inicio &&
			final &&
			ahora >= Number( inicio ) &&
			ahora <= Number( final )
		);
	};

	const activasCupones = promociones.filter(
		( p ) => esActiva( p ) && p.parentSlug === 'cupones'
	).length;

	const activasDescuentos = promociones.filter(
		( p ) => esActiva( p ) && p.parentSlug === 'descuentos'
	).length;

	return (
		<div { ...useBlockProps() }>
			{ promociones.length > 0 ? (
				<>
					{ activasCupones > 1 && (
						<p className="advertencia-promocion">
							VARIOS CUPONES ACTIVOS. Sólo se tendrá en cuenta uno
							de los de mayor descuento.
						</p>
					) }
					{ activasDescuentos > 1 && (
						<p className="advertencia-promocion">
							VARIOS DESCUENTOS ACTIVOS. Sólo se aplicará uno de
							los de mayor descuento.
						</p>
					) }
					{ promociones.map( ( promocion ) => {
						const nombrePromocion =
							promocion?.meta
								?.makistyle_cmb2_promociones_taxonomia_nombre_promocion ||
							'';
						const porcentajeDescuento =
							promocion?.meta
								?.makistyle_cmb2_promociones_taxonomia_porcentaje_descuento ||
							'';
						const codigoDescuento =
							promocion?.meta
								?.makistyle_cmb2_promociones_taxonomia_codigo_descuento ||
							'';
						const promocionActiva =
							promocion?.meta
								?.makistyle_cmb2_promociones_taxonomia_promocion_activa ===
							'on';
						const fechaInicio =
							promocion?.meta
								?.makistyle_cmb2_promociones_taxonomia_fecha_inicio;
						const fechaFinal =
							promocion?.meta
								?.makistyle_cmb2_promociones_taxonomia_fecha_final;

						const dentroDeRango =
							fechaInicio &&
							fechaFinal &&
							ahora >= Number( fechaInicio ) &&
							ahora <= Number( fechaFinal );
						const mostrarDesvanecida =
							! promocionActiva || ! dentroDeRango;

						const handleCopyCode = () => {
							navigator.clipboard.writeText( codigoDescuento );
							setCopiedId( promocion.id );
							setTimeout( () => {
								setCopiedId( null );
							}, 2000 );
						};

						const handleKeyDown = ( e ) => {
							if ( e.key === 'Enter' || e.key === ' ' ) {
								e.preventDefault();
								handleCopyCode();
							}
						};

						return (
							<p
								key={ promocion.id }
								className="promocion-item"
								style={
									mostrarDesvanecida
										? { opacity: 0.35 }
										: undefined
								}
							>
								Promoción: { nombrePromocion }
								{ porcentajeDescuento &&
									` ( -${ porcentajeDescuento }% )` }
								{ codigoDescuento && (
									<>
										{ ' . CÓDIGO: ' }
										<span
											role="button"
											tabIndex={ 0 }
											className="codigo-badge"
											onClick={ handleCopyCode }
											onKeyDown={ handleKeyDown }
										>
											{ codigoDescuento }
											{ copiedId === promocion.id && (
												<span className="codigo-copiado-tooltip">
													Código copiado
												</span>
											) }
										</span>
									</>
								) }
							</p>
						);
					} ) }
				</>
			) : (
				<p className="promocion-item">{ __( '', 'makistyle' ) }</p>
			) }
		</div>
	);
}
