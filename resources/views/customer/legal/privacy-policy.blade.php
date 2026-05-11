@extends('customer.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
            <h1 class="text-3xl font-bold text-white">Privacy Policy</h1>
            <p class="text-primary-100 mt-2">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="px-8 py-8 prose prose-lg max-w-none">
            <h2>1. Your Privacy Matters</h2>
            <p>At UPTREND LMS, we are committed to protecting your personal information and being transparent about how we collect, use, and share your data.</p>

            <h2>2. Information We Collect</h2>
            <p>To provide you with loan services, we collect:</p>
            <ul>
                <li><strong>Personal Details:</strong> Name, address, phone number, email, and identification documents</li>
                <li><strong>Financial Information:</strong> Income, employment details, bank account information, and credit history</li>
                <li><strong>Loan Information:</strong> Application details, repayment history, and account activity</li>
                <li><strong>Website Usage:</strong> How you interact with our customer portal</li>
            </ul>

            <h2>3. How We Use Your Information</h2>
            <p>We use your information to:</p>
            <ul>
                <li>Process your loan applications and manage your account</li>
                <li>Assess your creditworthiness and determine loan terms</li>
                <li>Send you important updates about your loans and payments</li>
                <li>Provide customer support and answer your questions</li>
                <li>Comply with legal requirements and prevent fraud</li>
                <li>Improve our services based on your feedback</li>
            </ul>

            <h2>4. Sharing Your Information</h2>
            <p>We only share your information when necessary:</p>
            <ul>
                <li><strong>Credit Bureaus:</strong> To check and report your credit history</li>
                <li><strong>Service Partners:</strong> Trusted companies that help us provide services</li>
                <li><strong>Legal Requirements:</strong> When required by law or to protect our rights</li>
                <li><strong>With Your Consent:</strong> For any other purposes you specifically approve</li>
            </ul>

            <h2>5. Keeping Your Data Safe</h2>
            <p>We protect your information with:</p>
            <ul>
                <li>Bank-level encryption for all sensitive data</li>
                <li>Secure servers and regular security updates</li>
                <li>Strict access controls for our employees</li>
                <li>Regular security audits and monitoring</li>
            </ul>

            <h2>6. Your Rights and Choices</h2>
            <p>You can:</p>
            <ul>
                <li>View and update your personal information in your account</li>
                <li>Request a copy of the information we have about you</li>
                <li>Ask us to correct any inaccurate information</li>
                <li>Opt out of marketing emails (loan-related emails will continue)</li>
                <li>Contact us with any privacy concerns</li>
            </ul>

            <h2>7. How Long We Keep Your Data</h2>
            <p>We keep your information:</p>
            <ul>
                <li>While you have an active account with us</li>
                <li>For 7 years after your last loan is closed (legal requirement)</li>
                <li>As long as needed to provide ongoing services</li>
                <li>To comply with regulatory and legal obligations</li>
            </ul>

            <h2>8. Cookies and Website Data</h2>
            <p>Our website uses cookies to remember your preferences and improve your experience. You can control these through your browser settings.</p>

            <h2>9. Changes to This Policy</h2>
            <p>We may update this policy occasionally. We'll notify you of important changes via email or through your customer portal.</p>

            <h2>10. Contact Us</h2>
            <p>Have questions about your privacy? Contact us:</p>
            <div class="bg-primary-50 p-6 rounded-lg border border-primary-200">
                <ul class="space-y-2">
                    <li><strong>Email:</strong> privacy@uptrendlms.com</li>
                    <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                    <li><strong>Mail:</strong> 123 Business St, Suite 100</li>
                    <li><strong>Hours:</strong> Monday-Friday, 9 AM - 5 PM</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection