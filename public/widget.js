(function () {
  if (window.__A11Y_BRIDGE_WIDGET__) {
    return;
  }

  window.__A11Y_BRIDGE_WIDGET__ = true;

  var currentScript =
    document.currentScript ||
    document.querySelector('script[data-a11y-bridge]');

  if (!(currentScript instanceof HTMLScriptElement)) {
    return;
  }

  var siteKey = currentScript.getAttribute('data-a11y-bridge');
  if (!siteKey) {
    return;
  }

  var platformOrigin = new URL(currentScript.src, window.location.href).origin;
  var storageKey = 'a11yBridgePrefs:' + siteKey;

  injectStyles();

  fetch(platformOrigin + '/api/public/widget-config/' + encodeURIComponent(siteKey))
    .then(function (response) {
      return response.json();
    })
    .then(function (payload) {
      if (!payload.success || !payload.data) {
        throw new Error('Missing widget config.');
      }

      renderWidget(payload.data);
    })
    .catch(function () {
      window.__A11Y_BRIDGE_WIDGET__ = false;
    });

  function renderWidget(config) {
    var prefs = loadPrefs();
    applyPrefs(prefs);

    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-' + config.widget.position + ' ab-' + config.widget.size;

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ab-widget-button';
    button.setAttribute('aria-expanded', 'false');
    button.style.setProperty('--ab-widget-color', config.widget.color || '#0f6a73');

    var buttonGlow = document.createElement('span');
    buttonGlow.className = 'ab-widget-button-glow';
    buttonGlow.setAttribute('aria-hidden', 'true');

    var buttonIcon = document.createElement('span');
    buttonIcon.className = 'ab-widget-button-icon';
    buttonIcon.setAttribute('aria-hidden', 'true');
    buttonIcon.textContent = 'AB';

    var buttonLabel = document.createElement('span');
    buttonLabel.className = 'ab-widget-button-label';
    buttonLabel.textContent = config.widget.label || 'Accessibility';

    var buttonChevron = document.createElement('span');
    buttonChevron.className = 'ab-widget-button-chevron';
    buttonChevron.setAttribute('aria-hidden', 'true');
    buttonChevron.textContent = '›';

    button.appendChild(buttonGlow);
    button.appendChild(buttonIcon);
    button.appendChild(buttonLabel);
    button.appendChild(buttonChevron);

    var panel = document.createElement('section');
    panel.className = 'ab-widget-panel';
    panel.setAttribute('aria-hidden', 'true');

    var panelHeader = document.createElement('div');
    panelHeader.className = 'ab-widget-header';

    var panelBrand = document.createElement('div');
    panelBrand.className = 'ab-widget-brand';

    var panelBadge = document.createElement('span');
    panelBadge.className = 'ab-widget-brand-badge';
    panelBadge.textContent = 'AB';

    var panelBrandText = document.createElement('div');
    panelBrandText.className = 'ab-widget-brand-text';

    var eyebrow = document.createElement('span');
    eyebrow.className = 'ab-widget-eyebrow';
    eyebrow.textContent = 'A11Y Bridge';

    var title = document.createElement('h2');
    title.className = 'ab-widget-title';
    title.textContent =
      config.widget.language === 'en' ? 'Accessibility settings' : 'הגדרות נגישות';

    panelBrandText.appendChild(eyebrow);
    panelBrandText.appendChild(title);
    panelBrand.appendChild(panelBadge);
    panelBrand.appendChild(panelBrandText);

    var closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'ab-widget-close';
    closeButton.setAttribute('aria-label', config.widget.language === 'en' ? 'Close panel' : 'סגור פאנל');
    closeButton.textContent = '×';
    closeButton.addEventListener('click', closePanel);

    panelHeader.appendChild(panelBrand);
    panelHeader.appendChild(closeButton);
    panel.appendChild(panelHeader);

    var description = document.createElement('p');
    description.className = 'ab-widget-description';
    description.textContent =
      config.widget.language === 'en'
        ? 'Display preferences and direct access to the accessibility statement.'
        : 'העדפות תצוגה וגישה ישירה להצהרת הנגישות.';

    panel.appendChild(description);

    var infoStrip = document.createElement('div');
    infoStrip.className = 'ab-widget-strip';

    var siteName = document.createElement('span');
    siteName.className = 'ab-widget-chip';
    siteName.textContent = config.siteName || 'Website';

    var statusChip = document.createElement('span');
    statusChip.className = 'ab-widget-chip ab-widget-chip-muted';
    statusChip.textContent = config.widget.language === 'en' ? 'Preferences panel' : 'פאנל העדפות';

    infoStrip.appendChild(siteName);
    infoStrip.appendChild(statusChip);
    panel.appendChild(infoStrip);

    var controls = document.createElement('div');
    controls.className = 'ab-widget-controls';

    if (config.widget.showFontScale) {
      controls.appendChild(
        createControlRow(
          config.widget.language === 'en' ? 'Text size' : 'גודל טקסט',
          config.widget.language === 'en'
            ? 'Adjust display density without changing site content.'
            : 'התאמת גודל הטקסט מבלי לשנות את התוכן באתר.',
          createActionGroup([
            { label: 'A-', onClick: function () { updatePrefs({ fontScale: 'small' }); } },
            { label: 'A', onClick: function () { updatePrefs({ fontScale: 'normal' }); } },
            { label: 'A+', onClick: function () { updatePrefs({ fontScale: 'large' }); } }
          ])
        )
      );
    }

    if (config.widget.showContrast) {
      controls.appendChild(
        createControlRow(
          config.widget.language === 'en' ? 'High contrast' : 'ניגודיות גבוהה',
          config.widget.language === 'en'
            ? 'Boost contrast for stronger separation between text and background.'
            : 'חיזוק הניגודיות להפרדה ברורה יותר בין טקסט לרקע.',
          createToggle('contrast', Boolean(prefs.contrast))
        )
      );
    }

    if (config.widget.showUnderlineLinks) {
      controls.appendChild(
        createControlRow(
          config.widget.language === 'en' ? 'Link highlight' : 'הדגשת קישורים',
          config.widget.language === 'en'
            ? 'Underline links to make navigation targets easier to scan.'
            : 'קו תחתי לקישורים כדי להקל על זיהוי נקודות ניווט.',
          createToggle('underlineLinks', Boolean(prefs.underlineLinks))
        )
      );
    }

    if (config.widget.showReduceMotion) {
      controls.appendChild(
        createControlRow(
          config.widget.language === 'en' ? 'Reduce motion' : 'הפחתת תנועה',
          config.widget.language === 'en'
            ? 'Reduce animated motion and fast transitions on the page.'
            : 'הפחתת אנימציות ומעברים מהירים לאורך העמוד.',
          createToggle('reduceMotion', Boolean(prefs.reduceMotion))
        )
      );
    }

    panel.appendChild(controls);

    var footer = document.createElement('div');
    footer.className = 'ab-widget-footer';

    if (config.statementUrl) {
      var statementLink = document.createElement('a');
      statementLink.className = 'ab-widget-link';
      statementLink.href = config.statementUrl;
      statementLink.target = '_blank';
      statementLink.rel = 'noopener noreferrer';
      statementLink.textContent =
        config.widget.language === 'en' ? 'Accessibility statement' : 'להצהרת הנגישות';
      footer.appendChild(statementLink);
    }

    var note = document.createElement('p');
    note.className = 'ab-widget-note';
    note.textContent =
      config.widget.language === 'en'
        ? 'Preferences support the experience. Full compliance still depends on site code, content, and testing.'
        : 'העדפות תצוגה תומכות בחוויה. ציות מלא עדיין תלוי בקוד האתר, בתוכן ובבדיקות.';

    footer.appendChild(note);
    panel.appendChild(footer);

    button.addEventListener('click', function () {
      if (button.getAttribute('aria-expanded') === 'true') {
        closePanel();
        return;
      }

      openPanel();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closePanel();
      }
    });

    document.addEventListener('click', function (event) {
      if (!shell.contains(event.target)) {
        closePanel();
      }
    });

    shell.appendChild(button);
    shell.appendChild(panel);
    document.body.appendChild(shell);

    function openPanel() {
      button.setAttribute('aria-expanded', 'true');
      shell.classList.add('ab-open');
      panel.setAttribute('aria-hidden', 'false');
    }

    function closePanel() {
      button.setAttribute('aria-expanded', 'false');
      shell.classList.remove('ab-open');
      panel.setAttribute('aria-hidden', 'true');
    }

    function createControlRow(labelText, descriptionText, actionNode) {
      var row = document.createElement('div');
      row.className = 'ab-widget-row';

      var content = document.createElement('div');
      content.className = 'ab-widget-row-copy';

      var label = document.createElement('span');
      label.className = 'ab-widget-row-label';
      label.textContent = labelText;

      var descriptionCopy = document.createElement('span');
      descriptionCopy.className = 'ab-widget-row-description';
      descriptionCopy.textContent = descriptionText;

      content.appendChild(label);
      content.appendChild(descriptionCopy);
      row.appendChild(content);
      row.appendChild(actionNode);

      return row;
    }

    function createActionGroup(actions) {
      var group = document.createElement('div');
      group.className = 'ab-widget-actions';

      actions.forEach(function (definition) {
        var action = document.createElement('button');
        action.type = 'button';
        action.className = 'ab-widget-action';
        action.textContent = definition.label;
        action.addEventListener('click', definition.onClick);
        group.appendChild(action);
      });

      return group;
    }

    function createToggle(key, active) {
      var toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'ab-widget-toggle';
      toggle.setAttribute('aria-pressed', String(active));
      syncToggle(toggle, active);

      toggle.addEventListener('click', function () {
        var nextValue = !loadPrefs()[key];
        updatePrefs(Object.assign({}, { [key]: nextValue }));
        syncToggle(toggle, nextValue);
      });

      return toggle;
    }

    function syncToggle(toggle, active) {
      toggle.setAttribute('aria-pressed', String(active));
      toggle.textContent =
        active
          ? (config.widget.language === 'en' ? 'On' : 'פעיל')
          : (config.widget.language === 'en' ? 'Off' : 'כבוי');
    }
  }

  function injectStyles() {
    if (document.getElementById('ab-widget-styles')) {
      return;
    }

    var style = document.createElement('style');
    style.id = 'ab-widget-styles';
    style.textContent = ''
      + '.ab-widget-shell{--ab-radius:28px;--ab-radius-small:18px;--ab-motion-fast:140ms;--ab-motion-base:180ms;--ab-motion-soft:220ms;position:fixed;z-index:999999;bottom:20px;right:20px;display:flex;flex-direction:column-reverse;gap:14px;align-items:flex-end;font-family:Inter,system-ui,-apple-system,BlinkMacSystemFont,"Segoe UI",sans-serif;}'
      + '.ab-widget-shell.ab-bottom-left{left:20px;right:auto;align-items:flex-start;}'
      + '.ab-widget-button{--ab-widget-color:#0f6a73;position:relative;display:inline-flex;align-items:center;gap:12px;min-height:60px;padding:0 18px 0 14px;border:0;border-radius:999px;background:linear-gradient(135deg,var(--ab-widget-color),#16333a 72%);color:#fff;font-weight:700;box-shadow:0 18px 46px rgba(8,22,31,.24);cursor:pointer;overflow:hidden;backdrop-filter:blur(10px);transition:transform var(--ab-motion-base) ease,box-shadow var(--ab-motion-base) ease,filter var(--ab-motion-base) ease;}'
      + '.ab-widget-button-glow{position:absolute;inset:-1px;background:radial-gradient(circle at top right,rgba(255,255,255,.32),transparent 36%);pointer-events:none;}'
      + '.ab-widget-button:hover{transform:translateY(-1px);filter:saturate(1.02);box-shadow:0 22px 52px rgba(8,22,31,.28);}'
      + '.ab-widget-button-icon{position:relative;width:34px;height:34px;display:inline-grid;place-items:center;border-radius:50%;background:rgba(255,255,255,.18);border:1px solid rgba(255,255,255,.18);font-size:11px;font-weight:900;letter-spacing:.08em;}'
      + '.ab-widget-button-label,.ab-widget-button-chevron{position:relative;}'
      + '.ab-widget-button-label{display:block;font-size:15px;}'
      + '.ab-widget-button-chevron{font-size:24px;line-height:1;opacity:.82;transition:transform var(--ab-motion-base) ease;}'
      + '.ab-widget-shell.ab-open .ab-widget-button-chevron{transform:rotate(90deg);}'
      + '.ab-compact .ab-widget-button{min-height:52px;padding-inline:13px 16px;}'
      + '.ab-compact .ab-widget-button-icon{width:30px;height:30px;font-size:13px;}'
      + '.ab-compact .ab-widget-button-label{font-size:14px;}'
      + '.ab-large .ab-widget-button{min-height:68px;padding-inline:16px 22px;}'
      + '.ab-large .ab-widget-button-label{font-size:17px;}'
      + '.ab-widget-panel{width:min(360px,calc(100vw - 28px));border-radius:var(--ab-radius);background:linear-gradient(180deg,rgba(255,251,243,.98),rgba(248,240,227,.98));color:#16303a;border:1px solid rgba(22,48,58,.1);box-shadow:0 30px 80px rgba(10,24,32,.2);padding:18px;position:relative;overflow:hidden;opacity:0;transform:translateY(12px) scale(.985);visibility:hidden;pointer-events:none;transition:opacity var(--ab-motion-soft) ease,transform var(--ab-motion-soft) ease,visibility var(--ab-motion-soft) ease;}'
      + '.ab-widget-shell.ab-open .ab-widget-panel{opacity:1;transform:translateY(0) scale(1);visibility:visible;pointer-events:auto;}'
      + '.ab-widget-panel::before{content:"";position:absolute;inset:0 0 auto 0;height:92px;background:radial-gradient(circle at top right,rgba(217,140,43,.26),transparent 45%),linear-gradient(135deg,rgba(15,106,115,.12),rgba(15,106,115,0));pointer-events:none;}'
      + '.ab-widget-header,.ab-widget-brand,.ab-widget-row,.ab-widget-footer{position:relative;display:flex;}'
      + '.ab-widget-header{align-items:flex-start;justify-content:space-between;gap:12px;}'
      + '.ab-widget-brand{align-items:center;gap:12px;}'
      + '.ab-widget-brand-badge{width:42px;height:42px;display:inline-grid;place-items:center;border-radius:16px;background:linear-gradient(135deg,#173941,#0f6a73);color:#fff;font-weight:800;box-shadow:0 10px 24px rgba(15,106,115,.18);}'
      + '.ab-widget-brand-text{display:grid;gap:2px;}'
      + '.ab-widget-eyebrow{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#6f7f85;font-weight:800;}'
      + '.ab-widget-title{margin:0;font:700 22px/1.05 Georgia,"Times New Roman",serif;}'
      + '.ab-widget-close{width:36px;height:36px;border:1px solid rgba(22,48,58,.1);border-radius:14px;background:rgba(255,255,255,.72);color:#16303a;font-size:26px;line-height:1;cursor:pointer;transition:transform var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease;}'
      + '.ab-widget-close:hover{transform:translateY(-1px);background:#fff;}'
      + '.ab-widget-description{position:relative;margin:14px 0 0;color:#53646b;font-size:14px;line-height:1.7;}'
      + '.ab-widget-strip{position:relative;display:flex;flex-wrap:wrap;gap:8px;margin-top:14px;}'
      + '.ab-widget-chip{display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border-radius:999px;background:#17333b;color:#fff;font-size:12px;font-weight:700;box-shadow:0 8px 20px rgba(23,51,59,.16);}'
      + '.ab-widget-chip-muted{background:rgba(23,51,59,.08);color:#16303a;box-shadow:none;}'
      + '.ab-widget-controls{position:relative;display:grid;gap:12px;margin-top:16px;}'
      + '.ab-widget-row{align-items:flex-start;justify-content:space-between;gap:12px;padding:14px;border-radius:20px;background:rgba(255,255,255,.74);border:1px solid rgba(22,48,58,.08);box-shadow:inset 0 1px 0 rgba(255,255,255,.6);transition:border-color var(--ab-motion-base) ease,transform var(--ab-motion-base) ease,box-shadow var(--ab-motion-base) ease;}'
      + '.ab-widget-row:hover{transform:translateY(-1px);border-color:rgba(15,106,115,.14);box-shadow:0 12px 24px rgba(14,31,40,.06);}'
      + '.ab-widget-row-copy{display:grid;gap:4px;max-width:190px;}'
      + '.ab-widget-row-label{font-size:14px;font-weight:800;color:#16303a;}'
      + '.ab-widget-row-description{font-size:12px;line-height:1.55;color:#6a787d;}'
      + '.ab-widget-actions{display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;}'
      + '.ab-widget-action,.ab-widget-toggle{min-height:38px;padding:0 12px;border-radius:999px;border:1px solid rgba(22,48,58,.12);background:#fff;color:#16303a;cursor:pointer;font-weight:700;box-shadow:0 6px 16px rgba(14,31,40,.08);transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease;}'
      + '.ab-widget-action:hover,.ab-widget-toggle:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(14,31,40,.1);}'
      + '.ab-widget-toggle[aria-pressed="true"]{background:linear-gradient(135deg,#0f6a73,#17333b);color:#fff;border-color:transparent;}'
      + '.ab-widget-footer{flex-direction:column;gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid rgba(22,48,58,.08);}'
      + '.ab-widget-link{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 14px;border-radius:14px;background:#fff;color:#0f6a73;font-weight:700;text-decoration:none;border:1px solid rgba(15,106,115,.14);width:max-content;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease;}'
      + '.ab-widget-link:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(14,31,40,.08);}'
      + '.ab-widget-note{margin:0;color:#5d6e74;font-size:12px;line-height:1.6;}'
      + 'html.ab-pref-contrast{filter:contrast(1.18);}'
      + 'html.ab-pref-underline a{text-decoration:underline !important;}'
      + 'html.ab-pref-reduce-motion *,html.ab-pref-reduce-motion *::before,html.ab-pref-reduce-motion *::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;scroll-behavior:auto !important;}'
      + 'html.ab-font-small{font-size:93.75%;}'
      + 'html.ab-font-large{font-size:112.5%;}'
      + '.ab-widget-button:focus-visible,.ab-widget-action:focus-visible,.ab-widget-toggle:focus-visible,.ab-widget-link:focus-visible,.ab-widget-close:focus-visible{outline:3px solid rgba(15,106,115,.28);outline-offset:3px;}'
      + '@media (max-width:560px){.ab-widget-shell{left:14px !important;right:14px !important;align-items:stretch;}.ab-widget-button{width:100%;justify-content:flex-start;}.ab-widget-panel{width:100%;}.ab-widget-row{flex-direction:column;}.ab-widget-row-copy{max-width:none;width:100%;}.ab-widget-actions{justify-content:flex-start;}}';

    document.head.appendChild(style);
  }

  function loadPrefs() {
    try {
      var raw = window.localStorage.getItem(storageKey);

      if (!raw) {
        return {};
      }

      return JSON.parse(raw);
    } catch (error) {
      return {};
    }
  }

  function savePrefs(prefs) {
    window.localStorage.setItem(storageKey, JSON.stringify(prefs));
  }

  function updatePrefs(partial) {
    var nextPrefs = Object.assign({}, loadPrefs(), partial);
    savePrefs(nextPrefs);
    applyPrefs(nextPrefs);
  }

  function applyPrefs(prefs) {
    var root = document.documentElement;

    root.classList.toggle('ab-pref-contrast', Boolean(prefs.contrast));
    root.classList.toggle('ab-pref-underline', Boolean(prefs.underlineLinks));
    root.classList.toggle('ab-pref-reduce-motion', Boolean(prefs.reduceMotion));
    root.classList.remove('ab-font-small', 'ab-font-large');

    if (prefs.fontScale === 'small') {
      root.classList.add('ab-font-small');
    }

    if (prefs.fontScale === 'large') {
      root.classList.add('ab-font-large');
    }
  }
})();
