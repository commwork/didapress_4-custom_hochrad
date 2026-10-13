/**
 * Layout G: keep the hero and main navigation hidden until the hero media is ready.
 */
(function () {
	var root = document.querySelector('.section--layout-g');
	if (!root || !document.documentElement.classList.contains('is-layout-g-loading')) {
		return;
	}

	var DESKTOP_MQ = window.matchMedia('(min-width: 768px)');
	var REDUCE_MQ = window.matchMedia('(prefers-reduced-motion: reduce)');
	var MAX_WAIT = 10000;
	var revealed = false;

	function reveal() {
		if (revealed) {
			return;
		}
		revealed = true;
		document.documentElement.classList.remove('is-layout-g-loading');
	}

	function whenImage(img) {
		if (!img) {
			return Promise.resolve();
		}
		if (img.complete) {
			return Promise.resolve();
		}
		return new Promise(function (resolve) {
			img.addEventListener('load', resolve, { once: true });
			img.addEventListener('error', resolve, { once: true });
		});
	}

	function whenVideo(video) {
		if (!video) {
			return Promise.resolve();
		}
		if (video.readyState >= 2) {
			return Promise.resolve();
		}
		video.preload = 'auto';
		if (video.paused && video.networkState !== 2) {
			video.load();
		}
		return new Promise(function (resolve) {
			video.addEventListener('loadeddata', resolve, { once: true });
			video.addEventListener('error', resolve, { once: true });
		});
	}

	function heroReady() {
		if (REDUCE_MQ.matches) {
			return Promise.resolve();
		}

		if (DESKTOP_MQ.matches) {
			return whenVideo(root.querySelector('.header-video-bg__media'));
		}

		return whenImage(root.querySelector('.header-video-bg__placeholder'));
	}

	window.setTimeout(reveal, MAX_WAIT);
	heroReady().then(reveal);
})();
