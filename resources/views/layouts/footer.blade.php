<footer class="mt-12 bg-gradient-to-r from-gray-900 via-gray-800 to-gray-900 text-white">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Main Footer Content -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-8 py-12">
            <!-- About Section -->
            <div class="space-y-4">
                <h3 class="text-lg font-bold text-white">UPTREND LMS</h3>
                <p class="text-sm text-gray-400 leading-relaxed">
                    Modern loan management system built for efficiency and growth. Streamline your lending operations.
                </p>
                <div class="flex gap-3">
                    <button type="button" data-footer-title="Facebook"
                        data-footer-description="Follow UPTREND LMS on Facebook for product updates, announcements, and customer success stories."
                        class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                        aria-label="Learn more about Facebook">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M24 12.073c0-6.627-5.373-12-12-12s-12 5.373-12 12c0 5.99 4.388 10.954 10.125 11.854v-8.385H7.078v-3.47h3.047V9.43c0-3.007 1.792-4.669 4.533-4.669 1.312 0 2.686.235 2.686.235v2.953H15.83c-1.491 0-1.956.925-1.956 1.874v2.25h3.328l-.532 3.47h-2.796v8.385C19.612 23.027 24 18.062 24 12.073z"/></svg>
                    </button>
                    <button type="button" data-footer-title="Twitter"
                        data-footer-description="Get short-form product updates, service notices, and system tips from the UPTREND LMS team."
                        class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                        aria-label="Learn more about Twitter">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M23.953 4.57a10 10 0 01-2.825.775 4.958 4.958 0 002.163-2.723c-.951.555-2.005.959-3.127 1.184a4.92 4.92 0 00-8.384 4.482C7.69 8.095 4.067 6.13 1.64 3.162a4.822 4.822 0 00-.666 2.475c0 1.71.87 3.213 2.188 4.096a4.904 4.904 0 01-2.228-.616v.06a4.923 4.923 0 003.946 4.827 4.996 4.996 0 01-2.212.085 4.936 4.936 0 004.604 3.417 9.867 9.867 0 01-6.102 2.105c-.39 0-.779-.023-1.17-.067a13.995 13.995 0 007.557 2.209c9.053 0 13.998-7.496 13.998-13.985 0-.21 0-.42-.015-.63A9.935 9.935 0 0024 4.59z"/></svg>
                    </button>
                    <button type="button" data-footer-title="LinkedIn"
                        data-footer-description="See professional updates, company milestones, and partnership announcements on LinkedIn."
                        class="h-9 w-9 rounded-lg bg-white/10 hover:bg-white/20 flex items-center justify-center transition"
                        aria-label="Learn more about LinkedIn">
                        <svg class="h-4 w-4" fill="currentColor" viewBox="0 0 24 24"><path d="M20.447 20.452h-3.554v-5.569c0-1.328-.027-3.037-1.852-3.037-1.853 0-2.136 1.445-2.136 2.939v5.667H9.351V9h3.414v1.561h.046c.477-.9 1.637-1.85 3.37-1.85 3.601 0 4.267 2.37 4.267 5.455v6.286zM5.337 7.433c-1.144 0-2.063-.926-2.063-2.065 0-1.138.92-2.063 2.063-2.063 1.14 0 2.064.925 2.064 2.063 0 1.139-.925 2.065-2.064 2.065zm1.782 13.019H3.555V9h3.564v11.452zM22.225 0H1.771C.792 0 0 .774 0 1.729v20.542C0 23.227.792 24 1.771 24h20.451C23.2 24 24 23.227 24 22.271V1.729C24 .774 23.2 0 22.222 0h.003z"/></svg>
                    </button>
                </div>
            </div>

            <!-- Quick Links -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Quick Links</h4>
                <ul class="space-y-2">
                    <li>
                        <a href="{{ route('dashboard') }}" data-footer-title="Dashboard"
                            data-footer-description="Open the dashboard to review your portfolio summary, recent activity, and outstanding items."
                            data-footer-href="{{ route('dashboard') }}"
                            class="text-sm text-gray-400 hover:text-white transition">Dashboard</a>
                    </li>
                    <li>
                        <a href="{{ route('loans.index') }}" data-footer-title="Loans"
                            data-footer-description="Browse all loan records, repayment progress, and current loan statuses in one place."
                            data-footer-href="{{ route('loans.index') }}"
                            class="text-sm text-gray-400 hover:text-white transition">Loans</a>
                    </li>
                    <li>
                        <a href="{{ route('applications.index') }}" data-footer-title="Applications"
                            data-footer-description="Review applications that are pending, approved, rejected, or ready for follow-up."
                            data-footer-href="{{ route('applications.index') }}"
                            class="text-sm text-gray-400 hover:text-white transition">Applications</a>
                    </li>
                    <li>
                        <a href="{{ route('borrowers.index') }}" data-footer-title="Borrowers"
                            data-footer-description="View customer profiles, contact details, and borrower history for your business."
                            data-footer-href="{{ route('borrowers.index') }}"
                            class="text-sm text-gray-400 hover:text-white transition">Borrowers</a>
                    </li>
                    <li>
                        <a href="{{ route('repayments.index') }}" data-footer-title="Repayments"
                            data-footer-description="Monitor repayment schedules, received payments, and overdue balances."
                            data-footer-href="{{ route('repayments.index') }}"
                            class="text-sm text-gray-400 hover:text-white transition">Repayments</a>
                    </li>
                </ul>
            </div>

          
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Resources</h4>
                <ul class="space-y-2">
                    <li>
                        <button type="button" data-footer-title="Documentation"
                            data-footer-description="Read setup steps, workflows, and role-specific guidance for using UPTREND LMS."
                            class="text-sm text-gray-400 hover:text-white transition text-left">Documentation</button>
                    </li>
                    <li>
                        <button type="button" data-footer-title="API Reference"
                            data-footer-description="Explore API endpoints and integration details for custom workflows and data syncing."
                            class="text-sm text-gray-400 hover:text-white transition text-left">API Reference</button>
                    </li>
                    <li>
                        <button type="button" data-footer-title="Support Center"
                            data-footer-description="Get help with account issues, product questions, and technical troubleshooting."
                            class="text-sm text-gray-400 hover:text-white transition text-left">Support Center</button>
                    </li>
                    <li>
                        <button type="button" data-footer-title="Video Tutorials"
                            data-footer-description="Watch step-by-step walkthroughs for common loan management tasks and reports."
                            class="text-sm text-gray-400 hover:text-white transition text-left">Video Tutorials</button>
                    </li>
                    <li>
                        <button type="button" data-footer-title="Community Forum"
                            data-footer-description="Connect with other users to exchange ideas, tips, and implementation advice."
                            class="text-sm text-gray-400 hover:text-white transition text-left">Community Forum</button>
                    </li>
                </ul>
            </div>

            <!-- Contact Info -->
            <div>
                <h4 class="text-sm font-semibold uppercase tracking-wider text-gray-300 mb-4">Contact Us</h4>
                <ul class="space-y-3">
                    <li>
                        <button type="button" class="flex items-start gap-2 text-sm text-gray-400 text-left hover:text-white transition"
                            data-footer-title="Support Email"
                            data-footer-description="Use this email for account help, product questions, and support requests from the UPTREND LMS team."
                            data-footer-href="mailto:support@uptrendlms.com">
                        <svg class="h-5 w-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span>support@uptrendlms.com</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-start gap-2 text-sm text-gray-400 text-left hover:text-white transition"
                            data-footer-title="Phone Number"
                            data-footer-description="Call this number during business hours for support, onboarding, and service requests."
                            data-footer-href="tel:+15551234567">
                        <svg class="h-5 w-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span>+256 742 954755/+256 781 835522/+256 761 465767</span>
                        </button>
                    </li>
                    <li>
                        <button type="button" class="flex items-start gap-2 text-sm text-gray-400 text-left hover:text-white transition"
                            data-footer-title="Office Address"
                            data-footer-description="Visit our business office for formal correspondence or scheduled support visits."
                            data-footer-href="#">
                        <svg class="h-5 w-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>COCIS, Block B.</span>
                        </button>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Bottom Bar -->
        <div class="border-t border-gray-700 py-6">
            <div class="flex flex-col md:flex-row items-center justify-between gap-4">
                <div class="flex flex-col sm:flex-row items-center gap-4 text-sm text-gray-400">
                    <p>&copy; {{ date('Y') }} UPTREND LMS. All rights reserved.</p>
                    <div class="flex gap-4">
                        <a href="{{ route('privacy-policy') }}" data-footer-title="Privacy Policy"
                            data-footer-description="Learn how we protect your personal information and financial data in compliance with privacy regulations."
                            data-footer-href="{{ route('privacy-policy') }}"
                            class="hover:text-white transition">Privacy Policy</a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('terms-of-service') }}" data-footer-title="Terms of Service"
                            data-footer-description="Review the terms and conditions that govern your use of UPTREND LMS services."
                            data-footer-href="{{ route('terms-of-service') }}"
                            class="hover:text-white transition">Terms of Service</a>
                        <span class="text-gray-600">|</span>
                        <a href="{{ route('cookie-policy') }}" data-footer-title="Cookie Policy"
                            data-footer-description="Understand how we use cookies to improve your experience and protect your privacy."
                            data-footer-href="{{ route('cookie-policy') }}"
                            class="hover:text-white transition">Cookie Policy</a>
                    </div>
                </div>
                <div class="flex items-center gap-2">
                    <span class="text-xs text-gray-500">Powered by</span>
                    <span class="text-sm font-semibold text-transparent bg-clip-text bg-gradient-to-r from-blue-400 to-purple-400">Laravel Framework</span>
                </div>
            </div>
        </div>

        <div id="footer-info-modal" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/60 px-4 py-6">
            <div class="w-full max-w-lg rounded-2xl border border-white/10 bg-slate-900 text-white shadow-2xl">
                <div class="flex items-start justify-between gap-4 border-b border-white/10 px-6 py-5">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.2em] text-primary-300">Footer Details</p>
                        <h3 id="footer-info-title" class="mt-2 text-2xl font-bold">Information</h3>
                    </div>
                    <button type="button" id="footer-info-close" class="rounded-full bg-white/10 px-3 py-1 text-sm font-semibold text-gray-200 hover:bg-white/20">
                        Close
                    </button>
                </div>
                <div class="px-6 py-5">
                    <p id="footer-info-body" class="text-sm leading-6 text-gray-300"></p>
                </div>
                <div class="flex flex-col gap-3 border-t border-white/10 px-6 py-5 sm:flex-row sm:justify-end">
                    <a id="footer-info-action" href="#" class="hidden rounded-xl bg-gradient-to-r from-primary-500 to-primary-600 px-5 py-3 text-sm font-semibold text-white transition hover:from-primary-600 hover:to-primary-700">Open item</a>
                    <button type="button" id="footer-info-dismiss" class="rounded-xl border border-white/10 bg-white/5 px-5 py-3 text-sm font-semibold text-gray-200 transition hover:bg-white/10">
                        Got it
                    </button>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const modal = document.getElementById('footer-info-modal');
                if (!modal) {
                    return;
                }

                const title = document.getElementById('footer-info-title');
                const body = document.getElementById('footer-info-body');
                const action = document.getElementById('footer-info-action');
                const closeButtons = [
                    document.getElementById('footer-info-close'),
                    document.getElementById('footer-info-dismiss'),
                ];

                function hideModal() {
                    modal.classList.add('hidden');
                    modal.classList.remove('flex');
                }

                function showModal(trigger) {
                    title.textContent = trigger.getAttribute('data-footer-title') || 'Information';
                    body.textContent = trigger.getAttribute('data-footer-description') || 'More details are not available right now.';

                    const href = trigger.getAttribute('data-footer-href');
                    if (href) {
                        action.href = href;
                        action.classList.remove('hidden');
                    } else {
                        action.classList.add('hidden');
                    }

                    modal.classList.remove('hidden');
                    modal.classList.add('flex');
                }

                document.addEventListener('click', function (event) {
                    const trigger = event.target.closest('[data-footer-title]');
                    if (trigger) {
                        event.preventDefault();
                        showModal(trigger);
                        return;
                    }

                    if (event.target === modal) {
                        hideModal();
                    }
                });

                closeButtons.forEach(function (button) {
                    if (button) {
                        button.addEventListener('click', hideModal);
                    }
                });

                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        hideModal();
                    }
                });
            });
        </script>
    </div>
</footer>
