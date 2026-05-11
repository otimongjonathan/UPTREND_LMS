@extends('customer.layouts.app')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-primary-500 to-primary-600 px-8 py-6">
            <h1 class="text-3xl font-bold text-white">Cookie Policy</h1>
            <p class="text-primary-100 mt-2">Last updated: {{ date('F j, Y') }}</p>
        </div>

        <div class="px-8 py-8 prose prose-lg max-w-none">
            <h2>1. What Are Cookies?</h2>
            <p>Cookies are small files stored on your device when you visit our website. They help us remember your preferences and provide a better experience when you use our customer portal.</p>

            <h2>2. Why We Use Cookies</h2>
            <p>We use cookies to:</p>
            <ul>
                <li>Keep you logged in to your account</li>
                <li>Remember your preferences and settings</li>
                <li>Protect your account from unauthorized access</li>
                <li>Understand how you use our website to improve it</li>
                <li>Provide personalized content and features</li>
            </ul>

            <h2>3. Types of Cookies We Use</h2>
            
            <div class="bg-blue-50 p-6 rounded-lg border border-blue-200 mb-6">
                <h3 class="text-blue-800 font-semibold mb-3">Essential Cookies (Always Active)</h3>
                <p class="text-blue-700">These cookies are necessary for our website to work properly:</p>
                <ul class="text-blue-700 mt-2">
                    <li><strong>Login Sessions:</strong> Keep you logged in while you browse</li>
                    <li><strong>Security:</strong> Protect against unauthorized access</li>
                    <li><strong>Form Data:</strong> Remember information you've entered</li>
                </ul>
            </div>

            <div class="bg-green-50 p-6 rounded-lg border border-green-200 mb-6">
                <h3 class="text-green-800 font-semibold mb-3">Preference Cookies</h3>
                <p class="text-green-700">These cookies remember your choices:</p>
                <ul class="text-green-700 mt-2">
                    <li><strong>Language Settings:</strong> Your preferred language</li>
                    <li><strong>Display Options:</strong> How you like information displayed</li>
                    <li><strong>Notification Preferences:</strong> Your communication choices</li>
                </ul>
            </div>

            <div class="bg-yellow-50 p-6 rounded-lg border border-yellow-200 mb-6">
                <h3 class="text-yellow-800 font-semibold mb-3">Analytics Cookies</h3>
                <p class="text-yellow-700">These help us improve our service:</p>
                <ul class="text-yellow-700 mt-2">
                    <li><strong>Usage Statistics:</strong> Which pages are most helpful</li>
                    <li><strong>Performance Monitoring:</strong> How fast pages load</li>
                    <li><strong>Error Tracking:</strong> Technical issues to fix</li>
                </ul>
            </div>

            <h2>4. Managing Your Cookie Preferences</h2>
            
            <h3>Browser Settings</h3>
            <p>You can control cookies through your browser:</p>
            <ul>
                <li><strong>Chrome:</strong> Settings → Privacy and Security → Cookies</li>
                <li><strong>Firefox:</strong> Options → Privacy & Security → Cookies</li>
                <li><strong>Safari:</strong> Preferences → Privacy → Cookies</li>
                <li><strong>Edge:</strong> Settings → Cookies and Site Permissions</li>
            </ul>

            <h3>What Happens If You Disable Cookies?</h3>
            <div class="bg-red-50 p-6 rounded-lg border border-red-200">
                <p class="text-red-700">Disabling cookies may affect your experience:</p>
                <ul class="text-red-700 mt-2">
                    <li>You may need to log in more frequently</li>
                    <li>Your preferences won't be remembered</li>
                    <li>Some features may not work properly</li>
                    <li>We can't provide personalized content</li>
                </ul>
            </div>

            <h2>5. Third-Party Cookies</h2>
            <p>We may use trusted third-party services that set their own cookies:</p>
            <ul>
                <li><strong>Payment Processors:</strong> For secure payment handling</li>
                <li><strong>Customer Support:</strong> For chat and help features</li>
                <li><strong>Analytics Services:</strong> To understand website usage</li>
            </ul>

            <h2>6. Cookie Details</h2>
            <div class="overflow-x-auto">
                <table class="min-w-full border border-gray-300 text-sm">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="border border-gray-300 px-4 py-2 text-left">Cookie Name</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Purpose</th>
                            <th class="border border-gray-300 px-4 py-2 text-left">Duration</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">laravel_session</td>
                            <td class="border border-gray-300 px-4 py-2">Keeps you logged in</td>
                            <td class="border border-gray-300 px-4 py-2">Until you close browser</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">XSRF-TOKEN</td>
                            <td class="border border-gray-300 px-4 py-2">Security protection</td>
                            <td class="border border-gray-300 px-4 py-2">Until you close browser</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">remember_me</td>
                            <td class="border border-gray-300 px-4 py-2">Remember login choice</td>
                            <td class="border border-gray-300 px-4 py-2">30 days</td>
                        </tr>
                        <tr>
                            <td class="border border-gray-300 px-4 py-2">preferences</td>
                            <td class="border border-gray-300 px-4 py-2">Your settings</td>
                            <td class="border border-gray-300 px-4 py-2">1 year</td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <h2>7. Updates to This Policy</h2>
            <p>We may update this cookie policy from time to time. We'll let you know about important changes through your customer portal or by email.</p>

            <h2>8. Questions About Cookies?</h2>
            <p>We're happy to help explain our cookie usage:</p>
            <div class="bg-primary-50 p-6 rounded-lg border border-primary-200">
                <ul class="space-y-2">
                    <li><strong>Email:</strong> privacy@uptrendlms.com</li>
                    <li><strong>Phone:</strong> +1 (555) 123-4567</li>
                    <li><strong>Customer Portal:</strong> Use the help section in your account</li>
                    <li><strong>Hours:</strong> Monday-Friday, 9 AM - 5 PM</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@endsection