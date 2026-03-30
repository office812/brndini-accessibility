import { execFile } from 'node:child_process'
import crypto from 'node:crypto'
import fs from 'node:fs'
import path from 'node:path'
import { promisify } from 'node:util'
import { fileURLToPath } from 'node:url'

import { load } from 'cheerio'
import express from 'express'

const __filename = fileURLToPath(import.meta.url)
const __dirname = path.dirname(__filename)
const projectRoot = path.resolve(__dirname, '..')
const distPath = path.join(projectRoot, 'dist')
const widgetScriptPath = path.join(projectRoot, 'server', 'public', 'widget.js')
const dataDir = path.join(projectRoot, 'server', 'data')
const storePath = path.join(dataDir, 'store.json')
const port = Number(process.env.PORT || 8787)
const publicBaseUrl = process.env.PUBLIC_BASE_URL || `http://localhost:${port}`
const app = express()
const execFileAsync = promisify(execFile)

const genericLinkLabels = new Set([
  'click here',
  'here',
  'read more',
  'more',
  'learn more',
  'details',
  'עוד',
  'לפרטים',
  'לחץ כאן',
])

ensureStore()

app.use(express.json({ limit: '1mb' }))

app.get('/api/health', (_request, response) => {
  const store = readStore()
  response.json({
    ok: true,
    product: 'A11Y Bridge Platform',
    users: store.users.length,
  })
})

app.get('/api/auth/session', (request, response) => {
  const user = getAuthenticatedUser(request)

  response.json({
    success: true,
    data: user ? buildSessionPayload(user) : null,
  })
})

app.post('/api/auth/register', (request, response) => {
  const email = String(request.body?.email || '').trim().toLowerCase()
  const password = String(request.body?.password || '')
  const companyName = String(request.body?.companyName || '').trim()
  const siteName = String(request.body?.siteName || '').trim()
  const domain = normalizeSiteUrl(String(request.body?.domain || '').trim())

  if (!email || !password || !companyName || !siteName || !domain) {
    response.status(400).json({
      success: false,
      error: 'חסרים פרטי הרשמה בסיסיים.',
    })
    return
  }

  if (password.length < 8) {
    response.status(400).json({
      success: false,
      error: 'הסיסמה חייבת להכיל לפחות 8 תווים.',
    })
    return
  }

  const store = readStore()

  if (store.users.some((user) => user.email === email)) {
    response.status(409).json({
      success: false,
      error: 'כבר קיים משתמש עם כתובת המייל הזו.',
    })
    return
  }

  const passwordSalt = crypto.randomBytes(16).toString('hex')
  const passwordHash = hashPassword(password, passwordSalt)
  const userId = crypto.randomUUID()
  const site = createDefaultSite(siteName, domain)

  const user = {
    id: userId,
    email,
    companyName,
    contactEmail: email,
    passwordHash,
    passwordSalt,
    createdAt: new Date().toISOString(),
    sites: [site],
  }

  store.users.push(user)
  const sessionId = createSession(store, user.id)
  writeStore(store)
  setSessionCookie(response, sessionId)

  response.status(201).json({
    success: true,
    data: buildSessionPayload(user),
  })
})

app.post('/api/auth/login', (request, response) => {
  const email = String(request.body?.email || '').trim().toLowerCase()
  const password = String(request.body?.password || '')
  const store = readStore()
  const user = store.users.find((candidate) => candidate.email === email)

  if (!user || user.passwordHash !== hashPassword(password, user.passwordSalt)) {
    response.status(401).json({
      success: false,
      error: 'אימייל או סיסמה לא נכונים.',
    })
    return
  }

  const sessionId = createSession(store, user.id)
  writeStore(store)
  setSessionCookie(response, sessionId)

  response.json({
    success: true,
    data: buildSessionPayload(user),
  })
})

app.post('/api/auth/logout', (request, response) => {
  const store = readStore()
  const sessionId = getSessionIdFromRequest(request)

  if (sessionId) {
    store.sessions = store.sessions.filter((session) => session.id !== sessionId)
    writeStore(store)
  }

  clearSessionCookie(response)
  response.json({ success: true })
})

app.put('/api/site', (request, response) => {
  const user = getAuthenticatedUser(request)

  if (!user) {
    response.status(401).json({
      success: false,
      error: 'צריך להתחבר כדי לעדכן הגדרות.',
    })
    return
  }

  const companyName = String(request.body?.companyName || '').trim()
  const contactEmail = String(request.body?.contactEmail || '').trim()
  const siteName = String(request.body?.siteName || '').trim()
  const domain = normalizeSiteUrl(String(request.body?.domain || '').trim())
  const statementUrl = normalizeOptionalUrl(String(request.body?.statementUrl || '').trim())
  const serviceMode = sanitizeServiceMode(String(request.body?.serviceMode || 'audit_and_fix'))
  const widget = sanitizeWidgetSettings(request.body?.widget)

  if (!companyName || !contactEmail || !siteName || !domain) {
    response.status(400).json({
      success: false,
      error: 'חסרים פרטי אתר או חברה.',
    })
    return
  }

  const store = readStore()
  const storedUser = store.users.find((candidate) => candidate.id === user.id)

  if (!storedUser) {
    response.status(404).json({
      success: false,
      error: 'המשתמש לא נמצא.',
    })
    return
  }

  storedUser.companyName = companyName
  storedUser.contactEmail = contactEmail
  storedUser.sites[0] = {
    ...storedUser.sites[0],
    siteName,
    domain,
    statementUrl,
    serviceMode,
    widget,
    updatedAt: new Date().toISOString(),
  }

  writeStore(store)

  response.json({
    success: true,
    data: buildSessionPayload(storedUser),
  })
})

app.get('/api/public/widget-config/:publicKey', (request, response) => {
  const publicKey = String(request.params.publicKey || '')
  const store = readStore()
  const site = findSiteByPublicKey(store, publicKey)

  response.setHeader('Access-Control-Allow-Origin', '*')
  response.setHeader('Cache-Control', 'no-store')

  if (!site) {
    response.status(404).json({
      success: false,
      error: 'Unknown site key.',
    })
    return
  }

  response.json({
    success: true,
    data: {
      siteKey: site.publicKey,
      siteName: site.siteName,
      domain: site.domain,
      statementUrl: site.statementUrl,
      widget: site.widget,
      updatedAt: site.updatedAt,
    },
  })
})

app.get('/widget.js', (_request, response) => {
  response.type('application/javascript')
  response.setHeader('Cache-Control', 'no-store')
  response.sendFile(widgetScriptPath)
})

app.post('/api/scan', async (request, response) => {
  const submittedUrl = typeof request.body?.url === 'string' ? request.body.url : ''

  if (!submittedUrl.trim()) {
    response.status(400).json({ error: 'חסר URL לסריקה.' })
    return
  }

  try {
    const report = await scanUrl(submittedUrl)
    response.json(report)
  } catch (error) {
    const details = error instanceof Error ? error.message : 'Unknown scan error.'
    response.status(422).json({
      error: 'לא הצלחנו לסרוק את ה-URL שסיפקת.',
      details,
    })
  }
})

if (fs.existsSync(distPath)) {
  app.use(express.static(distPath))
  app.get('/{*path}', (_request, response) => {
    response.sendFile(path.join(distPath, 'index.html'))
  })
}

app.listen(port, () => {
  console.log(`A11Y Bridge platform listening on ${publicBaseUrl}`)
})

function ensureStore() {
  if (!fs.existsSync(dataDir)) {
    fs.mkdirSync(dataDir, { recursive: true })
  }

  if (!fs.existsSync(storePath)) {
    fs.writeFileSync(
      storePath,
      JSON.stringify({ users: [], sessions: [] }, null, 2),
      'utf8',
    )
  }
}

function readStore() {
  const raw = fs.readFileSync(storePath, 'utf8')
  const parsed = JSON.parse(raw)

  return {
    users: Array.isArray(parsed.users) ? parsed.users : [],
    sessions: Array.isArray(parsed.sessions) ? parsed.sessions : [],
  }
}

function writeStore(store) {
  fs.writeFileSync(storePath, JSON.stringify(store, null, 2), 'utf8')
}

function createDefaultSite(siteName, domain) {
  return {
    id: crypto.randomUUID(),
    siteName,
    domain,
    statementUrl: '',
    publicKey: crypto.randomBytes(9).toString('base64url'),
    serviceMode: 'audit_and_fix',
    updatedAt: new Date().toISOString(),
    widget: {
      position: 'bottom-right',
      color: '#0f6a73',
      size: 'comfortable',
      label: 'נגישות',
      language: 'he',
      showContrast: true,
      showFontScale: true,
      showUnderlineLinks: true,
      showReduceMotion: true,
    },
  }
}

function buildSessionPayload(user) {
  return {
    user: {
      id: user.id,
      email: user.email,
      companyName: user.companyName,
      contactEmail: user.contactEmail,
      createdAt: user.createdAt,
      site: user.sites[0],
    },
    embedScriptUrl: `${publicBaseUrl}/widget.js`,
  }
}

function hashPassword(password, salt) {
  return crypto.scryptSync(password, salt, 64).toString('hex')
}

function createSession(store, userId) {
  const sessionId = crypto.randomUUID()
  const now = new Date().toISOString()

  store.sessions = store.sessions
    .filter((session) => session.userId !== userId)
    .concat({
      id: sessionId,
      userId,
      createdAt: now,
    })

  return sessionId
}

function parseCookies(cookieHeader = '') {
  return cookieHeader
    .split(';')
    .map((part) => part.trim())
    .filter(Boolean)
    .reduce((accumulator, part) => {
      const separatorIndex = part.indexOf('=')
      const key = separatorIndex >= 0 ? part.slice(0, separatorIndex) : part
      const value = separatorIndex >= 0 ? part.slice(separatorIndex + 1) : ''
      accumulator[key] = decodeURIComponent(value)
      return accumulator
    }, {})
}

function getSessionIdFromRequest(request) {
  const cookies = parseCookies(request.headers.cookie || '')
  return typeof cookies.ab_session === 'string' ? cookies.ab_session : ''
}

function getAuthenticatedUser(request) {
  const sessionId = getSessionIdFromRequest(request)

  if (!sessionId) {
    return null
  }

  const store = readStore()
  const session = store.sessions.find((candidate) => candidate.id === sessionId)

  if (!session) {
    return null
  }

  return store.users.find((candidate) => candidate.id === session.userId) || null
}

function setSessionCookie(response, sessionId) {
  const secureSuffix = process.env.NODE_ENV === 'production' ? '; Secure' : ''
  response.setHeader(
    'Set-Cookie',
    `ab_session=${encodeURIComponent(sessionId)}; HttpOnly; Path=/; SameSite=Lax; Max-Age=2592000${secureSuffix}`,
  )
}

function clearSessionCookie(response) {
  response.setHeader(
    'Set-Cookie',
    'ab_session=; HttpOnly; Path=/; SameSite=Lax; Max-Age=0',
  )
}

function normalizeSiteUrl(input) {
  const raw = input.trim()

  if (!raw) {
    return ''
  }

  const candidate = /^https?:\/\//i.test(raw) ? raw : `https://${raw}`

  try {
    const normalized = new URL(candidate)
    normalized.hash = ''
    normalized.search = ''
    return normalized.toString().replace(/\/$/, '')
  } catch {
    return ''
  }
}

function normalizeOptionalUrl(input) {
  if (!input) {
    return ''
  }

  return normalizeSiteUrl(input)
}

function sanitizeServiceMode(value) {
  if (['audit_only', 'audit_and_fix', 'managed_service'].includes(value)) {
    return value
  }

  return 'audit_and_fix'
}

function sanitizeWidgetSettings(input) {
  const raw = typeof input === 'object' && input !== null ? input : {}

  return {
    position:
      raw.position === 'bottom-left' || raw.position === 'bottom-right'
        ? raw.position
        : 'bottom-right',
    color:
      typeof raw.color === 'string' && /^#[0-9a-f]{6}$/i.test(raw.color)
        ? raw.color
        : '#0f6a73',
    size:
      raw.size === 'compact' || raw.size === 'comfortable' || raw.size === 'large'
        ? raw.size
        : 'comfortable',
    label:
      typeof raw.label === 'string' && raw.label.trim()
        ? raw.label.trim().slice(0, 40)
        : 'נגישות',
    language: raw.language === 'en' ? 'en' : 'he',
    showContrast: Boolean(raw.showContrast),
    showFontScale: Boolean(raw.showFontScale),
    showUnderlineLinks: Boolean(raw.showUnderlineLinks),
    showReduceMotion: Boolean(raw.showReduceMotion),
  }
}

function findSiteByPublicKey(store, publicKey) {
  for (const user of store.users) {
    const site = user.sites.find((candidate) => candidate.publicKey === publicKey)
    if (site) {
      return site
    }
  }

  return null
}

function normalizeUrl(input) {
  const raw = input.trim()
  const candidate = /^https?:\/\//i.test(raw) ? raw : `https://${raw}`
  return new URL(candidate)
}

function hasAccessibleName($node) {
  const visibleText = $node.text().replace(/\s+/g, ' ').trim()
  return Boolean(
    visibleText ||
      $node.attr('aria-label') ||
      $node.attr('aria-labelledby') ||
      $node.attr('title') ||
      $node.attr('value'),
  )
}

function countUnlabeledInputs($, selector) {
  let count = 0

  $(selector).each((_, element) => {
    const $field = $(element)
    const type = ($field.attr('type') || '').toLowerCase()

    if (type === 'hidden') {
      return
    }

    const id = $field.attr('id')
    const hasProgrammaticLabel = Boolean(
      $field.attr('aria-label') ||
        $field.attr('aria-labelledby') ||
        $field.attr('title'),
    )
    const hasWrappedLabel = $field.closest('label').length > 0
    const hasForLabel = id ? $(`label[for="${id}"]`).length > 0 : false

    if (!hasProgrammaticLabel && !hasWrappedLabel && !hasForLabel) {
      count += 1
    }
  })

  return count
}

function createIssue(id, title, severity, count, description, impact, action, wcag) {
  return { id, title, severity, count, description, impact, action, wcag }
}

function scoreFromIssues(issues) {
  const weights = {
    critical: 12,
    high: 8,
    medium: 5,
    low: 2,
  }

  const penalty = issues.reduce((sum, issue) => {
    const cappedCount = Math.min(issue.count, 6)
    return sum + cappedCount * weights[issue.severity]
  }, 0)

  return Math.max(14, 100 - penalty)
}

function buildReport({ normalizedUrl, title, issues, signals }) {
  const issueCount = issues.reduce((sum, issue) => sum + issue.count, 0)
  const score = scoreFromIssues(issues)
  const highlights = []
  const autofixes = []
  const nextSteps = []

  if (issues.length === 0) {
    highlights.push('לא נמצאו בעיות בסיסיות ב-HTML הראשי של העמוד הזה.')
    nextSteps.push('להמשיך לבדיקות מקלדת, קורא מסך, פוקוס ותהליכים מרובי שלבים.')
  } else {
    const criticalIssue = issues.find((issue) => issue.severity === 'critical')
    const highIssue = issues.find((issue) => issue.severity === 'high')

    highlights.push(`נמצאו ${issueCount} מופעים שדורשים triage ראשוני לפני בדיקה ידנית.`)

    if (criticalIssue) {
      highlights.push(`החסם הבולט ביותר כרגע: ${criticalIssue.title}.`)
      nextSteps.push(`לטפל קודם בבעיה: ${criticalIssue.title}.`)
    }

    if (highIssue) {
      highlights.push(`נדרש גם תיקון מהיר לבעיה ברמת חומרה גבוהה: ${highIssue.title}.`)
    }
  }

  issues.forEach((issue) => {
    if (issue.id === 'missing-lang') {
      autofixes.push('להוסיף lang ברמת ה-document root.')
    }

    if (issue.id === 'missing-main') {
      autofixes.push('להוסיף main landmark יחיד ולעדכן Skip link גלובלי.')
    }

    if (issue.id === 'iframe-without-title') {
      autofixes.push('לדרוש title לכל iframe דרך קומפוננטה או בדיקת CMS.')
    }

    if (issue.id === 'generic-links') {
      autofixes.push('להוסיף ולידציה טקסטואלית ל-CMS כדי לחסום “לחץ כאן” ו-“עוד”.')
    }

    if (issue.id === 'missing-alt') {
      nextSteps.push('להוסיף שדה alt מחייב ב-CMS או בזרימת העלאת מדיה.')
    }

    if (issue.id === 'missing-field-labels') {
      nextSteps.push('להקשיח את ספריית הקומפוננטות כך שלא ניתן יהיה ליצור field ללא label.')
    }
  })

  if (autofixes.length === 0) {
    autofixes.push('אין auto-fix בטוח מובהק. נדרש טיפול ברכיבי UI או בתוכן.')
  }

  if (nextSteps.length === 0) {
    nextSteps.push('להריץ audit ידני על ניווט מקלדת, פוקוס ותהליכים עסקיים מרכזיים.')
  }

  return {
    url: normalizedUrl.toString(),
    title,
    score,
    summary:
      'הדוח נוצר מסריקת HTML של עמוד יחיד. הוא טוב ל-triage ולמכירה ראשונית, אבל אינו מחליף audit אנושי או אישור עמידה רשמי.',
    highlights: [...new Set(highlights)],
    autofixes: [...new Set(autofixes)],
    nextSteps: [...new Set(nextSteps)],
    disclaimer:
      'הסריקה הזו אינה אישור עמידה מלא ב-WCAG או בדרישות חוק. היא שכבת מיפוי סיכונים התחלתית בלבד.',
    signals,
    issues,
  }
}

async function scanUrl(rawUrl) {
  const normalizedUrl = normalizeUrl(rawUrl)
  const html = await downloadHtml(normalizedUrl)
  const $ = load(html)
  const issues = []

  const imagesWithoutAlt = $('img').filter((_, element) => !$(element).attr('alt')).length
  if (imagesWithoutAlt > 0) {
    issues.push(
      createIssue(
        'missing-alt',
        'תמונות ללא alt',
        'critical',
        imagesWithoutAlt,
        'נמצאו תמונות ללא טקסט חלופי.',
        'קוראי מסך מפספסים תוכן או משמעות של התמונה.',
        'להוסיף alt משמעותי או alt ריק כאשר התמונה דקורטיבית.',
        ['1.1.1'],
      ),
    )
  }

  const namelessButtons = $('button').filter((_, element) => !hasAccessibleName($(element))).length
  if (namelessButtons > 0) {
    issues.push(
      createIssue(
        'nameless-buttons',
        'כפתורים ללא שם נגיש',
        'critical',
        namelessButtons,
        'כפתורים נמצאו ללא טקסט גלוי או שם נגיש חלופי.',
        'משתמשים לא מבינים איזו פעולה הכפתור מבצע.',
        'להוסיף טקסט גלוי, aria-label או aria-labelledby מדויק.',
        ['4.1.2', '2.4.6'],
      ),
    )
  }

  const missingFieldLabels = countUnlabeledInputs($, 'input, select, textarea')
  if (missingFieldLabels > 0) {
    issues.push(
      createIssue(
        'missing-field-labels',
        'רכיבי טופס ללא label',
        'critical',
        missingFieldLabels,
        'שדות טופס נמצאו ללא label משויך או שם נגיש חלופי.',
        'המשתמש עלול לא להבין איזה מידע נדרש ממנו.',
        'להצמיד label, aria-label או aria-labelledby לכל שדה.',
        ['1.3.1', '3.3.2', '4.1.2'],
      ),
    )
  }

  const documentLang = $('html').attr('lang')
  if (!documentLang) {
    issues.push(
      createIssue(
        'missing-lang',
        'חסר lang במסמך',
        'high',
        1,
        'שורש המסמך לא מגדיר שפה.',
        'קוראי מסך עשויים לבחור הגייה לא נכונה.',
        'להוסיף lang מתאים למסמך.',
        ['3.1.1'],
      ),
    )
  }

  const h1Count = $('h1').length
  if (h1Count === 0) {
    issues.push(
      createIssue(
        'missing-h1',
        'אין H1 ראשי',
        'high',
        1,
        'לא נמצאה כותרת H1 בעמוד.',
        'קשה להבין את נושא העמוד והיררכיית התוכן.',
        'להוסיף H1 ייחודי ומתאר.',
        ['1.3.1', '2.4.6'],
      ),
    )
  } else if (h1Count > 1) {
    issues.push(
      createIssue(
        'multiple-h1',
        'יש יותר מ-H1 אחד',
        'medium',
        h1Count,
        'נמצאו מספר כותרות H1.',
        'היררכיית הכותרות עלולה להיות לא עקבית.',
        'לצמצם ל-H1 אחד ולהמשיך ב-H2/H3 לפי מבנה.',
        ['1.3.1', '2.4.6'],
      ),
    )
  }

  if ($('main').length === 0) {
    issues.push(
      createIssue(
        'missing-main',
        'חסר main landmark',
        'medium',
        1,
        'לא נמצא main יחיד שמסמן את התוכן העיקרי.',
        'ניווט מהיר בין לנדמרקים נפגע.',
        'להוסיף main ולהצמיד אליו Skip link.',
        ['1.3.1', '2.4.1'],
      ),
    )
  }

  const genericLinks = $('a')
    .filter((_, element) => genericLinkLabels.has($(element).text().replace(/\s+/g, ' ').trim().toLowerCase()))
    .length
  if (genericLinks > 0) {
    issues.push(
      createIssue(
        'generic-links',
        'קישורים עם טקסט גנרי',
        'medium',
        genericLinks,
        'נמצאו קישורים עם טקסט לא תיאורי.',
        'רשימת קישורים הופכת לפחות מובנת.',
        'להחליף לטקסט שמתאר את יעד הקישור.',
        ['2.4.4'],
      ),
    )
  }

  const iframeWithoutTitle = $('iframe').filter((_, element) => !$(element).attr('title')).length
  if (iframeWithoutTitle > 0) {
    issues.push(
      createIssue(
        'iframe-without-title',
        'iframe ללא title',
        'medium',
        iframeWithoutTitle,
        'נמצאו iframe ללא title.',
        'קוראי מסך לא יודעים מה תפקיד המסגרת.',
        'להוסיף title תיאורי לכל iframe.',
        ['4.1.2', '2.4.1'],
      ),
    )
  }

  const positiveTabIndex = $('[tabindex]')
    .filter((_, element) => {
      const value = Number.parseInt($(element).attr('tabindex') || '0', 10)
      return Number.isFinite(value) && value > 0
    })
    .length
  if (positiveTabIndex > 0) {
    issues.push(
      createIssue(
        'positive-tabindex',
        'tabindex חיובי',
        'low',
        positiveTabIndex,
        'נמצאו אלמנטים עם tabindex חיובי.',
        'סדר המיקוד עלול להיות מבלבל ולא עקבי.',
        'להעדיף סדר DOM תקין ו-tabindex 0 רק בעת הצורך.',
        ['2.4.3'],
      ),
    )
  }

  const title =
    $('title').first().text().trim() || `Accessibility scan for ${normalizedUrl.host}`

  const signals = [
    { label: 'Images', value: String($('img').length) },
    { label: 'Form fields', value: String($('input, select, textarea').length) },
    { label: 'Links', value: String($('a').length) },
    { label: 'Landmarks', value: String($('header, nav, main, footer, aside').length) },
  ]

  return buildReport({ normalizedUrl, title, issues, signals })
}

async function downloadHtml(normalizedUrl) {
  try {
    const response = await fetch(normalizedUrl, {
      redirect: 'follow',
      headers: {
        'user-agent':
          'A11Y Bridge MVP Scanner/0.2 (+https://localhost; product triage demo)',
        accept: 'text/html,application/xhtml+xml',
      },
    })

    if (!response.ok) {
      throw new Error(`האתר החזיר שגיאה ${response.status}.`)
    }

    const contentType = response.headers.get('content-type') || ''

    if (!contentType.includes('text/html')) {
      throw new Error('ה-URL לא החזיר HTML שאפשר לסרוק.')
    }

    return await response.text()
  } catch {
    const { stdout } = await execFileAsync('curl', [
      '-Ls',
      '--max-time',
      '20',
      '-A',
      'A11Y Bridge MVP Scanner/0.2',
      normalizedUrl.toString(),
    ])

    if (!stdout.trim()) {
      throw new Error('לא הצלחנו להוריד HTML מהאתר.')
    }

    return stdout
  }
}
