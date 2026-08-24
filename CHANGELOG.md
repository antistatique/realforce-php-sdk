# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Added
- test(resource): assert verb, URL and timeout passed to makeRequest
- test(coverage): cover the __call resource-type guard

### Fixed
- fix(dependabot): remove config that silently disabled all version updates
- fix(ci): run workflows on pull_request so fork contributions get CI
- fix(php85): remove deprecated calls and fail the suite on new ones
- fix(resource): build endpoint URLs without a duplicated slash
- fix(curl): type cURL handles as CurlHandle and check curl_init failure
- fix(http): reject malformed URLs instead of failing with a TypeError
- fix(auth): send the X-API-KEY header for a falsy-but-valid token
- fix(http): treat an empty 2xx body as a success instead of a failure
- fix(request): raise a LogicException when the mandatory lang is missing
- fix(api): tighten getApiToken/getLastError return types and unmask __call errors
- fix(ci): report coverage to Coveralls from a single matrix leg

### Changed
- fix(composer): bound the php constraint (>=8.3 => ^8.3)
- fix(linter): raise psalm errorLevel (7 => 2)

### Removed
- chore(deps): drop unused phpmd/phpmd dev dependency

## [1.0.0] - 2026-08-24
### Added
- Under heavy development
- add support for Amenities Categories
- add support for Amenities Groups
- test(coverage): increase coverage by covering PropertiesListRequest::updatedBefore/updatedAfter
- test(coverage): increase coverage by covering RealforceClient::success
- test(coverage): increase coverage by covering RealforceClient::getLastError
- test(coverage): increase coverage by covering RealforceClient::getLastResponseHttpStatus
- test(coverage): increase coverage by covering LocationsRequest getters & setters
- feat(php): add official support for PHP 8.5

[Unreleased]: https://github.com/antistatique/realforce-php-sdk/compare/1.0.0...HEAD
[1.0.0]: https://github.com/antistatique/realforce-php-sdk/releases/tag/1.0.0
