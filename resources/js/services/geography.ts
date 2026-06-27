export interface CascaderConfig {
  scope: 'worldwide' | 'multi_country' | 'single_country' | 'sub_country'
  initialCountryCode: string | null
  allowedCountryCodes: string[]
  showCountrySelector: boolean
  levelLabels: string[]
  maxDepth: number
  levels?: Array<{ index: number; db_level: number; label: string }>
}

export async function fetchCascaderConfig(organisationSlug: string): Promise<CascaderConfig> {
  const response = await fetch(
    `/organisations/${organisationSlug}/api/geography/cascader-config`,
    {
      headers: {
        'Accept': 'application/json',
        'X-Requested-With': 'XMLHttpRequest',
      },
    }
  )

  if (!response.ok) {
    throw new Error(`Failed to fetch cascader config: ${response.statusText}`)
  }

  const json = await response.json()
  return json
}
