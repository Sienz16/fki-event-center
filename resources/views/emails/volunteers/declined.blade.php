@component('mail::message')
# Dear {{ $studentName }},

Thank you for your interest in volunteering for **{{ $eventName }}**. 

We appreciate your willingness to contribute to our event. However, after careful consideration of all applications, we regret to inform you that we are unable to accommodate your volunteer request at this time.

Please don't let this discourage you from applying for future volunteer opportunities. We value your enthusiasm and encourage you to continue participating in our community events.

@component('mail::button', ['url' => route('student.volunteers.index')])
View Other Opportunities
@endcomponent

Thank you for your understanding.

Best regards,<br>
{{ config('app.name') }} Team
@endcomponent 