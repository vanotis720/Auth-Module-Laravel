# AuthModule-Laravel

AuthModule-Laravel is a starting point for a laravel application which must contain authentication by email and / or social networks with the possibility of resetting a forgotten password.

## Setup

clone this repository via [git ](https://git-scm.com/) to begin.

```bash
gh repo clone vanotis720/Auth-Module-Laravel
```
and using [composer](https://getcomposer.org/) install the necessary dependencies

```bash
composer install
```
think about making migrations with artisan

```bash
php artisan migrate
```

## Usage

The project is ready to be used on condition of:

* fill in the .env:

   - your mailtrap credentials for the password recovery test

  - for each of your social providers:

    client id
    client secret
    callback url

Once the dependencies are installed, you can run ```bash php artisan serve``` to start the application. You will then be able to access it at localhost:8000

## Features

  * Login and register with Social network
  * Register with username, email and password (avatar automatically generate)
  * Login with Email/Username and Password
  * Get User Profil
  * Remember user session
  * forgot password recovery

## Feedback

Feel free to send us [feedback](https://vanderotis.site/#contact) or file an issue. Feature requests are always welcome. If you wish to contribute, please contact me [here](https://vanderotis.site/#contact).


## License
> You can check out the full license [here](https://choosealicense.com/licenses/mit/)

This project is licensed under the terms of the MIT license.