// window.mkv2YtPlayers = window.mkv2YtPlayers || [];

// const containers = document.querySelectorAll( '[data-youtube-destacado]' );

// if ( containers.length > 0 ) {
// 	// Cargar el script de la API de YouTube solo una vez
// 	if ( ! document.getElementById( 'script-youtube' ) ) {
// 		const tag = document.createElement( 'script' );
// 		tag.id = 'script-youtube';
// 		tag.src = 'https://www.youtube.com/iframe_api';
// 		const firstScriptTag = document.getElementsByTagName( 'script' )[ 0 ];
// 		firstScriptTag.parentNode.insertBefore( tag, firstScriptTag );
// 	}

// 	window.onYouTubeIframeAPIReady = function () {
// 		containers.forEach( ( container ) => {
// 			const playerId = container.getAttribute( 'id' );
// 			const videoId = container.getAttribute( 'data-videoid' );
// 			const isAutoplay =
// 				container.getAttribute( 'data-autoplay' ) === '1';
// 			const heightVideo =
// 				container.getAttribute( 'data-height' ) ?? '600';
// 			const spinnerId = container.getAttribute( 'data-spinner-id' );
// 			const spinnerEl = spinnerId
// 				? document.getElementById( spinnerId )
// 				: null;

// 			const player = new YT.Player( playerId, {
// 				height: heightVideo,
// 				width: '100%',
// 				videoId,
// 				playerVars: { playsinline: 1 },
// 				events: {
// 					onReady: ( event ) => {
// 						if ( spinnerEl ) spinnerEl.remove();
// 						if ( isAutoplay ) {
// 							player.mute();
// 							event.target.playVideo();
// 						}
// 					},
// 					onStateChange: ( event ) => {
// 						if ( event.data === YT.PlayerState.PLAYING ) {
// 							window.mkv2YtPlayers.forEach( ( p ) => {
// 								if ( p !== player ) {
// 									p.pauseVideo();
// 								}
// 							} );
// 						}
// 					},
// 				},
// 			} );
// 			window.mkv2YtPlayers.push( player );
// 		} );
// 	};
// }
