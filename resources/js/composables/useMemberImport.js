import { useI18n } from 'vue-i18n'

/**
 * Composable for bulk member import (100–50,000 rows).
 *
 * Architecture:
 *  1. uploadFile(file)   — POST multipart file → server stores it, dispatches queue job
 *                          Returns { job_id, status_url } immediately (HTTP 202)
 *  2. pollStatus(jobId)  — GET status endpoint every 2s until completed/failed
 *  3. parsePreview(file) — Client-side parse for the preview table only (no server round-trip)
 */
export const useMemberImport = (organisation) => {
  const { t } = useI18n()

  // ── Preview (client-side only) ───────────────────────────────────────────

  /**
   * Parse the file locally for the preview table.
   * We do NOT send parsed JSON to the server — that's wasteful for large files.
   * The server will re-parse the raw file from the queue job.
   */
  const parsePreview = async (file) => {
    return new Promise((resolve, reject) => {
      const reader = new FileReader()

      reader.onload = (event) => {
        try {
          const content = event.target.result
          const parsed = parseCSV(content)
          resolve(parsed)
        } catch (err) {
          reject(err)
        }
      }

      reader.onerror = () => reject(new Error('Failed to read file'))
      reader.readAsText(file)
    })
  }

  const detectDelimiter = (firstLine) => {
    const commas     = (firstLine.match(/,/g)  || []).length
    const semicolons = (firstLine.match(/;/g)  || []).length
    return semicolons > commas ? ';' : ','
  }

  const normaliseHeader = (h) =>
    h.toLowerCase().trim().replace(/[-_\s]/g, '')

  const parseCSV = (content) => {
    if (!content?.trim()) throw new Error(t('modals.member_import.validation.empty_file'))

    const lines = content.trim().split(/\r?\n/)
    if (lines.length < 2) throw new Error(t('modals.member_import.validation.empty_file'))

    const delimiter = detectDelimiter(lines[0])
    const headers   = parseCSVLine(lines[0], delimiter)

    if (!headers.length) throw new Error(t('modals.member_import.validation.invalid_headers'))

    const normHeaders = headers.map(normaliseHeader)
    if (!normHeaders.includes('email')) {
      throw new Error(t('modals.member_import.validation.missing_email'))
    }

    const rows = []
    for (let i = 1; i < lines.length; i++) {
      const line = lines[i].trim()
      if (!line) continue

      const values = parseCSVLine(line, delimiter)
      const row    = {}

      headers.forEach((header, idx) => {
        const value = values[idx] !== undefined ? values[idx].trim() : ''
        row[header]                    = value
        row[normaliseHeader(header)]   = value
      })

      rows.push(row)
    }

    return { headers, rows }
  }

  const parseCSVLine = (line, delimiter = ',') => {
    const result = []
    let current = ''
    let insideQuotes = false

    for (let i = 0; i < line.length; i++) {
      const char = line[i]
      if (char === '"') {
        if (insideQuotes && line[i + 1] === '"') { current += '"'; i++ }
        else insideQuotes = !insideQuotes
      } else if (char === delimiter && !insideQuotes) {
        result.push(current.trim())
        current = ''
      } else {
        current += char
      }
    }
    result.push(current.trim())
    return result
  }

  // ── Upload ───────────────────────────────────────────────────────────────

  /**
   * Upload the raw file as multipart/form-data.
   * Returns { job_id, status_url } on HTTP 202.
   */
  const uploadFile = async (file) => {
    const formData = new FormData()
    formData.append('file', file)

    const csrf = document.querySelector('meta[name="csrf-token"]')?.content
    if (!csrf) throw new Error('CSRF token not found')

    const response = await fetch(
      `/organisations/${organisation.slug}/members/import`,
      {
        method:  'POST',
        headers: {
          'X-CSRF-TOKEN':    csrf,
          'X-Requested-With': 'XMLHttpRequest',
          'Accept':          'application/json',
        },
        body: formData,
      }
    )

    if (!response.ok) {
      const data = await response.json().catch(() => ({}))
      throw new Error(data.message || `Upload failed (HTTP ${response.status})`)
    }

    return response.json() // { job_id, status_url }
  }

  // ── Polling ──────────────────────────────────────────────────────────────

  /**
   * Poll the status endpoint every 2 seconds.
   *
   * @param {string}   jobId
   * @param {Function} onProgress  called on each poll: (status) => void
   * @param {Function} onComplete  called when status === 'completed': (status) => void
   * @param {Function} onError     called on failure or timeout: (Error) => void
   * @returns {Function}           cancel() — call to stop polling early
   */
  const pollStatus = (jobId, onProgress, onComplete, onError) => {
    let cancelled = false
    const slug = organisation.slug

    const tick = async () => {
      if (cancelled) return

      try {
        const response = await fetch(
          `/organisations/${slug}/members/import/${jobId}/status`,
          {
            headers: {
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json',
            },
          }
        )

        if (!response.ok) {
          onError?.(new Error(`Status check failed (HTTP ${response.status})`))
          return
        }

        const data = await response.json()
        onProgress?.(data)

        if (data.status === 'completed') {
          onComplete?.(data)
          return
        }

        if (data.status === 'failed') {
          onError?.(new Error(data.error_log?.[0]?.message || 'Import failed'))
          return
        }

        // Schedule next poll
        if (!cancelled) setTimeout(tick, 2000)

      } catch (err) {
        onError?.(err)
      }
    }

    // Start polling immediately
    tick()

    return () => { cancelled = true }
  }

  return {
    parsePreview,
    uploadFile,
    pollStatus,
  }
}
