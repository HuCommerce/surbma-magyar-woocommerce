# Requirements Gathering

## Codebase Analysis Summary

### Key Findings

| Category | Count | Notes |
|----------|-------|-------|
| **Total closures found** | ~195 | All anonymous `function()` instances |
| **Already static** | 4 | In `checkout.php` and `tax-number.php` |
| **Cannot be static** | 3 | Use outer scope via `use()` keyword |
| **Convertible** | ~188 | Safe to add `static` keyword |
| **Uses `$this`** | 0 | No class context issues |

### Detailed Breakdown of Non-Convertible Functions

These 3 functions **must remain non-static** because they use the `use()` keyword to capture outer scope variables:

1. `lib/pages.php:97` - filters pages by status parameter
2. `lib/pages.php:143` - returns callback using page_key parameter
3. `modules/translations.php:126` - filters translations using options array

## Initial Questions & Answers

### Q1: Should we convert ALL 188 eligible functions at once, or phased approach?
**Answer:** Phased approach (module by module) to ensure nothing is missed.

### Q2: Do you want automated testing/verification after the conversion?
**Answer:** Yes, automated testing required.

### Q3: What's the priority level for this change?
**Answer:** High priority - immediate implementation after spec and tasks are created.

### Q4: Any specific files or modules to EXCLUDE?
**Answer:** No exclusions - all files included.

### Q5: Should JavaScript closures be documented separately?
**Answer:** No - focusing on PHP only.

---

## Confirmed Scope

### Implementation Phases

| Phase | Directory | Est. Files | Est. Functions |
|-------|-----------|------------|----------------|
| 1 | `lib/` | 5 | ~25 |
| 2 | `modules/` | 18 | ~90 |
| 3 | `modules-hu/` | 7 | ~30 |
| 4 | `pages/` | 3 | ~5 |

### Testing Strategy
- Automated verification after each phase
- Check that all hooks/filters still fire correctly
- Validate no PHP errors or warnings introduced

### Exclusions
- JavaScript closures (out of scope)
- Functions using `use()` keyword (3 total - technically cannot be static)

---

