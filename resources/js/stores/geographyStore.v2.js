import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const CACHE_TTL = 5 * 60 * 1000 // 5 minutes

export const useGeographyStore = defineStore('geography', () => {

  // =========================
  // STATE CONTRACTS
  // =========================

  // flatDataByCountry shape:
  // {
  //   [countryCode]: {
  //     nodes: [],                  // raw nodes from API
  //     nodeMap: {},                // { id: node }
  //     levelMap: {},               // { level: [nodes] }
  //     childrenMap: {},            // { parentId: [children] }
  //     lookupKey: {},              // { "NP.3.15": node } — for fast path lookup (NOT canonical geo_reference)
  //     loaded: boolean,
  //     loading: boolean,
  //     error: null | string,
  //     version: null | string,     // API version
  //     schemaVersion: null | string, // schema version from API
  //     fetchedAt: number,          // timestamp for cache invalidation
  //   }
  // }
  const flatDataByCountry = ref({})

  // regions: [{ code, name }, ...]
  const regions = ref([])
  const regionsLoaded = ref(false)
  const regionsLoading = ref(false)
  const regionsError = ref(null)
  const regionsFetchedAt = ref(null)

  // countriesByRegion: { [regionCode]: [countries] }
  const countriesByRegion = ref({})
  // { [regionCode]: boolean }
  const countriesLoading = ref({})
  // { [regionCode]: string | null }
  const countriesError = ref({})
  // { [regionCode]: timestamp }
  const countriesFetchedAt = ref({})

  // =========================
  // NORMALIZATION (PURE)
  // =========================

  const buildMaps = (rawNodes, countryCode) => {
    const nodeMap = {}
    const levelMap = {}
    const childrenMap = {}
    const lookupKey = {} // neutral name: used for fast lookup, NOT canonical geo_reference

    // First pass: build nodeMap, levelMap, childrenMap
    for (const node of rawNodes) {
      nodeMap[node.id] = node

      if (!levelMap[node.admin_level]) {
        levelMap[node.admin_level] = []
      }
      levelMap[node.admin_level].push(node)

      if (node.parent_id) {
        if (!childrenMap[node.parent_id]) {
          childrenMap[node.parent_id] = []
        }
        childrenMap[node.parent_id].push(node)
      }
    }

    // Second pass: build lookupKey from materialized path
    // Used ONLY for fast O(1) lookup, NOT canonical geo_reference construction
    for (const node of rawNodes) {
      if (node.path) {
        const pathIds = node.path
          .split('/')
          .filter(Boolean)
          .map(Number)

        const key = [countryCode, ...pathIds].join('.')
        lookupKey[key] = node
      }
    }

    return { nodeMap, levelMap, childrenMap, lookupKey }
  }

  // =========================
  // ACTIONS — FLAT DATA
  // =========================

  const fetchFlat = async (countryCode) => {
    if (!countryCode) return

    const state = flatDataByCountry.value[countryCode]

    // Check if already loaded or loading
    if (state?.loaded || state?.loading) return

    // Check cache TTL
    if (state?.loaded && Date.now() - state.fetchedAt < CACHE_TTL) return

    // Atomically mark as loading BEFORE async call (prevents race condition)
    flatDataByCountry.value = {
      ...flatDataByCountry.value,
      [countryCode]: {
        ...(state || {}),
        loading: true,
        error: null,
      },
    }

    try {
      const res = await fetch(`/api/geography/countries/${countryCode}/flat`)
      if (!res.ok) throw new Error(`API ${res.status}`)

      const data = await res.json()
      const maps = buildMaps(data.data.nodes, countryCode)

      flatDataByCountry.value = {
        ...flatDataByCountry.value,
        [countryCode]: {
          nodes: data.data.nodes,
          ...maps,
          loaded: true,
          loading: false,
          error: null,
          version: data.version,
          schemaVersion: data.schema,
          fetchedAt: Date.now(),
        },
      }

    } catch (err) {
      flatDataByCountry.value = {
        ...flatDataByCountry.value,
        [countryCode]: {
          ...(flatDataByCountry.value[countryCode] || {}),
          loading: false,
          error: err.message,
        },
      }
    }
  }

  // =========================
  // ACTIONS — REGIONS
  // =========================

  const loadRegions = async () => {
    if (regionsLoaded.value || regionsLoading.value) return

    // Check cache TTL
    if (regionsLoaded.value && Date.now() - regionsFetchedAt.value < CACHE_TTL) return

    regionsLoading.value = true
    regionsError.value = null

    try {
      const res = await fetch('/api/geography/regions')
      if (!res.ok) throw new Error(`API ${res.status}`)

      const data = await res.json()
      regions.value = data.data || []
      regionsLoaded.value = true
      regionsFetchedAt.value = Date.now()

    } catch (err) {
      regionsError.value = err.message
    } finally {
      regionsLoading.value = false
    }
  }

  const loadCountriesForRegion = async (regionCode) => {
    if (!regionCode) return

    // Check if already loaded or loading
    if (countriesByRegion.value[regionCode] || countriesLoading.value[regionCode]) {
      return
    }

    // Check cache TTL
    if (
      countriesByRegion.value[regionCode] &&
      Date.now() - countriesFetchedAt.value[regionCode] < CACHE_TTL
    ) {
      return
    }

    // Atomically mark as loading BEFORE async call
    countriesLoading.value = {
      ...countriesLoading.value,
      [regionCode]: true,
    }

    try {
      const res = await fetch(`/api/geography/regions/${regionCode}/countries`)
      if (!res.ok) throw new Error(`API ${res.status}`)

      const data = await res.json()

      countriesByRegion.value = {
        ...countriesByRegion.value,
        [regionCode]: data.data || [],
      }
      countriesFetchedAt.value = {
        ...countriesFetchedAt.value,
        [regionCode]: Date.now(),
      }

    } catch (err) {
      countriesError.value = {
        ...countriesError.value,
        [regionCode]: err.message,
      }
    } finally {
      countriesLoading.value = {
        ...countriesLoading.value,
        [regionCode]: false,
      }
    }
  }

  // =========================
  // GETTERS (PURE READ MODELS)
  // =========================

  const getCountryState = (countryCode) =>
    flatDataByCountry.value[countryCode] || null

  const getNodeById = (countryCode, id) =>
    flatDataByCountry.value[countryCode]?.nodeMap?.[id] || null

  const getChildrenOf = (countryCode, parentId) =>
    flatDataByCountry.value[countryCode]?.childrenMap?.[parentId] || []

  const getLevelNodes = (countryCode, level) =>
    flatDataByCountry.value[countryCode]?.levelMap?.[level] || []

  const getNodeByPath = (countryCode, path) =>
    flatDataByCountry.value[countryCode]?.lookupKey?.[path] || null

  const getRegions = computed(() => regions.value)

  const getCountries = (regionCode) =>
    countriesByRegion.value[regionCode] || []

  // =========================
  // RESET
  // =========================

  const reset = () => {
    flatDataByCountry.value = {}
    regions.value = []
    regionsLoaded.value = false
    regionsLoading.value = false
    regionsError.value = null
    regionsFetchedAt.value = null
    countriesByRegion.value = {}
    countriesLoading.value = {}
    countriesError.value = {}
    countriesFetchedAt.value = {}
  }

  return {
    // State
    flatDataByCountry,
    regions,
    regionsLoaded,
    regionsLoading,
    regionsError,
    regionsFetchedAt,
    countriesByRegion,
    countriesLoading,
    countriesError,
    countriesFetchedAt,

    // Actions
    fetchFlat,
    loadRegions,
    loadCountriesForRegion,
    reset,

    // Getters
    getCountryState,
    getNodeById,
    getChildrenOf,
    getLevelNodes,
    getNodeByPath,
    getRegions,
    getCountries,
  }
})
