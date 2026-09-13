<x-mail::message>
# New career application

**Name:** {{ $application->name }}
**Email:** {{ $application->email }}
**Phone:** {{ $application->phone ?: '—' }}

{{ $application->message }}

The CV is stored on the private disk and is not a public URL.
</x-mail::message>
