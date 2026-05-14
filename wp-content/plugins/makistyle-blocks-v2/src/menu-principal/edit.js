import { __ } from '@wordpress/i18n';
import { useBlockProps, URLInput } from '@wordpress/block-editor';
import { Button, TextControl } from '@wordpress/components';
import { useState } from '@wordpress/element';

export default function Edit( { attributes, setAttributes } ) {
	const { menuItems } = attributes;
	const [ isExpanded, setIsExpanded ] = useState( false );

	// Inicializar menuItems si está vacío
	if ( menuItems.length === 0 ) {
		setAttributes( {
			menuItems: [
				{
					text: '',
					url: '',
					id: crypto.randomUUID(),
				},
			],
		} );
	}

	const updateMenuItem = ( index, field, value ) => {
		const newMenuItems = [ ...menuItems ];
		newMenuItems[ index ][ field ] = value;
		setAttributes( { menuItems: newMenuItems } );
	};

	const addMenuItem = () => {
		const newItem = {
			text: '',
			url: '',
			id: crypto.randomUUID(), // ID único
		};
		setAttributes( {
			menuItems: [ ...menuItems, newItem ],
		} );
	};

	const deleteMenuItem = ( index ) => {
		const newMenuItems = menuItems.filter( ( _, i ) => i !== index );
		setAttributes( { menuItems: newMenuItems } );
	};

	// Verificar si hay campos vacíos
	const hasEmptyFields = menuItems.some(
		( item ) => ! item.text.trim() || ! item.url.trim()
	);
	return (
		<div { ...useBlockProps() }>
			<h3>{ __( 'Menú', 'makistyle' ) }</h3>

			{ /* Acordeón personalizado */ }
			<div className="menu-acordeon-wrap">
				<button
					onClick={ () => setIsExpanded( ! isExpanded ) }
					className="menu-acordeon-toggle"
					type="button"
				>
					<span>{ __( 'Elementos del menú', 'makistyle' ) }</span>
					<span>{ isExpanded ? '▼' : '▶' }</span>
				</button>

				{ isExpanded && (
					<div className="menu-acordeon-content">
						{ menuItems.map( ( item, index ) => (
							<div
								key={ item.id || index }
								className="menu-item-wrap"
							>
								<div
									className={ `menu-item-field${
										! item.text.trim()
											? ' campo-invalido'
											: ''
									}` }
								>
									<TextControl
										label={ __( 'Texto', 'makistyle' ) }
										value={ item.text }
										onChange={ ( value ) =>
											updateMenuItem(
												index,
												'text',
												value
											)
										}
									/>
								</div>
								<div className="menu-item-url-wrap">
									<label
										className="menu-item-url-label"
										htmlFor={ `url-input-${ index }` }
									>
										{ __( 'URL', 'makistyle' ) }
									</label>
									<div
										className={ `menu-item-url-input-wrap${
											! item.url.trim()
												? ' campo-invalido'
												: ''
										}` }
									>
										<URLInput
											id={ `url-input-${ index }` }
											value={ item.url }
											onChange={ ( value ) =>
												updateMenuItem(
													index,
													'url',
													value
												)
											}
										/>
									</div>
								</div>
								<Button
									onClick={ () => deleteMenuItem( index ) }
									className="btn-eliminar"
								>
									{ __( 'Eliminar', 'makistyle' ) }
								</Button>
							</div>
						) ) }
						<Button
							onClick={ addMenuItem }
							disabled={ hasEmptyFields }
							className="btn-anadir"
						>
							{ __( 'Añadir', 'makistyle' ) }
						</Button>
					</div>
				) }
			</div>
		</div>
	);
}
