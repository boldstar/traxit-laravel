Hello <i>{{ $client["client"]->first_name }}   
        @if($client["client"]->spouse_first_name != "")
        & {{ $client["client"]->spouse_first_name }} 
        @endif
        {{ $client["client"]->last_name }}, 
        </i>



<div style="
text-align:center;
margin: 25px;
">
        <div>
        <h1 style="color:black;">{{ $accountName }}</h1>
        </div>
        <div>
        <p 
        style="
        color:black;
        background: rgb(243, 243, 243);
        margin-bottom: 30px;
        padding: 10px;
        border-radius: 8px;
        ">You are receiving this email as an invitation to our document sharing portal.<br> By clicking the button below you will be asked to register with our service<br> so that we can manage documents securely with eachother.<br>Once registered you will be able to sign and download documents shared by us.
        </p>
        </div>
        <div>
        <a href="https://fileshare.traxit.io/register?client_id={{$client['client']->id}}&client_email={{$client_email}}"
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
        >Click To Register</a>
        </div>
        <div>
        <p
        style="
        margin: 20px;
        margin-bottom: 30px;
        font-weight:bold;
        color:black;
        "
        >Please contact us if you have any issues, also if you did not request this service please ignore</p>
        </div>

</div>


<h3 style="margin-bottom: 0;" class="account">{{ $accountName }}</h3>
<h3 style="margin-bottom: 0;" class="account-email">{{ $accountEmail }}</h3>
<h3 style="margin-bottom: 0;" class="phone">{{ $phoneNumber }}</h3>
<h3 style="margin-bottom: 0;" class="fax">{{ $faxNumber }}</h3>
