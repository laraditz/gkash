# Changelog

All notable changes to `laraditz/gkash` will be documented in this file

## 1.0.3 - 2023-01-05

### Added
- Add `merchant_id` column to `gkash_payments` table.
- Add `log_request` config to log http request for easier debugging.

## Change
- Fix bug on `amount` on signature generation.

## 1.0.3 - 2023-01-05

### Changed
- Update live URL and payment route.

## 1.0.2 - 2022-12-23

### Changed
- Update callback.

## 1.0.1 - 2022-12-19

### Added
- Add `vendor_ref_no` column to `gkash_payments` table.

## 1.0.0 - 2022-12-19

- initial release

### Added
- Add `createPayment` method.
- Add `BackendReceived` event.
