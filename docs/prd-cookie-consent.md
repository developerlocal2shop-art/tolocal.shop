# Product Requirements Document

## Title
Centralized Cookie and Third-Party Service Consent

## Purpose
Give visitors a clear, withdrawable choice about optional cookies and third-party services before those services are initialized. Preserve essential application behavior and make Swedish the native default when no explicit language preference is available, without depending on Google Translate.

## Context
The current site has a legacy footer notice with only an Accept action, plus a separate “Access to page” modal. Third-party services are loaded from multiple shared footers, layouts, page templates, and authenticated forms rather than from one consent-aware controller but other website's function should not be break.



The audit identified Google Analytics, Google Maps/Places and geocoding, Google Translate, Facebook SDK, direct map and media embeds, IP-geolocation providers, remote editor/CDN assets, and payment-related scripts. No Meta Pixel was found in the scanned views. Existing state includes Laravel session/CSRF cookies, `countSiteOpens`, `googtrans`, browser location cookies, and a legacy localStorage acceptance flag. Their purpose and consent classification must be reviewed individually; first-party or Laravel-managed does not automatically mean essential.

The application currently defaults Laravel locale to English. Swedish translations already exist in `resources/lang/sv/message.php`, while the database-backed language helper defaults to language ID 1. Confirm an active Swedish language record exists before changing that default.

## Goals
- Present equally prominent Accept All, Reject Non-Essential, and Manage Preferences actions on first visit.
- Keep optional categories off until the user grants consent.
- Apply the same consent controls to public storefront, vendor/company.
- Let users reopen preferences and withdraw or change consent at any time.
- Keep Google Translate available after Functional consent, without loading it before consent.
- Render native Swedish by default when no explicit language preference exists, including when location is unavailable or denied.
- Avoid a database migration or server-side consent audit record.

## Requirements

### Consent experience
- Add one reusable consent component and one central consent controller used by applicable layouts.
- The first layer must offer Accept All, Reject Non-Essential, and Manage Preferences with comparable visual prominence and interaction effort.
- The preference view must display categories and their descriptions, keep necessary storage enabled where required, and default optional categories to off.
- Persist the selected categories, consent timestamp, and policy version in browser storage.
- Provide a Cookie Settings control in applicable footers that reopens preferences.
- Allow saving changed preferences and withdrawing optional consent. Do not require a page reload to make a selection take effect, although a reload may be used to reliably stop services already initialized.

### Provider loading and withdrawal
- Do not initialize optional providers, make their network requests, or render active third-party iframe sources before consent for the applicable category.
- Load Google Analytics only after Analytics consent. Existing analytics event calls must safely do nothing when the analytics library is unavailable.
- Load Google Maps, Places, geocoding, Google Translate, and related browser geolocation behavior only after Functional consent and any separate browser permission required by the browser.
- Load marketing services only after Marketing consent. The audit found a Facebook SDK; no Meta Pixel was found. Review embedded YouTube, Vimeo, and other third-party media by actual purpose before assigning a category.
- Review third-party editor, CDN, font, and payment assets by purpose and page context. Do not break required editing or checkout workflows; document any service that cannot reasonably be gated and the applicable basis for its treatment.
- On withdrawal, prevent future collection and loading, revoke analytics consent where supported, and remove provider cookies or client-side state where feasible. State clearly that data already transmitted to a provider cannot be recalled.
- Review server-side IP-geolocation calls and visitor-counting behavior as well as browser scripts. An entirely client-side banner is insufficient if server-side optional data collection continues before consent.
- Review session, CSRF, cart, authentication, language, and location state individually. Retain functionality needed by the requested service while avoiding a blanket classification of Laravel-managed cookies as necessary.

### Language and location
- Use native Laravel/database language support for the Swedish fallback; Google Translate must not be required for default Swedish content.
- When no explicit language choice is saved, resolve the default language by code `sv`, not by assuming a database ID. Preserve a user's explicit language choice.
- If no location is available, denied, or consent is not granted for the relevant location functionality, continue with Swedish as the language fallback and use the application's existing non-location defaults.
- Google Translate must initialize correctly after Functional consent, preserve the existing `googtrans` behavior where appropriate, and not load before consent.
- If an active Swedish database language row is missing, do not add a migration as part of this work; use native locale resources and document the missing operational data requirement.

### Privacy and classification
- Before implementation, maintain an inventory of cookies, storage, providers, and data flows, including server-side IP geolocation and browser location.
- Assign categories based on actual purpose and applicable requirements, not solely on whether a cookie is first-party, Laravel-managed, or technically required by current code.
- This PRD defines technical behavior and is not a legal determination. Product/legal owners must approve final provider and cookie classifications and policy text.

## Acceptance Criteria
- On a clean browser profile, optional providers are not initialized or requested before the user makes a choice; optional iframe elements do not have active third-party `src` values.
- Accept All enables all documented optional categories; Reject Non-Essential enables none; custom preferences enable only selected categories.
- The first layer presents Accept All, Reject Non-Essential, and Manage Preferences with comparable prominence.
- Consent persists across navigation and reloads. Cookie Settings reopens the current preferences on public, vendor/company.
- Changing or withdrawing a category prevents subsequent provider requests after the effective page transition/reload, clears relevant provider state where feasible, and does not break necessary application behavior.
- Google Analytics events do not throw errors when Analytics consent is absent.
- Google Maps, geocoding, and Google Translate work after Functional consent. Maps-dependent vendor forms remain usable through manual entry when Functional consent is off.
- With no saved language and no available location, native Swedish renders without loading Google Translate. A saved language choice continues to override the fallback.
- The implementation requires no database migration and creates no server-side consent audit record.

## Scope
- Shared public, vendor/company, layouts and footers.
- Shared and page-specific analytics, Maps/Places/geocoding, Translate, Facebook SDK, embedded map/media providers, geolocation, visitor counting, and relevant externally hosted assets.
- Browser preference persistence and preference withdrawal.
- Native Swedish fallback and preservation of explicit language selection.

## Out of Scope
- Adding a consent database table, migration, or auditable server-side consent history.
- Changing ordinary outbound social links that do not initialize third-party content on the page.
- Guaranteeing removal or recall of information already sent to a third party.
- Legal certification or final legal classification of every cookie/provider.

## Constraints
- Do not run or add a database migration for this work.
- Do not classify every Laravel/session cookie as essential without purpose review.
- Google Translate remains optional and must not be loaded before Functional consent.
- Keep essential application flows, including authentication and CSRF protection, operational.
- This document defines requirements only. No implementation changes are included in this PRD.

## Implementation References
- Consent and provider loading: `resources/views/components/client/footer.blade.php`, `resources/views/components/client/footervendor.blade.php`, `resources/views/components/client/footer_en.blade.php`, `resources/views/client/footer.blade.php`, `resources/views/client/footervendor.blade.php`, and `resources/views/includes/client/head.blade.php`.
- Layout integration: `resources/views/layouts/client/`
- Existing location and visitor tracking: `app/Http/Controllers/Client/Pages.php` and `app/Helpers/helpers.php`.
- Language behavior: `app/Http/Middleware/Language.php`, `app/Helpers/helpers.php`, `app/Http/Controllers/Client/Pages.php`, `config/app.php`, and `resources/lang/sv/message.php`.
- Proposed shared files: `resources/views/components/cookie-consent.blade.php` and `public/js/cookie-consent.js`.

## Verification
- Run focused PHPUnit tests and the root PHPUnit suite.
- In a clean browser profile, inspect Network and storage before consent and after each preference selection on public, vendor/company pages.
- Test reject, accept-all, custom preferences, reload persistence, settings reopening, and withdrawal.
- Verify Maps and Translate after Functional consent, manual form behavior without it, and analytics event safety when Analytics is off.
- Verify Swedish fallback without location or Google Translate, and verify an explicit language selection takes precedence.
