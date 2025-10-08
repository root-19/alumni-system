@component('mail::message')
# Welcome, {{ $user->name }} 🎉

Thank you for registering!

Here are your details:

- **Name:** {{ $user->name }} {{ $user->middle_name ?? '' }} {{ $user->suffix ?? '' }}
- **Email:** {{ $user->email }}
- **Program:** {{ $user->program ?? 'N/A' }}
- **Year Graduated:** {{ $user->year_graduated ?? 'N/A' }}
- **Gender:** {{ $user->gender ?? 'N/A' }}
- **Status:** {{ $user->status ?? 'N/A' }}
- **Contact Number:** {{ $user->contact_number ?? 'N/A' }}
- **Address:** {{ $user->address ?? 'N/A' }}

@if($user->profile_image_path)
You also uploaded a profile image ✅
@endif

@component('mail::button', ['url' => route('dashboard')])
Go to Dashboard
@endcomponent

Thanks,<br>
{{ config('app.name') }}
@endcomponent
