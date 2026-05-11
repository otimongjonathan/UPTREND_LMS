@extends('customer.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
            <h1 class="text-3xl font-bold text-white">Terms of Service</h1>
            <p class="text-primary-100 mt-2">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="px-8 py-8 prose prose-lg max-w-none">
            <h2>1. Welcome to UPTREND LMS</h2>
            <p>These terms govern your use of our customer portal and loan services. By creating an account or applying for a loan, you agree to these terms.</p>

            <h2>2. Our Services</h2>
            <p>UPTREND LMS provides:</p>
            <ul>
                <li>Online loan applications and account management</li>
                <li>Secure customer portal for tracking your loans</li>
                <li>Payment processing and repayment scheduling</li>
                <li>Customer support and financial guidance</li>
                <li>Document upload and storage services</li>
            </ul>

            <h2>3. Your Responsibilities</h2>
            <p>As a customer, you agree to:</p>
            <ul>
                <li>Provide accurate and truthful information in all applications</li>
                <li>Keep your login credentials secure and confidential</li>
                <li>Make loan payments on time according to your agreement</li>
                <li>Notify us immediately of any changes to your contact information</li>
                <li>Use our services only for lawful purposes</li>
            </ul>

            <h2>4. Account Security</h2>
            <p>To protect your account:</p>
            <ul>
                <li>Choose a strong, unique password</li>
                <li>Never share your login credentials with others</li>
                <li>Log out when using shared or public computers</li>
                <li>Report suspicious activity immediately</li>
                <li>Keep your contact information up to date</li>
            </ul>

            <h2>5. Loan Terms and Conditions</h2>
            <p>Each loan is subject to:</p>
            <ul>
                <li>Individual loan agreements with specific terms</li>
                <li>Interest rates and fees as disclosed in your agreement</li>
                <li>Repayment schedules and payment methods</li>
                <li>Late payment fees and collection procedures</li>
                <li>Credit reporting to credit bureaus</li>
            </ul>

            <h2>6. Prohibited Activities</h2>
            <p>You may not:</p>
            <ul>
                <li>Provide false or misleading information</li>
                <li>Use our services for illegal activities</li>
                <li>Attempt to access other customers' accounts</li>
                <li>Interfere with our website or services</li>
                <li>Use automated tools to access our systems</li>
            </ul>

            <h2>7. Privacy and Data Protection</h2>
            <p>Your privacy is important to us. Please review our Privacy Policy to understand how we collect, use, and protect your information.</p>

            <h2>8. Service Availability</h2>
            <p>While we strive to keep our services available 24/7, we may occasionally need to perform maintenance or updates that could temporarily affect access.</p>

            <h2>9. Limitation of Liability</h2>
            <p>Our liability is limited to the extent permitted by law. We are not responsible for:</p>
            <ul>
                <li>Technical issues beyond our control</li>
                <li>Third-party service interruptions</li>
                <li>Indirect or consequential damages</li>
                <li>Loss of data due to user error</li>
            </ul>

            <h2>10. Account Termination</h2>
            <p>We may suspend or close your account if:</p>
            <ul>
                <li>You violate these terms of service</li>
                <li>You provide false information</li>
                <li>Your account shows suspicious activity</li>
                <li>Required by law or regulation</li>
            </ul>

            <h2>11. Changes to Terms</h2>
            <p>We may update these terms occasionally. We'll notify you of significant changes and give you time to review them before they take effect.</p>

            <h2>12. Dispute Resolution</h2>
            <p>If you have a dispute with us, we encourage you to contact our customer service team first. We're committed to resolving issues fairly and quickly.</p>

            <h2>13. Contact Information</h2>
            <p>Questions about these terms? We're here to help:</p>
            <div class="bg-primary-50 p-6 rounded-lg border border-primary-200">
                <ul class="space-y-2">
                    <li><strong>Customer Service:</strong> support@uptrendlms.com</li>
                    <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                    <li><strong>Legal Questions:</strong> legal@uptrendlms.com</li>
                    <li><strong>Hours:</strong> Monday-Friday, 9 AM - 5 PM</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection