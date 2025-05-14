<h2>New Contact Message</h2>

<p><strong>Email:</strong> {{ $data['email'] }}</p>
<p><strong>Subject:</strong> {{ $data['subject'] }}</p>
<p><strong>Message:</strong></p>
<p>{!! nl2br(e($data['message'])) !!}</p>
