<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Privacy Policy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Privacy Policy</h1>
                    <p class="text-primary-100 mt-2">Last updated: {{ date('F j, Y') }}</p>
                </div>

                <div class="px-8 py-8 prose prose-lg max-w-none">
                    <h2>1. Information We Collect</h2>
                    <p>UPTREND LMS collects information necessary to provide loan management services:</p>
                    <ul>
                        <li><strong>Personal Information:</strong> Name, address, phone number, email, date of birth, and identification documents</li>
                        <li><strong>Financial Information:</strong> Income details, employment history, bank account information, and credit history</li>
                        <li><strong>Loan Data:</strong> Application details, repayment history, and transaction records</li>
                        <li><strong>Technical Data:</strong> IP address, browser type, and usage analytics</li>
                    </ul>

                    <h2>2. How We Use Your Information</h2>
                    <p>We use collected information to:</p>
                    <ul>
                        <li>Process and evaluate loan applications</li>
                        <li>Manage loan accounts and repayment schedules</li>
                        <li>Conduct credit assessments and risk analysis</li>
                        <li>Communicate about your account and services</li>
                        <li>Comply with legal and regulatory requirements</li>
                        <li>Improve our services and user experience</li>
                    </ul>

                    <h2>3. Information Sharing</h2>
                    <p>We may share your information with:</p>
                    <ul>
                        <li><strong>Credit Bureaus:</strong> For credit reporting and verification</li>
                        <li><strong>Service Providers:</strong> Third-party vendors who assist in our operations</li>
                        <li><strong>Legal Authorities:</strong> When required by law or to protect our rights</li>
                        <li><strong>Business Partners:</strong> With your consent for specific services</li>
                    </ul>

                    <h2>4. Data Security</h2>
                    <p>We implement comprehensive security measures:</p>
                    <ul>
                        <li>Encryption of sensitive data in transit and at rest</li>
                        <li>Regular security audits and vulnerability assessments</li>
                        <li>Access controls and employee training programs</li>
                        <li>Secure data centers with physical and digital protections</li>
                    </ul>

                    <h2>5. Your Rights</h2>
                    <p>You have the right to:</p>
                    <ul>
                        <li>Access and review your personal information</li>
                        <li>Request corrections to inaccurate data</li>
                        <li>Request deletion of your data (subject to legal requirements)</li>
                        <li>Opt-out of marketing communications</li>
                        <li>File complaints with regulatory authorities</li>
                    </ul>

                    <h2>6. Data Retention</h2>
                    <p>We retain your information for as long as necessary to:</p>
                    <ul>
                        <li>Provide ongoing services</li>
                        <li>Comply with legal obligations</li>
                        <li>Resolve disputes and enforce agreements</li>
                        <li>Meet regulatory requirements (typically 7 years for financial records)</li>
                    </ul>

                    <h2>7. Cookies and Tracking</h2>
                    <p>Our website uses cookies to enhance user experience and analyze usage patterns. You can control cookie preferences through your browser settings.</p>

                    <h2>8. Contact Information</h2>
                    <p>For privacy-related questions or requests:</p>
                    <ul>
                        <li><strong>Email:</strong> privacy@uptrendlms.com</li>
                        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                        <li><strong>Address:</strong> 123 Business St, Suite 100</li>
                    </ul>

                    <h2>9. Policy Updates</h2>
                    <p>We may update this policy periodically. Significant changes will be communicated through email or website notifications.</p>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>