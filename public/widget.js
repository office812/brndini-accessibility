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
      if (payload && payload.inactive) {
        renderInactiveWidget(payload);
        return;
      }

      if (!payload.success || !payload.data) {
        throw new Error('Missing widget config.');
      }

      renderWidget(payload.data);
    })
    .catch(function () {
      renderInactiveWidget({
        inactive: true,
        siteName: 'A11Y Bridge',
        purchaseUrl: platformOrigin + '/#pricing'
      });
    });

  function getWidgetIconSvg(type) {
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

  function getPresetLabel(type) {
    switch (type) {
      case 'high-tech':
        return 'הייטק';
      case 'elegant':
        return 'אלגנטי';
      case 'bold':
        return 'נועז';
      default:
        return 'קלאסי';
    }
  }

  function renderWidget(config) {
    var prefs = loadPrefs();
    applyPrefs(prefs);
    var preset = config.widget.preset || 'classic';
    var panelLayout = config.widget.panelLayout || 'stacked';

    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-' + config.widget.position + ' ab-' + config.widget.size + ' ab-preset-' + preset;

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ab-widget-button ab-mode-' + config.widget.buttonMode + ' ab-style-' + config.widget.buttonStyle + ' ab-preset-' + preset;
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-label', config.widget.label || 'נגישות');
    button.style.setProperty('--ab-widget-color', config.widget.color || '#0f6a73');

    var buttonGlow = document.createElement('span');
    buttonGlow.className = 'ab-widget-button-glow';
    buttonGlow.setAttribute('aria-hidden', 'true');

    var buttonIcon = document.createElement('span');
    buttonIcon.className = 'ab-widget-button-icon';
    buttonIcon.setAttribute('aria-hidden', 'true');
    buttonIcon.innerHTML = getWidgetIconSvg(config.widget.icon);

    var buttonLabel = document.createElement('span');
    buttonLabel.className = 'ab-widget-button-label';
    buttonLabel.textContent = config.widget.label || 'נגישות';

    var buttonChevron = document.createElement('span');
    buttonChevron.className = 'ab-widget-button-chevron';
    buttonChevron.setAttribute('aria-hidden', 'true');
    buttonChevron.textContent = '›';

    button.appendChild(buttonGlow);
    button.appendChild(buttonIcon);
    button.appendChild(buttonLabel);
    button.appendChild(buttonChevron);

    var panel = document.createElement('section');
    panel.className = 'ab-widget-panel ab-layout-' + panelLayout + ' ab-preset-' + preset;
    panel.setAttribute('aria-hidden', 'true');

    var panelHeader = document.createElement('div');
    panelHeader.className = 'ab-widget-header';

    var panelBrand = document.createElement('div');
    panelBrand.className = 'ab-widget-brand';

    var panelBadge = document.createElement('span');
    panelBadge.className = 'ab-widget-brand-badge';
    panelBadge.innerHTML = getWidgetIconSvg(config.widget.icon);

    var panelBrandText = document.createElement('div');
    panelBrandText.className = 'ab-widget-brand-text';

    var eyebrow = document.createElement('span');
    eyebrow.className = 'ab-widget-eyebrow';
    eyebrow.textContent = 'A11Y Bridge';

    var title = document.createElement('h2');
    title.className = 'ab-widget-title';
    title.textContent = 'הגדרות נגישות';

    panelBrandText.appendChild(eyebrow);
    panelBrandText.appendChild(title);
    panelBrand.appendChild(panelBadge);
    panelBrand.appendChild(panelBrandText);

    var closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'ab-widget-close';
    closeButton.setAttribute('aria-label', 'סגור פאנל');
    closeButton.textContent = '×';
    closeButton.addEventListener('click', closePanel);

    panelHeader.appendChild(panelBrand);
    panelHeader.appendChild(closeButton);
    panel.appendChild(panelHeader);

    var description = document.createElement('p');
    description.className = 'ab-widget-description';
    description.textContent = 'העדפות תצוגה וגישה ישירה להצהרת הנגישות.';

    panel.appendChild(description);

    var infoStrip = document.createElement('div');
    infoStrip.className = 'ab-widget-strip';

    var siteName = document.createElement('span');
    siteName.className = 'ab-widget-chip';
    siteName.textContent = config.siteName || 'האתר שלך';

    var statusChip = document.createElement('span');
    statusChip.className = 'ab-widget-chip ab-widget-chip-muted';
    statusChip.textContent = 'פריסט: ' + getPresetLabel(preset);

    var auditChip = document.createElement('span');
    auditChip.className = 'ab-widget-chip ab-widget-chip-muted';
    auditChip.textContent = config.audit && config.audit.score ? 'ציון ' + config.audit.score : 'פאנל העדפות';

    infoStrip.appendChild(siteName);
    infoStrip.appendChild(statusChip);
    infoStrip.appendChild(auditChip);
    panel.appendChild(infoStrip);

    var controls = document.createElement('div');
    controls.className = 'ab-widget-controls ab-layout-' + panelLayout;

    if (config.widget.showFontScale) {
      controls.appendChild(
        createControlRow(
          'גודל טקסט',
          'התאמת גודל הטקסט מבלי לשנות את התוכן באתר.',
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
          'ניגודיות גבוהה',
          'חיזוק הניגודיות להפרדה ברורה יותר בין טקסט לרקע.',
          createToggle('contrast', Boolean(prefs.contrast))
        )
      );
    }

    if (config.widget.showUnderlineLinks) {
      controls.appendChild(
        createControlRow(
          'הדגשת קישורים',
          'קו תחתי לקישורים כדי להקל על זיהוי נקודות ניווט.',
          createToggle('underlineLinks', Boolean(prefs.underlineLinks))
        )
      );
    }

    if (config.widget.showReduceMotion) {
      controls.appendChild(
        createControlRow(
          'הפחתת תנועה',
          'הפחתת אנימציות ומעברים מהירים לאורך העמוד.',
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
      statementLink.textContent = 'להצהרת הנגישות';
      footer.appendChild(statementLink);
    }

    var note = document.createElement('p');
    note.className = 'ab-widget-note';
    note.textContent = 'העדפות תצוגה תומכות בחוויה. ציות מלא עדיין תלוי בקוד האתר, בתוכן ובבדיקות.';

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
      toggle.textContent = active ? 'פעיל' : 'כבוי';
    }
  }

  function renderInactiveWidget(payload) {
    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-bottom-right ab-comfortable';

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ab-widget-button ab-license-inactive ab-mode-icon-label ab-style-midnight';
    button.setAttribute('aria-label', 'הרישיון לא פעיל');

    var buttonGlow = document.createElement('span');
    buttonGlow.className = 'ab-widget-button-glow';
    buttonGlow.setAttribute('aria-hidden', 'true');

    var buttonIcon = document.createElement('span');
    buttonIcon.className = 'ab-widget-button-icon';
    buttonIcon.setAttribute('aria-hidden', 'true');
    buttonIcon.innerHTML = getWidgetIconSvg('shield');

    var buttonLabel = document.createElement('span');
    buttonLabel.className = 'ab-widget-button-label';
    buttonLabel.textContent = 'הרישיון לא פעיל';

    button.appendChild(buttonGlow);
    button.appendChild(buttonIcon);
    button.appendChild(buttonLabel);

    button.addEventListener('click', function () {
      var purchaseUrl = payload && payload.purchaseUrl ? payload.purchaseUrl : platformOrigin + '/#pricing';
      window.location.href = purchaseUrl;
    });

    var note = document.createElement('div');
    note.className = 'ab-widget-inactive-note';
    note.textContent = 'לחץ להפעלת רישיון עבור האתר הזה';

    shell.appendChild(button);
    shell.appendChild(note);
    document.body.appendChild(shell);
  }

  function injectStyles() {
    if (document.getElementById('ab-widget-styles')) {
      return;
    }

    var style = document.createElement('style');
    style.id = 'ab-widget-styles';
    style.textContent = ''
      + '.ab-widget-shell{--ab-radius:30px;--ab-radius-small:18px;--ab-motion-fast:140ms;--ab-motion-base:180ms;--ab-motion-soft:220ms;position:fixed;z-index:999999;bottom:20px;right:20px;display:flex;flex-direction:column-reverse;gap:14px;align-items:flex-end;font-family:"Assistant","Segoe UI",Arial,sans-serif;}'
      + '.ab-widget-shell.ab-bottom-left{left:20px;right:auto;align-items:flex-start;}'
      + '.ab-widget-button{--ab-widget-color:#1d6dff;position:relative;display:inline-flex;align-items:center;gap:12px;min-height:58px;padding:0 18px 0 14px;border:1px solid rgba(255,255,255,.2);border-radius:999px;background:linear-gradient(135deg,var(--ab-widget-color),#081121 92%);color:#fff;font-weight:700;box-shadow:0 18px 44px rgba(15,23,42,.18);cursor:pointer;overflow:hidden;backdrop-filter:blur(18px);transition:transform var(--ab-motion-base) ease,box-shadow var(--ab-motion-base) ease,filter var(--ab-motion-base) ease,background var(--ab-motion-base) ease,border-color var(--ab-motion-base) ease,color var(--ab-motion-base) ease;}'
      + '.ab-widget-button-glow{position:absolute;inset:-1px;background:radial-gradient(circle at top right,rgba(255,255,255,.34),transparent 34%);pointer-events:none;}'
      + '.ab-widget-button:hover{transform:translateY(-1px);filter:saturate(1.02);box-shadow:0 24px 56px rgba(15,23,42,.22);}'
      + '.ab-widget-button-icon{position:relative;width:34px;height:34px;display:inline-grid;place-items:center;border-radius:50%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.18);box-shadow:inset 0 1px 0 rgba(255,255,255,.18);}'
      + '.ab-widget-button-icon svg,.ab-widget-brand-badge svg{width:18px;height:18px;}'
      + '.ab-widget-button-label,.ab-widget-button-chevron{position:relative;}'
      + '.ab-widget-button-label{display:block;font-size:15px;}'
      + '.ab-widget-button-chevron{font-size:22px;line-height:1;opacity:.78;transition:transform var(--ab-motion-base) ease;}'
      + '.ab-widget-shell.ab-open .ab-widget-button-chevron{transform:rotate(90deg);}'
      + '.ab-widget-button.ab-mode-label-only .ab-widget-button-icon{display:none;}'
      + '.ab-widget-button.ab-mode-icon-only{min-width:58px;justify-content:center;padding-inline:0;}'
      + '.ab-widget-button.ab-mode-icon-only .ab-widget-button-label,.ab-widget-button.ab-mode-icon-only .ab-widget-button-chevron{display:none;}'
      + '.ab-widget-button.ab-style-soft{background:linear-gradient(180deg,rgba(255,255,255,.94),rgba(237,244,255,.95));color:#13325b;border-color:rgba(29,109,255,.16);box-shadow:0 16px 34px rgba(29,109,255,.08);}'
      + '.ab-widget-button.ab-style-soft .ab-widget-button-icon,.ab-widget-button.ab-style-glass .ab-widget-button-icon{background:rgba(29,109,255,.08);border-color:rgba(29,109,255,.14);}'
      + '.ab-widget-button.ab-style-glass{background:linear-gradient(180deg,rgba(255,255,255,.56),rgba(255,255,255,.34));color:#10233f;border-color:rgba(255,255,255,.72);box-shadow:0 18px 40px rgba(15,23,42,.12);}'
      + '.ab-widget-button.ab-style-midnight{background:linear-gradient(135deg,#081121,#152d57);color:#f8fafc;border-color:rgba(255,255,255,.08);box-shadow:0 20px 46px rgba(8,17,33,.22);}'
      + '.ab-widget-button.ab-preset-classic{--ab-panel-accent:#1d6dff;}'
      + '.ab-widget-button.ab-preset-high-tech{background:linear-gradient(135deg,#2563eb,#06101f 86%);box-shadow:0 22px 50px rgba(37,99,235,.26);}'
      + '.ab-widget-button.ab-preset-elegant{background:linear-gradient(135deg,#1f2937,#67553d 88%);box-shadow:0 22px 50px rgba(103,85,61,.24);}'
      + '.ab-widget-button.ab-preset-bold{background:linear-gradient(135deg,#7c3aed,#ec4899 88%);box-shadow:0 22px 50px rgba(124,58,237,.28);}'
      + '.ab-widget-button.ab-license-inactive{background:linear-gradient(135deg,#d92d20,#7a0510);color:#fff;border-color:rgba(255,255,255,.08);box-shadow:0 20px 46px rgba(122,5,16,.22);}'
      + '.ab-compact .ab-widget-button{min-height:50px;padding-inline:13px 16px;}'
      + '.ab-compact .ab-widget-button-icon{width:30px;height:30px;font-size:13px;}'
      + '.ab-compact .ab-widget-button-label{font-size:14px;}'
      + '.ab-large .ab-widget-button{min-height:66px;padding-inline:16px 22px;}'
      + '.ab-large .ab-widget-button-label{font-size:17px;}'
      + '.ab-large .ab-widget-button.ab-mode-icon-only{min-width:66px;}'
      + '.ab-widget-panel{width:min(364px,calc(100vw - 28px));border-radius:var(--ab-radius);background:linear-gradient(180deg,rgba(255,255,255,.86),rgba(243,247,252,.9));color:#0f172a;border:1px solid rgba(255,255,255,.76);box-shadow:0 30px 90px rgba(15,23,42,.16);padding:18px;position:relative;overflow:hidden;opacity:0;transform:translateY(12px) scale(.985);visibility:hidden;pointer-events:none;backdrop-filter:blur(22px);}'
      + '.ab-widget-panel.ab-preset-high-tech{background:linear-gradient(180deg,rgba(245,249,255,.92),rgba(231,239,252,.92));}'
      + '.ab-widget-panel.ab-preset-elegant{background:linear-gradient(180deg,rgba(255,252,247,.94),rgba(245,239,231,.96));}'
      + '.ab-widget-panel.ab-preset-bold{background:linear-gradient(180deg,rgba(253,245,255,.94),rgba(239,230,255,.96));}'
      + '.ab-widget-panel::after{content:"";position:absolute;inset:0;pointer-events:none;background-image:linear-gradient(rgba(29,109,255,.025) 1px,transparent 1px),linear-gradient(90deg,rgba(29,109,255,.025) 1px,transparent 1px);background-size:28px 28px;mask-image:linear-gradient(180deg,rgba(0,0,0,.18),transparent 70%);}'
      + '.ab-widget-shell.ab-open .ab-widget-panel{opacity:1;transform:translateY(0) scale(1);visibility:visible;pointer-events:auto;}'
      + '.ab-widget-panel::before{content:"";position:absolute;inset:0 0 auto 0;height:88px;background:radial-gradient(circle at top right,rgba(96,165,250,.22),transparent 34%),linear-gradient(135deg,rgba(255,255,255,.46),rgba(255,255,255,0));pointer-events:none;}'
      + '.ab-widget-header,.ab-widget-brand,.ab-widget-row,.ab-widget-footer{position:relative;display:flex;}'
      + '.ab-widget-header{align-items:flex-start;justify-content:space-between;gap:12px;}'
      + '.ab-widget-brand{align-items:center;gap:12px;}'
      + '.ab-widget-brand-badge{width:42px;height:42px;display:inline-grid;place-items:center;border-radius:16px;background:linear-gradient(135deg,#081121,#1d6dff);color:#fff;font-weight:800;box-shadow:0 10px 24px rgba(29,109,255,.14);}'
      + '.ab-widget-brand-text{display:grid;gap:2px;}'
      + '.ab-widget-eyebrow{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#64748b;font-weight:800;}'
      + '.ab-widget-title{margin:0;font:700 21px/1.08 "Assistant","Segoe UI",Arial,sans-serif;letter-spacing:-.02em;}'
      + '.ab-widget-close{width:36px;height:36px;border:1px solid rgba(15,23,42,.08);border-radius:14px;background:rgba(255,255,255,.72);color:#0f172a;font-size:24px;line-height:1;cursor:pointer;transition:transform var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease;}'
      + '.ab-widget-close:hover{transform:translateY(-1px);background:#fff;border-color:rgba(15,23,42,.12);}'
      + '.ab-widget-description{position:relative;margin:14px 0 0;color:#64748b;font-size:14px;line-height:1.7;}'
      + '.ab-widget-strip{position:relative;display:flex;flex-wrap:wrap;gap:8px;margin-top:14px;}'
      + '.ab-widget-chip{display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border-radius:999px;background:#0b1220;color:#fff;font-size:12px;font-weight:700;box-shadow:0 8px 20px rgba(15,23,42,.1);}'
      + '.ab-widget-chip-muted{background:rgba(15,23,42,.05);color:#0f172a;box-shadow:none;}'
      + '.ab-widget-controls{position:relative;display:grid;gap:12px;margin-top:16px;}'
      + '.ab-widget-controls.ab-layout-split{grid-template-columns:repeat(2,minmax(0,1fr));}'
      + '.ab-widget-row{align-items:flex-start;justify-content:space-between;gap:12px;padding:14px;border-radius:22px;background:rgba(255,255,255,.72);border:1px solid rgba(255,255,255,.82);box-shadow:0 8px 18px rgba(15,23,42,.04);transition:border-color var(--ab-motion-base) ease,transform var(--ab-motion-base) ease,box-shadow var(--ab-motion-base) ease;}'
      + '.ab-widget-panel.ab-layout-split .ab-widget-row{flex-direction:column;align-items:stretch;min-height:148px;}'
      + '.ab-widget-panel.ab-layout-split .ab-widget-row-copy{max-width:none;}'
      + '.ab-widget-panel.ab-layout-split .ab-widget-actions{justify-content:flex-start;}'
      + '.ab-widget-row:hover{transform:translateY(-1px);border-color:rgba(29,109,255,.18);box-shadow:0 12px 24px rgba(15,23,42,.07);}'
      + '.ab-widget-row-copy{display:grid;gap:4px;max-width:190px;}'
      + '.ab-widget-row-label{font-size:14px;font-weight:800;color:#0f172a;}'
      + '.ab-widget-row-description{font-size:12px;line-height:1.55;color:#64748b;}'
      + '.ab-widget-actions{display:flex;gap:6px;flex-wrap:wrap;justify-content:flex-end;}'
      + '.ab-widget-action,.ab-widget-toggle{min-height:38px;padding:0 12px;border-radius:999px;border:1px solid rgba(15,23,42,.08);background:rgba(255,255,255,.94);color:#0f172a;cursor:pointer;font-weight:700;box-shadow:0 6px 16px rgba(15,23,42,.06);transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease,color var(--ab-motion-fast) ease;}'
      + '.ab-widget-action:hover,.ab-widget-toggle:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(15,23,42,.08);}'
      + '.ab-widget-toggle[aria-pressed="true"]{background:linear-gradient(135deg,#1d6dff,#081121);color:#fff;border-color:transparent;}'
      + '.ab-widget-footer{flex-direction:column;gap:10px;margin-top:16px;padding-top:14px;border-top:1px solid rgba(15,23,42,.08);}'
      + '.ab-widget-link{display:inline-flex;align-items:center;justify-content:center;min-height:42px;padding:0 14px;border-radius:14px;background:#fff;color:#1d6dff;font-weight:700;text-decoration:none;border:1px solid rgba(29,109,255,.16);width:max-content;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease;}'
      + '.ab-widget-link:hover{transform:translateY(-1px);border-color:rgba(29,109,255,.24);box-shadow:0 10px 18px rgba(15,23,42,.08);}'
      + '.ab-widget-note{margin:0;color:#64748b;font-size:12px;line-height:1.6;}'
      + '.ab-widget-inactive-note{max-width:230px;padding:10px 14px;border-radius:14px;background:rgba(217,45,32,.1);color:#7a0510;font-size:12px;font-weight:700;box-shadow:0 8px 18px rgba(122,5,16,.08);}'
      + 'html.ab-pref-contrast{filter:contrast(1.18);}'
      + 'html.ab-pref-underline a{text-decoration:underline !important;}'
      + 'html.ab-pref-reduce-motion *,html.ab-pref-reduce-motion *::before,html.ab-pref-reduce-motion *::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;scroll-behavior:auto !important;}'
      + 'html.ab-font-small{font-size:93.75%;}'
      + 'html.ab-font-large{font-size:112.5%;}'
      + '.ab-widget-button:focus-visible,.ab-widget-action:focus-visible,.ab-widget-toggle:focus-visible,.ab-widget-link:focus-visible,.ab-widget-close:focus-visible{outline:3px solid rgba(29,109,255,.22);outline-offset:3px;}'
      + '@media (max-width:560px){.ab-widget-shell{left:14px !important;right:14px !important;align-items:stretch;}.ab-widget-button{width:100%;justify-content:flex-start;}.ab-widget-panel{width:100%;}.ab-widget-row{flex-direction:column;}.ab-widget-row-copy{max-width:none;width:100%;}.ab-widget-actions{justify-content:flex-start;}.ab-widget-controls.ab-layout-split{grid-template-columns:1fr;}}';

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
