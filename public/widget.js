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
    button.textContent = config.widget.label || 'Accessibility';
    button.setAttribute('aria-expanded', 'false');
    button.style.setProperty('--ab-widget-color', config.widget.color || '#0f6a73');

    var panel = document.createElement('section');
    panel.className = 'ab-widget-panel';
    panel.hidden = true;

    var title = document.createElement('h2');
    title.className = 'ab-widget-title';
    title.textContent =
      config.widget.language === 'en' ? 'Accessibility settings' : 'הגדרות נגישות';

    var description = document.createElement('p');
    description.className = 'ab-widget-description';
    description.textContent =
      config.widget.language === 'en'
        ? 'Personal display preferences and direct access to the accessibility statement.'
        : 'העדפות תצוגה אישיות וגישה ישירה להצהרת הנגישות.';

    var siteName = document.createElement('p');
    siteName.className = 'ab-widget-site';
    siteName.textContent = config.siteName || '';

    panel.appendChild(title);
    panel.appendChild(description);
    panel.appendChild(siteName);

    var controls = document.createElement('div');
    controls.className = 'ab-widget-controls';

    if (config.widget.showFontScale) {
      controls.appendChild(
        createControlRow(
          config.widget.language === 'en' ? 'Text size' : 'גודל טקסט',
          [
            { label: 'A-', onClick: function () { updatePrefs({ fontScale: 'small' }); } },
            { label: 'A', onClick: function () { updatePrefs({ fontScale: 'normal' }); } },
            { label: 'A+', onClick: function () { updatePrefs({ fontScale: 'large' }); } }
          ]
        )
      );
    }

    if (config.widget.showContrast) {
      controls.appendChild(
        createToggle(
          config.widget.language === 'en' ? 'High contrast' : 'ניגודיות גבוהה',
          'contrast',
          Boolean(prefs.contrast)
        )
      );
    }

    if (config.widget.showUnderlineLinks) {
      controls.appendChild(
        createToggle(
          config.widget.language === 'en' ? 'Underline links' : 'הדגשת קישורים',
          'underlineLinks',
          Boolean(prefs.underlineLinks)
        )
      );
    }

    if (config.widget.showReduceMotion) {
      controls.appendChild(
        createToggle(
          config.widget.language === 'en' ? 'Reduce motion' : 'הפחתת תנועה',
          'reduceMotion',
          Boolean(prefs.reduceMotion)
        )
      );
    }

    panel.appendChild(controls);

    if (config.statementUrl) {
      var statementLink = document.createElement('a');
      statementLink.className = 'ab-widget-link';
      statementLink.href = config.statementUrl;
      statementLink.target = '_blank';
      statementLink.rel = 'noopener noreferrer';
      statementLink.textContent =
        config.widget.language === 'en' ? 'Accessibility statement' : 'להצהרת הנגישות';
      panel.appendChild(statementLink);
    }

    var note = document.createElement('p');
    note.className = 'ab-widget-note';
    note.textContent =
      config.widget.language === 'en'
        ? 'This widget provides preferences and guidance. Full accessibility still depends on site code and content.'
        : 'ה-widget נותן העדפות והכוונה. נגישות מלאה עדיין תלויה בקוד ובתוכן האתר.';
    panel.appendChild(note);

    button.addEventListener('click', function () {
      var nextExpanded = button.getAttribute('aria-expanded') !== 'true';
      button.setAttribute('aria-expanded', String(nextExpanded));
      panel.hidden = !nextExpanded;
    });

    shell.appendChild(button);
    shell.appendChild(panel);
    document.body.appendChild(shell);

    function createControlRow(labelText, buttons) {
      var row = document.createElement('div');
      row.className = 'ab-widget-row';

      var label = document.createElement('span');
      label.className = 'ab-widget-row-label';
      label.textContent = labelText;
      row.appendChild(label);

      var actions = document.createElement('div');
      actions.className = 'ab-widget-actions';

      buttons.forEach(function (definition) {
        var action = document.createElement('button');
        action.type = 'button';
        action.className = 'ab-widget-action';
        action.textContent = definition.label;
        action.addEventListener('click', definition.onClick);
        actions.appendChild(action);
      });

      row.appendChild(actions);
      return row;
    }

    function createToggle(labelText, key, active) {
      var row = document.createElement('div');
      row.className = 'ab-widget-row';

      var label = document.createElement('span');
      label.className = 'ab-widget-row-label';
      label.textContent = labelText;

      var toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'ab-widget-toggle';
      toggle.setAttribute('aria-pressed', String(active));
      toggle.textContent =
        active
          ? (config.widget.language === 'en' ? 'On' : 'פעיל')
          : (config.widget.language === 'en' ? 'Off' : 'כבוי');

      toggle.addEventListener('click', function () {
        var nextValue = !loadPrefs()[key];
        updatePrefs(Object.assign({}, { [key]: nextValue }));
        toggle.setAttribute('aria-pressed', String(nextValue));
        toggle.textContent =
          nextValue
            ? (config.widget.language === 'en' ? 'On' : 'פעיל')
            : (config.widget.language === 'en' ? 'Off' : 'כבוי');
      });

      row.appendChild(label);
      row.appendChild(toggle);
      return row;
    }
  }

  function injectStyles() {
    if (document.getElementById('ab-widget-styles')) {
      return;
    }

    var style = document.createElement('style');
    style.id = 'ab-widget-styles';
    style.textContent = ''
      + '.ab-widget-shell{position:fixed;z-index:999999;bottom:20px;right:20px;display:flex;flex-direction:column-reverse;gap:12px;align-items:flex-end;font-family:system-ui,-apple-system,BlinkMacSystemFont,\"Segoe UI\",sans-serif;}'
      + '.ab-widget-shell.ab-bottom-left{left:20px;right:auto;align-items:flex-start;}'
      + '.ab-widget-button{--ab-widget-color:#0f6a73;min-height:52px;padding:0 18px;border:0;border-radius:999px;background:var(--ab-widget-color);color:#fff;font-weight:700;box-shadow:0 14px 34px rgba(17,37,51,.18);cursor:pointer;}'
      + '.ab-compact .ab-widget-button{min-height:44px;padding:0 14px;font-size:14px;}'
      + '.ab-large .ab-widget-button{min-height:58px;padding:0 22px;font-size:18px;}'
      + '.ab-widget-panel{width:min(320px,calc(100vw - 32px));border-radius:24px;background:#fffdf8;color:#132838;border:1px solid rgba(19,40,56,.1);box-shadow:0 20px 60px rgba(17,37,51,.16);padding:18px;}'
      + '.ab-widget-title{margin:0;font-size:20px;line-height:1.1;}'
      + '.ab-widget-description,.ab-widget-site,.ab-widget-note{margin:8px 0 0;color:#5d6b74;font-size:14px;}'
      + '.ab-widget-controls{display:grid;gap:10px;margin-top:16px;}'
      + '.ab-widget-row{display:flex;align-items:center;justify-content:space-between;gap:12px;padding:10px 12px;border-radius:16px;background:rgba(19,40,56,.04);}'
      + '.ab-widget-row-label{font-size:14px;font-weight:600;}'
      + '.ab-widget-actions{display:flex;gap:8px;}'
      + '.ab-widget-action,.ab-widget-toggle{min-height:34px;padding:0 10px;border-radius:999px;border:1px solid rgba(19,40,56,.12);background:#fff;color:#132838;cursor:pointer;}'
      + '.ab-widget-link{display:inline-flex;margin-top:16px;color:#0f6a73;font-weight:600;}'
      + 'html.ab-pref-contrast{filter:contrast(1.18);}'
      + 'html.ab-pref-underline a{text-decoration:underline !important;}'
      + 'html.ab-pref-reduce-motion *,html.ab-pref-reduce-motion *::before,html.ab-pref-reduce-motion *::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;scroll-behavior:auto !important;}'
      + 'html.ab-font-small{font-size:93.75%;}'
      + 'html.ab-font-large{font-size:112.5%;}'
      + '.ab-widget-button:focus-visible,.ab-widget-action:focus-visible,.ab-widget-toggle:focus-visible,.ab-widget-link:focus-visible{outline:3px solid rgba(15,106,115,.28);outline-offset:3px;}';

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
