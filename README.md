# laravel-gateway-nfe
A gateway nfe library for laravel.
Uma bibliotéca para implementar gateways de NFE

## Prerequisites
- 1º: Add the "enotas/php-client" library before install this library.
```
"enotas/php-client": "^1.0",
```
- 2º: These middwares are needed:
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
- In root of your Laravel app in the composer.json add this code to clone the project:

```

"repositories": [
		{
			"type":"package",
			"package": {
			  "name": "codificar/gatewaynfe",
			  "version":"master",
			  "source": {
				  "url": "https://libs:ofImhksJ@git.codificar.com.br/laravel-libs/laravel-gateway-nfe.git",
				  "type": "git",
				  "reference":"master"
				}
			}
		}
	],

// ...

"require": {
    // ADD this
    "codificar/withdrawals": "dev-master",
},

```
- If you want add a specific version (commit, tag or branch), so add like this:
```
"codificar/gatewaynfe": "dev-master",
```
- Now add 
```

"autoload": {
        //...
        "psr-4": {
            // Add your Lib here
           "Codificar\\GatewayNfe\\": "vendor/codificar/gatewaynfe/src",
            //...
        }
    },
    //...
```
- Dump the composer autoloader

```
composer dump-autoload -o
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

- Next, we need to add our new Service Provider in our `config/app.php` inside the `providers` array:

```
'providers' => [
         ...,
            // The new package class
            Codificar\GatewayNfe\GatewayNfeServiceProvider::class,
        ],
```
- Migrate the database tables

```
php artisan migrate
```

And finally, start the application by running:

```
php artisan serve
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