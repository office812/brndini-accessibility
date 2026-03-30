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

  if (!preview || !previewButton) {
    return;
  }

  var colorInput = document.querySelector('[data-preview="color"]');
  var labelInput = document.querySelector('[data-preview="label"]');
  var positionInput = document.querySelector('[data-preview="position"]');
  var sizeInput = document.querySelector('[data-preview="size"]');

  function syncPreview() {
    if (colorInput) {
      previewButton.style.backgroundColor = colorInput.value;
    }

    if (labelInput) {
      previewButton.textContent = labelInput.value || 'נגישות';
    }

    if (positionInput) {
      preview.classList.remove('preview-bottom-right', 'preview-bottom-left');
      preview.classList.add('preview-' + positionInput.value);
    }

    if (sizeInput) {
      preview.classList.remove('preview-size-compact', 'preview-size-comfortable', 'preview-size-large');
      preview.classList.add('preview-size-' + sizeInput.value);
    }
  }

  [colorInput, labelInput, positionInput, sizeInput].forEach(function (field) {
    if (field) {
      field.addEventListener('input', syncPreview);
      field.addEventListener('change', syncPreview);
    }
  });

  syncPreview();
});
