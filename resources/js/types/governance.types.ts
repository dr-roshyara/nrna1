export interface GovernanceLevelDefinition {
    id: number;
    tenant_id: string;
    level: number;
    committee_name: string;
    committee_code: string;
    geo_name: string;
    geo_code: string;
    geo_parent_code: string | null;
    description: string | null;
    is_active: boolean;
    sort_order: number;
    created_by: string | null;
    updated_by: string | null;
    created_at: string | null;
    updated_at: string | null;
}

export interface GovernanceLevelFormData {
    level: number;
    committee_name: string;
    committee_code: string;
    geo_name: string;
    geo_code: string;
    geo_parent_code: string | null;
    description: string | null;
    is_active: boolean;
    sort_order: number;
}
