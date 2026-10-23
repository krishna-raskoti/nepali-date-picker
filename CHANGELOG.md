# Changelog

All notable changes to `nepali-date-picker` will be documented in this file, following [Keep a Changelog](https://keepachangelog.com/en/1.1.0/) and [Semantic Versioning](https://semver.org/).

## [1.0.0] - 2026-10-23

### Added
- Bikram Sambat (BS) ↔ Gregorian (AD) date conversion, strict and immutable (`BsDate`, `AdDate`, `NepaliDateConverter`).
- Fluent formatting API (`format()`) with English/Nepali locales, Nepali numerals, and full/short/extra-short weekday and month name forms.
- BS date parsing (`NepaliDateParser`), accepting `YYYY-MM-DD`/`DD-MM-YYYY` order, `-`/`/`/`.` separators, and Devanagari-numeral input.
- Laravel integration: `NepaliDate` facade, service provider, publishable config (`default_locale`, `default_format`, `timezone`).
- Supported BS range: 2000-01-01 to 2100-12-31 (AD 1943-04-14 to 2044-04-12).