// PBDIGIT-355 (frontend M1): the page data the read-only Reference Geography explorer receives from
// App\Http\Controllers\ReferenceGeography (developer guides 34, 35). Only fields of the published contract.

export type LabelKind = 'display' | 'display_fallback' | 'source' | 'none'

export interface PlaceLabel {
  value: string | null
  kind: LabelKind
  language: string | null
}

export interface KnownAt {
  position: number
  latestUrl: string
  explorerUrl: string
}

export interface PlaceSummary {
  unitId: string
  label: PlaceLabel
  classifications: Record<string, string>
  url: string
}

export interface RelatedPlace extends PlaceSummary {
  scheme: string
}

export interface SourceName {
  source: string
  language: string
  type: string
  value: string
}

export interface DisplayName {
  value: string
  origin: 'CLDR' | 'PLATFORM_FALLBACK'
  resolvedLocale: string | null
  cldrRelease: string | null
  fallbackLocale: string | null
}

export interface PlaceProfile {
  unitId: string
  validAt: string
  exists: boolean
  label: PlaceLabel
  displayName: DisplayName | null
  sourceNames: SourceName[]
  classifications: Record<string, string>
  identifiers: Record<string, string>
  disputedKinds: string[]
  mergedInto: { unitId: string; url: string } | null
  parents: RelatedPlace[]
  children: RelatedPlace[]
}

interface CommonProps {
  knownAt: KnownAt
  validDate: string
  language: string
}

export interface ExplorerProps extends CommonProps {
  places: PlaceSummary[]
  empty: boolean
}

export interface PlacePageProps extends CommonProps {
  place: PlaceProfile
}
