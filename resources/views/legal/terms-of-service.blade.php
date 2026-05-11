<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Terms of Service') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Terms of Service</h1>
                    <p class="text-primary-100 mt-2">Last updated: {{ date('F j, Y') }}</p>
                </div>

                <div class="px-8 py-8 prose prose-lg max-w-none">
                    <h2>1. Acceptance of Terms</h2>
                    <p>By accessing and using UPTREND LMS, you agree to be bound by these Terms of Service and all applicable laws and regulations.</p>

                    <h2>2. Service Description</h2>
                    <p>UPTREND LMS provides:</p>
                    <ul>
                        <li>Loan management and processing services</li>
                        <li>Customer relationship management tools</li>
                        <li>Financial reporting and analytics</li>
                        <li>Document management and storage</li>
                        <li>Payment processing and tracking</li>
                    </ul>

                    <h2>3. User Responsibilities</h2>
                    <p>Users must:</p>
                    <ul>
                        <li>Provide accurate and complete information</li>
                        <li>Maintain the confidentiality of login credentials</li>
                        <li>Use the service only for lawful purposes</li>
                        <li>Comply with all applicable regulations</li>
                        <li>Report security breaches immediately</li>
                    </ul>

                    <h2>4. Prohibited Activities</h2>
                    <p>Users may not:</p>
                    <ul>
                        <li>Attempt to gain unauthorized access to the system</li>
                        <li>Use the service for fraudulent activities</li>
                        <li>Share access credentials with unauthorized persons</li>
                        <li>Reverse engineer or modify the software</li>
                        <li>Violate any applicable laws or regulations</li>
                    </ul>

                    <h2>5. Data and Privacy</h2>
                    <p>Our data handling practices are governed by our Privacy Policy. By using our services, you consent to the collection and use of information as outlined in our Privacy Policy.</p>

                    <h2>6. Service Availability</h2>
                    <p>While we strive for 99.9% uptime, we do not guarantee uninterrupted service. Scheduled maintenance will be communicated in advance when possible.</p>

                    <h2>7. Limitation of Liability</h2>
                    <p>UPTREND LMS shall not be liable for:</p>
                    <ul>
                        <li>Indirect, incidental, or consequential damages</li>
                        <li>Loss of profits or business opportunities</li>
                        <li>Data loss due to user error or system failure</li>
                        <li>Third-party service interruptions</li>
                    </ul>

                    <h2>8. Intellectual Property</h2>
                    <p>All software, content, and materials are the property of UPTREND LMS and are protected by copyright and other intellectual property laws.</p>

                    <h2>9. Termination</h2>
                    <p>We reserve the right to terminate or suspend access for violations of these terms or for any other reason at our discretion.</p>

                    <h2>10. Governing Law</h2>
                    <p>These terms are governed by the laws of the jurisdiction where UPTREND LMS operates.</p>

                    <h2>11. Contact Information</h2>
                    <p>For questions about these terms:</p>
                    <ul>
                        <li><strong>Email:</strong> legal@uptrendlms.com</li>
                        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                        <li><strong>Address:</strong> 123 Business St, Suite 100</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>