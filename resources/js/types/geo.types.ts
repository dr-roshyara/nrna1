export interface GovernanceGeoUnit {
    id: number;
    code: string;
    name: string;
    name_local: Record<string, string>;
    admin_level: number;
    admin_type: string;
    parent_id: number | null;
    path: string | null;
    children_count: number;
    is_active: boolean;
    country_code: string;
    governance_level: number | null;
    governance_committee_name: string | null;
    governance_committee_code: string | null;
    children?: GovernanceGeoUnit[];
    breadcrumb?: GeoUnitBreadcrumb[];
}

export interface GeoUnitBreadcrumb {
    id: number;
    code: string;
    name: string;
    admin_level: number;
    admin_type: string;
}

export interface GeoUnitSearchResult {
    id: number;
    code: string;
    name: string;
    admin_level: number;
    admin_type: string;
    full_path: string;
}

export interface GeoUnitsApiResponse {
    data: GovernanceGeoUnit[];
}

export interface GeoUnitDetailResponse {
    data: GovernanceGeoUnit;
}
