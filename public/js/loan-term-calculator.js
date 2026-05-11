// Loan Term Calculator for real-time preview
class LoanTermCalculator {
    constructor(loanAmount) {
        this.loanAmount = loanAmount;
        this.setupEventListeners();
    }
    
    setupEventListeners() {
        const frequencySelect = document.querySelector('select[name="payment_frequency"]');
        const dueDateInput = document.querySelector('input[name="first_due_date"]');
        
        if (frequencySelect && dueDateInput) {
            frequencySelect.addEventListener('change', () => this.updatePreview());
            dueDateInput.addEventListener('change', () => this.updatePreview());
        }
    }
    
    updatePreview() {
        const frequency = document.querySelector('select[name="payment_frequency"]').value;
        const dueDate = document.querySelector('input[name="first_due_date"]').value;
        
        if (!frequency || !dueDate) return;
        
        const terms = this.calculateTerms(frequency, dueDate);
        this.displayPreview(terms);
    }
    
    calculateTerms(frequency, dueDateStr) {
        const dueDate = new Date(dueDateStr);
        const today = new Date();
        const diffInDays = Math.ceil((dueDate - today) / (1000 * 60 * 60 * 24));
        
        let termMonths, installmentCount;
        
        switch(frequency) {
            case 'weekly':
                termMonths = Math.max(1, Math.round(diffInDays / 7 / 4.33));
                installmentCount = termMonths * 4;
                break;
            case 'bi_weekly':
                termMonths = Math.max(1, Math.round(diffInDays / 14 / 2.17));
                installmentCount = termMonths * 2;
                break;
            case 'monthly':
                termMonths = Math.max(1, Math.round(diffInDays / 30));
                installmentCount = termMonths;
                break;
            case 'quarterly':
                termMonths = Math.max(1, Math.round(diffInDays / 90));
                installmentCount = Math.max(1, Math.round(termMonths / 3));
                break;
            default:
                termMonths = 12;
                installmentCount = 12;
        }
        
        const installmentAmount = this.loanAmount / installmentCount;
        const finalDueDate = this.calculateFinalDueDate(dueDate, frequency, installmentCount);
        
        return {
            termMonths,
            installmentCount,
            installmentAmount: Math.round(installmentAmount * 100) / 100,
            finalDueDate
        };
    }
    
    calculateFinalDueDate(firstDueDate, frequency, installmentCount) {
        const finalDate = new Date(firstDueDate);
        
        for (let i = 1; i < installmentCount; i++) {
            switch(frequency) {
                case 'weekly':
                    finalDate.setDate(finalDate.getDate() + 7);
                    break;
                case 'bi_weekly':
                    finalDate.setDate(finalDate.getDate() + 14);
                    break;
                case 'monthly':
                    finalDate.setMonth(finalDate.getMonth() + 1);
                    break;
                case 'quarterly':
                    finalDate.setMonth(finalDate.getMonth() + 3);
                    break;
            }
        }
        
        return finalDate;
    }
    
    displayPreview(terms) {
        let previewDiv = document.getElementById('terms-preview');
        
        if (!previewDiv) {
            previewDiv = document.createElement('div');
            previewDiv.id = 'terms-preview';
            previewDiv.className = 'mt-4 p-4 bg-blue-50 border border-blue-200 rounded-lg';
            
            const form = document.querySelector('form[action*="update-terms"]');
            if (form) {
                form.appendChild(previewDiv);
            }
        }
        
        const formatter = new Intl.NumberFormat('en-UG', {
            style: 'currency',
            currency: 'UGX',
            minimumFractionDigits: 0
        });
        
        previewDiv.innerHTML = `
            <h5 class="font-semibold text-blue-800 mb-3">📊 Preview of New Terms</h5>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-3 text-sm">
                <div class="bg-white p-3 rounded border">
                    <div class="text-blue-600 font-medium">Term Duration</div>
                    <div class="text-lg font-bold text-blue-900">${terms.termMonths} months</div>
                </div>
                <div class="bg-white p-3 rounded border">
                    <div class="text-green-600 font-medium">Installments</div>
                    <div class="text-lg font-bold text-green-900">${terms.installmentCount}</div>
                </div>
                <div class="bg-white p-3 rounded border">
                    <div class="text-purple-600 font-medium">Per Installment</div>
                    <div class="text-lg font-bold text-purple-900">${formatter.format(terms.installmentAmount)}</div>
                </div>
                <div class="bg-white p-3 rounded border">
                    <div class="text-orange-600 font-medium">Final Due</div>
                    <div class="text-lg font-bold text-orange-900">${terms.finalDueDate.toLocaleDateString()}</div>
                </div>
            </div>
            <div class="mt-3 text-xs text-blue-700">
                💡 These calculations are estimates. Final terms will be confirmed after submission.
            </div>
        `;
    }
}

// Initialize calculator when page loads
document.addEventListener('DOMContentLoaded', function() {
    const loanAmountElement = document.querySelector('[data-loan-amount]');
    if (loanAmountElement) {
        const loanAmount = parseFloat(loanAmountElement.dataset.loanAmount);
        new LoanTermCalculator(loanAmount);
    }
});