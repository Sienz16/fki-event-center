@component('mail::message')
# Dear {{ $studentName }},

We are pleased to inform you that your volunteer request for **{{ $eventName }}** has been accepted! 

The event will take place on {{ \Carbon\Carbon::parse($eventDate)->format('l, F j, Y') }}.

We appreciate your willingness to contribute to this event. You will receive further details about your volunteer duties and schedule soon.

@component('mail::button', ['url' => route('student.volunteers.index')])
View Details
@endcomponent

Thank you for your enthusiasm and commitment to making this event successful!

Best regards,<br>
{{ config('app.name') }} Team
@endcomponent 