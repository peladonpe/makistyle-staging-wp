// const body = document.querySelector("body");
// const clasesBody = body.classList;
// if (clasesBody.contains("single")) {
//   console.log("is single");
// }
// import { URL_SERVIDOR } from "./const.js";

const audios = document.querySelectorAll('audio');

document.addEventListener('DOMContentLoaded', function () {
	if (audios) {
		for (let audio of audios) {
			audio.controlsList = 'nodownload';
		}
	}

	window.mkv2YtPlayers = window.mkv2YtPlayers || [];

	const containers = document.querySelectorAll('[data-youtube-destacado]');

	if (containers.length > 0) {
		// Cargar el script de la API de YouTube solo una vez
		if (!document.getElementById('script-youtube')) {
			const tag = document.createElement('script');
			tag.id = 'script-youtube';
			tag.src = 'https://www.youtube.com/iframe_api';
			const firstScriptTag = document.getElementsByTagName('script')[0];
			firstScriptTag.parentNode.insertBefore(tag, firstScriptTag);
		}

		window.onYouTubeIframeAPIReady = function () {
			containers.forEach((container) => {
				const playerId = container.getAttribute('id');
				const videoId = container.getAttribute('data-videoid');
				const isAutoplay =
					container.getAttribute('data-autoplay') === '1';
				const heightVideo =
					container.getAttribute('data-height') ?? '400';
				const spinnerId = container.getAttribute('data-spinner-id');
				const spinnerEl = spinnerId
					? document.getElementById(spinnerId)
					: null;
				const unmuteBtn =
					container.parentElement?.querySelector('.mkv2-unmute-btn');

				const player = new YT.Player(playerId, {
					height: heightVideo,
					width: '100%',
					videoId,
					playerVars: {
						playsinline: 1,
						...(isAutoplay ? { autoplay: 1, mute: 1 } : {}),
					},
					events: {
						onReady: () => {
							if (spinnerEl) spinnerEl.remove();
							if (isAutoplay && unmuteBtn) {
								unmuteBtn.addEventListener('click', () => {
									unmuteBtn.remove();
									player.seekTo(0);
									player.unMute();
									player.playVideo();
								});
							}
						},
						onStateChange: (event) => {
							if (event.data === YT.PlayerState.PLAYING) {
								window.mkv2YtPlayers.forEach((p) => {
									if (p !== player) {
										p.pauseVideo();
									}
								});
							}
						},
						onVolumeChange: () => {
							if (unmuteBtn && !player.isMuted()) {
								unmuteBtn.remove();
							}
						},
					},
				});
				window.mkv2YtPlayers.push(player);
			});
		};
	}

	// Lógica para videos locales
	const localVideos = document.querySelectorAll('.mkv2-local-video');
	localVideos.forEach((video) => {
		const container = video.closest('.mkv2-yt-wrapper');
		const unmuteBtn = container
			? container.querySelector('.mkv2-unmute-btn')
			: null;

		if (unmuteBtn) {
			unmuteBtn.addEventListener('click', () => {
				video.muted = false;
				video.currentTime = 0;
				video.play();
				unmuteBtn.remove();
			});

			// Si el usuario activa el audio manualmente desde los controles del video
			video.addEventListener('volumechange', () => {
				if (!video.muted && video.volume > 0) {
					unmuteBtn.remove();
				}
			});
		}
	});
});
