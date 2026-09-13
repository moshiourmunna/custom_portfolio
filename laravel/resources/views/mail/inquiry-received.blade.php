<x-mail::message>
# New inquiry

**Name:** {{ $inquiry->name ?: '—' }}
**Company:** {{ $inquiry->company ?: '—' }}
**Email:** {{ $inquiry->email ?: '—' }}
**Phone:** {{ $inquiry->phone ?: '—' }}
**Country:** {{ $inquiry->country ?: '—' }}
**Interest:** {{ $inquiry->interest ?: '—' }}

{{ $inquiry->message }}

<x-mail::button :url="url('/admin/inquiries')">
Open inbox
</x-mail::button>
</x-mail::message>
