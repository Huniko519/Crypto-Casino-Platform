@extends('frontend.layouts.main')

@section('title')
    {{ __('Privacy policy') }}
@endsection

@section('content')
    <p>
    This privacy notice discloses the privacy practices for <a href="{{ route('frontend.index') }}">{{ route('frontend.index') }}</a>. This privacy notice applies solely to information collected by this website. It will notify you of the following:
    </p>
    <ul>
        <li>What personally identifiable information is collected from you through the website, how it is used and with whom it may be shared.</li>
        <li>What choices are available to you regarding the use of your data.</li>
        <li>The security procedures in place to protect the misuse of your information.</li>
        <li>How you can correct any inaccuracies in the information.</li>
    </ul>
    <h3 class="mt-4">Information Collection, Use, and Sharing</h3>
    <p>
    We are the sole owners of the information collected on this site. We only have access to/collect information that you voluntarily give us via email or other direct contact from you. We will not sell or rent this information to anyone.
    </p>
    <p>
    We will use your information to respond to you, regarding the reason you contacted us. We will not share your information with any third party outside of our organization, other than as necessary to fulfill your request, e.g. to ship an order.
    </p>
    <p>
    Unless you ask us not to, we may contact you via email in the future to tell you about specials, new products or services, or changes to this privacy policy.
    </p>
    <h3 class="mt-4">Your Access to and Control Over Information</h3>
    <p>
    You may opt out of any future contacts from us at any time. You can do the following at any time by contacting us via the email address or phone number given on our website:
    </p>
    <ul>
        <li>See what data we have about you, if any.</li>
        <li>Change/correct any data we have about you.</li>
        <li>Have us delete any data we have about you.</li>
        <li>Express any concern you have about our use of your data.</li>
    </ul>
    <h3 class="mt-4">Security</h3>
    <p>
    We take precautions to protect your information. When you submit sensitive information via the website, your information is protected both online and offline.
    </p>
    <p>
    Wherever we collect sensitive information (such as credit card data), that information is encrypted and transmitted to us in a secure way. You can verify this by looking for a lock icon in the address bar and looking for "https" at the beginning of the address of the Web page.
    </p>
    <p>
    While we use encryption to protect sensitive information transmitted online, we also protect your information offline. Only employees who need the information to perform a specific job (for example, billing or customer service) are granted access to personally identifiable information. The computers/servers in which we store personally identifiable information are kept in a secure environment.
    </p>
@endsection