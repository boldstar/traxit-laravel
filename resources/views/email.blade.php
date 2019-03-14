Hello <i>{{ $client["client"]->first_name }}   
        @if($client["client"]->spouse_first_name != "")
        & {{ $client["client"]->spouse_first_name }} 
        @endif
        {{ $client["client"]->last_name }}, 
        </i>
        
        <p>We are currently working on your {{ $client["engagement"]->year }}, {{ $client["engagement"]->return_type }} tax return for the name of "{{ $client["engagement"]->name }}" and would like to inform you on the<strong> change in status of your Tax Return </strong></p>

        <div style="border: 1px solid blue; border-radius: 10px; text-align: left; padding: 5px">
            <h3>Current Status:  {{ $client["engagement"]->status }}</h3>
        </div>
        
        <h3>For questions and concerns</h3>
        <div>
            <p style="margin: 0; align-self: center">Please feel free to call us at: {{ $phoneNumber }}</p>
        </div>
        <div>
            <p style="margin: 0; align-self: center">Or reply to: {{ $userEmail }}</p>
        </div>
        <p>Please reply to this email to verify you received it and let me know when you will be able to get me this information.</p>
        <p>Looking forward to hearing from you, Thanks!</p>
        <br>
        
        <h3 style="margin-bottom: 0;">{{ $accountName }}</h3>
        <h3 style="margin-bottom: 0;">{{ $accountEmail }}</h3>
        <h3 style="margin-bottom: 0;">{{ $phoneNumber }}</h3>
        <h3 style="margin-bottom: 0;">{{ $faxNumber }}</h3>