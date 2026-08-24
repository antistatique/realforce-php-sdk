# Changelog
All notable changes to this project will be documented in this file.

The format is based on [Keep a Changelog](https://keepachangelog.com/en/1.0.0/),
and this project adheres to [Semantic Versioning](https://semver.org/spec/v2.0.0.html).

## [Unreleased]
### Fixed
- fix(dependabot): remove config that silently disabled all version updates

### Changed
- fix(composer): bound the php constraint (>=8.3 => ^8.3)
- fix(ci): run workflows on pull_request so fork contributions get CI

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
