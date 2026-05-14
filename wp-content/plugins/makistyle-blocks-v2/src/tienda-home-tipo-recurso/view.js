( function () {
	const sections = document.querySelectorAll(
		'.wp-block-makistyle-blocks-v2-tienda-home-tipo-recurso'
	);

	sections.forEach( function ( section ) {
		const track = section.querySelector( '.tienda__grid-cards' );
		const viewport = section.querySelector( '.mkv2-carousel-viewport' );
		const btnPrev = section.querySelector( '.mkv2-carousel-btn--prev' );
		const btnNext = section.querySelector( '.mkv2-carousel-btn--next' );
		const items = Array.from( track.children );
		let currentIndex = 0;

		function getCols() {
			if ( window.innerWidth >= 992 ) return 3;
			if ( window.innerWidth >= 576 ) return 2;
			return 1;
		}

		function getGap() {
			const rem = parseFloat(
				getComputedStyle( document.documentElement ).fontSize
			);
			return window.innerWidth >= 992 ? rem * 2 : rem;
		}

		function getCardWidth() {
			const cols = getCols();
			const gap = getGap();
			return ( viewport.offsetWidth - gap * ( cols - 1 ) ) / cols;
		}

		function render( animate ) {
			const cardW = getCardWidth();
			const gap = getGap();
			const maxIndex = Math.max( 0, items.length - getCols() );
			const offset = currentIndex * ( cardW + gap );

			track.style.transition = animate ? 'transform 0.4s ease' : 'none';
			track.style.transform = 'translateX(-' + offset + 'px)';

			btnPrev.hidden = currentIndex <= 0;
			btnNext.hidden = currentIndex >= maxIndex;

			if ( ! animate ) {
				requestAnimationFrame( function () {
					track.style.transition = 'transform 0.4s ease';
				} );
			}
		}

		function updateSizes() {
			const cardW = getCardWidth();
			const maxIndex = Math.max( 0, items.length - getCols() );
			currentIndex = Math.min( currentIndex, maxIndex );
			items.forEach( function ( item ) {
				item.style.width = cardW + 'px';
			} );
			render( false );
		}

		btnPrev.addEventListener( 'click', function () {
			if ( currentIndex > 0 ) {
				currentIndex--;
				render( true );
			}
		} );

		btnNext.addEventListener( 'click', function () {
			const maxIndex = Math.max( 0, items.length - getCols() );
			if ( currentIndex < maxIndex ) {
				currentIndex++;
				render( true );
			}
		} );

		let resizeTimer;
		window.addEventListener( 'resize', function () {
			clearTimeout( resizeTimer );
			resizeTimer = setTimeout( updateSizes, 100 );
		} );

		updateSizes();
	} );
} )();
