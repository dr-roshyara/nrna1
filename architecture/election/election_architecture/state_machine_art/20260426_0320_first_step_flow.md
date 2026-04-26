sequenceDiagram
    participant Customer
    participant System
    participant Admin
    
    Customer->>System: Create election (state = draft)
    Customer->>System: submitForApproval()
    System->>Admin: Notification
    Admin->>System: approve() or reject()
    alt Approved
        System->>System: state = administration
        Customer->>System: completeAdministration()
    else Rejected
        System->>System: records rejection reason
        Customer->>System: Cannot proceed
    end
    