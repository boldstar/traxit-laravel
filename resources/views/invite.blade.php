Hello <i>{{ $client["client"]->first_name }}   
        @if($client["client"]->spouse_first_name != "")
        & {{ $client["client"]->spouse_first_name }} 
        @endif
        {{ $client["client"]->last_name }}, 
        </i>

<div style="
display: flex;
align-items: center;
text-align:left;
margin: 25px;
">
<p 
 style="
 color:black;
 ">
You are receiving this email as an invitation to our document sharing portal.<br><br> By clicking the button below you will be asked to register with our service so that we can manage documents securely with eachother.<br><br>Once registered you will be able to sign and download documents shared by us.
</p>
</div>

<a href="http://localhost:8081/register?client_id={{$client['client']->id}}"
 style="
 background: #0077ff;
 padding: 10px 40px;
 border: none;
 border-radius: 8px;
 text-decoration: none;
 margin: 20px;
 color: white;
 font-weight: bold;
 font-size: 1.25rem;
"
>Portal Register</a>

<p
 style="
  margin: 20px;
 "
>Please contact us if you have any issues, also if you did not request this service please ignore</p>


<h3 style="margin-bottom: 0;" class="account">{{ $accountName }}</h3>
<h3 style="margin-bottom: 0;" class="account-email">{{ $accountEmail }}</h3>
<h3 style="margin-bottom: 0;" class="phone">{{ $phoneNumber }}</h3>
<h3 style="margin-bottom: 0;" class="fax">{{ $faxNumber }}</h3>