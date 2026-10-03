/**
 * Calendly popup: assets load strictly on click, never preloaded.
 * Preloading (even on idle) would set Calendly cookies before consent —
 * the same Loi 25 violation already gated for GA4 and Meta Pixel.
 */
(function () {
	'use strict';

	var data = window.trepiedCalendlyData || {};
	var defaultUrl = data.url || '';

	var calendlyLoaded = false;
	var calendlyLoading = false;
	var pendingCallbacks = [];

	function loadCalendly(callback) {
		if (calendlyLoaded) {
			if (callback) callback();
			return;
		}

		if (callback) pendingCallbacks.push(callback);

		if (calendlyLoading) return;
		calendlyLoading = true;

		var link = document.createElement('link');
		link.rel = 'stylesheet';
		link.href = 'https://assets.calendly.com/assets/external/widget.css';
		document.head.appendChild(link);

		var script = document.createElement('script');
		script.src = 'https://assets.calendly.com/assets/external/widget.js';
		script.async = true;
		script.onload = function () {
			calendlyLoaded = true;
			calendlyLoading = false;
			pendingCallbacks.forEach(function (cb) { cb(); });
			pendingCallbacks = [];
		};
		script.onerror = function () {
			calendlyLoading = false;
			pendingCallbacks = [];
			console.error('Failed to load Calendly');
		};
		document.body.appendChild(script);
	}

	function openCalendly(url) {
		if (typeof window.Calendly !== 'undefined') {
			window.Calendly.initPopupWidget({ url: url });
		}
	}

	// GA4 Key Event: conversion signal fired when a user opens the Calendly
	// popup. Uses gtag() directly (never dataLayer.push) to match the
	// pattern in inc/consent/analytics.php. Silently skips when gtag isn't
	// defined — e.g. no GA4 ID configured, non-production, or consent not
	// yet granting analytics (Consent Mode still queues it either way).
	function trackBookClick(trigger) {
		if (typeof gtag !== 'function') return;

		var section = trigger.closest('section');
		var ctaLocation = (section && section.id) || (trigger.closest('nav') ? 'nav' : 'unknown');

		gtag('event', 'book_click', { cta_location: ctaLocation });
	}

	document.addEventListener('click', function (event) {
		var trigger = event.target.closest('.calendly-popup-trigger');
		if (!trigger) return;

		event.preventDefault();
		var url = trigger.dataset.calendlyUrl || defaultUrl;

		trackBookClick(trigger);

		loadCalendly(function () {
			openCalendly(url);
		});
	});
})();
