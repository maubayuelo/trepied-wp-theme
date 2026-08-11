/**
 * GA4 Consent Mode v2 update + Meta Pixel gating.
 *
 * Listens for `magneto:consent` only — never reads the magneto_consent
 * cookie directly. consent.js is the single source of truth.
 */
(function () {
	'use strict';

	var data = window.magnetoGatingData || {};
	var pixelId = data.pixelId || '';
	var pixelInjected = false;

	function updateGtagConsent(analytics, marketing) {
		if (typeof window.gtag !== 'function') {
			return;
		}
		window.gtag('consent', 'update', {
			analytics_storage: analytics ? 'granted' : 'denied',
			ad_storage: marketing ? 'granted' : 'denied',
			ad_user_data: marketing ? 'granted' : 'denied',
			ad_personalization: marketing ? 'granted' : 'denied',
		});
	}

	function injectMetaPixel() {
		if (pixelInjected || !pixelId) {
			return;
		}
		pixelInjected = true;

		/* eslint-disable */
		(function (f, b, e, v, n, t, s) {
			if (f.fbq) return;
			n = f.fbq = function () {
				n.callMethod ? n.callMethod.apply(n, arguments) : n.queue.push(arguments);
			};
			if (!f._fbq) f._fbq = n;
			n.push = n;
			n.loaded = true;
			n.version = '2.0';
			n.queue = [];
			t = b.createElement(e);
			t.async = true;
			t.src = v;
			s = b.getElementsByTagName(e)[0];
			s.parentNode.insertBefore(t, s);
		})(window, document, 'script', 'https://connect.facebook.net/en_US/fbevents.js');
		/* eslint-enable */

		window.fbq('init', pixelId);
		window.fbq('track', 'PageView');
	}

	// Guard: once injected, a later revoke->re-accept within the same
	// session must not fire a second PageView (pixelInjected stays true).
	function handleConsent(event) {
		var detail = event.detail || {};
		updateGtagConsent(!!detail.analytics, !!detail.marketing);
		if (detail.marketing) {
			injectMetaPixel();
		}
	}

	window.addEventListener('magneto:consent', handleConsent);
})();
