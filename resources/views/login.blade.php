<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>OTP Authentication</title>
</head>
<body>
    <h3>OTP Authentication</h3>

    {{-- phone input --}}
    <div id="phone_input">
        <div id="recaptcha-container"></div>
        <input type="text" id="phone_number" placeholder="Input Phone">
        <button type="button" onclick="sendOTP();" disabled id="send_otp">
            Send OTP
        </button>
    </div>

    {{-- verification form --}}
    <div id="verification_input" style="display: none">
        <input id="verification_code" type="text" placeholder="Input OTP">
        <button type="button" onclick="verifyOTP();">Verify OTP</button>
        <button type="button" onclick="tryAgain();">Retry</button>
    </div>

    <!-- Load jQuery script -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="{{ asset('js/firebase.js') }}" type="module"></script>
</body>
</html>