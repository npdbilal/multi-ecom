@extends('layouts.app')

@section('title', trans_db('auth.login') . ' — ' . setting('store_name', 'Multi-Ecom'))

@section('content')
<div class="mx-auto max-w-md px-4 py-16">
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold tracking-tight">{{ trans_db('auth.login') }}</h1>
        <p class="mt-2 text-sm text-neutral-500">{{ trans_db('shop.secure_signin', null, [], 'Secure sign-in powered by Firebase') }}</p>
    </div>

    @if(!$firebaseConfigured)
        <div class="rounded-xl bg-amber-50 border border-amber-200 text-amber-800 text-sm p-5">
            Firebase is not configured yet. Add your <code>FIREBASE_*</code> keys to <code>.env</code> (see README) to enable sign-in.
        </div>
    @else
        <!-- Method tabs -->
        <div class="grid grid-cols-3 gap-1 bg-neutral-100 rounded-full p-1 mb-8 text-sm font-medium">
            <button type="button" data-tab="phone" class="tab-btn py-2.5 rounded-full transition">📱 {{ trans_db('shop.phone', null, [], 'Phone') }}</button>
            <button type="button" data-tab="email" class="tab-btn py-2.5 rounded-full transition">{{ trans_db('auth.email') }}</button>
            <button type="button" data-tab="google" class="tab-btn py-2.5 rounded-full transition">Google</button>
        </div>

        <div id="auth-error" class="hidden mb-5 text-sm text-red-700 bg-red-50 border border-red-200 rounded-xl p-4"></div>

        <!-- PHONE / OTP -->
        <div id="tab-phone" class="tab-panel">
            <div id="phone-step-1">
                <label class="block text-sm font-medium mb-2">{{ trans_db('shop.phone_number', null, [], 'Phone Number') }}</label>
                <input type="tel" id="phone-number" placeholder="+92 300 1234567"
                       class="w-full border border-neutral-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-neutral-900">
                <p class="text-xs text-neutral-500 mt-2">{{ trans_db('shop.phone_hint', null, [], 'Include your country code, e.g. +92…') }}</p>
                <div id="recaptcha-container" class="mt-3"></div>
                <button type="button" id="btn-send-otp"
                        class="mt-4 w-full bg-neutral-900 text-white rounded-full py-3.5 font-medium hover:bg-neutral-700 transition">
                    {{ trans_db('shop.send_otp', null, [], 'Send OTP') }}
                </button>
            </div>
            <div id="phone-step-2" class="hidden">
                <label class="block text-sm font-medium mb-2">{{ trans_db('shop.enter_otp', null, [], 'Enter the 6-digit code sent to your phone') }}</label>
                <input type="text" id="otp-code" inputmode="numeric" maxlength="6" placeholder="••••••"
                       class="w-full border border-neutral-300 rounded-xl px-4 py-3 text-center text-3xl tracking-[0.5em] focus:outline-none focus:ring-2 focus:ring-neutral-900">
                <button type="button" id="btn-verify-otp"
                        class="mt-4 w-full bg-neutral-900 text-white rounded-full py-3.5 font-medium hover:bg-neutral-700 transition">
                    {{ trans_db('shop.verify_otp', null, [], 'Verify OTP') }}
                </button>
                <div class="flex justify-between mt-4 text-sm">
                    <button type="button" id="btn-change-number" class="text-neutral-500 hover:text-neutral-900">← {{ trans_db('shop.back') }}</button>
                    <button type="button" id="btn-resend-otp" class="font-medium hover:underline">{{ trans_db('shop.resend_otp', null, [], 'Resend code') }}</button>
                </div>
            </div>
        </div>

        <!-- EMAIL + PASSWORD -->
        <div id="tab-email" class="tab-panel hidden">
            <div class="flex justify-center gap-2 mb-6 text-sm">
                <button type="button" id="email-mode-signin" class="email-mode px-4 py-2 rounded-full font-medium transition">{{ trans_db('auth.login') }}</button>
                <button type="button" id="email-mode-signup" class="email-mode px-4 py-2 rounded-full font-medium transition">{{ trans_db('shop.create_account', null, [], 'Create Account') }}</button>
            </div>
            <label class="block text-sm font-medium mb-2">{{ trans_db('auth.email') }}</label>
            <input type="email" id="email-address" class="w-full border border-neutral-300 rounded-xl px-4 py-3 mb-4 focus:outline-none focus:ring-2 focus:ring-neutral-900">
            <label class="block text-sm font-medium mb-2">{{ trans_db('auth.password') }}</label>
            <input type="password" id="email-password" class="w-full border border-neutral-300 rounded-xl px-4 py-3 focus:outline-none focus:ring-2 focus:ring-neutral-900">
            <button type="button" id="btn-email-auth"
                    class="mt-5 w-full bg-neutral-900 text-white rounded-full py-3.5 font-medium hover:bg-neutral-700 transition">
                {{ trans_db('auth.login') }}
            </button>
        </div>

        <!-- GOOGLE -->
        <div id="tab-google" class="tab-panel hidden text-center">
            <p class="text-sm text-neutral-500 mb-5">{{ trans_db('shop.google_hint', null, [], 'Sign in with your Google account') }}</p>
            <button type="button" id="btn-google"
                    class="w-full flex items-center justify-center gap-3 border border-neutral-300 rounded-full py-3.5 font-medium hover:border-neutral-900 transition">
                <svg class="w-5 h-5" viewBox="0 0 24 24"><path fill="#4285F4" d="M23.5 12.3c0-.9-.1-1.5-.3-2.3H12v4.3h6.5c-.1 1.1-.8 2.7-2.4 3.8l-.1.1 3.5 2.7.2.1c2.2-2 3.8-5 3.8-8.7z"/><path fill="#34A853" d="M12 24c3.2 0 5.9-1.1 7.9-2.9l-3.8-2.9c-1 .7-2.4 1.2-4.1 1.2-3.1 0-5.8-2.1-6.8-5l-.1.1-3.7 2.9v.1C3.4 21.5 7.4 24 12 24z"/><path fill="#FBBC05" d="M5.2 14.4c-.2-.7-.4-1.5-.4-2.4s.1-1.7.4-2.4l-.1-.1-3.6-2.8-.1.1C.5 8.5 0 10.1 0 12s.5 3.5 1.4 5.1l3.8-2.7z"/><path fill="#EA4335" d="M12 4.7c1.8 0 3 .8 3.7 1.4l3.3-3.2C17.9 1.1 15.2 0 12 0 7.4 0 3.4 2.5 1.4 6.9l3.8 2.8c1-2.9 3.7-5 6.8-5z"/></svg>
                {{ trans_db('shop.continue_with_google', null, [], 'Continue with Google') }}
            </button>
        </div>

        <p class="text-xs text-neutral-400 text-center mt-8">{{ trans_db('shop.firebase_note', null, [], 'Your credentials are verified securely by Firebase') }}</p>
    @endif
</div>

@if($firebaseConfigured)
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-app-compat.js"></script>
<script src="https://www.gstatic.com/firebasejs/10.12.0/firebase-auth-compat.js"></script>
<script>
(function () {
    firebase.initializeApp({
        apiKey: @json(config('firebase.api_key')),
        authDomain: @json(config('firebase.auth_domain')),
        projectId: @json(config('firebase.project_id')),
    });
    const auth = firebase.auth();
    const verifyUrl = @json(route('auth.firebase.verify'));
    const csrf = @json(csrf_token());
    const errBox = document.getElementById('auth-error');

    function showError(msg) { errBox.textContent = msg; errBox.classList.remove('hidden'); }
    function clearError() { errBox.classList.add('hidden'); }

    async function loginWithFirebase(user) {
        try {
            const idToken = await user.getIdToken(true);
            const res = await fetch(verifyUrl, {
                method: 'POST',
                headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': csrf },
                body: JSON.stringify({ id_token: idToken }),
            });
            const data = await res.json();
            if (!res.ok) throw new Error(data.error || 'Verification failed');
            window.location.href = data.redirect;
        } catch (e) { showError(e.message); }
    }

    const tabs = document.querySelectorAll('.tab-btn');
    const panels = document.querySelectorAll('.tab-panel');
    function selectTab(name) {
        clearError();
        tabs.forEach(b => {
            const active = b.dataset.tab === name;
            b.classList.toggle('bg-white', active);
            b.classList.toggle('shadow', active);
            b.classList.toggle('text-neutral-900', active);
            b.classList.toggle('text-neutral-500', !active);
        });
        panels.forEach(p => p.classList.toggle('hidden', p.id !== 'tab-' + name));
    }
    tabs.forEach(b => b.addEventListener('click', () => selectTab(b.dataset.tab)));
    selectTab('phone');

    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier(
        document.getElementById('recaptcha-container'), { size: 'invisible' }
    );
    let confirmationResult = null;

    async function sendOtp() {
        clearError();
        const phone = document.getElementById('phone-number').value.trim();
        if (!phone) { showError(@json(trans_db('shop.phone_required', null, [], 'Please enter your phone number.'))); return; }
        try {
            confirmationResult = await auth.signInWithPhoneNumber(phone, window.recaptchaVerifier);
            document.getElementById('phone-step-1').classList.add('hidden');
            document.getElementById('phone-step-2').classList.remove('hidden');
        } catch (e) {
            showError(e.message);
            window.recaptchaVerifier.render().then(id => grecaptcha.reset(id));
        }
    }
    document.getElementById('btn-send-otp').addEventListener('click', sendOtp);
    document.getElementById('btn-resend-otp').addEventListener('click', sendOtp);
    document.getElementById('btn-change-number').addEventListener('click', () => {
        document.getElementById('phone-step-2').classList.add('hidden');
        document.getElementById('phone-step-1').classList.remove('hidden');
    });
    document.getElementById('btn-verify-otp').addEventListener('click', async () => {
        clearError();
        const code = document.getElementById('otp-code').value.trim();
        if (!confirmationResult || !code) { showError(@json(trans_db('shop.otp_required', null, [], 'Please enter the OTP code.'))); return; }
        try {
            const result = await confirmationResult.confirm(code);
            await loginWithFirebase(result.user);
        } catch (e) { showError(e.message); }
    });

    let emailMode = @json($mode) === 'signup' ? 'signup' : 'signin';
    const btnSignin = document.getElementById('email-mode-signin');
    const btnSignup = document.getElementById('email-mode-signup');
    const btnEmail = document.getElementById('btn-email-auth');
    function setEmailMode(mode) {
        emailMode = mode;
        const on = ['bg-neutral-900', 'text-white'], off = ['text-neutral-500'];
        btnSignin.classList.remove(...on, ...off); btnSignup.classList.remove(...on, ...off);
        (mode === 'signin' ? btnSignin : btnSignup).classList.add(...on);
        (mode === 'signin' ? btnSignup : btnSignin).classList.add(...off);
        btnEmail.textContent = mode === 'signin'
            ? @json(trans_db('auth.login'))
            : @json(trans_db('shop.create_account', null, [], 'Create Account'));
    }
    btnSignin.addEventListener('click', () => setEmailMode('signin'));
    btnSignup.addEventListener('click', () => setEmailMode('signup'));
    setEmailMode(emailMode);
    btnEmail.addEventListener('click', async () => {
        clearError();
        const email = document.getElementById('email-address').value.trim();
        const password = document.getElementById('email-password').value;
        if (!email || !password) { showError(@json(trans_db('shop.email_pass_required', null, [], 'Please enter your email and password.'))); return; }
        try {
            const result = emailMode === 'signup'
                ? await auth.createUserWithEmailAndPassword(email, password)
                : await auth.signInWithEmailAndPassword(email, password);
            await loginWithFirebase(result.user);
        } catch (e) { showError(e.message); }
    });

    document.getElementById('btn-google').addEventListener('click', async () => {
        clearError();
        try {
            const result = await auth.signInWithPopup(new firebase.auth.GoogleAuthProvider());
            await loginWithFirebase(result.user);
        } catch (e) { showError(e.message); }
    });
})();
</script>
@endif
@endsection
