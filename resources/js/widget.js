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
  var featureLabelMap = {};
  var updateFeedbackTimer = null;

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
        return Object.assign({}, defaultPrefs);
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

  featureCatalog.forEach(function (feature) {
    featureLabelMap[feature.key] = feature.title;
  });

  injectStyles();
  loadWidgetConfig();

  function clearRenderedWidget() {
    document.querySelectorAll('.ab-widget-shell').forEach(function (node) {
      node.remove();
    });
  }

  function loadWidgetConfig() {
    clearRenderedWidget();

    fetch(platformOrigin + '/api/public/widget-config/' + encodeURIComponent(siteKey))
      .then(function (response) {
        if (!response.ok) {
          throw new Error('Widget config request failed with status ' + response.status);
        }

        return response.json();
      })
      .then(function (payload) {
        if (payload && payload.inactive) {
          renderInactiveWidget(payload);
          return;
        }

        if (!payload || !payload.success || !payload.data) {
          throw new Error('Missing widget config.');
        }

        reportWidgetSeen(payload.data);
        renderWidget(payload.data);
      })
      .catch(function () {
        renderUnavailableWidget({
          siteName: 'A11Y Bridge'
        }, loadWidgetConfig);
      });
  }

  function normalizePlan(plan) {
    if (plan === 'premium' || plan === 'growth' || plan === 'agency') {
      return 'premium';
    }

    return 'free';
  }

  function resolvePanelLayout(requestedLayout) {
    // The hosted widget panel is intentionally narrow.
    // Split sections inside a floating panel create overly narrow cards in real sites,
    // so we flatten the public widget to a stable stacked layout.
    return requestedLayout === 'split' ? 'stacked' : 'stacked';
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

  function getSectionIconSvg(type) {
    switch (type) {
      case 'profiles':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4a3.2 3.2 0 1 1 0 6.4A3.2 3.2 0 0 1 12 4Zm0 8.6c4.1 0 7.4 2.2 7.4 4.9V20H4.6v-2.5c0-2.7 3.3-4.9 7.4-4.9Z" fill="currentColor"/></svg>';
      case 'display':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 7.5A2.5 2.5 0 0 1 6.5 5h11A2.5 2.5 0 0 1 20 7.5v7A2.5 2.5 0 0 1 17.5 17h-11A2.5 2.5 0 0 1 4 14.5v-7Zm4 11.5h8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      case 'focus':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 5v4m0 6v4M5 12h4m6 0h4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/><circle cx="12" cy="12" r="3.2" fill="none" stroke="currentColor" stroke-width="1.8"/></svg>';
      default:
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14M8 11h8M5 15h14M8 19h8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
    }
  }

  function getFeatureIconSvg(type) {
    switch (type) {
      case 'contrastMode':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M12 4a8 8 0 1 0 0 16V4Z" fill="currentColor"/><path d="M12 4a8 8 0 0 1 0 16" fill="none" stroke="currentColor" stroke-width="1.6"/></svg>';
      case 'fontScale':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 18 4.2-12h1.7L16 18m-7.5-3h5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      case 'lineSpacing':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 8h10M7 12h10M7 16h10M4.5 5.5 3 7l1.5 1.5M19.5 18.5 21 17l-1.5-1.5" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      case 'underlineLinks':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M10 9.5 8.2 11.3a3 3 0 1 0 4.2 4.2l1.8-1.8M14 14.5l1.8-1.8a3 3 0 1 0-4.2-4.2L9.8 10.3" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      case 'highlightTitles':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M7 6v12M17 6v12M7 12h10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
      case 'readableFont':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 18 10.2 6h1.6L16 18m-7.4-3h4.8" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="M18.5 8.5h2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>';
      case 'reduceMotion':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h5l2-4 3 8 2-4h4" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      case 'cursorMode':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="m6 4 9 9-4 1 2 5-2 1-2-5-3 3V4Z" fill="currentColor"/></svg>';
      case 'hideImages':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7.5A2.5 2.5 0 0 1 7.5 5h9A2.5 2.5 0 0 1 19 7.5v9A2.5 2.5 0 0 1 16.5 19h-9A2.5 2.5 0 0 1 5 16.5v-9Zm3 7 2-2 3 3 2-2 2 2" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/><path d="m4 4 16 16" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round"/></svg>';
      case 'letterSpacing':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M6 18 9.8 6h1.4L15 18m-6-3h3.6M17.5 9 20 12l-2.5 3M3.5 9 1 12l2.5 3" fill="none" stroke="currentColor" stroke-width="1.7" stroke-linecap="round" stroke-linejoin="round"/></svg>';
      case 'textAlign':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M5 7h14M8 11h8M5 15h14M8 19h8" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
      case 'readingGuide':
        return '<svg viewBox="0 0 24 24" aria-hidden="true"><path d="M4 12h16M7 8h10M7 16h10" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>';
      default:
        return getSectionIconSvg('text');
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
    var panelLayout = resolvePanelLayout(config.widget.panelLayout || 'stacked');

    applyPrefs(prefs);

    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-' + position + ' ab-' + config.widget.size + ' ab-preset-' + preset + ' ab-layout-' + panelLayout;

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

    var buttonMeta = document.createElement('span');
    buttonMeta.className = 'ab-widget-button-meta';
    buttonMeta.setAttribute('aria-hidden', 'true');
    buttonMeta.textContent = plan === 'premium' ? 'פרימיום' : 'נגישות';

    var buttonChevron = document.createElement('span');
    buttonChevron.className = 'ab-widget-button-chevron';
    buttonChevron.setAttribute('aria-hidden', 'true');
    buttonChevron.textContent = '›';

    button.appendChild(buttonGlow);
    button.appendChild(buttonIcon);
    button.appendChild(buttonLabel);
    button.appendChild(buttonMeta);
    button.appendChild(buttonChevron);

    var panel = document.createElement('section');
    panel.className = 'ab-widget-panel ab-preset-' + preset + ' ab-layout-' + panelLayout;
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
    eyebrow.textContent = config.siteName || 'A11Y Bridge';

    var title = document.createElement('h2');
    title.className = 'ab-widget-title';
    title.textContent = 'התאמות נגישות מהירות';

    var subtitle = document.createElement('p');
    subtitle.className = 'ab-widget-brand-subtitle';
    subtitle.textContent = plan === 'premium'
      ? 'כל השינויים חלים מיד ונשמרים במכשיר הזה.'
      : 'החבילה החינמית פתוחה להתחלה מהירה ונשמרת במכשיר הזה.';

    panelBrandText.appendChild(eyebrow);
    panelBrandText.appendChild(title);
    panelBrandText.appendChild(subtitle);
    panelBrand.appendChild(panelBadge);
    panelBrand.appendChild(panelBrandText);

    var closeButton = document.createElement('button');
    closeButton.type = 'button';
    closeButton.className = 'ab-widget-close';
    closeButton.setAttribute('aria-label', 'סגור פאנל');
    closeButton.textContent = '×';

    var headerActions = document.createElement('div');
    headerActions.className = 'ab-widget-header-actions';

    var resetButton = document.createElement('button');
    resetButton.type = 'button';
    resetButton.className = 'ab-widget-reset';
    resetButton.textContent = 'איפוס';
    resetButton.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      updatePrefs(Object.assign({}, defaultPrefs));
    });

    headerActions.appendChild(resetButton);
    headerActions.appendChild(closeButton);

    panelHeader.appendChild(panelBrand);
    panelHeader.appendChild(headerActions);
    panel.appendChild(panelHeader);

    var infoStrip = document.createElement('div');
    infoStrip.className = 'ab-widget-strip';
    infoStrip.appendChild(createChip(plan === 'premium' ? 'מסלול מלא' : 'כלי חינמי', plan === 'premium' ? 'is-plan-premium' : 'is-plan-free'));
    infoStrip.appendChild(createChip('נשמר במכשיר הזה', 'is-chip-muted'));
    infoStrip.appendChild(createChip(config.statementUrl ? 'הצהרה מחוברת' : 'ללא הצהרה', config.statementUrl ? 'is-chip-positive' : 'is-chip-muted'));
    panel.appendChild(infoStrip);

    var panelBody = document.createElement('div');
    panelBody.className = 'ab-widget-body ab-layout-' + panelLayout;
    panel.appendChild(panelBody);

    function refreshPanel() {
      var currentScrollTop = panel.scrollTop;
      prefs = loadPrefs();
      applyPrefs(prefs);
      panelBody.innerHTML = '';
      shell.className = 'ab-widget-shell ab-' + position + ' ab-' + config.widget.size + ' ab-preset-' + preset + ' ab-layout-' + panelLayout + (shell.classList.contains('ab-open') ? ' ab-open' : '');
      panel.className = 'ab-widget-panel ab-preset-' + preset + ' ab-layout-' + panelLayout;
      panelBody.className = 'ab-widget-body ab-layout-' + panelLayout;

      panelBody.appendChild(createOverviewCard(config, plan, prefs));
      panelBody.appendChild(createActiveStateCard(prefs));
      panelBody.appendChild(createStarterStrip(plan, prefs));

      var sectionsWrap = document.createElement('div');
      sectionsWrap.className = 'ab-widget-sections ab-layout-' + panelLayout;
      var lockedPremiumItems = [];

      sectionsWrap.appendChild(createProfileSection(plan, prefs, lockedPremiumItems));
      sectionsWrap.appendChild(createFeatureSection('התאמות טקסט ותוכן', 'כלים לקריאה, מרווחים ויישור טקסט.', featureCatalog.filter(function (feature) {
        return feature.section === 'text';
      }), plan, prefs, 'text', lockedPremiumItems));
      sectionsWrap.appendChild(createFeatureSection('התאמות צבעים ותצוגה', 'ניגודיות, בהירות ונראות כללית של האתר.', featureCatalog.filter(function (feature) {
        return feature.section === 'display';
      }), plan, prefs, 'display', lockedPremiumItems));
      sectionsWrap.appendChild(createFeatureSection('ניווט, מיקוד ונוחות', 'הפחתת תנועה, סמן גדול וכלי ריכוז.', featureCatalog.filter(function (feature) {
        return feature.section === 'focus';
      }), plan, prefs, 'focus', lockedPremiumItems));

      if (plan !== 'premium' && lockedPremiumItems.length) {
        sectionsWrap.appendChild(createPremiumShowcaseSection(lockedPremiumItems, purchaseUrl));
      }
      panelBody.appendChild(createToolbar(sectionsWrap, plan));
      panelBody.appendChild(sectionsWrap);

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
      note.textContent = plan === 'premium'
        ? 'כל ההתאמות המתקדמות פתוחות כרגע בתוך אותו חלון עבודה.'
        : 'הכלי החינמי נותן התחלה חזקה. השכבה המתקדמת זמינה רק אם צריך יותר עומק.';
      footer.appendChild(note);
      panelBody.appendChild(footer);
      panel.scrollTop = currentScrollTop;
    }

    function createToolbar(sectionsWrap, planName) {
      var toolbar = document.createElement('section');
      toolbar.className = 'ab-widget-toolbar';

      var toolbarHead = document.createElement('div');
      toolbarHead.className = 'ab-widget-toolbar-head';

      var toolbarLabel = document.createElement('strong');
      toolbarLabel.className = 'ab-widget-toolbar-label';
      toolbarLabel.textContent = planName === 'premium'
        ? 'ניווט וחיפוש מהיר'
        : 'ניווט מהיר בתוך הכלי';

      toolbarHead.appendChild(toolbarLabel);

      var shortcuts = document.createElement('div');
      shortcuts.className = 'ab-widget-shortcuts';

      [
        { key: 'profiles', label: 'פרופילים' },
        { key: 'text', label: 'טקסט' },
        { key: 'display', label: 'צבעים' },
        { key: 'focus', label: 'מיקוד' },
        { key: 'premium', label: 'פרימיום' }
      ].forEach(function (item) {
        if (item.key === 'premium' && planName === 'premium') {
          return;
        }

        var target = sectionsWrap.querySelector('[data-widget-section="' + item.key + '"]');
        if (!target) {
          return;
        }

        var shortcut = document.createElement('button');
        shortcut.type = 'button';
        shortcut.className = 'ab-widget-shortcut';
        shortcut.textContent = item.label;
        shortcut.addEventListener('click', function () {
          target.scrollIntoView({ block: 'start', behavior: 'smooth' });
        });
        shortcuts.appendChild(shortcut);
      });

      var searchWrap = document.createElement('div');
      searchWrap.className = 'ab-widget-search';

      var searchIcon = document.createElement('span');
      searchIcon.className = 'ab-widget-search-icon';
      searchIcon.innerHTML = getSectionIconSvg('text');

      var searchInput = document.createElement('input');
      searchInput.type = 'search';
      searchInput.className = 'ab-widget-search-input';
      searchInput.placeholder = 'חפש התאמה, פרופיל או יכולת...';

      var searchMeta = document.createElement('span');
      searchMeta.className = 'ab-widget-search-meta';
      searchMeta.textContent = 'חיפוש מהיר';

      searchInput.addEventListener('input', function () {
        var query = searchInput.value.trim().toLowerCase();
        var cards = sectionsWrap.querySelectorAll('.ab-widget-card, .ab-widget-premium-item');
        var hits = [];

        cards.forEach(function (card) {
          var haystack = (card.textContent || '').trim().toLowerCase();
          var isHit = query.length > 1 && haystack.indexOf(query) !== -1;
          card.classList.toggle('is-search-hit', isHit);
          card.classList.toggle('is-search-dim', query.length > 1 && !isHit);
          if (isHit) {
            hits.push(card);
          }
        });

        if (query.length <= 1) {
          searchMeta.textContent = 'חיפוש מהיר';
          return;
        }

        if (!hits.length) {
          searchMeta.textContent = 'לא נמצאו התאמות';
          return;
        }

        searchMeta.textContent = 'נמצאו ' + hits.length + ' התאמות';
        hits[0].scrollIntoView({ block: 'nearest', behavior: 'smooth' });
      });

      searchWrap.appendChild(searchIcon);
      searchWrap.appendChild(searchInput);
      searchWrap.appendChild(searchMeta);
      toolbar.appendChild(toolbarHead);
      toolbar.appendChild(shortcuts);
      toolbar.appendChild(searchWrap);

      return toolbar;
    }

    function createOverviewCard(widgetConfig, planName, currentPrefs) {
      var overview = document.createElement('section');
      overview.className = 'ab-widget-overview';

      var activeEntries = getActiveAdjustmentEntries(currentPrefs);
      var unlockedFeatureCount = featureCatalog.filter(function (feature) {
        return feature.plan !== 'premium' || planName === 'premium';
      }).length;
      var unlockedProfileCount = profiles.filter(function (profile) {
        return profile.plan !== 'premium' || planName === 'premium';
      }).length;
      var scoreValue = widgetConfig.audit && widgetConfig.audit.score ? String(widgetConfig.audit.score) : '—';

      var summary = document.createElement('article');
      summary.className = 'ab-widget-summary-card';

      var summaryHead = document.createElement('div');
      summaryHead.className = 'ab-widget-summary-head';

      var summaryTitle = document.createElement('strong');
      summaryTitle.className = 'ab-widget-summary-title';
      summaryTitle.textContent = currentPrefs.profile && currentPrefs.profile !== 'none'
        ? 'פרופיל פעיל שמוחל עכשיו על האתר'
        : 'התאמות נגישות שמוחלות מיד על האתר';

      var summaryCopy = document.createElement('p');
      summaryCopy.className = 'ab-widget-summary-copy';
      summaryCopy.textContent = planName === 'premium'
        ? 'כל הפקדים, הפרופילים וההתאמות המתקדמות פתוחים כאן באותו חלון עבודה.'
        : 'אפשר להתחיל מיד עם טקסט, צבעים, מיקוד ופרופילים בסיסיים. הכול נשמר במכשיר הזה וחוזר גם בביקור הבא.';

      summaryHead.appendChild(summaryTitle);
      summaryHead.appendChild(summaryCopy);
      summary.appendChild(summaryHead);

      var metrics = document.createElement('div');
      metrics.className = 'ab-widget-summary-metrics';
      metrics.appendChild(createOverviewFact('מצב', currentPrefs.profile !== 'none' ? 'פרופיל פעיל' : 'התאמה ידנית'));
      metrics.appendChild(createOverviewFact('פתוח במסלול', String(unlockedFeatureCount) + ' פקדים · ' + String(unlockedProfileCount) + ' פרופילים'));
      metrics.appendChild(createOverviewFact('אתר', widgetConfig.statementUrl ? 'הצהרה מחוברת' : 'ללא הצהרה'));
      overview.appendChild(summary);
      overview.appendChild(metrics);

      var summaryNote = document.createElement('p');
      summaryNote.className = 'ab-widget-summary-note';
      summaryNote.textContent = activeEntries.length
        ? 'כדי לבטל התאמה, אפשר ללחוץ עליה באזור ההתאמות הפעילות.'
        : scoreValue !== '—'
          ? 'ציון הנגישות האחרון של האתר: ' + scoreValue + '.'
          : 'התחילו מפעולה מהירה אחת או עברו ישירות לפרופיל מתאים.';
      overview.appendChild(summaryNote);

      return overview;
    }

    function createOverviewFact(label, value) {
      var card = document.createElement('article');
      card.className = 'ab-widget-overview-card';

      var labelNode = document.createElement('span');
      labelNode.className = 'ab-widget-overview-label';
      labelNode.textContent = label;

      var valueNode = document.createElement('strong');
      valueNode.className = 'ab-widget-overview-value';
      valueNode.textContent = value;

      card.appendChild(labelNode);
      card.appendChild(valueNode);
      return card;
    }

    function createMiniMetric(label, value) {
      var metric = document.createElement('div');
      metric.className = 'ab-widget-hero-metric';

      var labelNode = document.createElement('span');
      labelNode.className = 'ab-widget-hero-metric-label';
      labelNode.textContent = label;

      var valueNode = document.createElement('strong');
      valueNode.className = 'ab-widget-hero-metric-value';
      valueNode.textContent = value;

      metric.appendChild(labelNode);
      metric.appendChild(valueNode);
      return metric;
    }

    function createPromiseCard(title, copy) {
      var card = document.createElement('article');
      card.className = 'ab-widget-promise-card';

      var titleNode = document.createElement('strong');
      titleNode.className = 'ab-widget-promise-title';
      titleNode.textContent = title;

      var copyNode = document.createElement('p');
      copyNode.className = 'ab-widget-promise-copy';
      copyNode.textContent = copy;

      card.appendChild(titleNode);
      card.appendChild(copyNode);
      return card;
    }

    function createActiveStateCard(currentPrefs) {
      var entries = getActiveAdjustmentEntries(currentPrefs);
      var section = document.createElement('section');
      section.className = 'ab-widget-active-state';

      var head = document.createElement('div');
      head.className = 'ab-widget-active-head';

      var titleNode = document.createElement('strong');
      titleNode.className = 'ab-widget-active-title';
      titleNode.textContent = 'התאמות פעילות כרגע';

      var metaNode = document.createElement('span');
      metaNode.className = 'ab-widget-active-meta';
      metaNode.textContent = entries.length ? String(entries.length) + ' פעילות' : 'הכול במצב רגיל';

      head.appendChild(titleNode);
      head.appendChild(metaNode);
      section.appendChild(head);

      if (!entries.length) {
        var emptyNode = document.createElement('p');
        emptyNode.className = 'ab-widget-active-empty';
        emptyNode.textContent = 'עדיין לא הופעלה התאמה. כל שינוי שתבצע יופיע כאן ויהיה זמין לאיפוס מהיר.';
        section.appendChild(emptyNode);
        return section;
      }

      var chips = document.createElement('div');
      chips.className = 'ab-widget-active-chips';

      entries.forEach(function (entry) {
        var chip = document.createElement('button');
        chip.type = 'button';
        chip.className = 'ab-widget-active-chip';
        chip.textContent = entry.label + ' ×';
        chip.addEventListener('click', function () {
          if (entry.key === 'profile') {
            updatePrefs(Object.assign({}, defaultPrefs));
            return;
          }

          updatePrefs({
            profile: 'none',
            [entry.key]: defaultPrefs[entry.key]
          });
        });
        chips.appendChild(chip);
      });

      section.appendChild(chips);
      return section;
    }

    function createStarterStrip(planName, currentPrefs) {
      var section = document.createElement('section');
      section.className = 'ab-widget-starter-strip';

      var intro = document.createElement('div');
      intro.className = 'ab-widget-starter-intro';

      var titleNode = document.createElement('strong');
      titleNode.className = 'ab-widget-starter-title';
      titleNode.textContent = 'פעולות מהירות להתחלה';

      var copyNode = document.createElement('p');
      copyNode.className = 'ab-widget-starter-copy';
      copyNode.textContent = planName === 'premium'
        ? 'בחרו את מצב ההתחלה שנוח לכם, ומשם המשיכו בגלילה רגילה לכל ההתאמות.'
        : 'אפשר להתחיל מ־3 פעולות מהירות שכבר פתוחות בחינם, ואז לרדת להתאמות המלאות.';

      intro.appendChild(titleNode);
      intro.appendChild(copyNode);
      section.appendChild(intro);

      var grid = document.createElement('div');
      grid.className = 'ab-widget-starter-grid';

      grid.appendChild(createStarterActionCard({
        title: 'קריאה נוחה',
        description: 'גופן קריא, ריווח נעים והדגשת קישורים.',
        icon: 'readableFont',
        active: Boolean(currentPrefs.readableFont || currentPrefs.lineSpacing !== 'normal' || currentPrefs.underlineLinks),
        buttonLabel: 'הפעל',
        action: function () {
          updatePrefs({
            profile: 'none',
            readableFont: true,
            lineSpacing: 'relaxed',
            underlineLinks: true
          });
        }
      }));

      grid.appendChild(createStarterActionCard({
        title: 'ניגודיות חזקה',
        description: 'מגדיל נראות כללית, קישורים וכותרות.',
        icon: 'contrastMode',
        active: currentPrefs.contrastMode === 'high' || Boolean(currentPrefs.highlightTitles),
        buttonLabel: 'הפעל',
        action: function () {
          updatePrefs({
            profile: 'none',
            contrastMode: 'high',
            highlightTitles: true,
            underlineLinks: true
          });
        }
      }));

      grid.appendChild(createStarterActionCard({
        title: 'פוקוס שקט',
        description: 'מפחית תנועה ומייצב את חוויית הקריאה.',
        icon: 'reduceMotion',
        active: Boolean(currentPrefs.reduceMotion || currentPrefs.cursorMode !== 'default'),
        buttonLabel: 'הפעל',
        action: function () {
          updatePrefs({
            profile: 'none',
            reduceMotion: true,
            cursorMode: 'dark'
          });
        }
      }));

      grid.appendChild(createStarterActionCard({
        title: 'איפוס מלא',
        description: 'חוזר לברירת המחדל של האתר בלחיצה אחת.',
        icon: 'fontScale',
        active: false,
        buttonLabel: 'אפס',
        subtle: true,
        action: function () {
          updatePrefs(Object.assign({}, defaultPrefs));
        }
      }));

      section.appendChild(grid);
      return section;
    }

    function createStarterActionCard(config) {
      var card = document.createElement('article');
      card.className = 'ab-widget-starter-card' + (config.subtle ? ' is-subtle' : '') + (config.active ? ' is-active' : '');

      var iconNode = document.createElement('span');
      iconNode.className = 'ab-widget-card-icon';
      iconNode.innerHTML = getFeatureIconSvg(config.icon);

      var copyWrap = document.createElement('div');
      copyWrap.className = 'ab-widget-starter-copywrap';

      var titleNode = document.createElement('strong');
      titleNode.className = 'ab-widget-card-title';
      titleNode.textContent = config.title;

      var descNode = document.createElement('p');
      descNode.className = 'ab-widget-card-copy';
      descNode.textContent = config.description;

      copyWrap.appendChild(titleNode);
      copyWrap.appendChild(descNode);

      var actionWrap = document.createElement('div');
      actionWrap.className = 'ab-widget-card-actions';

      if (config.active) {
        actionWrap.appendChild(createMiniBadge('פעיל'));
      }

      var button = document.createElement('button');
      button.type = 'button';
      button.className = 'ab-widget-choice-button' + (config.subtle ? '' : ' is-active');
      button.textContent = config.buttonLabel;
      button.addEventListener('click', config.action);

      actionWrap.appendChild(button);
      card.appendChild(iconNode);
      card.appendChild(copyWrap);
      card.appendChild(actionWrap);

      return card;
    }

    function getActiveAdjustmentEntries(currentPrefs) {
      var entries = [];

      if (currentPrefs.profile && currentPrefs.profile !== 'none') {
        var currentProfile = profiles.find(function (profile) {
          return profile.key === currentPrefs.profile;
        });

        entries.push({
          key: 'profile',
          label: currentProfile ? currentProfile.title : 'פרופיל פעיל'
        });
      }

      featureCatalog.forEach(function (feature) {
        var currentValue = currentPrefs[feature.key];
        var defaultValue = defaultPrefs[feature.key];

        if (feature.type === 'toggle') {
          if (Boolean(currentValue) && !Boolean(defaultValue)) {
            entries.push({
              key: feature.key,
              label: feature.title
            });
          }
          return;
        }

        if (typeof currentValue !== 'undefined' && currentValue !== defaultValue) {
          var option = (feature.options || []).find(function (item) {
            return item.value === currentValue;
          });

          entries.push({
            key: feature.key,
            label: feature.title + (option ? ' · ' + option.label : '')
          });
        }
      });

      return entries;
    }

    button.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      if (button.getAttribute('aria-expanded') === 'true') {
        closePanel();
        return;
      }

      openPanel();
    });

    closeButton.addEventListener('click', function (event) {
      event.preventDefault();
      event.stopPropagation();
      closePanel();
    });

    document.addEventListener('keydown', function (event) {
      if (event.key === 'Escape') {
        closePanel();
      }
    });

    shell.addEventListener('click', function (event) {
      event.stopPropagation();
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

    function createProfileSection(planName, currentPrefs, lockedPremiumItems) {
      var section = document.createElement('section');
      section.className = 'ab-widget-section is-full';
      section.setAttribute('data-widget-section', 'profiles');
      var unlockedProfiles = profiles.filter(function (profile) {
        return profile.plan !== 'premium' || planName === 'premium';
      });
      var lockedProfiles = profiles.filter(function (profile) {
        return profile.plan === 'premium' && planName !== 'premium';
      });

      section.appendChild(createSectionHead('בחרו פרופיל נגישות', 'הפעלת תצורה מהירה של התאמות מוכנות מראש.', 'profiles', String(unlockedProfiles.length) + ' מצבים זמינים'));

      var profileGrid = document.createElement('div');
      profileGrid.className = 'ab-widget-grid ab-widget-grid-profiles';

      unlockedProfiles.forEach(function (profile) {
        var card = document.createElement('article');
        card.className = 'ab-widget-card ab-widget-profile-card' + (currentPrefs.profile === profile.key ? ' is-active' : '');

        var iconNode = document.createElement('span');
        iconNode.className = 'ab-widget-card-icon';
        iconNode.innerHTML = getFeatureIconSvg(profile.key === 'vision' ? 'contrastMode' : profile.key === 'epilepsy' ? 'reduceMotion' : profile.key === 'adhd' ? 'readingGuide' : profile.key === 'cognitive' ? 'letterSpacing' : profile.key === 'dyslexia' ? 'readableFont' : 'fontScale');

        var titleNode = document.createElement('strong');
        titleNode.className = 'ab-widget-card-title';
        titleNode.textContent = profile.title;

        var descNode = document.createElement('p');
        descNode.className = 'ab-widget-card-copy';
        descNode.textContent = profile.description;

        var actionWrap = document.createElement('div');
        actionWrap.className = 'ab-widget-card-actions';
        var profileButton = document.createElement('button');
        profileButton.type = 'button';
        profileButton.className = 'ab-widget-choice-button' + (currentPrefs.profile === profile.key ? ' is-active' : '');
        if (profile.key === 'none') {
          profileButton.textContent = 'איפוס';
        } else if (currentPrefs.profile === profile.key) {
          profileButton.textContent = 'בטל פרופיל';
        } else {
          profileButton.textContent = 'הפעל';
        }
        profileButton.addEventListener('click', function () {
          if (profile.key !== 'none' && currentPrefs.profile === profile.key) {
            updatePrefs(Object.assign({}, defaultPrefs));
            return;
          }

          updatePrefs(profile.prefs());
        });
        actionWrap.appendChild(profileButton);

        card.appendChild(iconNode);
        card.appendChild(titleNode);
        card.appendChild(descNode);
        card.appendChild(actionWrap);
        profileGrid.appendChild(card);
      });

      section.appendChild(profileGrid);

      if (lockedProfiles.length) {
        lockedProfiles.forEach(function (profile) {
          lockedPremiumItems.push({
            title: profile.title,
            description: profile.description,
            icon: profile.key === 'vision' ? 'contrastMode' : profile.key === 'epilepsy' ? 'reduceMotion' : profile.key === 'adhd' ? 'readingGuide' : profile.key === 'cognitive' ? 'letterSpacing' : profile.key === 'dyslexia' ? 'readableFont' : 'fontScale',
            group: 'פרופילים'
          });
        });
      }

      return section;
    }

    function createFeatureSection(titleText, descriptionText, features, planName, currentPrefs, sectionType, lockedPremiumItems) {
      var section = document.createElement('section');
      section.className = 'ab-widget-section';
      section.setAttribute('data-widget-section', sectionType || 'text');
      var unlockedFeatures = features.filter(function (feature) {
        return feature.plan !== 'premium' || planName === 'premium';
      });
      var lockedFeatures = features.filter(function (feature) {
        return feature.plan === 'premium' && planName !== 'premium';
      });

      section.appendChild(createSectionHead(titleText, descriptionText, sectionType, String(unlockedFeatures.length) + ' פקדים זמינים'));

      var grid = document.createElement('div');
      grid.className = 'ab-widget-grid';

      unlockedFeatures.forEach(function (feature) {
        var card = document.createElement('article');
        card.className = 'ab-widget-card';

        var iconNode = document.createElement('span');
        iconNode.className = 'ab-widget-card-icon';
        iconNode.innerHTML = getFeatureIconSvg(feature.key);

        var titleNode = document.createElement('strong');
        titleNode.className = 'ab-widget-card-title';
        titleNode.textContent = feature.title;

        var descNode = document.createElement('p');
        descNode.className = 'ab-widget-card-copy';
        descNode.textContent = feature.description;

        var actionWrap = document.createElement('div');
        actionWrap.className = 'ab-widget-card-actions';

        if (feature.type === 'toggle') {
          actionWrap.appendChild(createToggle(feature, currentPrefs[feature.key]));
        } else {
          actionWrap.appendChild(createChoiceGroup(feature, currentPrefs[feature.key]));
        }

        card.appendChild(iconNode);
        card.appendChild(titleNode);
        card.appendChild(descNode);
        card.appendChild(actionWrap);
        grid.appendChild(card);
      });

      section.appendChild(grid);

      if (lockedFeatures.length) {
        lockedFeatures.forEach(function (feature) {
          lockedPremiumItems.push({
            title: feature.title,
            description: feature.description,
            icon: feature.key,
            group: titleText
          });
        });
      }

      return section;
    }

    function createSectionHead(titleText, descriptionText, sectionType, badgeText) {
      var head = document.createElement('div');
      head.className = 'ab-widget-section-head';

      var iconNode = document.createElement('span');
      iconNode.className = 'ab-widget-section-icon';
      iconNode.innerHTML = getSectionIconSvg(sectionType || 'text');

      var copyWrap = document.createElement('div');
      copyWrap.className = 'ab-widget-section-copywrap';

      var titleNode = document.createElement('h3');
      titleNode.className = 'ab-widget-section-title';
      titleNode.textContent = titleText;

      var descNode = document.createElement('p');
      descNode.className = 'ab-widget-section-copy';
      descNode.textContent = descriptionText;

      copyWrap.appendChild(titleNode);
      copyWrap.appendChild(descNode);
      head.appendChild(iconNode);
      head.appendChild(copyWrap);

      if (badgeText) {
        var badgeNode = document.createElement('span');
        badgeNode.className = 'ab-widget-section-badge';
        badgeNode.textContent = badgeText;
        head.appendChild(badgeNode);
      }

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

    function createPremiumShowcaseSection(items, premiumUrl) {
      var uniqueItems = [];
      var seen = {};

      items.forEach(function (item) {
        var key = item.group + '::' + item.title;
        if (!seen[key]) {
          seen[key] = true;
          uniqueItems.push(item);
        }
      });

      var section = document.createElement('section');
      section.className = 'ab-widget-section is-full';
      section.setAttribute('data-widget-section', 'premium');
      section.appendChild(createSectionHead('יכולות פרימיום', 'כל ההתאמות המתקדמות מרוכזות כאן בצורה מסודרת, בלי לפזר כרטיסים נעולים לכל אורך הווידג׳ט.', 'focus', String(uniqueItems.length) + ' יכולות מתקדמות'));

      var card = document.createElement('article');
      card.className = 'ab-widget-upgrade-card';
      card.appendChild(createMiniBadge('שכבה מתקדמת'));

      var titleNode = document.createElement('strong');
      titleNode.className = 'ab-widget-card-title';
      titleNode.textContent = 'כשצריך יותר עומק ושליטה';

      var descNode = document.createElement('p');
      descNode.className = 'ab-widget-card-copy';
      descNode.textContent = 'הכלי החינמי נשאר חזק גם בלי שדרוג. השכבה המתקדמת נועדה למצבים שבהם צריך יותר פרופילים, יותר שליטה ויותר עומק.';

      var grid = document.createElement('div');
      grid.className = 'ab-widget-premium-list';

      uniqueItems.slice(0, 4).forEach(function (item) {
        var premiumItem = document.createElement('div');
        premiumItem.className = 'ab-widget-premium-item';

        var iconNode = document.createElement('span');
        iconNode.className = 'ab-widget-card-icon';
        iconNode.innerHTML = getFeatureIconSvg(item.icon);

        var copy = document.createElement('div');
        copy.className = 'ab-widget-premium-copy';

        var label = document.createElement('strong');
        label.className = 'ab-widget-card-title';
        label.textContent = item.title;

        var meta = document.createElement('p');
        meta.className = 'ab-widget-card-copy';
        meta.textContent = item.group + ' · ' + item.description;

        copy.appendChild(label);
        copy.appendChild(meta);
        premiumItem.appendChild(iconNode);
        premiumItem.appendChild(copy);
        grid.appendChild(premiumItem);
      });

      if (uniqueItems.length > 4) {
        var moreNode = document.createElement('p');
        moreNode.className = 'ab-widget-premium-more';
        moreNode.textContent = 'ועוד ' + String(uniqueItems.length - 4) + ' יכולות מתקדמות זמינות בשכבה הרחבה.';
        grid.appendChild(moreNode);
      }

      var noteNode = document.createElement('p');
      noteNode.className = 'ab-widget-premium-note';
      noteNode.textContent = 'לא חייבים לשדרג כדי להשתמש בכלי. זה רק שלב המשך למי שצריך יותר עומק.';

      var actionWrap = document.createElement('div');
      actionWrap.className = 'ab-widget-card-actions';
      actionWrap.appendChild(createLockButton(premiumUrl));

      card.appendChild(titleNode);
      card.appendChild(descNode);
      card.appendChild(grid);
      card.appendChild(noteNode);
      card.appendChild(actionWrap);
      section.appendChild(card);

      return section;
    }

    function createCompareRow(label, value) {
      var row = document.createElement('div');
      row.className = 'ab-widget-compare-row';

      var labelNode = document.createElement('strong');
      labelNode.className = 'ab-widget-compare-label';
      labelNode.textContent = label;

      var valueNode = document.createElement('span');
      valueNode.className = 'ab-widget-compare-value';
      valueNode.textContent = value;

      row.appendChild(labelNode);
      row.appendChild(valueNode);
      return row;
    }

    function createLockButton(url) {
      var link = document.createElement('a');
      link.className = 'ab-widget-lock-button';
      link.href = url;
      link.textContent = 'שדרג לפרימיום';
      return link;
    }

    function createChoiceGroup(feature, currentValue) {
      var group = document.createElement('div');
      group.className = 'ab-widget-choice-group';
      var defaultValue = defaultPrefs[feature.key];

      feature.options.forEach(function (option) {
        var button = document.createElement('button');
        button.type = 'button';
        button.className = 'ab-widget-choice-button' + (currentValue === option.value ? ' is-active' : '');
        button.textContent = option.label;
        button.addEventListener('click', function () {
          var nextValue = currentValue === option.value && typeof defaultValue !== 'undefined'
            ? defaultValue
            : option.value;

          updatePrefs({
            profile: 'none',
            [feature.key]: nextValue
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

      Object.keys(defaultPrefs).forEach(function (key) {
        if (typeof nextPrefs[key] === 'undefined' || nextPrefs[key] === null) {
          nextPrefs[key] = defaultPrefs[key];
        }
      });

      savePrefs(nextPrefs);
      shell.classList.add('ab-is-updating');
      clearTimeout(updateFeedbackTimer);
      updateFeedbackTimer = window.setTimeout(function () {
        shell.classList.remove('ab-is-updating');
      }, 420);
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

  function renderUnavailableWidget(payload, retryFn) {
    var shell = document.createElement('div');
    shell.className = 'ab-widget-shell ab-' + (forcedPosition || 'bottom-left') + ' ab-comfortable';

    var button = document.createElement('button');
    button.type = 'button';
    button.className = 'ab-widget-button ab-widget-unavailable ab-mode-icon-label ab-style-midnight';
    button.setAttribute('aria-label', 'נגישות זמנית לא זמינה');

    var buttonGlow = document.createElement('span');
    buttonGlow.className = 'ab-widget-button-glow';
    buttonGlow.setAttribute('aria-hidden', 'true');

    var buttonIcon = document.createElement('span');
    buttonIcon.className = 'ab-widget-button-icon';
    buttonIcon.setAttribute('aria-hidden', 'true');
    buttonIcon.innerHTML = getWidgetIconSvg('pulse');

    var buttonLabel = document.createElement('span');
    buttonLabel.className = 'ab-widget-button-label';
    buttonLabel.textContent = 'נגישות זמנית לא זמינה';

    button.appendChild(buttonGlow);
    button.appendChild(buttonIcon);
    button.appendChild(buttonLabel);

    button.addEventListener('click', function () {
      shell.remove();
      if (typeof retryFn === 'function') {
        retryFn();
      }
    });

    var note = document.createElement('div');
    note.className = 'ab-widget-unavailable-note';
    note.textContent = 'לא הצלחנו לטעון את ההגדרות כרגע. לחץ כדי לנסות שוב.';

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
      .ab-widget-shell{--ab-radius:24px;--ab-motion-fast:140ms;--ab-motion-base:220ms;position:fixed;z-index:999999;bottom:14px;right:14px;display:flex;flex-direction:column-reverse;gap:12px;align-items:flex-end;width:auto;max-width:calc(100vw - 28px);font-family:"IBM Plex Sans Hebrew","Segoe UI",Arial,sans-serif;}
      .ab-widget-shell.ab-bottom-left{left:14px;right:auto;align-items:flex-start;}
      .ab-widget-button{--ab-widget-color:#1d6dff;position:relative;display:inline-flex;align-items:center;gap:12px;min-height:56px;padding:0 18px 0 14px;border:1px solid rgba(255,255,255,.14);border-radius:999px;background:linear-gradient(135deg,var(--ab-widget-color),#10203a 92%);color:#fff;font-weight:800;box-shadow:0 16px 36px rgba(15,23,42,.16);cursor:pointer;overflow:hidden;transition:transform var(--ab-motion-base) ease,box-shadow var(--ab-motion-base) ease,filter var(--ab-motion-base) ease;}
      .ab-widget-button:hover{transform:translateY(-1px);box-shadow:0 18px 40px rgba(15,23,42,.2);}
      .ab-widget-button::after{content:"";position:absolute;inset:-8px;pointer-events:none;border-radius:inherit;background:radial-gradient(circle at center,rgba(96,165,250,.25),transparent 55%);opacity:0;transform:scale(.9);transition:opacity var(--ab-motion-base) ease,transform var(--ab-motion-base) ease;}
      .ab-widget-button:hover::after,.ab-widget-shell.ab-open .ab-widget-button::after{opacity:1;transform:scale(1);}
      .ab-widget-button-glow{position:absolute;inset:-1px;background:radial-gradient(circle at top right,rgba(255,255,255,.28),transparent 34%);pointer-events:none;}
      .ab-widget-button-icon{position:relative;width:34px;height:34px;display:inline-grid;place-items:center;border-radius:50%;background:rgba(255,255,255,.12);border:1px solid rgba(255,255,255,.16);}
      .ab-widget-button-icon svg{width:18px;height:18px;}
      .ab-widget-button-label,.ab-widget-button-chevron,.ab-widget-button-meta{position:relative;}
      .ab-widget-button-meta{font-size:11px;letter-spacing:.06em;text-transform:uppercase;opacity:.7;}
      .ab-widget-button-chevron{font-size:22px;line-height:1;opacity:.78;transition:transform var(--ab-motion-base) ease;}
      .ab-widget-shell.ab-open .ab-widget-button-chevron{transform:rotate(90deg);}
      .ab-widget-button.ab-mode-label-only .ab-widget-button-icon,.ab-widget-button.ab-mode-label-only .ab-widget-button-meta{display:none;}
      .ab-widget-button.ab-mode-icon-only{min-width:60px;justify-content:center;padding-inline:0;}
      .ab-widget-button.ab-mode-icon-only .ab-widget-button-label,.ab-widget-button.ab-mode-icon-only .ab-widget-button-chevron,.ab-widget-button.ab-mode-icon-only .ab-widget-button-meta{display:none;}
      .ab-widget-button.ab-style-soft{background:linear-gradient(180deg,rgba(255,255,255,.94),rgba(237,244,255,.95));color:#13325b;border-color:rgba(29,109,255,.16);}
      .ab-widget-button.ab-style-glass{background:linear-gradient(180deg,rgba(255,255,255,.62),rgba(255,255,255,.34));color:#10233f;border-color:rgba(255,255,255,.72);}
      .ab-widget-button.ab-style-midnight{background:linear-gradient(135deg,#081121,#152d57);color:#f8fafc;border-color:rgba(255,255,255,.08);}
      .ab-widget-button.ab-preset-high-tech{background:linear-gradient(135deg,#2563eb,#06101f 86%);}
      .ab-widget-button.ab-preset-elegant{background:linear-gradient(135deg,#1f2937,#67553d 88%);}
      .ab-widget-button.ab-preset-bold{background:linear-gradient(135deg,#7c3aed,#ec4899 88%);}
      .ab-widget-button.ab-license-inactive{background:linear-gradient(135deg,#d92d20,#7a0510);}
      .ab-widget-button.ab-widget-unavailable{background:linear-gradient(135deg,#0f172a,#1d4ed8 88%);}
      .ab-widget-panel{display:none;width:min(456px,calc(100vw - 28px));max-width:calc(100vw - 28px);max-height:min(82vh,760px);overflow:auto;border-radius:var(--ab-radius);background:rgba(251,253,255,.97);color:#0f172a;border:1px solid rgba(15,23,42,.08);box-shadow:0 28px 80px rgba(15,23,42,.14);padding:16px;position:relative;opacity:0;transform:translateY(12px) scale(.985);visibility:hidden;pointer-events:none;backdrop-filter:blur(16px);}
      .ab-widget-panel::before{content:"";position:absolute;inset:0 0 auto 0;height:92px;border-radius:inherit;background:linear-gradient(180deg,rgba(236,244,255,.88),rgba(251,253,255,0));pointer-events:none;}
      .ab-widget-panel.ab-preset-high-tech{background:linear-gradient(180deg,rgba(245,249,255,.96),rgba(231,239,252,.98));}
      .ab-widget-panel.ab-preset-elegant{background:linear-gradient(180deg,rgba(255,252,247,.96),rgba(245,239,231,.98));}
      .ab-widget-panel.ab-preset-bold{background:linear-gradient(180deg,rgba(253,245,255,.96),rgba(239,230,255,.98));}
      .ab-widget-shell.ab-open .ab-widget-panel{display:block;opacity:1;transform:translateY(0) scale(1);visibility:visible;pointer-events:auto;}
      .ab-widget-panel::-webkit-scrollbar{width:10px;}
      .ab-widget-panel::-webkit-scrollbar-thumb{background:rgba(15,23,42,.12);border-radius:999px;}
      .ab-widget-header{display:flex;align-items:flex-start;justify-content:space-between;gap:12px;}
      .ab-widget-header-actions{display:flex;align-items:center;gap:8px;flex-shrink:0;}
      .ab-widget-brand{display:flex;align-items:center;gap:12px;}
      .ab-widget-brand-badge{width:42px;height:42px;display:inline-grid;place-items:center;padding:6px;border-radius:16px;background:rgba(255,255,255,.96);box-shadow:0 10px 24px rgba(29,109,255,.14);overflow:hidden;}
      .ab-widget-brand-logo{width:100%;height:100%;object-fit:contain;display:block;}
      .ab-widget-brand-text{display:grid;gap:2px;}
      .ab-widget-eyebrow{font-size:10px;letter-spacing:.08em;text-transform:uppercase;color:#64748b;font-weight:800;}
      .ab-widget-title{margin:0;font:800 21px/1.08 "IBM Plex Sans Hebrew","Segoe UI",Arial,sans-serif;letter-spacing:-.03em;}
      .ab-widget-brand-subtitle{margin:0;color:#526173;font-size:12px;line-height:1.55;}
      .ab-widget-close{width:38px;height:38px;border:1px solid rgba(15,23,42,.08);border-radius:14px;background:rgba(255,255,255,.72);color:#0f172a;font-size:24px;line-height:1;cursor:pointer;}
      .ab-widget-reset{min-height:38px;padding:0 14px;border:1px solid rgba(15,23,42,.08);border-radius:999px;background:rgba(255,255,255,.72);color:#334155;font:inherit;font-size:12px;font-weight:800;cursor:pointer;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease;}
      .ab-widget-reset:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(15,23,42,.08);}
      .ab-widget-strip{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;}
      .ab-widget-chip{display:inline-flex;align-items:center;min-height:31px;padding:0 11px;border-radius:999px;background:#0b1220;color:#fff;font-size:11px;font-weight:700;}
      .ab-widget-chip.is-chip-muted{background:rgba(15,23,42,.045);color:#334155;}
      .ab-widget-chip.is-chip-positive{background:rgba(21,128,61,.1);color:#166534;}
      .ab-widget-chip.is-plan-free{background:rgba(21,128,61,.1);color:#166534;}
      .ab-widget-chip.is-plan-premium{background:rgba(124,58,237,.12);color:#6d28d9;}
      .ab-widget-toolbar{display:grid;gap:10px;padding:12px 14px;border-radius:18px;background:linear-gradient(180deg,rgba(255,255,255,.82),rgba(247,250,253,.96));border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);}
      .ab-widget-toolbar-head{display:grid;gap:3px;}
      .ab-widget-toolbar-title{font-size:13px;font-weight:800;}
      .ab-widget-toolbar-copy{color:#64748b;font-size:12px;line-height:1.55;}
      .ab-widget-shortcuts{display:flex;flex-wrap:wrap;gap:8px;}
      .ab-widget-shortcut{min-height:34px;padding:0 12px;border:1px solid rgba(15,23,42,.08);border-radius:999px;background:rgba(255,255,255,.82);color:#334155;font:inherit;font-size:12px;font-weight:800;cursor:pointer;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,color var(--ab-motion-fast) ease;}
      .ab-widget-shortcut:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(15,23,42,.08);background:rgba(29,109,255,.08);color:#0d3ea7;}
      .ab-widget-search{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:10px;padding:10px 12px;border-radius:16px;background:rgba(255,255,255,.74);border:1px solid rgba(15,23,42,.06);}
      .ab-widget-search-icon{width:34px;height:34px;display:grid;place-items:center;border-radius:12px;background:rgba(29,109,255,.08);color:#0d3ea7;}
      .ab-widget-search-icon svg{width:16px;height:16px;}
      .ab-widget-search-input{width:100%;min-width:0;border:none;background:transparent;color:#0f172a;font:inherit;font-size:13px;font-weight:700;outline:none;}
      .ab-widget-search-input::placeholder{color:#94a3b8;font-weight:600;}
      .ab-widget-search-meta{font-size:11px;color:#64748b;font-weight:800;white-space:nowrap;}
      .ab-widget-body{display:grid;gap:14px;margin-top:14px;}
      .ab-widget-overview{display:grid;gap:10px;}
      .ab-widget-hero-card{display:grid;grid-template-columns:minmax(0,1.55fr) minmax(220px,.95fr);gap:12px;padding:15px;border-radius:22px;background:linear-gradient(135deg,rgba(29,109,255,.12),rgba(255,255,255,.92) 42%,rgba(124,58,237,.08));border:1px solid rgba(29,109,255,.12);box-shadow:0 14px 32px rgba(29,109,255,.08);}
      .ab-widget-hero-copy{display:grid;gap:10px;min-width:0;}
      .ab-widget-hero-title{font-size:22px;line-height:1.15;font-weight:900;letter-spacing:-.03em;}
      .ab-widget-hero-description{margin:0;color:#475569;font-size:13px;line-height:1.75;}
      .ab-widget-hero-pills{display:flex;flex-wrap:wrap;gap:8px;}
      .ab-widget-hero-actions{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
      .ab-widget-hero-metric{display:grid;gap:3px;padding:11px 12px;border-radius:16px;background:rgba(255,255,255,.76);border:1px solid rgba(255,255,255,.7);box-shadow:0 10px 18px rgba(15,23,42,.04);}
      .ab-widget-hero-metric-label{font-size:11px;color:#64748b;font-weight:800;letter-spacing:.03em;text-transform:uppercase;}
      .ab-widget-hero-metric-value{font-size:13px;font-weight:900;line-height:1.5;}
      .ab-widget-hero-aside{display:grid;gap:10px;align-content:start;}
      .ab-widget-promise-rail{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;}
      .ab-widget-promise-card{display:grid;gap:5px;padding:12px 13px;border-radius:18px;background:rgba(255,255,255,.76);border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);}
      .ab-widget-promise-title{font-size:12px;font-weight:900;color:#0f172a;}
      .ab-widget-promise-copy{margin:0;color:#64748b;font-size:11px;line-height:1.65;}
      .ab-widget-overview-card{display:grid;gap:4px;padding:12px 13px;border-radius:16px;background:rgba(255,255,255,.72);border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);}
      .ab-widget-overview-label{font-size:11px;color:#64748b;font-weight:700;letter-spacing:.04em;text-transform:uppercase;}
      .ab-widget-overview-value{font-size:15px;font-weight:800;}
      .ab-widget-active-state{display:grid;gap:10px;padding:13px 14px;border-radius:18px;background:linear-gradient(180deg,rgba(255,255,255,.82),rgba(247,250,253,.96));border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);}
      .ab-widget-active-head{display:flex;align-items:center;justify-content:space-between;gap:10px;}
      .ab-widget-active-title{font-size:13px;font-weight:800;}
      .ab-widget-active-meta{font-size:11px;color:#64748b;font-weight:800;}
      .ab-widget-active-empty{margin:0;color:#64748b;font-size:12px;line-height:1.6;}
      .ab-widget-active-chips{display:flex;flex-wrap:wrap;gap:8px;}
      .ab-widget-active-chip{min-height:34px;padding:0 12px;border:1px solid rgba(29,109,255,.12);border-radius:999px;background:rgba(29,109,255,.08);color:#0d3ea7;font:inherit;font-size:12px;font-weight:800;cursor:pointer;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease;}
      .ab-widget-active-chip:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(29,109,255,.12);background:rgba(29,109,255,.12);}
      .ab-widget-starter-strip{display:grid;gap:10px;padding:13px 14px;border-radius:18px;background:linear-gradient(180deg,rgba(255,255,255,.84),rgba(241,246,255,.96));border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);}
      .ab-widget-starter-intro{display:grid;gap:3px;}
      .ab-widget-starter-title{font-size:13px;font-weight:900;}
      .ab-widget-starter-copy{margin:0;color:#64748b;font-size:12px;line-height:1.65;}
      .ab-widget-starter-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
      .ab-widget-starter-card{display:grid;grid-template-columns:auto 1fr;gap:10px;align-items:start;padding:12px;border-radius:18px;background:rgba(255,255,255,.8);border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease;}
      .ab-widget-starter-card:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(15,23,42,.08);}
      .ab-widget-starter-card.is-active{border-color:rgba(29,109,255,.18);box-shadow:0 14px 28px rgba(29,109,255,.1);}
      .ab-widget-starter-card.is-subtle{background:linear-gradient(180deg,rgba(255,255,255,.8),rgba(248,250,252,.94));}
      .ab-widget-starter-copywrap{display:grid;gap:4px;min-width:0;}
      .ab-widget-starter-card .ab-widget-card-actions{justify-content:space-between;align-items:center;grid-column:1 / -1;}
      .ab-widget-sections{display:grid;gap:14px;min-width:0;grid-template-columns:1fr;}
      .ab-widget-sections.ab-layout-split{grid-template-columns:1fr;align-items:start;}
      .ab-widget-section{display:grid;gap:10px;}
      .ab-widget-section.is-full{grid-column:1 / -1;}
      .ab-widget-section-head{display:grid;grid-template-columns:auto 1fr auto;align-items:start;gap:10px;}
      .ab-widget-section-icon{width:40px;height:40px;display:grid;place-items:center;border-radius:14px;background:rgba(29,109,255,.08);color:#0d3ea7;box-shadow:inset 0 1px 0 rgba(255,255,255,.75);}
      .ab-widget-section-icon svg{width:18px;height:18px;}
      .ab-widget-section-copywrap{display:grid;gap:4px;}
      .ab-widget-section-title{margin:0;font-size:1rem;font-weight:800;}
      .ab-widget-section-copy{margin:0;color:#64748b;font-size:13px;line-height:1.6;}
      .ab-widget-section-badge{min-height:30px;padding:0 10px;border-radius:999px;background:rgba(15,23,42,.05);color:#334155;display:inline-flex;align-items:center;font-size:11px;font-weight:800;white-space:nowrap;}
      .ab-widget-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;min-width:0;}
      .ab-widget-grid-profiles{grid-template-columns:repeat(2,minmax(0,1fr));}
      .ab-widget-card{position:relative;display:grid;gap:8px;align-content:start;min-height:auto;min-width:0;padding:14px;border-radius:20px;background:rgba(247,250,253,.96);border:1px solid rgba(15,23,42,.06);box-shadow:0 8px 18px rgba(15,23,42,.04);transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease,opacity var(--ab-motion-fast) ease,filter var(--ab-motion-fast) ease;}
      .ab-widget-card:hover{transform:translateY(-2px);box-shadow:0 14px 28px rgba(15,23,42,.08);}
      .ab-widget-card.is-search-hit,.ab-widget-premium-item.is-search-hit{border-color:rgba(29,109,255,.22);box-shadow:0 14px 30px rgba(29,109,255,.12);}
      .ab-widget-card.is-search-dim,.ab-widget-premium-item.is-search-dim{opacity:.42;filter:saturate(.6);}
      .ab-widget-profile-card.is-active{border-color:rgba(29,109,255,.2);box-shadow:0 14px 28px rgba(29,109,255,.12);}
      .ab-widget-upgrade-card{position:relative;display:grid;gap:10px;padding:16px 16px 14px;border-radius:22px;background:linear-gradient(180deg,rgba(250,247,255,.98),rgba(243,238,255,.98));border:1px solid rgba(124,58,237,.12);box-shadow:0 10px 24px rgba(124,58,237,.08);overflow:hidden;}
      .ab-widget-upgrade-card::after{content:"";position:absolute;inset:auto -18% -38% auto;width:170px;height:170px;border-radius:50%;background:radial-gradient(circle,rgba(124,58,237,.14),transparent 68%);pointer-events:none;}
      .ab-widget-card-icon{width:40px;height:40px;display:grid;place-items:center;border-radius:16px;background:linear-gradient(180deg,rgba(29,109,255,.14),rgba(29,109,255,.05));color:#0d3ea7;border:1px solid rgba(29,109,255,.12);box-shadow:inset 0 1px 0 rgba(255,255,255,.8);}
      .ab-widget-card-icon svg{width:20px;height:20px;}
      .ab-widget-card-title{font-size:13px;font-weight:800;line-height:1.45;}
      .ab-widget-card-copy{margin:0;color:#64748b;font-size:11px;line-height:1.65;}
      .ab-widget-card-actions{margin-top:auto;display:flex;flex-wrap:wrap;gap:8px;min-width:0;}
      .ab-widget-mini-badge{position:absolute;top:12px;left:12px;display:inline-flex;align-items:center;min-height:26px;padding:0 10px;border-radius:999px;background:rgba(124,58,237,.1);color:#6d28d9;font-size:11px;font-weight:800;}
      .ab-widget-premium-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;margin-top:4px;}
      .ab-widget-premium-item{display:grid;grid-template-columns:auto 1fr;gap:10px;align-items:start;padding:11px 12px;border-radius:16px;background:rgba(255,255,255,.72);border:1px solid rgba(124,58,237,.1);transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease;}
      .ab-widget-premium-item:hover{transform:translateY(-2px);box-shadow:0 12px 22px rgba(124,58,237,.09);border-color:rgba(124,58,237,.18);}
      .ab-widget-premium-copy{display:grid;gap:4px;min-width:0;}
      .ab-widget-premium-stats{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:10px;}
      .ab-widget-premium-stat{display:grid;gap:2px;padding:10px 12px;border-radius:16px;background:rgba(255,255,255,.72);border:1px solid rgba(124,58,237,.08);}
      .ab-widget-premium-stat strong{font-size:12px;font-weight:800;}
      .ab-widget-premium-stat span{font-size:11px;color:#64748b;line-height:1.55;}
      .ab-widget-premium-stat.is-highlight{background:linear-gradient(135deg,rgba(124,58,237,.12),rgba(236,72,153,.08));border-color:rgba(124,58,237,.16);}
      .ab-widget-premium-compare{display:grid;gap:8px;}
      .ab-widget-compare-row{display:grid;gap:2px;padding:10px 12px;border-radius:16px;background:rgba(255,255,255,.72);border:1px solid rgba(124,58,237,.08);}
      .ab-widget-compare-label{font-size:11px;font-weight:900;color:#4c1d95;text-transform:uppercase;letter-spacing:.05em;}
      .ab-widget-compare-value{color:#64748b;font-size:12px;line-height:1.65;}
      .ab-widget-premium-more{margin:0;grid-column:1 / -1;color:#6d28d9;font-size:12px;font-weight:800;text-align:center;}
      .ab-widget-upgrade-list{margin:0;padding:0;list-style:none;display:grid;gap:6px;color:#4c1d95;font-size:12px;font-weight:700;}
      .ab-widget-upgrade-list li{position:relative;padding-right:16px;line-height:1.6;}
      .ab-widget-upgrade-list li::before{content:"";position:absolute;right:0;top:.55em;width:7px;height:7px;border-radius:50%;background:rgba(124,58,237,.55);}
      .ab-widget-choice-group{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;width:100%;}
      .ab-widget-choice-group > .ab-widget-choice-button:last-child:nth-child(odd){grid-column:1 / -1;}
      .ab-widget-choice-button,.ab-widget-toggle-button,.ab-widget-lock-button,.ab-widget-link{min-height:40px;min-width:0;padding:0 12px;border-radius:999px;border:1px solid rgba(15,23,42,.08);background:#fff;color:#0f172a;display:inline-flex;align-items:center;justify-content:center;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:transform var(--ab-motion-fast) ease,box-shadow var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,color var(--ab-motion-fast) ease;text-align:center;white-space:normal;line-height:1.35;}
      .ab-widget-choice-button:hover,.ab-widget-toggle-button:hover,.ab-widget-lock-button:hover,.ab-widget-link:hover{transform:translateY(-1px);box-shadow:0 10px 18px rgba(15,23,42,.08);}
      .ab-widget-choice-button.is-active,.ab-widget-toggle-button.is-active{background:linear-gradient(135deg,#1d6dff,#0d3ea7);color:#fff;border-color:transparent;}
      .ab-widget-lock-button{background:rgba(124,58,237,.08);color:#6d28d9;border-color:rgba(124,58,237,.16);}
      .ab-widget-toggle-button,.ab-widget-lock-button{width:100%;}
      .ab-widget-link-premium{background:linear-gradient(135deg,#7c3aed,#ec4899 88%);color:#fff;border-color:transparent;}
      .ab-widget-footer{display:grid;gap:10px;padding-top:14px;border-top:1px solid rgba(15,23,42,.08);}
      .ab-widget-note{margin:0;color:#64748b;font-size:12px;line-height:1.6;}
      .ab-widget-inactive-note{max-width:230px;padding:10px 14px;border-radius:14px;background:rgba(217,45,32,.1);color:#7a0510;font-size:12px;font-weight:700;box-shadow:0 8px 18px rgba(122,5,16,.08);}
      .ab-widget-unavailable-note{max-width:250px;padding:10px 14px;border-radius:14px;background:rgba(29,78,216,.12);color:#1e3a8a;font-size:12px;font-weight:700;box-shadow:0 8px 18px rgba(29,78,216,.08);}
      .ab-widget-summary-card{display:grid;gap:10px;padding:14px 15px;border-radius:20px;background:linear-gradient(180deg,rgba(255,255,255,.98),rgba(244,248,253,.96));border:1px solid rgba(15,23,42,.06);}
      .ab-widget-summary-head{display:grid;gap:5px;}
      .ab-widget-summary-title{font-size:20px;line-height:1.15;font-weight:800;letter-spacing:-.03em;}
      .ab-widget-summary-copy{margin:0;color:#526173;font-size:13px;line-height:1.7;}
      .ab-widget-summary-metrics{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:10px;}
      .ab-widget-summary-note{margin:0;padding:0 2px;color:#64748b;font-size:12px;line-height:1.6;}
      .ab-widget-overview-card{display:grid;gap:4px;padding:12px 13px;border-radius:16px;background:#fff;border:1px solid rgba(15,23,42,.06);box-shadow:none;}
      .ab-widget-overview-label{font-size:10px;color:#64748b;font-weight:700;letter-spacing:.06em;text-transform:uppercase;}
      .ab-widget-overview-value{font-size:13px;font-weight:800;line-height:1.5;}
      .ab-widget-toolbar{display:grid;gap:8px;padding:12px 13px;border-radius:18px;background:rgba(246,249,253,.96);border:1px solid rgba(15,23,42,.06);box-shadow:none;}
      .ab-widget-toolbar-head{display:flex;align-items:center;justify-content:space-between;gap:10px;}
      .ab-widget-toolbar-label{font-size:12px;font-weight:800;color:#0f172a;}
      .ab-widget-shortcuts{display:flex;flex-wrap:wrap;gap:6px;}
      .ab-widget-shortcut{min-height:32px;padding:0 11px;border:1px solid rgba(15,23,42,.07);border-radius:999px;background:#fff;color:#334155;font:inherit;font-size:11px;font-weight:800;cursor:pointer;transition:transform var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,color var(--ab-motion-fast) ease;}
      .ab-widget-shortcut:hover{transform:none;box-shadow:none;background:rgba(29,109,255,.08);color:#0d3ea7;}
      .ab-widget-search{display:grid;grid-template-columns:auto 1fr auto;align-items:center;gap:10px;padding:9px 11px;border-radius:14px;background:#fff;border:1px solid rgba(15,23,42,.06);}
      .ab-widget-search-meta{font-size:10px;color:#64748b;font-weight:800;white-space:nowrap;}
      .ab-widget-active-state{display:grid;gap:10px;padding:13px 14px;border-radius:18px;background:rgba(247,250,253,.96);border:1px solid rgba(15,23,42,.06);box-shadow:none;}
      .ab-widget-starter-strip{display:grid;gap:10px;padding:13px 14px;border-radius:18px;background:rgba(247,250,253,.96);border:1px solid rgba(15,23,42,.06);box-shadow:none;}
      .ab-widget-starter-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px;}
      .ab-widget-starter-card{display:grid;grid-template-columns:auto 1fr;gap:10px;align-items:start;padding:12px;border-radius:18px;background:#fff;border:1px solid rgba(15,23,42,.06);box-shadow:none;transition:border-color var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease;}
      .ab-widget-starter-card:hover{transform:none;box-shadow:none;background:rgba(249,251,255,.98);}
      .ab-widget-starter-card.is-active{border-color:rgba(29,109,255,.18);background:rgba(245,249,255,.98);box-shadow:none;}
      .ab-widget-section{display:grid;gap:10px;padding:2px 0;}
      .ab-widget-section-head{display:grid;grid-template-columns:auto 1fr auto;align-items:start;gap:10px;padding:0 2px;}
      .ab-widget-section-icon{width:36px;height:36px;display:grid;place-items:center;border-radius:12px;background:rgba(29,109,255,.08);color:#0d3ea7;box-shadow:none;}
      .ab-widget-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:9px;min-width:0;}
      .ab-widget-card{position:relative;display:grid;gap:8px;align-content:start;min-height:auto;min-width:0;padding:14px;border-radius:18px;background:#fff;border:1px solid rgba(15,23,42,.06);box-shadow:none;transition:border-color var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease,opacity var(--ab-motion-fast) ease,filter var(--ab-motion-fast) ease;}
      .ab-widget-card:hover{transform:none;box-shadow:none;background:rgba(250,252,255,.98);}
      .ab-widget-card.is-search-hit,.ab-widget-premium-item.is-search-hit{border-color:rgba(29,109,255,.2);box-shadow:none;}
      .ab-widget-profile-card.is-active{border-color:rgba(29,109,255,.2);background:rgba(245,249,255,.98);box-shadow:none;}
      .ab-widget-card-icon{width:36px;height:36px;display:grid;place-items:center;border-radius:14px;background:rgba(29,109,255,.08);color:#0d3ea7;border:1px solid rgba(29,109,255,.08);box-shadow:none;}
      .ab-widget-card-title{font-size:13px;font-weight:800;line-height:1.45;}
      .ab-widget-card-copy{margin:0;color:#64748b;font-size:11px;line-height:1.65;}
      .ab-widget-choice-button,.ab-widget-toggle-button,.ab-widget-lock-button,.ab-widget-link{min-height:38px;min-width:0;padding:0 12px;border-radius:999px;border:1px solid rgba(15,23,42,.08);background:#fff;color:#0f172a;display:inline-flex;align-items:center;justify-content:center;font:inherit;font-size:12px;font-weight:800;text-decoration:none;cursor:pointer;transition:background var(--ab-motion-fast) ease,color var(--ab-motion-fast) ease,border-color var(--ab-motion-fast) ease;box-shadow:none;text-align:center;white-space:normal;line-height:1.35;}
      .ab-widget-choice-button:hover,.ab-widget-toggle-button:hover,.ab-widget-lock-button:hover,.ab-widget-link:hover{transform:none;box-shadow:none;background:rgba(249,251,255,.98);}
      .ab-widget-choice-button.is-active,.ab-widget-toggle-button.is-active{background:#0f172a;color:#fff;border-color:#0f172a;}
      .ab-widget-upgrade-card{position:relative;display:grid;gap:10px;padding:16px 16px 14px;border-radius:20px;background:linear-gradient(180deg,rgba(247,248,252,.98),rgba(243,246,250,.98));border:1px solid rgba(15,23,42,.08);box-shadow:none;overflow:hidden;}
      .ab-widget-upgrade-card::after{display:none;}
      .ab-widget-mini-badge{position:absolute;top:12px;left:12px;display:inline-flex;align-items:center;min-height:24px;padding:0 9px;border-radius:999px;background:rgba(15,23,42,.06);color:#334155;font-size:10px;font-weight:800;}
      .ab-widget-premium-list{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:8px;margin-top:2px;}
      .ab-widget-premium-item{display:grid;grid-template-columns:auto 1fr;gap:10px;align-items:start;padding:11px 12px;border-radius:16px;background:#fff;border:1px solid rgba(15,23,42,.06);transition:border-color var(--ab-motion-fast) ease,background var(--ab-motion-fast) ease;box-shadow:none;}
      .ab-widget-premium-item:hover{transform:none;box-shadow:none;border-color:rgba(15,23,42,.1);background:rgba(250,252,255,.98);}
      .ab-widget-premium-more{margin:0;grid-column:1 / -1;color:#0f172a;font-size:12px;font-weight:700;text-align:center;}
      .ab-widget-premium-note{margin:0;color:#64748b;font-size:12px;line-height:1.65;}
      .ab-widget-link-premium{background:#0f172a;color:#fff;border-color:#0f172a;}
      .ab-widget-footer{display:grid;gap:10px;padding-top:14px;border-top:1px solid rgba(15,23,42,.08);}
      .ab-widget-note{margin:0;color:#64748b;font-size:12px;line-height:1.6;}
      .ab-widget-shell.ab-is-updating .ab-widget-panel{box-shadow:0 30px 78px rgba(29,109,255,.16);border-color:rgba(29,109,255,.14);}
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
      @media (max-width:920px){
        .ab-widget-shell{left:auto !important;right:12px !important;align-items:flex-end;bottom:12px;gap:10px;}
        .ab-widget-shell.ab-bottom-left{left:12px !important;right:auto !important;align-items:flex-start;}
        .ab-widget-panel{width:min(520px,calc(100vw - 24px));max-height:min(78vh,760px);padding:16px;}
        .ab-widget-search{grid-template-columns:1fr;}
        .ab-widget-search-icon,.ab-widget-search-meta{display:none;}
        .ab-widget-summary-metrics,
        .ab-widget-hero-card,
        .ab-widget-overview,
        .ab-widget-sections,
        .ab-widget-sections.ab-layout-split,
        .ab-widget-grid,
        .ab-widget-grid-profiles,
        .ab-widget-starter-grid,
        .ab-widget-premium-list,
        .ab-widget-premium-stats,
        .ab-widget-choice-group,
        .ab-widget-promise-rail,
        .ab-widget-hero-actions{grid-template-columns:1fr;}
        .ab-widget-section-head{grid-template-columns:auto 1fr;}
        .ab-widget-section-badge{grid-column:1 / -1;justify-self:start;}
      }
      @media (max-width:560px){
        .ab-widget-shell{left:auto !important;right:10px !important;align-items:flex-end;bottom:10px;gap:10px;}
        .ab-widget-shell.ab-bottom-left{left:10px !important;right:auto !important;align-items:flex-start;}
        .ab-widget-button{width:auto;max-width:calc(100vw - 28px);justify-content:flex-start;}
        .ab-widget-panel{width:min(430px,calc(100vw - 28px));}
        .ab-widget-toolbar{padding:12px;}
        .ab-widget-shortcuts{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));}
        .ab-widget-shortcut{justify-content:center;}
        .ab-widget-summary-metrics,
        .ab-widget-hero-card,
        .ab-widget-overview,
        .ab-widget-sections.ab-layout-split,
        .ab-widget-grid,
        .ab-widget-grid-profiles,
        .ab-widget-starter-grid,
        .ab-widget-premium-list,
        .ab-widget-premium-stats,
        .ab-widget-promise-rail,
        .ab-widget-hero-actions{grid-template-columns:1fr;}
        .ab-widget-header{align-items:flex-start;}
        .ab-widget-header-actions{width:100%;justify-content:flex-end;}
        .ab-widget-section-head{grid-template-columns:auto 1fr;}
        .ab-widget-section-badge{grid-column:1 / -1;justify-self:start;}
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
    try {
      window.localStorage.setItem(storageKey, JSON.stringify(prefs));
    } catch (error) {
      return;
    }
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
