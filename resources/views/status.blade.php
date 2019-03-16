Hello <i>{{ $client["client"]->first_name }}   
        @if($client["client"]->spouse_first_name != "")
        & {{ $client["client"]->spouse_first_name }} 
        @endif
        {{ $client["client"]->last_name }}, 
        </i>
        
        <p>We are currently working on your <span class="year">{{ $client["engagement"]->year }}</span>, <span class="return"{{ $client["engagement"]->return_type }}</span> tax return for the name of <span class="name">"{{ $client["engagement"]->name }}"</span> and would like to inform you on the<strong> change in status</strong></p>

        <div style="border: 1px solid blue; border-radius: 10px; text-align: left; padding: 5px">
            <h3 class="current">Current Status:  <span class="status">{{ $client["engagement"]->status }}</span></h3>
        </div>
        
        <h3>For questions and concerns</h3>
        <div>
            <p style="margin: 0; align-self: center">Please feel free to call us at: <span class="phone">{{ $phoneNumber }}</span></p>
        </div>
        <div>
            <p style="margin: 0; align-self: center">Or reply to: <span class="email">{{ $userEmail }}</span></p>
        </div>
        <p>Please reply to this email to verify you received it and let me know when you will be able to get me this information.</p>
        <p class="thanks">Looking forward to hearing from you, Thanks!</p>
        <br>
        
        <h3 style="margin-bottom: 0;" class="account">{{ $accountName }}</h3>
        <h3 style="margin-bottom: 0;" class="account-email">{{ $accountEmail }}</h3>
        <h3 style="margin-bottom: 0;" class="phone">{{ $phoneNumber }}</h3>
        <h3 style="margin-bottom: 0;" class="fax">{{ $faxNumber }}</h3>