document.addEventListener('click', (event) => {
	const target = event.target;

	if (!(target instanceof HTMLElement)) {
		return;
	}

	if (!target.classList.contains('ab-copy-button')) {
		return;
	}

	const text = target.dataset.copy || '';
	if (!text) {
		return;
	}

	navigator.clipboard.writeText(text).then(() => {
		const original = target.textContent;
		target.textContent = 'Copied';
		window.setTimeout(() => {
			target.textContent = original;
		}, 1500);
	});
});
