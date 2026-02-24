import { ref } from 'vue'
import { useCsrfRequest } from './useCsrfRequest'
import { useI18n } from 'vue-i18n'

/**
 * Composable for handling member import functionality
 * Manages file parsing, validation, and CSRF-protected submission
 */
export const useMemberImport = (organization) => {
  const csrfRequest = useCsrfRequest()
  const { t } = useI18n()

  /**
   * Parse CSV or Excel file into structured data
   */
  const parseFile = async (file) => {
    return new Promise((resolve, reject) => {
      const reader = new FileReader()

      reader.onload = (event) => {
        try {
          const content = event.target.result

          // Check if CSV or Excel
          if (file.name.match(/\.csv$/i)) {
            const parsed = parseCSV(content)
            resolve(parsed)
          } else if (file.name.match(/\.(xlsx|xls)$/i)) {
            // For Excel files, we'll need to use a library or handle it server-side
            // For now, try to parse as text if it's text-based
            try {
              const parsed = parseCSV(content)
              resolve(parsed)
            } catch {
              // If parsing as CSV fails, we'll send to server for parsing
              resolve({
                headers: [],
                rows: [],
                raw: content,
                isExcel: true
              })
            }
          } else {
            reject(new Error(t('modals.member_import.validation.invalid_format')))
          }
        } catch (error) {
          reject(error)
        }
      }

      reader.onerror = () => {
        reject(new Error('Failed to read file'))
      }

      // Read as text
      reader.readAsText(file)
    })
  }

  /**
   * Parse CSV content into headers and rows
   */
  const parseCSV = (content) => {
    if (!content || !content.trim()) {
      throw new Error(t('modals.member_import.validation.empty_file'))
    }

    // Split by newlines
    const lines = content.trim().split(/\r?\n/)

    if (lines.length < 2) {
      throw new Error(t('modals.member_import.validation.empty_file'))
    }

    // Parse headers from first line
    const headerLine = lines[0]
    const headers = parseCSVLine(headerLine)

    if (!headers || headers.length === 0) {
      throw new Error(t('modals.member_import.validation.invalid_headers'))
    }

    // Parse data rows
    const rows = []
    for (let i = 1; i < lines.length; i++) {
      const line = lines[i].trim()
      if (!line) continue // Skip empty lines

      const values = parseCSVLine(line)
      const row = {}

      // Map values to headers
      headers.forEach((header, index) => {
        row[header] = values[index] || ''
      })

      rows.push(row)
    }

    return { headers, rows }
  }

  /**
   * Parse single CSV line handling quoted fields
   */
  const parseCSVLine = (line) => {
    const result = []
    let current = ''
    let insideQuotes = false

    for (let i = 0; i < line.length; i++) {
      const char = line[i]
      const nextChar = line[i + 1]

      if (char === '"') {
        if (insideQuotes && nextChar === '"') {
          // Escaped quote
          current += '"'
          i++ // Skip next quote
        } else {
          // Toggle quote state
          insideQuotes = !insideQuotes
        }
      } else if (char === ',' && !insideQuotes) {
        // Field separator
        result.push(current.trim())
        current = ''
      } else {
        current += char
      }
    }

    // Add last field
    result.push(current.trim())

    return result
  }

  /**
   * Validate parsed member data
   */
  const validateData = async (data) => {
    const errors = []
    const emails = new Set()

    // Check if empty
    if (!data.rows || data.rows.length === 0) {
      return {
        valid: false,
        errors: [t('modals.member_import.validation.empty_file')]
      }
    }

    // Check headers
    const headers = data.headers.map(h => h.toLowerCase().trim())
    const hasEmail = headers.includes('email')

    if (!hasEmail) {
      errors.push(t('modals.member_import.validation.missing_email'))
      return { valid: false, errors }
    }

    // Validate each row
    data.rows.forEach((row, index) => {
      const rowNumber = index + 2 // +2 because row 1 is headers, 0-indexed
      const email = row.email || row.Email || ''

      // Check required fields
      if (!email) {
        errors.push(
          t('modals.member_import.validation.missing_required', {
            field: 'email',
            row: rowNumber
          })
        )
        return
      }

      // Validate email format
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/
      if (!emailRegex.test(email)) {
        errors.push(
          t('modals.member_import.validation.invalid_email', {
            row: rowNumber,
            email: email
          })
        )
        return
      }

      // Check for duplicates
      if (emails.has(email.toLowerCase())) {
        errors.push(
          t('modals.member_import.validation.duplicate_email', {
            row: rowNumber,
            email: email
          })
        )
        return
      }

      emails.add(email.toLowerCase())
    })

    return {
      valid: errors.length === 0,
      errors
    }
  }

  /**
   * Submit import to server with CSRF protection
   */
  const submitImport = async (importData) => {
    try {
      const response = await csrfRequest.post(
        `/organizations/${organization.slug}/members/import`,
        {
          headers: importData.headers,
          rows: importData.rows,
          fileName: importData.fileName
        }
      )

      if (!response.ok) {
        throw new Error(
          response.data?.message ||
          t('modals.member_import.error', { error: 'Unknown error' })
        )
      }

      return response.data
    } catch (error) {
      console.error('Member import submission error:', error)
      throw error
    }
  }

  return {
    parseFile,
    validateData,
    submitImport
  }
}
