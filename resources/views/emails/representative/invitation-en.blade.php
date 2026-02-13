<x-mail::message>
# Welcome as a Representative! 👋

Hello {{ $representativeName }},

You have been added as a representative of the organization **{{ $organizationName }}** by **{{ $creatorName }}**.

## Next Steps

To activate your account and access the election system, please set your password:

<x-mail::button :url="$setupUrl">
Set Password
</x-mail::button>

## Your Role

As a representative of {{ $organizationName }}, you can:
- **Manage members** and send invitations
- **Create and conduct** elections
- **View results** and export data
- **Generate reports** for elections

## Contact Information

If you have any questions, please contact:
**{{ $creatorName }}**
Email: {{ $organizationEmail }}

<x-mail::panel>
**Note:** This link is valid for 24 hours. If it expires, you can request a new one from the login page.
</x-mail::panel>

<x-mail::subcopy>
This is an automated notification. If you did not expect this email, please contact our support team immediately.
</x-mail::subcopy>

Welcome to Public Digit!
Your Election System Team
</x-mail::message>
