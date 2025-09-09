# Dynamic Token Manager for Drupal 10

The Dynamic Token Manager module provides a base framework for creating and managing dynamic tokens in Drupal. It offers a JavaScript foundation that other modules can extend to implement dynamic, time-based token functionality.

## Table of contents

- Features
- Requirements
- Installation
- Configuration
- Available
- Extending
- Maintainers

## Features

- Base JavaScript framework for dynamic token handling
- Interval-based token updates
- Lightweight and performant implementation
- Extensible architecture for custom token implementations

## Requirements

- Drupal 10.x
- Token module
- Filter module

## Installation

1. Add the package to your composer file as a repository:

```json
    "repositories": [
        {
            "type": "package",
            "package": {
                "name": "jasonmce/dynamic_token_manager",
                "version": "dev-main",
                "type": "drupal-module",
                "source": {
                    "url": "https://github.com/jasonmce/dynamic_token_manager.git",
                    "type": "git",
                    "reference": "dev-main"
                }
            }
        },
```

2. Install the module using Composer:

```bash
composer require jasonmce/dynamic_token_manager:dev-main
```

3. Enable the module through the Drupal admin interface or with Drush:

```bash
drush en dynamic_token_manager
```

## Configuration

### To Add Dynamic Tokens to a site:

1. Navigate to Administration > Configuration > Content Authoring > Dynamic Tokens.
2. To add a Dynamic Token select "Add dynamic token".
3. The label will be shown in administrative lists, and used to generate the token machine name.
4. The speed determines the displayed token refresh rate.
5. Select the Dynamic Token plugin you wish to use from the select pulldown.
6. Provide the values needed for the type of plugin you selected.
7. Save.

## Available Dynamic Tokens

- [Dynamic Text Token](https://github.com/jasonmce/Dynamic-text-token) - crossfades a list of text values


## Extending the Module

To create a custom dynamic token implementation:

1. Create a custom module that depends on `dynamic_token_manager`
2. Implement your token logic by extending the base behavior
3. Use the provided helper methods for interval management
4. Add your custom JavaScript to your module's library

## Maintainers

- Jason McEachen - https://github.com/jasonmce

## License

GPL-2.0-or-later
