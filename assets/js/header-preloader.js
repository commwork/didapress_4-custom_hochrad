/**
 * Layout G: keep the hero and main navigation hidden until the hero media is ready.
 */
(function () {
	var root = document.querySelector('.section--layout-g');
	if (!root) {
		return;
	}

	var videoBg = root.querySelector('.header-video-bg');
	var DESKTOP_MQ = window.matchMedia('(min-width: 768px)');
	var WIDE_MQ = window.matchMedia('(min-width: 1200px)');
	var REDUCE_MQ = window.matchMedia('(prefers-reduced-motion: reduce)');
	var MAX_WAIT = 10000;
	var revealed = false;

	function applyFullHeight() {
		if (!videoBg || !WIDE_MQ.matches) {
			return;
		}
		var media = videoBg.querySelector('.header-video-bg__media');
		var max = window.innerHeight * 0.86;
		var height = max;
		if (media && media.videoWidth > 0 && videoBg.clientWidth > 0) {
			height = Math.min(max, videoBg.clientWidth * media.videoHeight / media.videoWidth);
		}
		videoBg.style.setProperty('--header-video-height', height + 'px');
	}

	function finishOpening() {
		document.documentElement.classList.remove('is-header-opening');
		document.documentElement.classList.remove('is-layout-g-loading');
	}

	function expandVideo(defer) {
		if (!videoBg || videoBg.classList.contains('is-expanded')) {
			finishOpening();
			return;
		}
		applyFullHeight();
		if (REDUCE_MQ.matches) {
			videoBg.classList.add('is-expanded');
			finishOpening();
			return;
		}

		function open() {
			videoBg.classList.add('is-expanded');
			document.documentElement.classList.remove('is-layout-g-loading');
			window.setTimeout(finishOpening, 1000);
			videoBg.addEventListener('transitionend', function onEnd(event) {
				if (event.target !== videoBg || event.propertyName !== 'height') {
					return;
				}
				videoBg.removeEventListener('transitionend', onEnd);
				finishOpening();
			});
		}

		if (!defer) {
			open();
			return;
		}

		window.requestAnimationFrame(function () {
			window.requestAnimationFrame(open);
		});
	}

	function reveal(remember) {
		if (revealed) {
			return;
		}
		revealed = true;
		if (remember && window.hochradHeaderMedia) {
			try {
				localStorage.setItem('hochrad-header-ready', window.hochradHeaderMedia);
			} catch (e) {}
		}
		document.documentElement.classList.add('is-header-opening');
		expandVideo(false);
	}

	if (!document.documentElement.classList.contains('is-layout-g-loading')) {
		document.documentElement.classList.add('is-header-opening');
		expandVideo(true);
		return;
	}

	function whenImage(img) {
		if (!img) {
			return Promise.resolve(false);
		}
		if (img.complete) {
			return Promise.resolve(img.naturalWidth > 0);
		}
		return new Promise(function (resolve) {
			img.addEventListener('load', function () { resolve(true); }, { once: true });
			img.addEventListener('error', function () { resolve(false); }, { once: true });
		});
	}

	function whenVideo(video) {
		if (!video) {
			return Promise.resolve(false);
		}
		if (video.readyState >= 2) {
			return Promise.resolve(true);
		}
		video.preload = 'auto';
		if (video.paused && video.networkState !== 2) {
			video.load();
		}
		return new Promise(function (resolve) {
			video.addEventListener('loadeddata', function () { resolve(true); }, { once: true });
			video.addEventListener('error', function () { resolve(false); }, { once: true });
		});
	}

	function heroReady() {
		if (REDUCE_MQ.matches) {
			return Promise.resolve(false);
		}

		if (DESKTOP_MQ.matches) {
			return whenVideo(root.querySelector('.header-video-bg__media'));
		}

		return whenImage(root.querySelector('.header-video-bg__placeholder'));
	}

	window.setTimeout(function () { reveal(false); }, MAX_WAIT);
	heroReady().then(reveal);
})();
