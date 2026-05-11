// Abstraction layer — prevents direct fetch() calls scattered across components.
// All geography API calls for org setup go through here.

const BASE = '/api/organisation-geography'

export const geographyApi = {
    async getCountries() {
        const res = await fetch(`${BASE}/countries`, { credentials: 'same-origin' })
        if (!res.ok) throw new Error('Failed to load countries')
        return (await res.json()).data ?? []
    },

    async getContinents() {
        const res = await fetch(`${BASE}/continents`, { credentials: 'same-origin' })
        if (!res.ok) throw new Error('Failed to load continents')
        return (await res.json()).data ?? []
    },

    async getRegions(countryCode) {
        const res = await fetch(`${BASE}/countries/${countryCode}/regions`, { credentials: 'same-origin' })
        if (!res.ok) throw new Error('Failed to load regions')
        return (await res.json()).data ?? []
    },
}
