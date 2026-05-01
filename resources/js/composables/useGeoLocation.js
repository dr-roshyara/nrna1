import { ref, readonly } from 'vue'

export function useGeoLocation() {
    const status = ref('idle')
    const detectedLocale = ref(null)
    const detectedLocation = ref(null)
    const detectedTimezone = ref(null)

    const detect = async () => {
        // Never override an explicit user choice
        if (localStorage.getItem('preferred_locale')) {
            status.value = 'skipped'
            return null
        }

        status.value = 'detecting'

        const timezone = Intl.DateTimeFormat().resolvedOptions().timeZone

        try {
            const res = await fetch('/api/detect-location', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ timezone })
            })

            const data = await res.json()
            detectedLocale.value = data.locale
            detectedLocation.value = data.location
            detectedTimezone.value = data.timezone
            status.value = 'detected'

            return data.locale
        } catch (error) {
            console.error('❌ Geo-location detection failed:', error)
            status.value = 'error'
            return null
        }
    }

    const getUserDistrict = () => {
        const location = detectedLocation.value
        return {
            country: location?.country_code,
            region: location?.region,
            city: location?.city,
        }
    }

    return {
        status: readonly(status),
        detectedLocale: readonly(detectedLocale),
        detectedLocation: readonly(detectedLocation),
        detectedTimezone: readonly(detectedTimezone),
        detect,
        getUserDistrict,
    }
}
