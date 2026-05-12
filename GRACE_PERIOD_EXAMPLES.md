# Grace Period & Installment Date Examples

## How It Works

### Rule
After the **2-month grace period**, installments begin on the **same day of the month** as the loan was disbursed, and continue on that same day each month.

---

## Example 1: Loan Disbursed on May 15, 2024

### Timeline
- **Disbursement Date**: May 15, 2024
- **Grace Period**: 2 months (May 15 - July 14)
- **Grace Period Ends**: July 14, 2024

### Monthly Installments (12 months)
| Installment | Due Date | Notes |
|------------|----------|-------|
| 1 | **July 15, 2024** | Same day (15th) as disbursement, 2 months later |
| 2 | **August 15, 2024** | Same day (15th) of next month |
| 3 | **September 15, 2024** | Same day (15th) of next month |
| 4 | **October 15, 2024** | Same day (15th) of next month |
| 5 | **November 15, 2024** | Same day (15th) of next month |
| 6 | **December 15, 2024** | Same day (15th) of next month |
| 7 | **January 15, 2025** | Same day (15th) of next month |
| 8 | **February 15, 2025** | Same day (15th) of next month |
| 9 | **March 15, 2025** | Same day (15th) of next month |
| 10 | **April 15, 2025** | Same day (15th) of next month |
| 11 | **May 15, 2025** | Same day (15th) of next month |
| 12 | **June 15, 2025** | Same day (15th) of next month |

---

## Example 2: Loan Disbursed on January 1, 2024

### Timeline
- **Disbursement Date**: January 1, 2024
- **Grace Period**: 2 months (January 1 - February 29)
- **Grace Period Ends**: February 29, 2024

### Monthly Installments (6 months)
| Installment | Due Date | Notes |
|------------|----------|-------|
| 1 | **March 1, 2024** | Same day (1st) as disbursement, 2 months later |
| 2 | **April 1, 2024** | Same day (1st) of next month |
| 3 | **May 1, 2024** | Same day (1st) of next month |
| 4 | **June 1, 2024** | Same day (1st) of next month |
| 5 | **July 1, 2024** | Same day (1st) of next month |
| 6 | **August 1, 2024** | Same day (1st) of next month |

---

## Example 3: Loan Disbursed on March 31, 2024

### Timeline
- **Disbursement Date**: March 31, 2024
- **Grace Period**: 2 months (March 31 - May 30)
- **Grace Period Ends**: May 30, 2024

### Monthly Installments (12 months)
| Installment | Due Date | Notes |
|------------|----------|-------|
| 1 | **May 31, 2024** | Same day (31st) as disbursement, 2 months later |
| 2 | **June 30, 2024** | June has only 30 days (last day of month) |
| 3 | **July 31, 2024** | Same day (31st) |
| 4 | **August 31, 2024** | Same day (31st) |
| 5 | **September 30, 2024** | September has only 30 days (last day of month) |
| 6 | **October 31, 2024** | Same day (31st) |
| 7 | **November 30, 2024** | November has only 30 days (last day of month) |
| 8 | **December 31, 2024** | Same day (31st) |
| 9 | **January 31, 2025** | Same day (31st) |
| 10 | **February 28, 2025** | February has only 28 days (last day of month) |
| 11 | **March 31, 2025** | Same day (31st) |
| 12 | **April 30, 2025** | April has only 30 days (last day of month) |

> **Note**: When the disbursement day doesn't exist in a month (e.g., 31st in February), Carbon automatically adjusts to the last day of that month.

---

## Weekly Installments Example

### Loan Disbursed on May 15, 2024 (Wednesday)

- **Grace Period**: 2 months (May 15 - July 14)
- **First Payment**: July 15, 2024 (Monday)

### Weekly Installments (8 weeks)
| Installment | Due Date | Day of Week |
|------------|----------|-------------|
| 1 | July 15, 2024 | Monday |
| 2 | July 22, 2024 | Monday |
| 3 | July 29, 2024 | Monday |
| 4 | August 5, 2024 | Monday |
| 5 | August 12, 2024 | Monday |
| 6 | August 19, 2024 | Monday |
| 7 | August 26, 2024 | Monday |
| 8 | September 2, 2024 | Monday |

---

## Bi-Weekly Installments Example

### Loan Disbursed on May 15, 2024

- **Grace Period**: 2 months (May 15 - July 14)
- **First Payment**: July 15, 2024

### Bi-Weekly Installments (6 payments)
| Installment | Due Date | Weeks After Previous |
|------------|----------|---------------------|
| 1 | July 15, 2024 | - |
| 2 | July 29, 2024 | 2 weeks |
| 3 | August 12, 2024 | 2 weeks |
| 4 | August 26, 2024 | 2 weeks |
| 5 | September 9, 2024 | 2 weeks |
| 6 | September 23, 2024 | 2 weeks |

---

## Quarterly Installments Example

### Loan Disbursed on May 15, 2024

- **Grace Period**: 2 months (May 15 - July 14)
- **First Payment**: July 15, 2024

### Quarterly Installments (4 payments)
| Installment | Due Date | Months After Previous |
|------------|----------|----------------------|
| 1 | July 15, 2024 | - |
| 2 | October 15, 2024 | 3 months |
| 3 | January 15, 2025 | 3 months |
| 4 | April 15, 2025 | 3 months |

---

## Key Points

✅ **Grace period is always 2 months** from disbursement date  
✅ **First installment** is due on the same day of the month as disbursement, but 2 months later  
✅ **Subsequent installments** continue on that same day each period (month/week/etc.)  
✅ **Day consistency** is maintained across all installments  
✅ **Month-end handling** - If disbursed on 31st, months with fewer days use the last day  

## Code Implementation

The system uses Carbon's `addMonths()`, `addWeeks()` methods which automatically:
- Maintain the same day of month for monthly payments
- Handle month-end edge cases (e.g., Jan 31 → Feb 28/29)
- Keep consistent intervals for weekly/bi-weekly payments
