<x-mail::message>
# Notification: New User Registered

A new user has just joined the system. Here are the registration details:

<x-mail::table>
| Detail      | Information            |
| :---------- | :--------------------- |
| **Name** | {{ $userName }}        |
| **Email** | {{ $userEmail }}       |
| **Joined At**| {{ $createdAt }}       |
</x-mail::table>

<x-mail::button :url="''">
View User List
</x-mail::button>

Regards,<br>
{{ config('app.name') }}
</x-mail::message>