# Selective Admission

## Access control

Access keys can be specified in `config.php`. Access will be denied if a valid key is not passed as a URL parameter. This is not a high-security system, but does allow for some additional protection against brute force attacks against this service. 

The key should be appended to the service URL associated with the `key` parameter.

Example: `https://example.com/selective-admission-ws/?key=KEYVALUE`

## Installation

This project uses the [AuthNetJson Library](https://github.com/stymiee/authnetjson) to query Authorize.Net and get a JSON response as a PHP object.

A dependency to the above library is already added to composer.json file. 

Composer must be installed on your development system, and you should run `composer install` before you start developing or deploying.

## Configuration

Please rename file config-sample.php to config.php and update the variables as explained.
