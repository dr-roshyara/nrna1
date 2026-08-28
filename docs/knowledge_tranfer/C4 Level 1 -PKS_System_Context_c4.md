@startuml PKS_System_Context
!include https://raw.githubusercontent.com/plantuml-stdlib/C4-PlantUML/master/C4_Context.puml

LAYOUT_WITH_LEGEND()

title PKS — System Context Diagram (C4 Level 1)

' ============================================
' System Boundary
' ============================================
System_Boundary(pks, "Product Knowledge System") {
    System(pks_system, "PKS", "Manages governed engineering knowledge\nfor a specific product throughout its lifecycle")
}

' ============================================
' Actors (Human Users)
' ============================================
Person(architect, "Architect", "Defines strategic boundaries and validates architectural decisions")
Person(engineer, "Engineer", "Creates and consumes engineering knowledge")
Person(authority, "Program Authority", "Makes governance decisions and disposes recommendations")
Person(reviewer, "Reviewer", "Independently verifies knowledge and execution")
Person(ai_assistant, "AI Assistant", "Consumes governed knowledge to guide development")

' ============================================
' External Systems
' ============================================
System_Ext(work_management, "Work Management System", "External domain — manages work items, tickets, boards")

' ============================================
' Relationships (Post-DAR-1)
' ============================================

' Actors -> PKS
Rel(architect, pks_system, "defines boundaries, validates decisions", "Strategic DDD")
Rel(engineer, pks_system, "creates and consumes knowledge", "CRUD")
Rel(authority, pks_system, "disposes governance decisions", "Authority act")
Rel(reviewer, pks_system, "verifies execution and knowledge", "Critical Review")
Rel(ai_assistant, pks_system, "consumes governed knowledge", "YAML/Markdown")

' PKS -> External Systems
Rel(pks_system, work_management, "references work items; no dependency on them", "Read-only (DR-5)")

@enduml