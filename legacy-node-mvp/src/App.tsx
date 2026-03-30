import { startTransition, useEffect, useMemo, useState } from 'react'
import './App.css'

type WidgetPosition = 'bottom-right' | 'bottom-left'
type WidgetSize = 'compact' | 'comfortable' | 'large'
type WidgetLanguage = 'he' | 'en'
type ServiceMode = 'audit_only' | 'audit_and_fix' | 'managed_service'

type WidgetSettings = {
  position: WidgetPosition
  color: string
  size: WidgetSize
  label: string
  language: WidgetLanguage
  showContrast: boolean
  showFontScale: boolean
  showUnderlineLinks: boolean
  showReduceMotion: boolean
}

type SiteConfig = {
  id: string
  siteName: string
  domain: string
  statementUrl: string
  publicKey: string
  serviceMode: ServiceMode
  updatedAt: string
  widget: WidgetSettings
}

type SessionUser = {
  id: string
  email: string
  companyName: string
  contactEmail: string
  createdAt: string
  site: SiteConfig
}

type SessionPayload = {
  user: SessionUser
  embedScriptUrl: string
}

type ApiEnvelope<T> = {
  success: boolean
  data?: T | null
  error?: string
}

const positionOptions: Array<{ value: WidgetPosition; label: string }> = [
  { value: 'bottom-right', label: 'ימין למטה' },
  { value: 'bottom-left', label: 'שמאל למטה' },
]

const sizeOptions: Array<{ value: WidgetSize; label: string }> = [
  { value: 'compact', label: 'קומפקטי' },
  { value: 'comfortable', label: 'רגיל' },
  { value: 'large', label: 'גדול' },
]

const languageOptions: Array<{ value: WidgetLanguage; label: string }> = [
  { value: 'he', label: 'עברית' },
  { value: 'en', label: 'English' },
]

const serviceModeOptions: Array<{ value: ServiceMode; label: string }> = [
  { value: 'audit_only', label: 'Audit only' },
  { value: 'audit_and_fix', label: 'Audit + safe fixes' },
  { value: 'managed_service', label: 'Managed accessibility service' },
]

const capabilityCards = [
  {
    title: 'חשבון משתמש בפלטפורמה שלך',
    body:
      'כל לקוח פותח משתמש, מקבל site key ציבורי, ומנהל את כל הגדרות ה-widget מתוך dashboard מרכזי.',
  },
  {
    title: 'קוד הטמעה יציב',
    body:
      'הקוד באתר נשאר באותה צורה. ההגדרות עצמן נשלפות בזמן טעינה מהפלטפורמה שלך ומתעדכנות אוטומטית.',
  },
  {
    title: 'שכבת העדפות, לא הבטחת קסם',
    body:
      'ה-widget נותן העדפות תצוגה, קישור להצהרת נגישות ומסגרת governance. הוא לא אמור להחליף תיקוני קוד ובדיקות ידניות.',
  },
]

function defaultWidgetSettings(): WidgetSettings {
  return {
    position: 'bottom-right',
    color: '#0f6a73',
    size: 'comfortable',
    label: 'נגישות',
    language: 'he',
    showContrast: true,
    showFontScale: true,
    showUnderlineLinks: true,
    showReduceMotion: true,
  }
}

function App() {
  const [session, setSession] = useState<SessionPayload | null>(null)
  const [isLoadingSession, setIsLoadingSession] = useState(true)
  const [authMode, setAuthMode] = useState<'signup' | 'login'>('signup')
  const [statusMessage, setStatusMessage] = useState('')
  const [errorMessage, setErrorMessage] = useState('')
  const [isSubmitting, setIsSubmitting] = useState(false)
  const [isSaving, setIsSaving] = useState(false)

  const [signupForm, setSignupForm] = useState({
    companyName: '',
    email: '',
    password: '',
    siteName: '',
    domain: '',
  })

  const [loginForm, setLoginForm] = useState({
    email: '',
    password: '',
  })

  const [siteForm, setSiteForm] = useState({
    companyName: '',
    contactEmail: '',
    siteName: '',
    domain: '',
    statementUrl: '',
    serviceMode: 'audit_and_fix' as ServiceMode,
    widget: defaultWidgetSettings(),
  })

  useEffect(() => {
    void loadSession()
  }, [])

  useEffect(() => {
    if (!session) {
      return
    }

    setSiteForm({
      companyName: session.user.companyName,
      contactEmail: session.user.contactEmail,
      siteName: session.user.site.siteName,
      domain: session.user.site.domain,
      statementUrl: session.user.site.statementUrl,
      serviceMode: session.user.site.serviceMode,
      widget: session.user.site.widget,
    })
  }, [session])

  const embedCode = useMemo(() => {
    if (!session) {
      return ''
    }

    return `<script async src="${session.embedScriptUrl}" data-a11y-bridge="${session.user.site.publicKey}"></script>`
  }, [session])

  async function loadSession() {
    setIsLoadingSession(true)

    try {
      const response = await fetch('/api/auth/session')
      const payload = (await response.json()) as ApiEnvelope<SessionPayload>

      if (payload.success && payload.data) {
        setSession(payload.data)
      } else {
        setSession(null)
      }
    } catch {
      setSession(null)
    } finally {
      setIsLoadingSession(false)
    }
  }

  async function handleSignup(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault()
    setIsSubmitting(true)
    setErrorMessage('')
    setStatusMessage('')

    try {
      const response = await fetch('/api/auth/register', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(signupForm),
      })

      const payload = (await response.json()) as ApiEnvelope<SessionPayload>

      if (!response.ok || !payload.success || !payload.data) {
        throw new Error(payload.error ?? 'ההרשמה נכשלה.')
      }

      const nextSession = payload.data

      startTransition(() => {
        setSession(nextSession)
        setStatusMessage('החשבון נוצר והאתר שלך מוכן להגדרות widget.')
      })
    } catch (error) {
      setErrorMessage(
        error instanceof Error ? error.message : 'לא הצלחנו ליצור חשבון כרגע.',
      )
    } finally {
      setIsSubmitting(false)
    }
  }

  async function handleLogin(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault()
    setIsSubmitting(true)
    setErrorMessage('')
    setStatusMessage('')

    try {
      const response = await fetch('/api/auth/login', {
        method: 'POST',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(loginForm),
      })

      const payload = (await response.json()) as ApiEnvelope<SessionPayload>

      if (!response.ok || !payload.success || !payload.data) {
        throw new Error(payload.error ?? 'ההתחברות נכשלה.')
      }

      const nextSession = payload.data

      startTransition(() => {
        setSession(nextSession)
        setStatusMessage('התחברת בהצלחה לפלטפורמה.')
      })
    } catch (error) {
      setErrorMessage(
        error instanceof Error ? error.message : 'לא הצלחנו להתחבר כרגע.',
      )
    } finally {
      setIsSubmitting(false)
    }
  }

  async function handleSaveSettings(event: React.FormEvent<HTMLFormElement>) {
    event.preventDefault()
    setIsSaving(true)
    setErrorMessage('')
    setStatusMessage('')

    try {
      const response = await fetch('/api/site', {
        method: 'PUT',
        headers: {
          'Content-Type': 'application/json',
        },
        body: JSON.stringify(siteForm),
      })

      const payload = (await response.json()) as ApiEnvelope<SessionPayload>

      if (!response.ok || !payload.success || !payload.data) {
        throw new Error(payload.error ?? 'שמירת ההגדרות נכשלה.')
      }

      const nextSession = payload.data

      startTransition(() => {
        setSession(nextSession)
        setStatusMessage(
          'הגדרות ה-widget נשמרו. כל אתר שמטמיע את הקוד יקבל את השינויים אוטומטית.',
        )
      })
    } catch (error) {
      setErrorMessage(
        error instanceof Error ? error.message : 'שמירת ההגדרות נכשלה.',
      )
    } finally {
      setIsSaving(false)
    }
  }

  async function handleLogout() {
    await fetch('/api/auth/logout', { method: 'POST' })
    setSession(null)
    setStatusMessage('התנתקת מהפלטפורמה.')
    setErrorMessage('')
  }

  async function copyEmbedCode() {
    if (!embedCode) {
      return
    }

    await navigator.clipboard.writeText(embedCode)
    setStatusMessage('קוד ההטמעה הועתק.')
    setErrorMessage('')
  }

  function updateWidget<K extends keyof WidgetSettings>(key: K, value: WidgetSettings[K]) {
    setSiteForm((current) => ({
      ...current,
      widget: {
        ...current.widget,
        [key]: value,
      },
    }))
  }

  return (
    <>
      <a className="skip-link" href="#main-content">
        דלג לתוכן הראשי
      </a>

      <div className="page-shell">
        <header className="site-header">
          <a className="brand" href="#hero">
            <span className="brand-mark" aria-hidden="true">
              AB
            </span>
            <span>
              <strong>A11Y Bridge</strong>
              <small>Hosted accessibility widget and governance platform</small>
            </span>
          </a>

          <nav aria-label="ניווט ראשי" className="site-nav">
            <a href="#hero">פלטפורמה</a>
            <a href="#builder">Configurator</a>
            <a href="#embed">Embed code</a>
          </nav>
        </header>

        <main id="main-content">
          {isLoadingSession ? (
            <section className="section-block loading-block">
              <p className="eyebrow">Loading</p>
              <h1>טוען את סביבת הניהול שלך</h1>
              <p>בודק אם יש session פתוח כדי להחזיר אותך ישר לדשבורד.</p>
            </section>
          ) : !session ? (
            <>
              <section className="hero-section" id="hero">
                <div className="hero-copy">
                  <p className="eyebrow">Hosted SaaS flow</p>
                  <h1>פלטפורמת נגישות שבה כל לקוח מנהל בעצמו את ה-widget שלו.</h1>
                  <p className="hero-text">
                    הלקוח פותח משתמש, מגדיר צבע, מיקום, גודל, שפה וקישור להצהרת
                    נגישות, ומקבל קוד הטמעה קבוע. כל שינוי שנשמר בפלטפורמה שלך
                    מתעדכן אוטומטית באתר המוטמע.
                  </p>

                  <div className="hero-actions">
                    <button
                      className="primary-link"
                      type="button"
                      onClick={() => setAuthMode('signup')}
                    >
                      לפתוח חשבון
                    </button>
                    <button
                      className="secondary-link"
                      type="button"
                      onClick={() => setAuthMode('login')}
                    >
                      יש לי כבר משתמש
                    </button>
                  </div>
                </div>

                <section className="auth-card" aria-labelledby="auth-title">
                  <div className="auth-tabs" role="tablist" aria-label="Authentication mode">
                    <button
                      className={authMode === 'signup' ? 'is-active' : ''}
                      type="button"
                      role="tab"
                      aria-selected={authMode === 'signup'}
                      onClick={() => setAuthMode('signup')}
                    >
                      הרשמה
                    </button>
                    <button
                      className={authMode === 'login' ? 'is-active' : ''}
                      type="button"
                      role="tab"
                      aria-selected={authMode === 'login'}
                      onClick={() => setAuthMode('login')}
                    >
                      התחברות
                    </button>
                  </div>

                  <h2 id="auth-title">
                    {authMode === 'signup'
                      ? 'צור לקוח חדש בפלטפורמה'
                      : 'היכנס לדשבורד הקיים'}
                  </h2>

                  {authMode === 'signup' ? (
                    <form className="auth-form" onSubmit={handleSignup}>
                      <label htmlFor="signup-company">Company name</label>
                      <input
                        id="signup-company"
                        type="text"
                        value={signupForm.companyName}
                        onChange={(event) =>
                          setSignupForm((current) => ({
                            ...current,
                            companyName: event.target.value,
                          }))
                        }
                        required
                      />

                      <label htmlFor="signup-email">Email</label>
                      <input
                        id="signup-email"
                        type="email"
                        value={signupForm.email}
                        onChange={(event) =>
                          setSignupForm((current) => ({
                            ...current,
                            email: event.target.value,
                          }))
                        }
                        required
                      />

                      <label htmlFor="signup-password">Password</label>
                      <input
                        id="signup-password"
                        type="password"
                        minLength={8}
                        value={signupForm.password}
                        onChange={(event) =>
                          setSignupForm((current) => ({
                            ...current,
                            password: event.target.value,
                          }))
                        }
                        required
                      />

                      <label htmlFor="signup-site">Site name</label>
                      <input
                        id="signup-site"
                        type="text"
                        value={signupForm.siteName}
                        onChange={(event) =>
                          setSignupForm((current) => ({
                            ...current,
                            siteName: event.target.value,
                          }))
                        }
                        required
                      />

                      <label htmlFor="signup-domain">Domain</label>
                      <input
                        id="signup-domain"
                        type="url"
                        placeholder="https://your-site.com"
                        value={signupForm.domain}
                        onChange={(event) =>
                          setSignupForm((current) => ({
                            ...current,
                            domain: event.target.value,
                          }))
                        }
                        required
                      />

                      <button className="scan-button" type="submit" disabled={isSubmitting}>
                        {isSubmitting ? 'יוצר חשבון...' : 'Create account'}
                      </button>
                    </form>
                  ) : (
                    <form className="auth-form" onSubmit={handleLogin}>
                      <label htmlFor="login-email">Email</label>
                      <input
                        id="login-email"
                        type="email"
                        value={loginForm.email}
                        onChange={(event) =>
                          setLoginForm((current) => ({
                            ...current,
                            email: event.target.value,
                          }))
                        }
                        required
                      />

                      <label htmlFor="login-password">Password</label>
                      <input
                        id="login-password"
                        type="password"
                        value={loginForm.password}
                        onChange={(event) =>
                          setLoginForm((current) => ({
                            ...current,
                            password: event.target.value,
                          }))
                        }
                        required
                      />

                      <button className="scan-button" type="submit" disabled={isSubmitting}>
                        {isSubmitting ? 'מתחבר...' : 'Login'}
                      </button>
                    </form>
                  )}

                  {(statusMessage || errorMessage) && (
                    <div
                      className={`status-box ${errorMessage ? 'status-error' : ''}`}
                      aria-live="polite"
                    >
                      {errorMessage || statusMessage}
                    </div>
                  )}
                </section>
              </section>

              <section className="section-block" id="builder">
                <div className="section-heading">
                  <p className="eyebrow">Platform model</p>
                  <h2>איך המערכת הזאת עובדת בפועל</h2>
                  <p>
                    זהו flow של SaaS אמיתי: משתמש, site key, דשבורד ניהול, script
                    ציבורי, ועדכון הגדרות בזמן אמת בלי לגעת שוב בקוד באתר.
                  </p>
                </div>

                <div className="card-grid">
                  {capabilityCards.map((card) => (
                    <article key={card.title} className="product-card">
                      <h3>{card.title}</h3>
                      <p>{card.body}</p>
                    </article>
                  ))}
                </div>
              </section>
            </>
          ) : (
            <>
              <section className="hero-section dashboard-hero" id="hero">
                <div className="hero-copy">
                  <p className="eyebrow">Account active</p>
                  <h1>{session.user.companyName}</h1>
                  <p className="hero-text">
                    האתר <strong>{session.user.site.siteName}</strong> מחובר לפלטפורמה.
                    כל עדכון בהגדרות ה-widget נשמר אצלך ומוגש מחדש דרך אותו embed
                    script.
                  </p>

                  <div className="hero-actions">
                    <a className="primary-link" href="#builder">
                      לערוך הגדרות
                    </a>
                    <button className="secondary-link" type="button" onClick={handleLogout}>
                      Logout
                    </button>
                  </div>
                </div>

                <aside className="hero-panel" aria-label="Account summary">
                  <div className="promise-card">
                    <p className="eyebrow">Current site</p>
                    <h2>{session.user.site.siteName}</h2>
                    <p>{session.user.site.domain}</p>
                  </div>

                  <dl className="stat-grid">
                    <div className="stat-card">
                      <dt>Site key</dt>
                      <dd>{session.user.site.publicKey.slice(0, 8)}</dd>
                    </div>
                    <div className="stat-card">
                      <dt>Position</dt>
                      <dd>{siteForm.widget.position === 'bottom-right' ? 'ימין' : 'שמאל'}</dd>
                    </div>
                    <div className="stat-card">
                      <dt>Updated</dt>
                      <dd>{session.user.site.updatedAt.slice(0, 10)}</dd>
                    </div>
                  </dl>
                </aside>
              </section>

              <section className="section-block" id="builder">
                <div className="section-heading">
                  <p className="eyebrow">Configurator</p>
                  <h2>הגדרות widget ופרטי האתר</h2>
                  <p>
                    כאן הלקוח שולט בכל מה שיופיע באתר שלו: צבע, מיקום, גודל, שפה,
                    פיצ'רי העדפות וקישור להצהרת נגישות. הקוד באתר לא משתנה, רק
                    הקונפיגורציה.
                  </p>
                </div>

                <div className="builder-grid">
                  <form className="builder-form" onSubmit={handleSaveSettings}>
                    <label htmlFor="company-name">Company name</label>
                    <input
                      id="company-name"
                      type="text"
                      value={siteForm.companyName}
                      onChange={(event) =>
                        setSiteForm((current) => ({
                          ...current,
                          companyName: event.target.value,
                        }))
                      }
                      required
                    />

                    <label htmlFor="contact-email">Contact email</label>
                    <input
                      id="contact-email"
                      type="email"
                      value={siteForm.contactEmail}
                      onChange={(event) =>
                        setSiteForm((current) => ({
                          ...current,
                          contactEmail: event.target.value,
                        }))
                      }
                      required
                    />

                    <label htmlFor="site-name">Site name</label>
                    <input
                      id="site-name"
                      type="text"
                      value={siteForm.siteName}
                      onChange={(event) =>
                        setSiteForm((current) => ({
                          ...current,
                          siteName: event.target.value,
                        }))
                      }
                      required
                    />

                    <label htmlFor="site-domain">Domain</label>
                    <input
                      id="site-domain"
                      type="url"
                      value={siteForm.domain}
                      onChange={(event) =>
                        setSiteForm((current) => ({
                          ...current,
                          domain: event.target.value,
                        }))
                      }
                      required
                    />

                    <label htmlFor="statement-url">Accessibility statement URL</label>
                    <input
                      id="statement-url"
                      type="url"
                      placeholder="https://your-site.com/accessibility-statement"
                      value={siteForm.statementUrl}
                      onChange={(event) =>
                        setSiteForm((current) => ({
                          ...current,
                          statementUrl: event.target.value,
                        }))
                      }
                    />

                    <label htmlFor="service-mode">Service mode</label>
                    <select
                      id="service-mode"
                      value={siteForm.serviceMode}
                      onChange={(event) =>
                        setSiteForm((current) => ({
                          ...current,
                          serviceMode: event.target.value as ServiceMode,
                        }))
                      }
                    >
                      {serviceModeOptions.map((option) => (
                        <option key={option.value} value={option.value}>
                          {option.label}
                        </option>
                      ))}
                    </select>

                    <label htmlFor="widget-label">Button label</label>
                    <input
                      id="widget-label"
                      type="text"
                      value={siteForm.widget.label}
                      onChange={(event) => updateWidget('label', event.target.value)}
                      required
                    />

                    <div className="inline-grid">
                      <div>
                        <label htmlFor="widget-position">Position</label>
                        <select
                          id="widget-position"
                          value={siteForm.widget.position}
                          onChange={(event) =>
                            updateWidget('position', event.target.value as WidgetPosition)
                          }
                        >
                          {positionOptions.map((option) => (
                            <option key={option.value} value={option.value}>
                              {option.label}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label htmlFor="widget-size">Size</label>
                        <select
                          id="widget-size"
                          value={siteForm.widget.size}
                          onChange={(event) =>
                            updateWidget('size', event.target.value as WidgetSize)
                          }
                        >
                          {sizeOptions.map((option) => (
                            <option key={option.value} value={option.value}>
                              {option.label}
                            </option>
                          ))}
                        </select>
                      </div>
                    </div>

                    <div className="inline-grid">
                      <div>
                        <label htmlFor="widget-language">Language</label>
                        <select
                          id="widget-language"
                          value={siteForm.widget.language}
                          onChange={(event) =>
                            updateWidget('language', event.target.value as WidgetLanguage)
                          }
                        >
                          {languageOptions.map((option) => (
                            <option key={option.value} value={option.value}>
                              {option.label}
                            </option>
                          ))}
                        </select>
                      </div>

                      <div>
                        <label htmlFor="widget-color">Accent color</label>
                        <input
                          id="widget-color"
                          type="color"
                          value={siteForm.widget.color}
                          onChange={(event) => updateWidget('color', event.target.value)}
                        />
                      </div>
                    </div>

                    <fieldset className="toggle-grid">
                      <legend>Widget features</legend>
                      <label>
                        <input
                          type="checkbox"
                          checked={siteForm.widget.showContrast}
                          onChange={(event) =>
                            updateWidget('showContrast', event.target.checked)
                          }
                        />
                        High contrast
                      </label>
                      <label>
                        <input
                          type="checkbox"
                          checked={siteForm.widget.showFontScale}
                          onChange={(event) =>
                            updateWidget('showFontScale', event.target.checked)
                          }
                        />
                        Font scale controls
                      </label>
                      <label>
                        <input
                          type="checkbox"
                          checked={siteForm.widget.showUnderlineLinks}
                          onChange={(event) =>
                            updateWidget('showUnderlineLinks', event.target.checked)
                          }
                        />
                        Underline links
                      </label>
                      <label>
                        <input
                          type="checkbox"
                          checked={siteForm.widget.showReduceMotion}
                          onChange={(event) =>
                            updateWidget('showReduceMotion', event.target.checked)
                          }
                        />
                        Reduce motion
                      </label>
                    </fieldset>

                    <button className="scan-button" type="submit" disabled={isSaving}>
                      {isSaving ? 'שומר...' : 'Save widget settings'}
                    </button>
                  </form>

                  <aside className="preview-card" aria-labelledby="preview-title">
                    <p className="eyebrow">Live preview</p>
                    <h3 id="preview-title">כך ה-widget ייראה באתר</h3>
                    <WidgetPreview siteName={siteForm.siteName} settings={siteForm.widget} />
                    <p className="preview-note">
                      זהו preview לממשק ההעדפות. השינויים נשמרים בפלטפורמה שלך
                      ומוטענים בכל אתר דרך ה-script הציבורי.
                    </p>
                  </aside>
                </div>

                {(statusMessage || errorMessage) && (
                  <div
                    className={`status-box ${errorMessage ? 'status-error' : ''}`}
                    aria-live="polite"
                  >
                    {errorMessage || statusMessage}
                  </div>
                )}
              </section>

              <section className="section-block" id="embed">
                <div className="section-heading">
                  <p className="eyebrow">Embed</p>
                  <h2>קוד ההטמעה הקבוע</h2>
                  <p>
                    את הקוד הזה מטמיעים פעם אחת. מכאן והלאה כל שינוי מתבצע רק דרך
                    ה-dashboard שלך, וה-widget מושך את ההגדרות העדכניות לפי ה-site
                    key הציבורי.
                  </p>
                </div>

                <div className="embed-grid">
                  <section className="embed-card">
                    <h3>Embed snippet</h3>
                    <pre>
                      <code>{embedCode}</code>
                    </pre>
                    <button className="secondary-link" type="button" onClick={copyEmbedCode}>
                      Copy code
                    </button>
                  </section>

                  <section className="embed-card">
                    <h3>מה קורה בפועל</h3>
                    <ol>
                      <li>האתר טוען את `widget.js` מהפלטפורמה שלך.</li>
                      <li>ה-script קורא את ה-site key מתוך תגית הסקריפט.</li>
                      <li>הוא מושך config עדכני מהשרת שלך.</li>
                      <li>ה-widget נבנה מחדש לפי ההגדרות האחרונות בלי להחליף קוד.</li>
                    </ol>
                  </section>
                </div>
              </section>

              <section className="section-block">
                <div className="section-heading">
                  <p className="eyebrow">Compliance stance</p>
                  <h2>מה המערכת הזאת כן עושה, ומה לא</h2>
                  <p>
                    הפלטפורמה יכולה לנהל שכבת העדפות נגישות, קישור להצהרה, audit
                    ו-governance. היא לא אמורה להיות משווקת כתחליף מלא לתיקוני קוד,
                    תוכן, בדיקות ידניות או אישור משפטי.
                  </p>
                </div>
              </section>
            </>
          )}
        </main>
      </div>
    </>
  )
}

function WidgetPreview({
  siteName,
  settings,
}: {
  siteName: string
  settings: WidgetSettings
}) {
  const sizeClass = `size-${settings.size}`

  return (
    <div className="widget-preview-shell">
      <div className={`widget-preview ${settings.position} ${sizeClass}`}>
        <button
          type="button"
          className="widget-trigger"
          style={{ backgroundColor: settings.color }}
        >
          {settings.label}
        </button>

        <section className="widget-panel">
          <header>
            <p className="eyebrow">Accessibility settings</p>
            <h4>{siteName || 'Your site'}</h4>
          </header>

          <ul className="preview-controls">
            {settings.showFontScale && <li>גודל טקסט</li>}
            {settings.showContrast && <li>ניגודיות גבוהה</li>}
            {settings.showUnderlineLinks && <li>הדגשת קישורים</li>}
            {settings.showReduceMotion && <li>הפחתת אנימציה</li>}
            <li>קישור להצהרת נגישות</li>
          </ul>
        </section>
      </div>
    </div>
  )
}

export default App
