import { defineStore } from 'pinia'
import { ref, computed } from 'vue'

const CACHE_TTL = 5 * 60 * 1000 // 5 minutes

/**
 * PRODUCTION-GRADE Geography Store v3
 *
 * DDD-aligned projection layer for geographic data.
 *
 * CONTRACT:
 * - Backend owns canonical geo_reference (GeoReferenceBuilder)
 * - Frontend is read-only projection layer
 * - No domain logic in frontend
 * - Per-country caching with TTL
 *
 * BACKEND ASSUMPTIONS (documented contract):
 * - config.initialCountryCode: set for single-country scope
 * - config.showCountrySelector: indicates multi-country org
 * - /api/geography/regions: returns global regions list
 * - /api/geography/regions/{code}/countries: returns countries for region
 * - /api/geography/countries/{code}/flat: returns flat geo hierarchy
 */
export const useGeographyStore = defineStore('geography', () => {

  // =========================
  // STATE CONTRACTS
  // =========================

  // flatDataByCountry: { [countryCode]: CountryState }
  // CountryState = {
  //   nodes: [],                  // raw nodes from API
  //   nodeMap: {},                // O(1): id → node
  //   levelMap: {},               // O(1): level → [nodes]
  //   childrenMap: {},            // O(1): parentId → [children]
  //   lookupKey: {},              // O(1): "NP.3.15" → node (read model only, NOT canonical)
  //   loaded: boolean,
  //   loading: boolean,
  //   error: null | string,
  //   version: string,            // API version
  //   schemaVersion: string,      // schema version
  //   fetchedAt: number,          // timestamp for TTL
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
  const countriesLoading = ref({})    // { [regionCode]: boolean }
  const countriesError = ref({})      // { [regionCode]: string | null }
  const countriesFetchedAt = ref({})  // { [regionCode]: timestamp }

  // =========================
  // NORMALIZATION (PURE)
  // =========================

  const buildMaps = (rawNodes, countryCode) => {
    const nodeMap = {}
    const levelMap = {}
    const childrenMap = {}
    const lookupKey = {}

    // First pass: nodeMap, levelMap, childrenMap
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

    // Second pass: lookupKey from materialized path
    // NOTE: This is a READ MODEL, not canonical geo_reference
    // Canonical format is owned by backend (GeoReferenceBuilder)
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

  /**
   * Fetch flat geography for a country
   * Per-country caching with TTL
   */
  const fetchFlat = async (countryCode) => {
    if (!countryCode) return

    const state = flatDataByCountry.value[countryCode]

    // Already loaded or loading
    if (state?.loaded || state?.loading) return

    // Check cache TTL
    if (state?.loaded && Date.now() - state.fetchedAt < CACHE_TTL) return

    // Atomically mark as loading (race condition safe)
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

  /**
   * Load global regions list
   * Shared across all organizations
   */
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

  /**
   * Load countries for a specific region
   * Per-region caching with TTL
   */
  const loadCountriesForRegion = async (regionCode) => {
    if (!regionCode) return

    // Already loaded or loading
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

    // Atomically mark as loading (race condition safe)
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

  /**
   * Get complete state for a country
   * Used for debugging/status checks
   */
  const getCountryState = (countryCode) =>
    flatDataByCountry.value[countryCode] || null

  /**
   * O(1) lookup: get node by ID
   */
  const getNodeById = (countryCode, id) =>
    flatDataByCountry.value[countryCode]?.nodeMap?.[id] || null

  /**
   * O(1) lookup: get children of a node
   */
  const getChildrenOf = (countryCode, parentId) =>
    flatDataByCountry.value[countryCode]?.childrenMap?.[parentId] || []

  /**
   * O(1) lookup: get all nodes at a level
   */
  const getLevelNodes = (countryCode, level) =>
    flatDataByCountry.value[countryCode]?.levelMap?.[level] || []

  /**
   * O(1) lookup: get node by path
   * NOTE: lookupKey is a READ MODEL, not canonical geo_reference
   */
  const getNodeByPath = (countryCode, path) =>
    flatDataByCountry.value[countryCode]?.lookupKey?.[path] || null

  /**
   * Get regions list
   */
  const getRegions = computed(() => regions.value)

  /**
   * Get countries for a region
   */
  const getCountriesForRegion = (regionCode) =>
    countriesByRegion.value[regionCode] || []

  // =========================
  // RESET
  // =========================

  /**
   * Complete reset of store state
   * WARNING: Clears all caches
   */
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
    // State (read-only, mutated via actions only)
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
    getCountriesForRegion,
  }
})
