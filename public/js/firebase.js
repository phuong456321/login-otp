import { initializeApp } from "https://www.gstatic.com/firebasejs/9.8.3/firebase-app.js";
import {
    getAuth, // xác thực
    RecaptchaVerifier, // tạo mã xác thực
    signInWithPhoneNumber,
} from "https://www.gstatic.com/firebasejs/9.8.3/firebase-auth.js";

// cấu hình firebase
const firebaseConfig = {
    apiKey: "AIzaSyDavddu2TK7UdNlVnLKwY9KYG8baCJcI34",
    authDomain: "login-gg-with-otp.firebaseapp.com",
    projectId: "login-gg-with-otp",
    storageBucket: "login-gg-with-otp.firebasestorage.app",
    messagingSenderId: "348115228937",
    appId: "1:348115228937:web:80289ae537e7a249231bd8",
    measurementId: "G-TCH0GPMM5N"
  };

// khởi tạo firebase
const app = initializeApp(firebaseConfig);
const auth = getAuth(app);
auth.languageCode = "en";

window.onload = function () {
    render();
}

function render() {
    window.recaptchaVerifier = new RecaptchaVerifier(
        "recaptcha-container",
        {
            size: "normal",
           
            callback: (response) => {
                alert("Recaptcha success! Enter your phone number please!");
                $("#send_otp").removeAttr("disabled");
            },
            "expired-callback": () => {
                alert("Recaptcha has been expired! Retry again please!");
            },
        },
        auth
    );
    recaptchaVerifier.render();
}


// hàm gửi otp
window.sendOTP = function () {
    const phoneNumber = $("#phone_number").val();
    const appVerifier = window.recaptchaVerifier;

    signInWithPhoneNumber(auth, phoneNumber, appVerifier).then(
        (confirmationResult) => {
            window.confirmationResult = confirmationResult;
            alert("OTP has been sent! please check your phone!");
            $("#verification_input").show();
            $("#phone_input").hide();
        }
    );
};

// hàm xác minh otp
window.verifyOTP = function () {
    const code = $("#verification_code").val();

    confirmationResult
        .confirm(code)
        .then((result) => {
            alert("OTP has been verified successfully!");
            const user = result.user;

            $.ajax({
                url: "/otp-verified",
                method: "POST",
                data: {
                    _token: $('meta[name="csrf-token"]').attr("content"),
                },
                success: function (response) {
                    window.location.href = "/index";
                },
                error: function (error) {
                    alert("Server error, please try again.");
                },
            });
        })
        .catch((error) => {
            alert(error.message);
        });
};

// hàm thử lại
window.tryAgain = function () {
    $("#verification_input").hide();
    $("#phone_input").show();
    $("#verification_code").val("");
};