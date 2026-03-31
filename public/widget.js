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
  var forcedPosition = currentScript.getAttribute('data-a11y-position');
  if (!siteKey) {
    return;
  }

  var platformOrigin = new URL(currentScript.src, window.location.href).origin;
  var brandLogoUrl = platformOrigin + '/inn-logo.png';
  var storageKey = 'a11yBridgePrefs:' + siteKey;
  var purchaseFallbackUrl = platformOrigin + '/#pricing';
  var guideOverlay = null;
  var guideMoveHandlerAttached = false;

  var defaultPrefs = {
    fontScale: 'normal',
    contrastMode: 'normal',
    underlineLinks: false,
    reduceMotion: false,
    readableFont: false,
    highlightTitles: false,
    lineSpacing: 'normal',
    letterSpacing: 'normal',
    textAlign: 'default',
    hideImages: false,
    cursorMode: 'default',
    readingGuide: false,
    profile: 'none'
  };

  var featureCatalog = [
    {
      key: 'contrastMode',
      title: 'נראות וצבע',
      description: 'שליטה בניגודיות ובהירות כללית של האתר.',
      type: 'choice',
      plan: 'free',
      section: 'display',
      options: [
        { value: 'normal', label: 'רגיל' },
        { value: 'bright', label: 'בהיר' },
        { value: 'dark', label: 'כהה' },
        { value: 'high', label: 'גבוהה' }
      ]
    },
    {
      key: 'fontScale',
      title: 'גודל טקסט',
      description: 'הקטנה או הגדלה של הטקסטים ברחבי האתר.',
      type: 'choice',
      plan: 'free',
      section: 'text',
      options: [
        { value: 'small', label: 'קטן' },
        { value: 'normal', label: 'רגיל' },
        { value: 'large', label: 'גדול' }
      ]
    },
    {
      key: 'lineSpacing',
      title: 'גובה שורות',
      description: 'הגדלת הרווח בין שורות לקריאה נוחה יותר.',
      type: 'choice',
      plan: 'free',
      section: 'text',
      options: [
        { value: 'normal', label: 'רגיל' },
        { value: 'relaxed', label: 'מרווח' },
        { value: 'loose', label: 'מרווח מאוד' }
      ]
    },
    {
      key: 'underlineLinks',
      title: 'הדגשת קישורים',
      description: 'הבלטת קישורים כדי לזהות אזורי ניווט בקלות.',
      type: 'toggle',
      plan: 'free',
      section: 'text'
    },
    {
      key: 'highlightTitles',
      title: 'הדגשת כותרות',
      description: 'הבלטה חזקה יותר של כותרות ותתי־כותרות.',
      type: 'toggle',
      plan: 'free',
      section: 'text'
    },
    {
      key: 'readableFont',
      title: 'גופן קריא',
      description: 'מעבר לגופן ברור ונקי יותר לקריאה ממושכת.',
      type: 'toggle',
      plan: 'free',
      section: 'text'
    },
    {
      key: 'reduceMotion',
      title: 'הפחתת תנועה',
      description: 'כיבוי אנימציות ומעברים מהירים ברחבי האתר.',
      type: 'toggle',
      plan: 'free',
      section: 'focus'
    },
    {
      key: 'cursorMode',
      title: 'סמן גדול',
      description: 'הגדלת הסמן לבהיר או כהה לנראות משופרת.',
      type: 'choice',
      plan: 'free',
      section: 'focus',
      options: [
        { value: 'default', label: 'רגיל' },
        { value: 'light', label: 'בהיר' },
        { value: 'dark', label: 'כהה' }
      ]
    },
    {
      key: 'hideImages',
      title: 'הסתרת תמונות',
      description: 'הפחתת העומס החזותי על ידי הסתרת תמונות ותמונות רקע.',
      type: 'toggle',
      plan: 'premium',
      section: 'display'
    },
    {
      key: 'letterSpacing',
      title: 'מרווח בין אותיות',
      description: 'הרחבה עדינה או גדולה של מרווח האותיות.',
      type: 'choice',
      plan: 'premium',
      section: 'text',
      options: [
        { value: 'normal', label: 'רגיל' },
        { value: 'wide', label: 'מרווח' },
        { value: 'wider', label: 'מרווח מאוד' }
      ]
    },
    {
      key: 'textAlign',
      title: 'יישור תוכן',
      description: 'שינוי היישור של טקסטים לקריאה נוחה יותר.',
      type: 'choice',
      plan: 'premium',
      section: 'text',
      options: [
        { value: 'default', label: 'ברירת מחדל' },
        { value: 'right', label: 'ימין' },
        { value: 'center', label: 'מרכז' },
        { value: 'left', label: 'שמאל' }
      ]
    },
    {
      key: 'readingGuide',
      title: 'מדריך קריאה',
      description: 'פס מיקוד נע שמסייע להישאר על שורת הקריאה הפעילה.',
      type: 'toggle',
      plan: 'premium',
      section: 'focus'
    }
  ];

  var profiles = [
    {
      key: 'none',
      title: 'ללא פרופיל',
      description: 'חזרה להתאמות הרגילות שלך.',
      plan: 'free',
      prefs: function () {
        return {
          profile: 'none'
        };
      }
    },
    {
      key: 'epilepsy',
      title: 'פרופיל למניעת אפילפסיה',
      description: 'מפחית תנועה ומרגיע אזורים רגישים במסך.',
      plan: 'free',
      prefs: function () {
        return {
          profile: 'epilepsy',
          reduceMotion: true,
          contrastMode: 'normal',
          readingGuide: false
        };
      }
    },
    {
      key: 'vision',
      title: 'פרופיל מגבלות ראייה',
      description: 'הגדלת טקסט, קישורים מודגשים וניגודיות חזקה יותר.',
      plan: 'free',
      prefs: function () {
        return {
          profile: 'vision',
          fontScale: 'large',
          contrastMode: 'high',
          underlineLinks: true,
          highlightTitles: true,
          cursorMode: 'dark'
        };
      }
    },
    {
      key: 'adhd',
      title: 'פרופיל ADHD',
      description: 'מיקוד גבוה יותר עם מדריך קריאה ומרווחי קריאה.',
      plan: 'premium',
      prefs: function () {
        return {
          profile: 'adhd',
          fontScale: 'large',
          lineSpacing: 'relaxed',
          readingGuide: true,
          highlightTitles: true
        };
      }
    },
    {
      key: 'cognitive',
      title: 'פרופיל מגבלות קוגניטיביות',
      description: 'מסייע בקריאות, מיקוד וארגון טקסט נוח יותר.',
      plan: 'premium',
      prefs: function () {
        return {
          profile: 'cognitive',
          readableFont: true,
          lineSpacing: 'relaxed',
          letterSpacing: 'wide',
          contrastMode: 'bright'
        };
      }
    },
    {
      key: 'dyslexia',
      title: 'פרופיל ידידותי לדיסלקסיה',
      description: 'גופן קריא, מרווחים נדיבים והפחתת עומס טיפוגרפי.',
      plan: 'premium',
      prefs: function () {
        return {
          profile: 'dyslexia',
          readableFont: true,
          lineSpacing: 'loose',
          letterSpacing: 'wide',
          underlineLinks: true
        };
      }
    }
  ];

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

      reportWidgetSeen(payload.data);
      renderWidget(payload.data);
    })
    .catch(function () {
      renderInactiveWidget({
        inactive: true,
        siteName: 'A11Y Bridge',
        purchaseUrl: purchaseFallbackUrl
      });
    });

  function normalizePlan(plan) {
    if (plan === 'premium' || plan === 'growth' || plan === 'agency') {
      return 'premium';
    }

    return 'free';
  }

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

  function reportWidgetSeen(config) {
    if (!config || !config.siteKey) {
      return;
    }

    fetch(platformOrigin + '/api/public/widget-seen/' + encodeURIComponent(config.siteKey), {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        pageUrl: window.location.href,
        referrer: document.referrer || ''
      }),
      keepalive: true
    }).catch(function () {
      return null;
    });
  }

  function renderWidget(config) {
    var prefs = loadPrefs();
    var plan = normalizePlan(config.billing && config.billing.plan);
    var purchaseUrl = config.purchaseUrl || purchaseFallbackUrl;
    var preset = config.widget.preset || 'classic';
    var position = forcedPosition || config.widget.position || 'bottom-right';

    applyPrefs(prefs);

    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-' + position + ' ab-' + config.widget.size + ' ab-preset-' + preset;

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ab-widget-button ab-mode-' + config.widget.buttonMode + ' ab-style-' + config.widget.buttonStyle + ' ab-preset-' + preset;
    button.setAttribute('aria-expanded', 'false');
    button.setAttribute('aria-label', config.widget.label || 'נגישות');
    button.style.setProperty('--ab-widget-color', config.widget.color || '#1d6dff');

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
    panel.className = 'ab-widget-panel ab-preset-' + preset;
    panel.setAttribute('aria-hidden', 'true');

    var panelHeader = document.createElement('div');
    panelHeader.className = 'ab-widget-header';

    var panelBrand = document.createElement('div');
    panelBrand.className = 'ab-widget-brand';

    var panelBadge = document.createElement('span');
    panelBadge.className = 'ab-widget-brand-badge';

    var panelLogo = document.createElement('img');
    panelLogo.className = 'ab-widget-brand-logo';
    panelLogo.src = brandLogoUrl;
    panelLogo.alt = '';
    panelBadge.appendChild(panelLogo);

    var panelBrandText = document.createElement('div');
    panelBrandText.className = 'ab-widget-brand-text';

    var eyebrow = document.createElement('span');
    eyebrow.className = 'ab-widget-eyebrow';
    eyebrow.textContent = 'התאמות נגישות באתר';

    var title = document.createElement('h2');
    title.className = 'ab-widget-title';
    title.textContent = 'התאמות נגישות באתר';

    panelBrandText.appendChild(eyebrow);
    panelBrandText.appendChild(title);
    panelBrand.appendChild(panelBadge);
    panelBrand.appendChild(panelBrandText);

    var closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'ab-widget-close';
    closeButton.setAttribute('aria-label', 'סגור פאנל');
    closeButton.textContent = '×';

    panelHeader.appendChild(panelBrand);
    panelHeader.appendChild(closeButton);
    panel.appendChild(panelHeader);

    var infoStrip = document.createElement('div');
    infoStrip.className = 'ab-widget-strip';
    infoStrip.appendChild(createChip(config.siteName || 'האתר שלך'));
    infoStrip.appendChild(createChip(plan === 'premium' ? 'פרימיום' : 'חינם', plan === 'premium' ? 'is-plan-premium' : 'is-plan-free'));
    infoStrip.appendChild(createChip(config.audit && config.audit.score ? 'ציון ' + config.audit.score : 'מותאם אישית', 'is-chip-muted'));
    panel.appendChild(infoStrip);

    var panelBody = document.createElement('div');
    panelBody.className = 'ab-widget-body';
    panel.appendChild(panelBody);

    function refreshPanel() {
      prefs = loadPrefs();
      applyPrefs(prefs);
      panelBody.innerHTML = '';

      panelBody.appendChild(createProfileSection(plan, purchaseUrl, prefs));
      panelBody.appendChild(createFeatureSection('התאמות טקסט ותוכן', 'כלים לקריאה, מרווחים ויישור טקסט.', featureCatalog.filter(function (feature) {
        return feature.section === 'text';
      }), plan, purchaseUrl, prefs));
      panelBody.appendChild(createFeatureSection('התאמות צבעים ותצוגה', 'ניגודיות, בהירות ונראות כללית של האתר.', featureCatalog.filter(function (feature) {
        return feature.section === 'display';
      }), plan, purchaseUrl, prefs));
      panelBody.appendChild(createFeatureSection('ניווט, מיקוד ונוחות', 'הפחתת תנועה, סמן גדול וכלי ריכוז.', featureCatalog.filter(function (feature) {
        return feature.section === 'focus';
      }), plan, purchaseUrl, prefs));

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

      if (plan !== 'premium') {
        var upgradeLink = document.createElement('a');
        upgradeLink.className = 'ab-widget-link ab-widget-link-premium';
        upgradeLink.href = purchaseUrl;
        upgradeLink.textContent = 'שדרג לפרימיום';
        footer.appendChild(upgradeLink);
      }

      var note = document.createElement('p');
      note.className = 'ab-widget-note';
      note.textContent = 'מסלול חינם פותח כ־70% מהיכולות. פרימיום מוסיף את ההתאמות המתקדמות והפרופילים הייעודיים.';
      footer.appendChild(note);
      panelBody.appendChild(footer);
    }

    button.addEventListener('click', function () {
      if (button.getAttribute('aria-expanded') === 'true') {
        closePanel();
        return;
      }

      openPanel();
    });

    closeButton.addEventListener('click', closePanel);

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
    refreshPanel();

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

    function createProfileSection(planName, premiumUrl, currentPrefs) {
      var section = document.createElement('section');
      section.className = 'ab-widget-section';

      section.appendChild(createSectionHead('בחרו פרופיל נגישות', 'הפעלת תצורה מהירה של התאמות מוכנות מראש.'));

      var profileGrid = document.createElement('div');
      profileGrid.className = 'ab-widget-grid ab-widget-grid-profiles';

      profiles.forEach(function (profile) {
        var isPremium = profile.plan === 'premium';
        var isLocked = isPremium && planName !== 'premium';
        var card = document.createElement('article');
        card.className = 'ab-widget-card ab-widget-profile-card' + (currentPrefs.profile === profile.key ? ' is-active' : '');

        var titleNode = document.createElement('strong');
        titleNode.className = 'ab-widget-card-title';
        titleNode.textContent = profile.title;

        var descNode = document.createElement('p');
        descNode.className = 'ab-widget-card-copy';
        descNode.textContent = profile.description;

        var actionWrap = document.createElement('div');
        actionWrap.className = 'ab-widget-card-actions';

        if (isLocked) {
          actionWrap.appendChild(createLockButton(premiumUrl));
        } else {
          var profileButton = document.createElement('button');
          profileButton.type = 'button';
          profileButton.className = 'ab-widget-choice-button' + (currentPrefs.profile === profile.key ? ' is-active' : '');
          profileButton.textContent = currentPrefs.profile === profile.key && profile.key !== 'none' ? 'פעיל' : (profile.key === 'none' ? 'איפוס' : 'הפעל');
          profileButton.addEventListener('click', function () {
            updatePrefs(profile.prefs());
          });
          actionWrap.appendChild(profileButton);
        }

        if (isPremium) {
          card.appendChild(createMiniBadge('פרימיום'));
        }

        card.appendChild(titleNode);
        card.appendChild(descNode);
        card.appendChild(actionWrap);
        profileGrid.appendChild(card);
      });

      section.appendChild(profileGrid);
      return section;
    }

    function createFeatureSection(titleText, descriptionText, features, planName, premiumUrl, currentPrefs) {
      var section = document.createElement('section');
      section.className = 'ab-widget-section';
      section.appendChild(createSectionHead(titleText, descriptionText));

      var grid = document.createElement('div');
      grid.className = 'ab-widget-grid';

      features.forEach(function (feature) {
        var isLocked = feature.plan === 'premium' && planName !== 'premium';
        var card = document.createElement('article');
        card.className = 'ab-widget-card' + (isLocked ? ' is-locked' : '');

        var titleNode = document.createElement('strong');
        titleNode.className = 'ab-widget-card-title';
        titleNode.textContent = feature.title;

        var descNode = document.createElement('p');
        descNode.className = 'ab-widget-card-copy';
        descNode.textContent = feature.description;

        var actionWrap = document.createElement('div');
        actionWrap.className = 'ab-widget-card-actions';

        if (isLocked) {
          card.appendChild(createMiniBadge('פרימיום'));
          actionWrap.appendChild(createLockButton(premiumUrl));
        } else if (feature.type === 'toggle') {
          actionWrap.appendChild(createToggle(feature, currentPrefs[feature.key]));
        } else {
          actionWrap.appendChild(createChoiceGroup(feature, currentPrefs[feature.key]));
        }

        card.appendChild(titleNode);
        card.appendChild(descNode);
        card.appendChild(actionWrap);
        grid.appendChild(card);
      });

      section.appendChild(grid);
      return section;
    }

    function createSectionHead(titleText, descriptionText) {
      var head = document.createElement('div');
      head.className = 'ab-widget-section-head';

      var titleNode = document.createElement('h3');
      titleNode.className = 'ab-widget-section-title';
      titleNode.textContent = titleText;

      var descNode = document.createElement('p');
      descNode.className = 'ab-widget-section-copy';
      descNode.textContent = descriptionText;

      head.appendChild(titleNode);
      head.appendChild(descNode);
      return head;
    }

    function createChip(label, className) {
      var chip = document.createElement('span');
      chip.className = 'ab-widget-chip' + (className ? ' ' + className : '');
      chip.textContent = label;
      return chip;
    }

    function createMiniBadge(label) {
      var badge = document.createElement('span');
      badge.className = 'ab-widget-mini-badge';
      badge.textContent = label;
      return badge;
    }

    function createLockButton(url) {
      var link = document.createElement('a');
      link.className = 'ab-widget-lock-button';
      link.href = url;
      link.textContent = 'זמין בפרימיום';
      return link;
    }

    function createChoiceGroup(feature, currentValue) {
      var group = document.createElement('div');
      group.className = 'ab-widget-choice-group';

      feature.options.forEach(function (option) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'ab-widget-choice-button' + (currentValue === option.value ? ' is-active' : '');
        button.textContent = option.label;
        button.addEventListener('click', function () {
          updatePrefs({
            profile: 'none',
            [feature.key]: option.value
          });
        });
        group.appendChild(button);
      });

      return group;
    }

    function createToggle(feature, currentValue) {
      var toggle = document.createElement('button');
      toggle.type = 'button';
      toggle.className = 'ab-widget-toggle-button' + (currentValue ? ' is-active' : '');
      toggle.setAttribute('aria-pressed', String(Boolean(currentValue)));
      toggle.textContent = currentValue ? 'פעיל' : 'כבוי';
      toggle.addEventListener('click', function () {
        updatePrefs({
          profile: 'none',
          [feature.key]: !currentValue
        });
      });
      return toggle;
    }

    function updatePrefs(partial) {
      var nextPrefs = Object.assign({}, loadPrefs(), partial);
      savePrefs(nextPrefs);
      refreshPanel();
    }
  }

  function renderInactiveWidget(payload) {
    var widget = payload && payload.widget ? payload.widget : {
      position: 'bottom-right',
      size: 'comfortable',
      buttonMode: 'icon-label',
      icon: 'shield'
    };
    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-' + (widget.position || 'bottom-right') + ' ab-' + (widget.size || 'comfortable');

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ab-widget-button ab-license-inactive ab-mode-' + (widget.buttonMode || 'icon-label') + ' ab-style-midnight';
    button.setAttribute('aria-label', 'הרישיון לא פעיל');

    var buttonGlow = document.createElement('span');
    buttonGlow.className = 'ab-widget-button-glow';
    buttonGlow.setAttribute('aria-hidden', 'true');

    var buttonIcon = document.createElement('span');
    buttonIcon.className = 'ab-widget-button-icon';
    buttonIcon.setAttribute('aria-hidden', 'true');
    buttonIcon.innerHTML = getWidgetIconSvg(widget.icon || 'shield');

    var buttonLabel = document.createElement('span');
    buttonLabel.className = 'ab-widget-button-label';
    buttonLabel.textContent = 'הרישיון לא פעיל';

    button.appendChild(buttonGlow);
    button.appendChild(buttonIcon);
    button.appendChild(buttonLabel);

    button.addEventListener('click', function () {
      var purchaseUrl = payload && payload.purchaseUrl ? payload.purchaseUrl : purchaseFallbackUrl;
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

    var lightCursor = encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><circle cx="12" cy="12" r="8" fill="#ffffff" stroke="#0f172a" stroke-width="2"/></svg>');
    var darkCursor = encodeURIComponent('<svg xmlns="http://www.w3.org/2000/svg" width="32" height="32"><circle cx="12" cy="12" r="8" fill="#0f172a" stroke="#ffffff" stroke-width="2"/></svg>');

    var style = document.createElement('style');
    style.id = 'ab-widget-styles';
    style.textContent = `
      .ab-widget-shell{--ab-radius:28px;--ab-motion-fast:140ms;--ab-motion-base:220ms;position:fixed;z-index:999999;bottom:20px;right:20px;display:flex;flex-direction:column-reverse;gap:14px;align-items:flex-end;font-family:"Assistant","Segoe UI",Arial,sans-serif;}
      .ab-widget-shell.ab-bottom-left{left:20px;right:auto;align-items:flex-start;}
      .ab-widget-button{--ab-widget-color:#1d6dff;position:relative;display:inline-flex;align-items:center;gap:12px;min-height:60px;padding:0 18px 0 14px;border:1px solid rgba(255,255,255,.18);border-radius:999px;background:linear-gradient(135deg,var(--ab-widget-color),#081121 92%);color:#fff;font-weight:800;box-shadow:0 18px 44px rgba(15,23,42,.18);cursor:pointer;overflow:hidden;transition:transform var(--ab-motion-base) ease,box-shadow var(--ab-motion-base) ease,filter var(--ab-motion-base) ease;}
      .ab-widget-button:hover{transform:translateY(-1px);box-shadow:0 24px 56px rgba(15,23,42,.22);}
      .ab-widget-button-glow{position:absolute;inset:-1px;background:radial-gradient(circle at top right,rgba(255,255,255,.28),transparent 34%);pointer-events:none;}
      .ab-widget-button-icon{position:relative;width:34px;height:34px;display:inline-grid;place-items:center;border-radius:50%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);}
      .ab-widget-button-icon svg{width:18px;height:18px;}
      .ab-widget-button-label,.ab-widget-button-chevron{position:relative;}
      .ab-widget-button-chevron{font-size:22px;line-height:1;opacity:.78;transition:transform var(--ab-motion-base) ease;}
      .ab-widget-shell.ab-open .ab-widget-button-chevron{transform:rotate(90deg);}
      .ab-widget-button.ab-mode-label-only .ab-widget-button-icon{display:none;}
      .ab-widget-button.ab-mode-icon-only{min-width:60px;justify-content:center;padding-inline:0;}
      .ab-widget-button.ab-mode-icon-only .ab-widget-button-label,.ab-widget-button.ab-mode-icon-only .ab-widget-button-chevron{display:none;}
      .ab-widget-button.ab-style-soft{background:linear-gradient(180deg,rgba(255,255,255,.94),rgba(237,244,255,.95));color:#13325b;border-color:rgba(29,109,255,.16);}
      .ab-widget-button.ab-style-glass{background:linear-gradient(180deg,rgba(255,255,255,.62),rgba(255,255,255,.34));color:#10233f;border-color:rgba(255,255,255,.72);}
      .ab-widget-button.ab-style-midnight{background:linear-gradient(135deg,#081121,#152d57);color:#f8fafc;border-color:rgba(255,255,255,.08);}
      .ab-widget-button.ab-preset-high-tech{background:linear-gradient(135deg,#2563eb,#06101f 86%);}
      .ab-widget-button.ab-preset-elegant{background:linear-gradient(135deg,#1f2937,#67553d 88%);}
      .ab-widget-button.ab-preset-bold{background:linear-gradient(135deg,#7c3aed,#ec4899 88%);}
      .ab-widget-button.ab-license-inactive{background:linear-gradient(135deg,#d92d20,#7a0510);}
      .ab-widget-panel{width:min(430px,calc(100vw - 28px));max-height:min(82vh,760px);overflow:auto;border-radius:var(--ab-radius);background:linear-gradient(180deg,rgba(255,255,255,.94),rgba(244,248,252,.96));color:#0f172a;border:1px solid rgba(255,255,255,.76);box-shadow:0 30px 90px rgba(15,23,42,.16);padding:18px;position:relative;opacity:0;transform:translateY(12px) scale(.985);visibility:hidden;pointer-events:none;backdrop-filter:blur(22px);}
      .ab-widget-panel.ab-preset-high-tech{background:linear-gradient(180deg,rgba(245,249,255,.96),rgba(231,239,252,.98));}
      .ab-widget-panel.ab-preset-elegant{background:linear-gradient(180deg,rgba(255,252,247,.96),rgba(245,239,231,.98));}
      .ab-widget-panel.ab-preset-bold{background:linear-gradient(180deg,rgba(253,245,255,.96),rgba(239,230,255,.98));}
      .ab-widget-shell.ab-open .ab-widget-panel{opacity:1;transform:translateY(0) scale(1);visibility:visible;pointer-events:auto;}
      .ab-widget-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;}
      .ab-widget-brand{display:flex;align-items:center;gap:12px;}
      .ab-widget-brand-badge{width:42px;height:42px;display:inline-grid;place-items:center;padding:6px;border-radius:16px;background:rgba(255,255,255,.96);box-shadow:0 10px 24px rgba(29,109,255,.14);overflow:hidden;}
      .ab-widget-brand-logo{width:100%;height:100%;object-fit:contain;display:block;}
      .ab-widget-brand-text{display:grid;gap:2px;}
      .ab-widget-eyebrow{font-size:10px;letter-spacing:.14em;text-transform:uppercase;color:#64748b;font-weight:800;}
      .ab-widget-title{margin:0;font:800 22px/1.08 "Assistant","Segoe UI",Arial,sans-serif;letter-spacing:-.02em;}
      .ab-widget-close{width:38px;height:38px;border:1px solid rgba(15,23,42,.08);border-radius:14px;background:rgba(255,255,255,.72);color:#0f172a;font-size:24px;line-height:1;cursor:pointer;}
      .ab-widget-strip{display:flex;flex-wrap:wrap;gap:8px;margin-top:14px;}
      .ab-widget-chip{display:inline-flex;align-items:center;min-height:34px;padding:0 12px;border-radius:999px;background:#0b1220;color:#fff;font-size:12px;font-weight:700;}
      .ab-widget-chip.is-chip-muted{background:rgba(15,23,42,.05);color:#0f172a;}
      .ab-widget-chip.is-plan-free{background:rgba(21,128,61,.1);color:#166534;}
      .ab-widget-chip.is-plan-premium{background:rgba(124,58,237,.12);color:#6d28d9;}
      .ab-widget-body{display:grid;gap:18px;margin-top:16px;}
      .ab-widget-section{display:grid;gap:12px;}
      .ab-widget-section-head{display:grid;gap:4px;}
      .ab-widget-section-title{margin:0;font-size:1rem;font-weight:800;}
      .ab-widget-section-copy{margin:0;color:#64748b;font-size:13px;line-height:1.6;}
      .ab-widget-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:12px;}
      .ab-widget-grid-profiles{grid-template-columns:1fr;}
      .ab-widget-card{position:relative;display:grid;gap:10px;align-content:start;min-height:156px;padding:16px;border-radius:22px;background:rgba(247,250,253,.96);border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);}
      .ab-widget-card.is-locked{background:linear-gradient(180deg,rgba(250,252,255,.98),rgba(241,245,252,.98));}
      .ab-widget-profile-card.is-active{border-color:rgba(29,109,255,.2);box-shadow:0 14px 28px rgba(29,109,255,.12);}
      .ab-widget-card-title{font-size:14px;font-weight:800;}
      .ab-widget-card-copy{margin:0;color:#64748b;font-size:12px;line-height:1.6;}
      .ab-widget-card-actions{margin-top:auto;display:flex;flex-wrap:wrap;gap:8px;}
      .ab-widget-mini-badge{position:absolute;top:12px;left:12px;display:inline-flex;align-items:center;min-height:26px;padding:0 10px;border-radius:999px;background:rgba(124,58,237,.1);color:#6d28d9;font-size:11px;font-weight:800;}
      .ab-widget-choice-group{display:flex;flex-wrap:wrap;gap:8px;}
      .ab-widget-choice-button,.ab-widget-toggle-button,.ab-widget-lock-button,.ab-widget-link{min-height:40px;padding:0 12px;border-radius:999px;border:1px solid rgba(15,23,42,.08);background:#fff;color:#0f172a;display:inline-flex;align-items:center;justify-content:center;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,color var(--ab-motion-fast) ease;}
      .ab-widget-choice-button:hover,.ab-widget-toggle-button:hover,.ab-widget-lock-button:hover,.ab-widget-link:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(15,23,42,.08);}
      .ab-widget-choice-button.is-active,.ab-widget-toggle-button.is-active{background:linear-gradient(135deg,#1d6dff,#0d3ea7);color:#fff;border-color:transparent;}
      .ab-widget-lock-button{background:rgba(124,58,237,.08);color:#6d28d9;border-color:rgba(124,58,237,.16);}
      .ab-widget-link-premium{background:linear-gradient(135deg,#7c3aed,#ec4899 88%);color:#fff;border-color:transparent;}
      .ab-widget-footer{display:grid;gap:10px;padding-top:14px;border-top:1px solid rgba(15,23,42,.08);}
      .ab-widget-note{margin:0;color:#64748b;font-size:12px;line-height:1.6;}
      .ab-widget-inactive-note{max-width:230px;padding:10px 14px;border-radius:14px;background:rgba(217,45,32,.1);color:#7a0510;font-size:12px;font-weight:700;box-shadow:0 8px 18px rgba(122,5,16,.08);}
      .ab-reading-guide{position:fixed;left:0;right:0;height:72px;pointer-events:none;z-index:999998;background:linear-gradient(180deg,rgba(29,109,255,.04),rgba(29,109,255,.14),rgba(29,109,255,.04));border-top:1px solid rgba(29,109,255,.18);border-bottom:1px solid rgba(29,109,255,.18);backdrop-filter:blur(2px);display:none;}
      html.ab-theme-bright{filter:brightness(1.06) contrast(1.04);}
      html.ab-theme-dark{filter:brightness(.86) contrast(1.08);}
      html.ab-theme-high{filter:contrast(1.24) saturate(.94);}
      html.ab-pref-underline a{text-decoration:underline !important;text-underline-offset:.2em !important;}
      html.ab-pref-reduce-motion *,html.ab-pref-reduce-motion *::before,html.ab-pref-reduce-motion *::after{animation-duration:.01ms !important;animation-iteration-count:1 !important;transition-duration:.01ms !important;scroll-behavior:auto !important;}
      html.ab-pref-readable-font body,html.ab-pref-readable-font button,html.ab-pref-readable-font input,html.ab-pref-readable-font textarea,html.ab-pref-readable-font select,html.ab-pref-readable-font p,html.ab-pref-readable-font li,html.ab-pref-readable-font a,html.ab-pref-readable-font span{font-family:"Arial","Helvetica Neue",sans-serif !important;}
      html.ab-pref-highlight-titles h1,html.ab-pref-highlight-titles h2,html.ab-pref-highlight-titles h3,html.ab-pref-highlight-titles h4,html.ab-pref-highlight-titles h5,html.ab-pref-highlight-titles h6{color:#0d3ea7 !important;text-decoration:underline !important;text-decoration-thickness:3px !important;text-underline-offset:.18em !important;}
      html.ab-font-small{font-size:93.75%;}
      html.ab-font-large{font-size:112.5%;}
      html.ab-line-relaxed body,html.ab-line-relaxed p,html.ab-line-relaxed li,html.ab-line-relaxed span{line-height:1.95 !important;}
      html.ab-line-loose body,html.ab-line-loose p,html.ab-line-loose li,html.ab-line-loose span{line-height:2.2 !important;}
      html.ab-letter-wide body,html.ab-letter-wide p,html.ab-letter-wide li,html.ab-letter-wide a,html.ab-letter-wide span{letter-spacing:.04em !important;}
      html.ab-letter-wider body,html.ab-letter-wider p,html.ab-letter-wider li,html.ab-letter-wider a,html.ab-letter-wider span{letter-spacing:.08em !important;}
      html.ab-align-right body,html.ab-align-right p,html.ab-align-right li,html.ab-align-right h1,html.ab-align-right h2,html.ab-align-right h3,html.ab-align-right h4,html.ab-align-right h5,html.ab-align-right h6{text-align:right !important;}
      html.ab-align-center body,html.ab-align-center p,html.ab-align-center li,html.ab-align-center h1,html.ab-align-center h2,html.ab-align-center h3,html.ab-align-center h4,html.ab-align-center h5,html.ab-align-center h6{text-align:center !important;}
      html.ab-align-left body,html.ab-align-left p,html.ab-align-left li,html.ab-align-left h1,html.ab-align-left h2,html.ab-align-left h3,html.ab-align-left h4,html.ab-align-left h5,html.ab-align-left h6{text-align:left !important;}
      html.ab-hide-images img,html.ab-hide-images picture,html.ab-hide-images svg,html.ab-hide-images video,html.ab-hide-images canvas{opacity:.06 !important;filter:grayscale(1) !important;}
      html.ab-cursor-light,html.ab-cursor-light *{cursor:url("data:image/svg+xml,${lightCursor}") 12 12, auto !important;}
      html.ab-cursor-dark,html.ab-cursor-dark *{cursor:url("data:image/svg+xml,${darkCursor}") 12 12, auto !important;}
      .ab-widget-close:focus-visible,.ab-widget-choice-button:focus-visible,.ab-widget-toggle-button:focus-visible,.ab-widget-lock-button:focus-visible,.ab-widget-link:focus-visible,.ab-widget-button:focus-visible{outline:3px solid rgba(29,109,255,.22);outline-offset:3px;}
      @media (max-width:560px){
        .ab-widget-shell{left:auto !important;right:14px !important;align-items:flex-end;bottom:14px;}
        .ab-widget-shell.ab-bottom-left{left:14px !important;right:auto !important;align-items:flex-start;}
        .ab-widget-button{width:auto;max-width:calc(100vw - 28px);justify-content:flex-start;}
        .ab-widget-panel{width:min(430px,calc(100vw - 28px));}
        .ab-widget-grid{grid-template-columns:1fr;}
      }
    `;

    document.head.appendChild(style);
  }

  function loadPrefs() {
    try {
      var raw = window.localStorage.getItem(storageKey);

      if (!raw) {
        return Object.assign({}, defaultPrefs);
      }

      return Object.assign({}, defaultPrefs, JSON.parse(raw));
    } catch (error) {
      return Object.assign({}, defaultPrefs);
    }
  }

  function savePrefs(prefs) {
    window.localStorage.setItem(storageKey, JSON.stringify(prefs));
  }

  function applyPrefs(prefs) {
    var root = document.documentElement;

    root.classList.remove(
      'ab-theme-bright',
      'ab-theme-dark',
      'ab-theme-high',
      'ab-pref-underline',
      'ab-pref-reduce-motion',
      'ab-pref-readable-font',
      'ab-pref-highlight-titles',
      'ab-font-small',
      'ab-font-large',
      'ab-line-relaxed',
      'ab-line-loose',
      'ab-letter-wide',
      'ab-letter-wider',
      'ab-align-right',
      'ab-align-center',
      'ab-align-left',
      'ab-hide-images',
      'ab-cursor-light',
      'ab-cursor-dark'
    );

    if (prefs.contrastMode === 'bright') {
      root.classList.add('ab-theme-bright');
    } else if (prefs.contrastMode === 'dark') {
      root.classList.add('ab-theme-dark');
    } else if (prefs.contrastMode === 'high') {
      root.classList.add('ab-theme-high');
    }

    if (prefs.underlineLinks) {
      root.classList.add('ab-pref-underline');
    }

    if (prefs.reduceMotion) {
      root.classList.add('ab-pref-reduce-motion');
    }

    if (prefs.readableFont) {
      root.classList.add('ab-pref-readable-font');
    }

    if (prefs.highlightTitles) {
      root.classList.add('ab-pref-highlight-titles');
    }

    if (prefs.fontScale === 'small') {
      root.classList.add('ab-font-small');
    } else if (prefs.fontScale === 'large') {
      root.classList.add('ab-font-large');
    }

    if (prefs.lineSpacing === 'relaxed') {
      root.classList.add('ab-line-relaxed');
    } else if (prefs.lineSpacing === 'loose') {
      root.classList.add('ab-line-loose');
    }

    if (prefs.letterSpacing === 'wide') {
      root.classList.add('ab-letter-wide');
    } else if (prefs.letterSpacing === 'wider') {
      root.classList.add('ab-letter-wider');
    }

    if (prefs.textAlign === 'right') {
      root.classList.add('ab-align-right');
    } else if (prefs.textAlign === 'center') {
      root.classList.add('ab-align-center');
    } else if (prefs.textAlign === 'left') {
      root.classList.add('ab-align-left');
    }

    if (prefs.hideImages) {
      root.classList.add('ab-hide-images');
    }

    if (prefs.cursorMode === 'light') {
      root.classList.add('ab-cursor-light');
    } else if (prefs.cursorMode === 'dark') {
      root.classList.add('ab-cursor-dark');
    }

    syncReadingGuide(Boolean(prefs.readingGuide));
  }

  function syncReadingGuide(active) {
    if (!guideOverlay) {
      guideOverlay = document.createElement('div');
      guideOverlay.className = 'ab-reading-guide';
      document.body.appendChild(guideOverlay);
    }

    guideOverlay.style.display = active ? 'block' : 'none';

    if (!guideMoveHandlerAttached) {
      guideMoveHandlerAttached = true;

      var moveGuide = function (clientY) {
        if (!guideOverlay || guideOverlay.style.display !== 'block') {
          return;
        }

        guideOverlay.style.top = Math.max(0, clientY - 36) + 'px';
      };

      document.addEventListener('mousemove', function (event) {
        moveGuide(event.clientY);
      });

      document.addEventListener('touchmove', function (event) {
        if (event.touches && event.touches[0]) {
          moveGuide(event.touches[0].clientY);
        }
      }, { passive: true });
    }
  }
})();
