document.addEventListener('DOMContentLoaded', function () {
  function setMenuState(toggle, panel, backdrop, isOpen) {
    toggle.classList.toggle('is-open', isOpen);
    panel.classList.toggle('is-open', isOpen);
    if (backdrop) {
      backdrop.classList.toggle('is-open', isOpen);
    }
    toggle.setAttribute('aria-expanded', String(isOpen));
    document.body.classList.toggle('header-menu-open', isOpen);
  }

  document.querySelectorAll('[data-header-menu-toggle]').forEach(function (toggle) {
    var key = toggle.getAttribute('data-header-menu-toggle');
    var panel = key ? document.querySelector('[data-header-menu-panel="' + key + '"]') : null;
    var backdrop = key ? document.querySelector('[data-header-menu-backdrop="' + key + '"]') : null;

    if (!panel) {
      return;
    }

    toggle.addEventListener('click', function () {
      var isOpen = !panel.classList.contains('is-open');

      document.querySelectorAll('[data-header-menu-panel].is-open').forEach(function (openPanel) {
        var openKey = openPanel.getAttribute('data-header-menu-panel');
        var openToggle = openKey ? document.querySelector('[data-header-menu-toggle="' + openKey + '"]') : null;
        var openBackdrop = openKey ? document.querySelector('[data-header-menu-backdrop="' + openKey + '"]') : null;

        if (openToggle) {
          setMenuState(openToggle, openPanel, openBackdrop, false);
        }
      });

      setMenuState(toggle, panel, backdrop, isOpen);
    });

    document.addEventListener('click', function (event) {
      if (window.innerWidth > 960) {
        return;
      }

      if (!toggle.contains(event.target) && !panel.contains(event.target)) {
        setMenuState(toggle, panel, backdrop, false);
      }
    });

    document.addEventListener('keydown', function (event) {
      if (event.key !== 'Escape') {
        return;
      }

      setMenuState(toggle, panel, backdrop, false);
    });

    panel.querySelectorAll('a, button').forEach(function (node) {
      node.addEventListener('click', function () {
        if (window.innerWidth > 960) {
          return;
        }

        setMenuState(toggle, panel, backdrop, false);
      });
    });

    if (backdrop) {
      backdrop.addEventListener('click', function () {
        setMenuState(toggle, panel, backdrop, false);
      });
    }

    window.addEventListener('resize', function () {
      if (window.innerWidth > 960) {
        setMenuState(toggle, panel, backdrop, false);
      }
    });
  });

  document.querySelectorAll('[data-copy-target]').forEach(function (copyButton) {
    copyButton.addEventListener('click', function () {
      var targetId = copyButton.getAttribute('data-copy-target');
      var target = targetId ? document.getElementById(targetId) : null;

      if (!target || !navigator.clipboard) {
        return;
      }

      navigator.clipboard.writeText(target.textContent || '').then(function () {
        var originalText = copyButton.textContent;
        copyButton.textContent = 'הועתק';

        window.setTimeout(function () {
          copyButton.textContent = originalText;
        }, 1500);
      });
    });
  });

  var siteSwitcher = document.querySelector('[data-site-switcher]');

  if (siteSwitcher) {
    siteSwitcher.addEventListener('change', function () {
      if (siteSwitcher.value) {
        window.location.href = siteSwitcher.value;
      }
    });
  }

  var preview = document.getElementById('widget-preview');
  var previewPanel = document.getElementById('widget-preview-panel');
  var previewButton = document.getElementById('widget-preview-button');
  var previewIcon = document.getElementById('widget-preview-icon');
  var previewLabel = document.getElementById('widget-preview-label');

  if (!preview || !previewButton || !previewPanel) {
    return;
  }

  var colorInput = document.querySelector('[data-preview="color"]');
  var labelInput = document.querySelector('[data-preview="label"]');
  var positionInput = document.querySelector('[data-preview="position"]');
  var sizeInput = document.querySelector('[data-preview="size"]');
  var buttonModeInput = document.querySelector('[data-preview="button-mode"]');
  var buttonStyleInput = document.querySelector('[data-preview="button-style"]');
  var iconInput = document.querySelector('[data-preview="icon"]');
  var presetInput = document.querySelector('[data-preview="preset"]');
  var panelLayoutInput = document.querySelector('[data-preview="panel-layout"]');
  var previewShellChip = document.querySelector('.preview-shell-chip');

  function getLayoutLabel(type) {
    return type === 'split' ? 'מפוצל' : 'מדורג';
  }

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

  function swapClasses(element, list, prefix, value) {
    if (!element) {
      return;
    }

    list.forEach(function (item) {
      element.classList.remove(prefix + item);
    });
    element.classList.add(prefix + value);
  }

  function syncPreview() {
    if (colorInput) {
      previewButton.style.setProperty('--preview-widget-color', colorInput.value);
    }

    if (labelInput && previewLabel) {
      previewLabel.textContent = labelInput.value || 'נגישות';
    }

    if (positionInput) {
      swapClasses(preview, ['bottom-right', 'bottom-left'], 'preview-', positionInput.value);
    }

    if (sizeInput) {
      swapClasses(preview, ['size-compact', 'size-comfortable', 'size-large'], 'preview-', 'size-' + sizeInput.value);
    }

    if (buttonModeInput) {
      swapClasses(previewButton, ['icon-label', 'label-only', 'icon-only'], 'preview-mode-', buttonModeInput.value);
    }

    if (buttonStyleInput) {
      swapClasses(previewButton, ['solid', 'soft', 'glass', 'midnight'], 'preview-style-', buttonStyleInput.value);
    }

    if (presetInput) {
      swapClasses(previewButton, ['classic', 'high-tech', 'elegant', 'bold'], 'preview-preset-', presetInput.value);
      swapClasses(previewPanel, ['classic', 'high-tech', 'elegant', 'bold'], 'preview-preset-', presetInput.value);
    }

    if (panelLayoutInput) {
      swapClasses(previewPanel, ['stacked', 'split'], 'preview-layout-', panelLayoutInput.value);

      if (previewShellChip) {
        previewShellChip.textContent = getLayoutLabel(panelLayoutInput.value);
      }
    }

    if (iconInput && previewIcon) {
      previewIcon.innerHTML = getPreviewIcon(iconInput.value);
      previewIcon.setAttribute('data-icon', iconInput.value);
    }
  }

  [colorInput, labelInput, positionInput, sizeInput, buttonModeInput, buttonStyleInput, iconInput, presetInput, panelLayoutInput].forEach(function (field) {
    if (field) {
      field.addEventListener('input', syncPreview);
      field.addEventListener('change', syncPreview);
    }
  });

  syncPreview();
});
