/**
 * Page header layout H: wallpaper video autoplay on desktop, paused on mobile.
 */
(function () {
	var DESKTOP_MQ = '(min-width: 992px)';
	var REDUCE_MQ = '(prefers-reduced-motion: reduce)';

	function isDesktop() {
		return window.matchMedia(DESKTOP_MQ).matches;
	}

	function prefersReducedMotion() {
		return window.matchMedia(REDUCE_MQ).matches;
	}

	function keepMuted(video) {
		if (!video.muted) {
			video.muted = true;
		}
		video.defaultMuted = true;
		if (video.volume !== 0) {
			video.volume = 0;
		}
		if (!video.hasAttribute('muted')) {
			video.setAttribute('muted', 'muted');
		}
	}

	function setPlaying(root, video, playing) {
		keepMuted(video);
		root.classList.toggle('is-playing', playing);
		root.classList.toggle('is-paused', !playing);

		if (playing) {
			var playPromise = video.play();
			if (playPromise && typeof playPromise.catch === 'function') {
				playPromise.catch(function () {
					root.classList.remove('is-playing');
					root.classList.add('is-paused');
				});
			}
			return;
		}

		video.pause();
	}

	function initRoot(root) {
		var video = root.querySelector('.header-video__media');
		var playBtn = root.querySelector('.header-video__play');
		var stopBtn = root.querySelector('.header-video__stop');

		if (!video) {
			return;
		}

		video.controls = false;
		video.loop = true;
		keepMuted(video);
		video.setAttribute('playsinline', '');
		video.setAttribute('webkit-playsinline', '');
		video.addEventListener('volumechange', function () {
			if (!video.muted || video.volume !== 0) {
				keepMuted(video);
			}
		});

		function applyMode() {
			if (isDesktop() && !prefersReducedMotion()) {
				setPlaying(root, video, true);
				return;
			}

			setPlaying(root, video, false);
		}

		if (playBtn) {
			playBtn.addEventListener('click', function () {
				setPlaying(root, video, true);
			});
		}

		if (stopBtn) {
			stopBtn.addEventListener('click', function () {
				setPlaying(root, video, false);
			});
		}

		if (typeof window.matchMedia(DESKTOP_MQ).addEventListener === 'function') {
			window.matchMedia(DESKTOP_MQ).addEventListener('change', applyMode);
		}

		applyMode();
	}

	function boot() {
		document.querySelectorAll('[data-header-video]').forEach(initRoot);
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', boot);
	} else {
		boot();
	}
})();
