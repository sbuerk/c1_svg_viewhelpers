# Changelog

## Unreleased

### Changed

- **Breaking:** Move settings from plugin.tx_c1svgviewhelpers.settings.svg to
  plugin.tx_c1svgviewhelpers.settings.svg. Please adapt your TypoScript config.
- Rework tests

### Added

- Experimental: Add preload header tag for faster loading of the symbols file.
  This is by default off and can be enabled in settings or with a viewhelper
  argument.
  It seems, the preloading works with this, but Chrome logs a warning:
  "was preloaded using link preload but not used within a few seconds"
  See: https://bugs.chromium.org/p/chromium/issues/detail?id=1065069

## 1.0.0 - 2022-08-14

- First release
