# Dynamic Token Manager for Drupal 10

The Dynamic Token Manager module provides a base framework for creating and managing dynamic tokens in Drupal. It offers a JavaScript foundation that other modules can extend to implement dynamic, time-based token functionality.

## Note!
This is only a manager module.  It is only useful once you install a [dynamic token plugin](#available-dynamic-tokens) for it to manage.

## History

During a technical interview about Drupal services and design patterns, I realized that my answer to a question was also a good way to get some hard coded javascript out of my personal website's [Profile](https://www.JasonMcEachen.com/profile) page.  A little refresher reading, rolling out a quick development scaffold, GPT coding with a handfull of fixes and improvements, and it was done.  I figured I would share it in case any of the design or architecture is useful to others.

## Table of contents

- Features
- Requirements
- Installation
- Configuration
- Available Dynamic Tokens
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
                "version": "^1.0",
                "type": "drupal-module",
                "source": {
                    "url": "https://github.com/jasonmce/dynamic_token_manager.git",
                    "type": "git",
                    "reference": "main"
                }
            }
        },
```

2. Install the module using Composer:

```bash
composer require jasonmce/dynamic_token_manager:^1.0
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
