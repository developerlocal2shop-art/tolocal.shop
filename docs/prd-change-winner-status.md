# Product Requirements Document

## Title
Enable one-way "Winner Status" update for user prize vouchers

## Purpose
Allow authenticated users to mark a prize voucher as "Used" through the existing user prizes flow, while preventing the voucher status from being reverted back to "To Use" once it has been set to used.

## Context
The `resources/views/account/userprizes.blade.php` view displays user prize records, including voucher codes and a toggle control for marking a prize as used. The existing AJAX request points to `/admin/changeWinnerStatus`.

## Requirements
- Add a backend route that accepts status-change requests at `/admin/changeWinnerStatus`.
- Implement a controller action to process requests from authenticated users only.
- The action must update the `status` field on the corresponding `CompetitionWinners` record when transitioning from active (`1`) to used (`0`).
- If the voucher is already used (`status == 0`), the endpoint must reject any request to change it back to active (`1`).
- The action should verify the logged-in user owns the prize record (`customer_id == auth()->id()`).

## Acceptance Criteria
- `GET /admin/changeWinnerStatus?id={id}&status=0` updates the prize record if the record belongs to the current user and is not already used.
- `GET /admin/changeWinnerStatus?id={id}&status=1` is rejected when the prize record is already marked as used.
- The response returns JSON that indicates success or failure.
- The backend route exists without modifying the current view or JavaScript code.

## Constraints
- No change should allow a used prize to become active again.
- The endpoint should reuse the existing AJAX URL used by the current Blade template.
- The route should be placed within the Laravel web routing configuration.

## Notes
This document is a requirement definition only. No implementation changes are included in this PRD.
