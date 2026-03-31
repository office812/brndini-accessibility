document.addEventListener('DOMContentLoaded', function () {
  var copyButton = document.querySelector('[data-copy-target]');

  if (copyButton) {
    copyButton.addEventListener('click', function () {
      var targetId = copyButton.getAttribute('data-copy-target');
      var target = targetId ? document.getElementById(targetId) : null;

      if (!target || !navigator.clipboard) {
        return;
      }

      navigator.clipboard.writeText(target.textContent || '').then(function () {
        copyButton.textContent = 'הועתק';

        window.setTimeout(function () {
          copyButton.textContent = 'העתק קוד הטמעה';
        }, 1500);
      });
    });
  }

  var preview = document.getElementById('widget-preview');
  var previewButton = document.getElementById('widget-preview-button');
  var previewIcon = document.getElementById('widget-preview-icon');
  var previewLabel = document.getElementById('widget-preview-label');

  if (!preview || !previewButton) {
    return;
  }

  var colorInput = document.querySelector('[data-preview="color"]');
  var labelInput = document.querySelector('[data-preview="label"]');
  var positionInput = document.querySelector('[data-preview="position"]');
  var sizeInput = document.querySelector('[data-preview="size"]');
  var buttonModeInput = document.querySelector('[data-preview="button-mode"]');
  var buttonStyleInput = document.querySelector('[data-preview="button-style"]');
  var iconInput = document.querySelector('[data-preview="icon"]');

  function getPreviewIcon(type) {
    switch (type) {
      case 'spark':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l1.9 5.1L19 10l-5.1 1.9L12 17l-1.9-5.1L5 10l5.1-1.9L12 3z" fill="currentColor"/></svg>';
      case 'shield':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 3l7 3v5c0 4.4-2.9 8.4-7 10-4.1-1.6-7-5.6-7-10V6l7-3z" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"/></svg>';
      case 'pulse':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M3 12h4l2-4 3 8 2-4h7" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      default:
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><circle cx="12" cy="5.3" r="2.2" fill="currentColor"/><path d="M12 9v5m0 0l-3.6 6m3.6-6l3.6 6m-7.2-3.2H6m12 0h-2.4M9.2 10.7 6.4 14m8.4-3.3 2.8 3.3" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
    }
  }

  function syncPreview() {
    if (colorInput) {
      previewButton.style.setProperty('--preview-widget-color', colorInput.value);
    }

    if (labelInput && previewLabel) {
      previewLabel.textContent = labelInput.value || 'נגישות';
    }

    if (positionInput) {
      preview.classList.remove('preview-bottom-right', 'preview-bottom-left');
      preview.classList.add('preview-' + positionInput.value);
    }

    if (sizeInput) {
      preview.classList.remove('preview-size-compact', 'preview-size-comfortable', 'preview-size-large');
      preview.classList.add('preview-size-' + sizeInput.value);
    }

    if (buttonModeInput) {
      previewButton.classList.remove('preview-mode-icon-label', 'preview-mode-label-only', 'preview-mode-icon-only');
      previewButton.classList.add('preview-mode-' + buttonModeInput.value);
    }

    if (buttonStyleInput) {
      previewButton.classList.remove('preview-style-solid', 'preview-style-soft', 'preview-style-glass', 'preview-style-midnight');
      previewButton.classList.add('preview-style-' + buttonStyleInput.value);
    }

    if (iconInput && previewIcon) {
      previewIcon.innerHTML = getPreviewIcon(iconInput.value);
      previewIcon.setAttribute('data-icon', iconInput.value);
    }
  }

  [colorInput, labelInput, positionInput, sizeInput, buttonModeInput, buttonStyleInput, iconInput].forEach(function (field) {
    if (field) {
      field.addEventListener('input', syncPreview);
      field.addEventListener('change', syncPreview);
    }
  });

  syncPreview();
});
