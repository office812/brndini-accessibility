document.addEventListener('DOMContentLoaded', function () {
  var topProgress = document.querySelector('[data-top-progress]');
  var progressTimer = null;
  var progressFinishTimer = null;

  function clearProgressTimers() {
    if (progressTimer) {
      window.clearTimeout(progressTimer);
      progressTimer = null;
    }

    if (progressFinishTimer) {
      window.clearTimeout(progressFinishTimer);
      progressFinishTimer = null;
    }
  }

  function startTopProgress() {
    if (!topProgress) {
      return;
    }

    clearProgressTimers();
    document.body.classList.add('is-navigating');
    topProgress.classList.add('is-active');
    topProgress.classList.remove('is-complete');

    progressTimer = window.setTimeout(function () {
      topProgress.classList.add('is-warm');
    }, 180);
  }

  function finishTopProgress() {
    if (!topProgress) {
      return;
    }

    clearProgressTimers();
    topProgress.classList.add('is-active', 'is-complete');

    progressFinishTimer = window.setTimeout(function () {
      topProgress.classList.remove('is-active', 'is-complete', 'is-warm');
      document.body.classList.remove('is-navigating');
    }, 420);
  }

  function loadingLabelFor(text) {
    if (!text) {
      return 'טוען...';
    }

    if (text.indexOf('שמור') !== -1) {
      return 'שומר...';
    }

    if (text.indexOf('הרץ') !== -1 || text.indexOf('בדיקה') !== -1) {
      return 'מריץ...';
    }

    if (text.indexOf('הפעל') !== -1) {
      return 'מפעיל...';
    }

    if (text.indexOf('להתחבר') !== -1 || text.indexOf('התחברות') !== -1) {
      return 'מתחבר...';
    }

    if (text.indexOf('ליצור') !== -1 || text.indexOf('פתיחת') !== -1 || text.indexOf('צור') !== -1) {
      return 'יוצר...';
    }

    if (text.indexOf('פרסם') !== -1) {
      return 'מפרסם...';
    }

    return 'טוען...';
  }

  function setInteractiveLoading(node) {
    if (!node || node.classList.contains('is-loading')) {
      return;
    }

    var rect = node.getBoundingClientRect();
    var originalText = (node.textContent || '').trim();

    node.dataset.loadingOriginal = originalText;
    node.style.width = rect.width + 'px';
    node.classList.add('is-loading');
    node.setAttribute('aria-busy', 'true');

    if (node.tagName === 'BUTTON') {
      node.disabled = true;
    }

    if (originalText) {
      node.textContent = loadingLabelFor(originalText);
    }
  }

  document.documentElement.classList.add('js-ui');
  window.requestAnimationFrame(function () {
    document.body.classList.add('page-is-ready');
    finishTopProgress();
  });

  window.addEventListener('pageshow', function () {
    document.body.classList.add('page-is-ready');
    finishTopProgress();
  });

  function syncStickyHeaderState() {
    document.body.classList.toggle('has-sticky-header-scrolled', window.scrollY > 16);
  }

  syncStickyHeaderState();
  window.addEventListener('scroll', syncStickyHeaderState, { passive: true });

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
        startTopProgress();
        window.location.href = siteSwitcher.value;
      }
    });
  }

  document.querySelectorAll('[data-dashboard-tabs]').forEach(function (tabsRoot) {
    var buttons = Array.prototype.slice.call(tabsRoot.querySelectorAll('[data-dashboard-tab-button]'));
    var panels = Array.prototype.slice.call(tabsRoot.querySelectorAll('[data-dashboard-tab-panel]'));
    var links = Array.prototype.slice.call(tabsRoot.querySelectorAll('[data-dashboard-tab-link]'));

    if (!buttons.length || !panels.length) {
      return;
    }

    function setActiveTab(tabName, shouldUpdateHash) {
      var activeName = tabName;

      if (!panels.some(function (panel) { return panel.getAttribute('data-dashboard-tab-panel') === activeName; })) {
        activeName = buttons[0].getAttribute('data-dashboard-tab-button');
      }

      buttons.forEach(function (button) {
        var isActive = button.getAttribute('data-dashboard-tab-button') === activeName;
        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-selected', String(isActive));
      });

      panels.forEach(function (panel) {
        var isActive = panel.getAttribute('data-dashboard-tab-panel') === activeName;
        panel.classList.toggle('is-active', isActive);
        panel.hidden = !isActive;
      });

      if (shouldUpdateHash) {
        var nextHash = '#tab-' + activeName;
        if (window.location.hash !== nextHash) {
          history.replaceState(null, '', nextHash);
        }
      }
    }

    buttons.forEach(function (button) {
      button.setAttribute('role', 'tab');
      button.addEventListener('click', function () {
        setActiveTab(button.getAttribute('data-dashboard-tab-button'), true);
      });
    });

    panels.forEach(function (panel) {
      panel.setAttribute('role', 'tabpanel');
    });

    links.forEach(function (link) {
      link.addEventListener('click', function () {
        setActiveTab(link.getAttribute('data-dashboard-tab-link'), true);

        var serviceType = link.getAttribute('data-service-type');
        if (serviceType) {
          var serviceSelect = tabsRoot.querySelector('#service_type');
          if (serviceSelect) {
            serviceSelect.value = serviceType;
          }
        }
      });
    });

    var initialHash = (window.location.hash || '').replace('#tab-', '');
    setActiveTab(initialHash || buttons[0].getAttribute('data-dashboard-tab-button'), false);
  });

  document.querySelectorAll('[data-filter-root]').forEach(function (filterRoot) {
    var searchInput = filterRoot.querySelector('[data-filter-search]');
    var fieldInputs = Array.prototype.slice.call(filterRoot.querySelectorAll('[data-filter-field]'));
    var scope = filterRoot.parentElement;
    var items = scope ? Array.prototype.slice.call(scope.querySelectorAll('[data-filter-item]')) : [];

    if (!items.length) {
      return;
    }

    function applyFilters() {
      var searchTerm = searchInput ? (searchInput.value || '').trim().toLowerCase() : '';

      items.forEach(function (item) {
        var matchesSearch = true;
        var matchesFields = true;

        if (searchTerm) {
          var haystack = (item.getAttribute('data-filter-search-text') || '').toLowerCase();
          matchesSearch = haystack.indexOf(searchTerm) !== -1;
        }

        fieldInputs.forEach(function (fieldInput) {
          if (!matchesFields) {
            return;
          }

          var fieldName = fieldInput.getAttribute('data-filter-field');
          var expectedValue = (fieldInput.value || '').trim();

          if (!fieldName || !expectedValue) {
            return;
          }

          var actualValue = item.getAttribute('data-filter-' + fieldName) || '';
          if (actualValue !== expectedValue) {
            matchesFields = false;
          }
        });

        item.hidden = !(matchesSearch && matchesFields);
      });
    }

    if (searchInput) {
      searchInput.addEventListener('input', applyFilters);
    }

    fieldInputs.forEach(function (fieldInput) {
      fieldInput.addEventListener('change', applyFilters);
    });

    applyFilters();
  });

  document.querySelectorAll('[data-leads-status-jump]').forEach(function (button) {
    button.addEventListener('click', function () {
      var status = button.getAttribute('data-leads-status-jump');
      var statusSelect = document.getElementById('super_admin_leads_status');

      if (!statusSelect) {
        return;
      }

      statusSelect.value = status || '';
      statusSelect.dispatchEvent(new Event('change', { bubbles: true }));
      statusSelect.scrollIntoView({ behavior: 'smooth', block: 'center' });
      statusSelect.focus({ preventScroll: true });
    });
  });

  document.querySelectorAll('[data-widget-pane-root]').forEach(function (root) {
    var buttons = Array.prototype.slice.call(root.querySelectorAll('[data-widget-pane-button]'));
    var panes = Array.prototype.slice.call(root.querySelectorAll('[data-widget-pane]'));

    if (!buttons.length || !panes.length) {
      return;
    }

    function setActivePane(name) {
      var activeName = name;

      if (!panes.some(function (pane) { return pane.getAttribute('data-widget-pane') === activeName; })) {
        activeName = buttons[0].getAttribute('data-widget-pane-button');
      }

      buttons.forEach(function (button) {
        var isActive = button.getAttribute('data-widget-pane-button') === activeName;
        button.classList.toggle('is-active', isActive);
        button.setAttribute('aria-selected', String(isActive));
      });

      panes.forEach(function (pane) {
        var isActive = pane.getAttribute('data-widget-pane') === activeName;
        pane.classList.toggle('is-active', isActive);
        pane.hidden = !isActive;
      });
    }

    buttons.forEach(function (button) {
      button.addEventListener('click', function () {
        setActivePane(button.getAttribute('data-widget-pane-button'));
      });
    });

    setActivePane(buttons[0].getAttribute('data-widget-pane-button'));
  });

  document.querySelectorAll('[data-flow-wizard]').forEach(function (form) {
    var stages = Array.prototype.slice.call(form.querySelectorAll('[data-flow-stage]'));
    var stepInput = form.querySelector('[data-flow-step-input]');
    var stepButtons = Array.prototype.slice.call(form.querySelectorAll('[data-flow-step]'));
    var progressFill = form.querySelector('[data-flow-progress-fill]');
    var progressLabel = form.querySelector('[data-flow-progress-label]');
    var progressCaption = form.querySelector('[data-flow-progress-caption]');
    var current = Number(stepInput && stepInput.value ? stepInput.value : 1);

    if (!stages.length) {
      return;
    }

    function findStage(number) {
      return form.querySelector('[data-flow-stage="' + number + '"]');
    }

    function validateStage(number) {
      var stage = findStage(number);
      if (!stage) {
        return true;
      }

      var fields = Array.prototype.slice.call(stage.querySelectorAll('input[required], select[required], textarea[required]'));
      var isValid = true;

      fields.forEach(function (field) {
        if (!field.reportValidity()) {
          isValid = false;
        }
      });

      var password = form.querySelector('#signup_password');
      var confirmation = form.querySelector('#signup_password_confirmation');
      if (stage.contains(password) || stage.contains(confirmation)) {
        if (password && confirmation && password.value !== confirmation.value) {
          confirmation.setCustomValidity('הסיסמאות חייבות להיות זהות.');
          confirmation.reportValidity();
          return false;
        }

        if (confirmation) {
          confirmation.setCustomValidity('');
        }
      }

      return isValid;
    }

    function updateSummary() {
      form.querySelectorAll('[data-flow-summary-target]').forEach(function (node) {
        var fieldName = node.getAttribute('data-flow-summary-target');
        var field = fieldName ? form.querySelector('[name="' + fieldName + '"]') : null;

        if (!field) {
          return;
        }

        if (!node.dataset.flowEmpty) {
          node.dataset.flowEmpty = (node.textContent || '').trim();
        }

        var value = '';
        if (field.tagName === 'SELECT') {
          value = field.options[field.selectedIndex] ? field.options[field.selectedIndex].textContent.trim() : '';
        } else {
          value = (field.value || '').trim();
        }

        node.textContent = value || node.getAttribute('data-flow-empty') || node.dataset.flowEmpty || '';
      });
    }

    function syncSuggestedFields() {
      var domainField = form.querySelector('[name="domain"]');
      var siteNameField = form.querySelector('[name="site_name"]');
      var companyNameField = form.querySelector('[name="company_name"]');

      if (domainField && siteNameField && !siteNameField.value.trim()) {
        var rawDomain = domainField.value.trim()
          .replace(/^https?:\/\//i, '')
          .replace(/^www\./i, '')
          .split('/')[0];

        if (rawDomain) {
          var guessedName = rawDomain.split('.')[0].replace(/[-_]+/g, ' ').trim();
          if (guessedName) {
            siteNameField.value = guessedName.replace(/\b\w/g, function (char) { return char.toUpperCase(); });
          }
        }
      }

      if (siteNameField && companyNameField && !companyNameField.value.trim() && siteNameField.value.trim()) {
        companyNameField.value = siteNameField.value.trim();
      }
    }

    function syncContactRequirements() {
      var contactField = form.querySelector('[name="preferred_contact"]');
      var phoneField = form.querySelector('[name="contact_phone"]');

      if (!contactField || !phoneField) {
        return;
      }

      var requiresPhone = contactField.value === 'phone' || contactField.value === 'whatsapp';
      phoneField.required = requiresPhone;
      phoneField.toggleAttribute('data-required-by-contact', requiresPhone);
    }

    function render() {
      stages.forEach(function (stage, index) {
        stage.classList.toggle('is-active', index + 1 === current);
      });

      stepButtons.forEach(function (button, index) {
        var isActive = index + 1 === current;
        button.classList.toggle('is-active', isActive);
        button.classList.toggle('is-complete', index + 1 < current);
      });

      if (stepInput) {
        stepInput.value = String(current);
      }

      if (progressFill) {
        progressFill.style.width = String((current / stages.length) * 100) + '%';
      }

      if (progressLabel) {
        progressLabel.textContent = 'שלב ' + current + ' מתוך ' + stages.length;
      }

      if (progressCaption) {
        var activeStage = findStage(current);
        progressCaption.textContent = (activeStage && activeStage.getAttribute('data-flow-caption')) || '';
      }

      syncContactRequirements();
      updateSummary();
    }

    form.querySelectorAll('[data-flow-next]').forEach(function (button) {
      button.addEventListener('click', function () {
        if (!validateStage(current)) {
          return;
        }

        current = Math.min(current + 1, stages.length);
        render();
      });
    });

    form.querySelectorAll('[data-flow-prev]').forEach(function (button) {
      button.addEventListener('click', function () {
        current = Math.max(current - 1, 1);
        render();
      });
    });

    stepButtons.forEach(function (button) {
      button.addEventListener('click', function () {
        var target = Number(button.getAttribute('data-flow-step') || current);
        if (target > current && !validateStage(current)) {
          return;
        }

        current = Math.max(1, Math.min(target, stages.length));
        render();
      });
    });

    Array.prototype.slice.call(form.querySelectorAll('input, select, textarea')).forEach(function (field) {
      field.addEventListener('input', function () {
        syncSuggestedFields();
        syncContactRequirements();
        updateSummary();
      });
      field.addEventListener('change', function () {
        syncSuggestedFields();
        syncContactRequirements();
        updateSummary();
      });
    });

    syncSuggestedFields();
    syncContactRequirements();
    render();
  });

  document.querySelectorAll('a[href]').forEach(function (link) {
    link.addEventListener('click', function (event) {
      var href = link.getAttribute('href');

      if (!href || href.charAt(0) === '#' || link.hasAttribute('download') || link.getAttribute('target') === '_blank') {
        return;
      }

      if (event.metaKey || event.ctrlKey || event.shiftKey || event.altKey || event.button !== 0) {
        return;
      }

      var url = null;

      try {
        url = new URL(link.href, window.location.href);
      } catch (error) {
        return;
      }

      if (url.origin !== window.location.origin) {
        return;
      }

      if (url.pathname === window.location.pathname && url.search === window.location.search && url.hash) {
        return;
      }

      startTopProgress();

      if (
        link.classList.contains('primary-button') ||
        link.classList.contains('secondary-button') ||
        link.classList.contains('ghost-button') ||
        link.classList.contains('nav-button')
      ) {
        setInteractiveLoading(link);
      }
    });
  });

  document.querySelectorAll('form').forEach(function (form) {
    form.addEventListener('submit', function (event) {
      var submitter = event.submitter;

      if (!submitter) {
        submitter = form.querySelector('button[type="submit"], input[type="submit"]');
      }

      startTopProgress();
      setInteractiveLoading(submitter);
    });
  });

  document.querySelectorAll('[data-password-toggle]').forEach(function (toggle) {
    toggle.addEventListener('click', function () {
      var targetId = toggle.getAttribute('data-password-toggle');
      var input = targetId ? document.getElementById(targetId) : null;

      if (!input) {
        return;
      }

      var isPassword = input.getAttribute('type') === 'password';
      input.setAttribute('type', isPassword ? 'text' : 'password');
      toggle.classList.toggle('is-active', isPassword);
      toggle.setAttribute('aria-label', isPassword ? 'הסתר סיסמה' : 'הצג או הסתר סיסמה');
    });
  });

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
