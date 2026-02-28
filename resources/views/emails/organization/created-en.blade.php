<x-mail::message>
# Your organisation has been created! 🎉

Hello,

the organisation **{{ $organizationName }}** has just been successfully created in the voting system by **{{ $creatorName }}**.

## What you can do now:

<x-mail::button :url="$dashboardUrl">
Go to organisation Dashboard
</x-mail::button>

### Next steps:
1. **Invite members** – Add your organisation members and eligible voters
2. **Create your first election** – Set up voting period and ballot options
3. **Assign election commission** – Designate poll supervisors

All data is stored GDPR-compliant on servers in Germany.

<x-mail::panel>
**Your Contact:**
{{ $creatorName }} (Administrator)
E-Mail: {{ $organizationEmail }}
</x-mail::panel>

<x-mail::subcopy>
This is an automatic confirmation. If you did not expect this email, please contact support.
</x-mail::subcopy>

Thank you for using our voting system!
Your Public Digit Team
</x-mail::message>
