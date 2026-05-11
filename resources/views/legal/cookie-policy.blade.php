<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Cookie Policy') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
                <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
                    <h1 class="text-3xl font-bold text-white">Cookie Policy</h1>
                    <p class="text-primary-100 mt-2">Last updated: {{ date('F j, Y') }}</p>
                </div>

                <div class="px-8 py-8 prose prose-lg max-w-none">
                    <h2>1. What Are Cookies</h2>
                    <p>Cookies are small text files stored on your device when you visit our website. They help us provide a better user experience and analyze how our service is used.</p>

                    <h2>2. Types of Cookies We Use</h2>
                    
                    <h3>Essential Cookies</h3>
                    <p>These cookies are necessary for the website to function properly:</p>
                    <ul>
                        <li><strong>Session Cookies:</strong> Maintain your login session</li>
                        <li><strong>Security Cookies:</strong> Protect against cross-site request forgery</li>
                        <li><strong>Authentication Cookies:</strong> Remember your login status</li>
                    </ul>

                    <h3>Functional Cookies</h3>
                    <p>These cookies enhance your experience:</p>
                    <ul>
                        <li><strong>Preference Cookies:</strong> Remember your settings and preferences</li>
                        <li><strong>Language Cookies:</strong> Store your language selection</li>
                        <li><strong>Theme Cookies:</strong> Remember your display preferences</li>
                    </ul>

                    <h3>Analytics Cookies</h3>
                    <p>These cookies help us understand how you use our service:</p>
                    <ul>
                        <li><strong>Usage Analytics:</strong> Track page views and user interactions</li>
                        <li><strong>Performance Monitoring:</strong> Identify slow-loading pages</li>
                        <li><strong>Error Tracking:</strong> Monitor and fix technical issues</li>
                    </ul>

                    <h2>3. Third-Party Cookies</h2>
                    <p>We may use third-party services that set their own cookies:</p>
                    <ul>
                        <li><strong>Google Analytics:</strong> Website usage statistics</li>
                        <li><strong>Payment Processors:</strong> Secure payment processing</li>
                        <li><strong>Support Tools:</strong> Customer service chat widgets</li>
                    </ul>

                    <h2>4. Cookie Duration</h2>
                    <ul>
                        <li><strong>Session Cookies:</strong> Deleted when you close your browser</li>
                        <li><strong>Persistent Cookies:</strong> Remain for a specified period (typically 30 days to 2 years)</li>
                        <li><strong>Secure Cookies:</strong> Only transmitted over encrypted connections</li>
                    </ul>

                    <h2>5. Managing Cookies</h2>
                    <p>You can control cookies through:</p>
                    
                    <h3>Browser Settings</h3>
                    <ul>
                        <li><strong>Chrome:</strong> Settings > Privacy and Security > Cookies</li>
                        <li><strong>Firefox:</strong> Options > Privacy & Security > Cookies</li>
                        <li><strong>Safari:</strong> Preferences > Privacy > Cookies</li>
                        <li><strong>Edge:</strong> Settings > Cookies and Site Permissions</li>
                    </ul>

                    <h3>Cookie Preferences</h3>
                    <p>You can manage your cookie preferences through our cookie consent banner or by contacting us directly.</p>

                    <h2>6. Impact of Disabling Cookies</h2>
                    <p>Disabling certain cookies may affect:</p>
                    <ul>
                        <li>Login functionality and session management</li>
                        <li>Personalized settings and preferences</li>
                        <li>Website performance and user experience</li>
                        <li>Analytics and improvement capabilities</li>
                    </ul>

                    <h2>7. Cookie List</h2>
                    <table class="min-w-full border border-gray-300">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="border border-gray-300 px-4 py-2">Cookie Name</th>
                                <th class="border border-gray-300 px-4 py-2">Purpose</th>
                                <th class="border border-gray-300 px-4 py-2">Duration</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">laravel_session</td>
                                <td class="border border-gray-300 px-4 py-2">Maintains user session</td>
                                <td class="border border-gray-300 px-4 py-2">Session</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">XSRF-TOKEN</td>
                                <td class="border border-gray-300 px-4 py-2">Security protection</td>
                                <td class="border border-gray-300 px-4 py-2">Session</td>
                            </tr>
                            <tr>
                                <td class="border border-gray-300 px-4 py-2">remember_token</td>
                                <td class="border border-gray-300 px-4 py-2">Remember login</td>
                                <td class="border border-gray-300 px-4 py-2">30 days</td>
                            </tr>
                        </tbody>
                    </table>

                    <h2>8. Updates to This Policy</h2>
                    <p>We may update this Cookie Policy to reflect changes in our practices or for legal reasons. We will notify you of significant changes.</p>

                    <h2>9. Contact Us</h2>
                    <p>For questions about our cookie usage:</p>
                    <ul>
                        <li><strong>Email:</strong> privacy@uptrendlms.com</li>
                        <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                        <li><strong>Address:</strong> 123 Business St, Suite 100</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>