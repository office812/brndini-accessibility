(function () {
	const body = document.body;
	if (!body) {
		return;
	}

	body.classList.add('ab-focus-ready');

	const skipLink = document.querySelector('.ab-skip-link');
	if (!(skipLink instanceof HTMLAnchorElement)) {
		return;
	}

	const target =
		document.querySelector('#main-content') ||
		document.querySelector('main') ||
		document.querySelector('[role="main"]') ||
		document.querySelector('#content');

	if (!(target instanceof HTMLElement)) {
		return;
	}

	if (!target.id) {
		target.id = 'main-content';
	}

	skipLink.href = '#' + target.id;
})();
