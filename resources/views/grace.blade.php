<style>
    .subscribe {
        display: flex;
        flex-direction: column;
        align-items: center;
        border-radius: 10px;
        margin-top: 150px;
    }
</style>


<div class="subscribe shadow col-md-9 mx-auto card p-0">
    <div class="card-header w-100">
        <span class="font-weight-bold h5">Your Account Has Been Scheduled To Be Canceled On The Date Of {{ $subscription->cancel_at }}</span>
    </div>
    <div class="card-body">
        <h4 class="mx-3">During this grace period you may resume the subscription at anytime!</h4>
        <p class="p-3">If the account is not resumed before the provided date you must contact support to resume your subscription.</p>
        <a href="/" class="btn btn-primary font-weight-bold">I understand</a>
    </div>
</div>