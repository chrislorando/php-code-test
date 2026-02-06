<x-mail::message>
# Hello, {{ $name }}!

Welcome to our platform. Your account successfully created.
Please click button below to enter the dashboard.

<x-mail::button :url="''">
Enter Dashboard
</x-mail::button>

Thanks,<br>
{{ config('app.name') }}
</x-mail::message>
