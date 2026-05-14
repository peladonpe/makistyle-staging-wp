import { useBlockProps } from '@wordpress/block-editor';
export default function save( { attributes } ) {
	const { menuItems } = attributes;

	// Filtrar items que tienen texto
	const validMenuItems = menuItems.filter(
		( item ) => item.text && item.text.trim() !== ''
	);

	return (
		<div { ...useBlockProps.save() }>
			{ /* menu  */ }
			<nav
				className="navbar navbar-principal"
				id="navbar-principal"
				data-bs-theme="dark"
			>
				<div className="navbar__zona-superior">
					<button
						className="navbar-toggler"
						type="button"
						data-bs-toggle="offcanvas"
						data-bs-target="#offcanvasMenu"
						aria-controls="offcanvasMenu"
						aria-label="Toggle navigation"
					>
						<span className="navbar-toggler-icon"></span>
					</button>
				</div>
				<div
					className="collapse navbar-collapse show navbar__contenido-colapsable"
					id="navbar__contenido-colapsable"
				></div>
			</nav>
			{ /* fin menu  */ }
			{ /* offcanvas  */ }
			<div
				className="offcanvas offcanvas-start"
				tabIndex="-1"
				id="offcanvasMenu"
				aria-labelledby="offcanvasMenuLabel"
				data-bs-theme="dark"
			>
				<div className="offcanvas-header" data-bs-theme="dark">
					<div
						className="nav-item offcanvas__logo"
						data-bs-dismiss="offcanvas"
					>
						<a href="javascript:void(0)" className="nav-link">
							<img
								className="offcanvas-logo__img"
								src=""
								alt="icono logo"
								style={ { visibility: 'hidden' } }
							/>
						</a>
					</div>
					<button
						type="button"
						className="btn-close offcanvas-header__btn-close"
						id="offcanvas-header__btn-close"
						data-bs-dismiss="offcanvas"
						aria-label="Close"
					></button>
				</div>
				<div className="offcanvas-body">
					<nav className="navbar-offcanvas">
						<ul
							id="menu-menu-general"
							className="navbar-nav offcanvas-body__ul"
						>
							{ validMenuItems.map( ( item, index ) => (
								<li
									key={ item.id || index }
									className="menu-item nav-item offcanvas-body__li"
									data-bs-dismiss="offcanvas"
								>
									<a href={ item.url } className="nav-link">
										{ item.text }
									</a>
								</li>
							) ) }
						</ul>
					</nav>
				</div>
			</div>

			{ /* fin offcanvas  */ }
		</div>
	);
}
