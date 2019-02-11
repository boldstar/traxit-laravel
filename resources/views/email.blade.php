Hello <i>{{ $client['client']->last_name }}, {{ $client['client']->first_name }} 
@if($client['client']->spouse_first_name != '')
& {{ $client['client']->spouse_first_name }}
@endif
</i>

<p>We are currently working on your {{ $client['engagement']->year }}, {{ $client['engagement']->return_type }} tax return for the name of "{{ $client['engagement']->name }}" and have some questions for you</p>

<p>Note: The following questions and issues were raised during the performance of our work. Please provide responses to the following items so that we can continue.</p>

<div style="border: 1px solid blue; border-radius: 10px; text-align: left; padding: 5px">
    <h3>Pending Questions:</h3>
    <p>
        {!! $client['question']->question !!}
    </p>
</div>

<h3>For questions and concerns</h3>
<div style="display: flex;">
    <p style="margin: 0 5px; align-self: center">please feel free to call us at: </p>
    <h6 style="margin: 0; align-self: center">{{ $phoneNumber }}</h6>
</div>
<div style="display: flex;">
    <p style="margin: 0 5px; align-self: center">Or reply to: </p>
    <h6 style="margin: 0; align-self: center">{{ $userEmail }}</h6>
</div>
<p>Please reply to this email to verify you received it and let me know when you will be able to get me this information.</p>
<h3>Looking forward to hearing from you, Thanks!</h3>
<br>

<h3>{{ $accountName }}</h3>
<h5>{{ $accountEmail }}</h5>
<h5>{{ $phoneNumber }}</h5>
<h5>{{ $faxNumber }}</h5>