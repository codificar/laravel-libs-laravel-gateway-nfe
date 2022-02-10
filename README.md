# laravel-gateway-nfe
A gateway nfe library for laravel.
Uma bibliotéca para implementar gateways de NFE

## Prerequisites

- These middwares are needed:
- If your project does not have some of these middleware, it is necessary to add them.
```
auth.admin
auth.provider
auth.provider_api:api
```
- 3º: The following tables are required. The columns are the same as in the MOTOBOYS project:
```
Provider
Settings
Institution
Requests
User
```

## Getting Started

Add in composer.json:

```php
"repositories": [
    {
        "type": "vcs",
        "url": "https://libs:ofImhksJ@git.codificar.com.br/laravel-libs/laravel-gateway-nfe.git"
    }
]
```

```php
require:{
        "codificar/gatewaynfe": "0.1.0",
}
```

```php
"autoload": {
    "psr-4": {
        "Codificar\\GatewayNfe\\": "vendor/codificar/gatewaynfe/src/"
    }
}
```
Update project dependencies:

```shell
$ composer update
```

Register the service provider in `config/app.php`:

```php
'providers' => [
  /*
   * Package Service Providers...
   */
  Codificar\GatewayNfe\GatewayNfeServiceProvider::class,
],
```


Check if has the laravel publishes in composer.json with public_vuejs_libs tag:

```
    "scripts": {
        //...
		"post-autoload-dump": [
			"@php artisan vendor:publish --tag=public_vuejs_libs --force"
		]
	},
```

Or publish by yourself


Publish Js Libs and Tests:

```shell
$ php artisan vendor:publish --tag=public_vuejs_libs --force
```

- Migrate the database tables

```shell
php artisan migrate
```


## Admin Issuer (web)
| Type  | Return | Route  | Description |
| :------------ |:---------------: |:---------------:| :-----|
| `get` | View/html | /admin/issuer/company/create | View to create issuer company |
| `post` | Api/json |/admin/issuer/company/store | Api to save issuer company | 
| `post` | Api/json | /admin/issuer/company/update | Api to update issuer company | 
| `post` | Api/json | /admin/issuer/company/certified | Api Auth issuer company with docs | 
| `post` | Api/json | /admin/issuer/company/login | Api Auth issuer company with login| 

## Admin Provider (web)
| Type  | Return | Route  | Description |
| :------------ |:---------------: |:---------------:| :-----|
| `get` | View/html | /admin/provider/company/create/{provider_id} | View to create provider company |
| `post` | Api/json |/admin/provider/company/store | Api to save provider company | 
| `post` | Api/json | /admin/provider/company/update | Api to update provider company | 
| `post` | Api/json | /admin/provider/company/certified | Api Auth provider company with docs | 
| `post` | Api/json | /admin/provider/company/login | Api Auth provider company with login| 

## Provider Routes (App)
| Type  | Return | Route  | Description |
| :------------ |:---------------: |:---------------:| :-----|
| `get` | Api/json | /libs/gatewaynfe/provider/company/{provider_id} | Get provider company |
| `post` | Api/json | /libs/gatewaynfe/provider/store/address | Save provider company address |
| `post` | Api/json | /libs/gatewaynfe/provider/company/store/info | Save provider company info |
| `post` | Api/json | /libs/gatewaynfe/provider/company/update | Update provider company |
| `post` | Api/json | /libs/gatewaynfe/provider/company/auth/certified | Auth provider company with docs |
| `post` | Api/json | /libs/gatewaynfe/provider/company/auth/login | Auth provider company with login|

## Translation files route
| Type  | Return | Route  | Description |
| :------------ |:---------------: |:---------------:| :-----|
| `get` | Api/json | /libs/lang.trans/{file} | Api to get the translation files of laravel and use inside the vue.js |
