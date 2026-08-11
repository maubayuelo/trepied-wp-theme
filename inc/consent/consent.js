/**
 * Loi 25 consent banner + preference panel.
 *
 * Cookie contract (shared with a future React headless port — do not
 * rename without updating that port too):
 *   name: magneto_consent
 *   value: { v: 1, analytics: bool, marketing: bool, ts: unix_seconds }
 *   6 months, SameSite=Lax, Secure on HTTPS
 *
 * Single source of truth: window.magnetoConsent.get()/.set()/.openPanel().
 * Other scripts (GA4, Meta Pixel) must listen for the `magneto:consent`
 * CustomEvent instead of reading the cookie themselves.
 */
(function () {
	'use strict';

	var data = window.magnetoConsentData || {};
	var COOKIE_NAME = data.cookieName || 'magneto_consent';
	var VERSION = data.version || 1;
	var strings = data.strings || {};
	var privacyUrl = data.privacyUrl || '';

	// ---- Cookie helpers ----
	function getCookie(name) {
		var escaped = name.replace(/([.$?*|{}()[\]\\/+^])/g, '\\$1');
		var match = document.cookie.match(new RegExp('(?:^|; )' + escaped + '=([^;]*)'));
		return match ? decodeURIComponent(match[1]) : null;
	}

	function setCookie(name, value) {
		var maxAge = 60 * 60 * 24 * 30 * 6; // 6 months
		var secure = window.location.protocol === 'https:' ? '; Secure' : '';
		document.cookie = name + '=' + encodeURIComponent(value) + '; Max-Age=' + maxAge + '; Path=/; SameSite=Lax' + secure;
	}

	// ---- State ----
	function readState() {
		var raw = getCookie(COOKIE_NAME);
		if (!raw) {
			return null;
		}

		var parsed;
		try {
			parsed = JSON.parse(raw);
		} catch (e) {
			return null;
		}

		if (!parsed || parsed.v !== VERSION) {
			return null;
		}

		return {
			analytics: !!parsed.analytics,
			marketing: !!parsed.marketing,
		};
	}

	function dispatchConsent(analytics, marketing) {
		window.dispatchEvent(new CustomEvent('magneto:consent', {
			detail: { analytics: !!analytics, marketing: !!marketing },
		}));
	}

	var currentState = readState();

	function writeState(analytics, marketing) {
		var payload = {
			v: VERSION,
			analytics: !!analytics,
			marketing: !!marketing,
			ts: Math.floor(Date.now() / 1000),
		};
		setCookie(COOKIE_NAME, JSON.stringify(payload));
		currentState = { analytics: payload.analytics, marketing: payload.marketing };
		dispatchConsent(currentState.analytics, currentState.marketing);
		return currentState;
	}

	// ---- DOM helpers ----
	function el(tag, attrs, children) {
		var node = document.createElement(tag);
		attrs = attrs || {};
		Object.keys(attrs).forEach(function (key) {
			if (key === 'html') {
				node.innerHTML = attrs[key];
			} else if (key === 'class') {
				node.className = attrs[key];
			} else {
				node.setAttribute(key, attrs[key]);
			}
		});
		(children || []).forEach(function (child) {
			if (child) {
				node.appendChild(child);
			}
		});
		return node;
	}

	// ---- Banner (first level) ----
	var banner;

	function buildBanner() {
		var text = el('p', { class: 'magneto-consent-banner__text' });
		text.appendChild(document.createTextNode(strings.bannerMessageBefore || ''));
		if (privacyUrl) {
			var link = el('a', { href: privacyUrl });
			link.textContent = strings.bannerMessageLink || '';
			text.appendChild(link);
		} else {
			text.appendChild(document.createTextNode(strings.bannerMessageLink || ''));
		}
		text.appendChild(document.createTextNode(strings.bannerMessageAfter || ''));

		var acceptBtn = el('button', { type: 'button', class: 'magneto-consent-btn magneto-consent-btn--accept' });
		acceptBtn.textContent = strings.acceptAll || 'Accept All';
		acceptBtn.addEventListener('click', function () {
			writeState(true, true);
			hideBanner();
		});

		var rejectBtn = el('button', { type: 'button', class: 'magneto-consent-btn magneto-consent-btn--reject' });
		rejectBtn.textContent = strings.rejectAll || 'Reject All';
		rejectBtn.addEventListener('click', function () {
			writeState(false, false);
			hideBanner();
		});

		var customizeBtn = el('button', { type: 'button', class: 'magneto-consent-btn magneto-consent-btn--customize' });
		customizeBtn.textContent = strings.customize || 'Customize';
		customizeBtn.addEventListener('click', function () {
			openPanel(customizeBtn);
		});

		var actions = el('div', { class: 'magneto-consent-banner__actions' }, [acceptBtn, rejectBtn, customizeBtn]);
		var inner = el('div', { class: 'magneto-consent-banner__inner' }, [text, actions]);

		banner = el('div', {
			class: 'magneto-consent-banner',
			role: 'region',
			'aria-label': strings.panelOpenAria || 'Cookie consent',
		}, [inner]);

		document.body.appendChild(banner);
	}

	function showBanner() {
		if (!banner) {
			buildBanner();
		}
		requestAnimationFrame(function () {
			banner.classList.add('is-visible');
		});
	}

	function hideBanner() {
		if (banner) {
			banner.classList.remove('is-visible');
		}
	}

	// ---- Panel (customize) ----
	var backdrop, panel, lastFocusedEl, analyticsInput, marketingInput;

	function makeToggle(id, checked, disabled) {
		var input = el('input', { type: 'checkbox', id: id });
		if (checked) {
			input.checked = true;
		}
		if (disabled) {
			input.disabled = true;
		}
		var track = el('span', { class: 'magneto-consent-toggle__track' });
		var thumb = el('span', { class: 'magneto-consent-toggle__thumb' });
		return el('label', { class: 'magneto-consent-toggle' }, [input, track, thumb]);
	}

	function buildCategoryRow(labelText, descText, toggleWrap, forId) {
		var labelEl = el('label', { class: 'magneto-consent-category__label', for: forId });
		labelEl.textContent = labelText || '';
		var descEl = el('p', { class: 'magneto-consent-category__desc' });
		descEl.textContent = descText || '';
		var textWrap = el('div', { class: 'magneto-consent-category__text' }, [labelEl, descEl]);
		return el('div', { class: 'magneto-consent-category' }, [textWrap, toggleWrap]);
	}

	function buildPanel() {
		var state = currentState || { analytics: false, marketing: false };

		var essentialToggle = makeToggle('magneto-cat-essential', true, true);
		var analyticsToggle = makeToggle('magneto-cat-analytics', state.analytics, false);
		var marketingToggle = makeToggle('magneto-cat-marketing', state.marketing, false);

		analyticsInput = analyticsToggle.querySelector('input');
		marketingInput = marketingToggle.querySelector('input');

		var essentialRow = buildCategoryRow(strings.essentialLabel, strings.essentialDesc, essentialToggle, 'magneto-cat-essential');
		var analyticsRow = buildCategoryRow(strings.analyticsLabel, strings.analyticsDesc, analyticsToggle, 'magneto-cat-analytics');
		var marketingRow = buildCategoryRow(strings.marketingLabel, strings.marketingDesc, marketingToggle, 'magneto-cat-marketing');

		var titleEl = el('h2', { class: 'magneto-consent-panel__title', id: 'magneto-consent-title' });
		titleEl.textContent = strings.panelTitle || 'Manage Cookie Preferences';

		var introEl = el('p', { class: 'magneto-consent-panel__intro' });
		introEl.textContent = strings.panelIntro || '';

		var closeBtn = el('button', {
			type: 'button',
			class: 'magneto-consent-panel__close',
			'aria-label': strings.close || 'Close',
		});
		closeBtn.innerHTML = '&times;';
		closeBtn.addEventListener('click', closePanel);

		var saveBtn = el('button', { type: 'button', class: 'magneto-consent-panel__save' });
		saveBtn.textContent = strings.save || 'Save My Choices';
		saveBtn.addEventListener('click', function () {
			writeState(analyticsInput.checked, marketingInput.checked);
			hideBanner();
			closePanel();
		});

		panel = el('div', {
			class: 'magneto-consent-panel',
			role: 'dialog',
			'aria-modal': 'true',
			'aria-labelledby': 'magneto-consent-title',
		}, [closeBtn, titleEl, introEl, essentialRow, analyticsRow, marketingRow, saveBtn]);

		backdrop = el('div', { class: 'magneto-consent-backdrop' }, [panel]);
		backdrop.addEventListener('click', function (event) {
			if (event.target === backdrop) {
				closePanel();
			}
		});

		document.body.appendChild(backdrop);
	}

	function getFocusableEls() {
		var selector = 'button, [href], input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])';
		return Array.prototype.slice.call(panel.querySelectorAll(selector)).filter(function (node) {
			return !node.disabled && node.offsetParent !== null;
		});
	}

	function onKeydown(event) {
		if (event.key === 'Escape') {
			closePanel();
			return;
		}

		if (event.key !== 'Tab') {
			return;
		}

		var focusableEls = getFocusableEls();
		if (!focusableEls.length) {
			return;
		}

		var first = focusableEls[0];
		var last = focusableEls[focusableEls.length - 1];

		if (event.shiftKey && document.activeElement === first) {
			event.preventDefault();
			last.focus();
		} else if (!event.shiftKey && document.activeElement === last) {
			event.preventDefault();
			first.focus();
		}
	}

	function openPanel(triggerEl) {
		if (!backdrop) {
			buildPanel();
		} else {
			// Re-sync toggles with the latest known state each time it opens
			var state = currentState || { analytics: false, marketing: false };
			analyticsInput.checked = state.analytics;
			marketingInput.checked = state.marketing;
		}

		lastFocusedEl = triggerEl || document.activeElement;

		backdrop.classList.add('is-visible');
		document.body.classList.add('magneto-consent-lock');
		document.addEventListener('keydown', onKeydown);

		var focusableEls = getFocusableEls();
		if (focusableEls.length) {
			focusableEls[0].focus();
		}
	}

	function closePanel() {
		if (!backdrop) {
			return;
		}
		backdrop.classList.remove('is-visible');
		document.body.classList.remove('magneto-consent-lock');
		document.removeEventListener('keydown', onKeydown);
		if (lastFocusedEl && typeof lastFocusedEl.focus === 'function') {
			lastFocusedEl.focus();
		}
	}

	// ---- Public API ----
	window.magnetoConsent = {
		get: function () {
			return currentState ? { analytics: currentState.analytics, marketing: currentState.marketing } : null;
		},
		set: function (choice) {
			choice = choice || {};
			writeState(choice.analytics, choice.marketing);
			hideBanner();
		},
		openPanel: function () {
			openPanel(document.activeElement);
		},
	};

	// ---- Init ----
	function init() {
		// Let any listener registered before this point (e.g. GA4/Pixel
		// gating scripts) know the current state immediately on load.
		dispatchConsent(currentState ? currentState.analytics : false, currentState ? currentState.marketing : false);

		if (!currentState) {
			showBanner();
		}

		var manageLink = document.getElementById('magneto-consent-manage');
		if (manageLink) {
			manageLink.addEventListener('click', function (event) {
				event.preventDefault();
				openPanel(manageLink);
			});
		}
	}

	if (document.readyState === 'loading') {
		document.addEventListener('DOMContentLoaded', init);
	} else {
		init();
	}
})();
