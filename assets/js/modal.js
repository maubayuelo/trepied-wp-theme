/**
 * Reusable accessible dialog module.
 *
 * Handles focus trap, Esc, backdrop click, body scroll lock, and focus
 * restore to the trigger element. Consumers own their own content via
 * onOpen/onClose callbacks — this module only manages the dialog
 * lifecycle, not what's inside it.
 *
 * Usage:
 *   const modal = TrepiedModal.create({
 *     root: document.getElementById('my-modal'),   // backdrop/overlay element
 *     dialog: document.querySelector('.my-modal__dialog'), // focus-trap scope (defaults to root)
 *     onOpen: () => { ... },
 *     onClose: () => { ... },
 *   });
 *   trigger.addEventListener('click', () => modal.open(trigger));
 *   closeBtn.addEventListener('click', () => modal.close());
 *
 * The markup is expected to already carry role="dialog" aria-modal="true"
 * and aria-labelledby on the dialog element — this module does not set
 * ARIA attributes, since the labelling text is caller-specific.
 */
(function (global) {
	'use strict';

	function getFocusableEls(container) {
		var selector = 'button, [href], input:not([type="hidden"]), select, textarea, [tabindex]:not([tabindex="-1"])';
		return Array.prototype.slice.call(container.querySelectorAll(selector)).filter(function (node) {
			return !node.disabled && node.offsetParent !== null;
		});
	}

	function create(options) {
		options = options || {};

		var root = options.root;
		if (!root) {
			throw new Error('TrepiedModal.create: `root` element is required.');
		}

		var dialog = options.dialog || root;
		var onOpen = typeof options.onOpen === 'function' ? options.onOpen : function () {};
		var onClose = typeof options.onClose === 'function' ? options.onClose : function () {};
		var lockClass = options.lockClass || 'modal-open';
		var visibleClass = options.visibleClass || 'show';
		var hiddenClass = options.hiddenClass || 'hidden';
		var closeOnBackdrop = options.closeOnBackdrop !== false;

		var lastFocusedEl = null;
		var isOpen = false;

		function handleKeydown(event) {
			if (event.key === 'Escape') {
				close();
				return;
			}

			if (event.key !== 'Tab') {
				return;
			}

			var focusableEls = getFocusableEls(dialog);
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

		function handleBackdropClick(event) {
			if (closeOnBackdrop && event.target === root) {
				close();
			}
		}

		function open(triggerEl) {
			if (isOpen) {
				return;
			}
			isOpen = true;
			lastFocusedEl = triggerEl || document.activeElement;

			onOpen();

			root.classList.remove(hiddenClass);
			root.classList.add(visibleClass);
			document.body.classList.add(lockClass);
			document.body.style.overflow = 'hidden';

			document.addEventListener('keydown', handleKeydown);
			root.addEventListener('click', handleBackdropClick);

			var focusableEls = getFocusableEls(dialog);
			if (focusableEls.length) {
				focusableEls[0].focus();
			} else if (typeof dialog.focus === 'function') {
				dialog.focus();
			}
		}

		function close() {
			if (!isOpen) {
				return;
			}
			isOpen = false;

			root.classList.add(hiddenClass);
			root.classList.remove(visibleClass);
			document.body.classList.remove(lockClass);
			document.body.style.overflow = '';

			document.removeEventListener('keydown', handleKeydown);
			root.removeEventListener('click', handleBackdropClick);

			// onClose (content teardown) runs before focus restore so any
			// media (iframes, etc.) is gone from the DOM before the user's
			// focus lands back on the trigger.
			onClose();

			if (lastFocusedEl && typeof lastFocusedEl.focus === 'function') {
				lastFocusedEl.focus();
			}
			lastFocusedEl = null;
		}

		return {
			open: open,
			close: close,
			isOpen: function () {
				return isOpen;
			},
		};
	}

	global.TrepiedModal = { create: create };
})(window);
