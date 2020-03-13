# Phonebook App - Laravel 5 Repository Pattern, Vue.js and Vuex

Simple phonebook app that demonstrates usage of repository pattern in Laravel 5, vuex and vue.js.

- [Demo](https://phonebook.marionovak.online)
- [Screenshot - Frontend Preview](https://drive.google.com/file/d/1bShaUGYhD9kXhzsb3YH7qVDf2iYwBCEL/view?usp=sharing)
- [Postman collection, API Docs](https://documenter.getpostman.com/view/97483/phonebook/RW83PXHn)
- [Postman environment](/Phonebook.postman_environment.json)

## Flow

- Register and login with email
- Search Contacts
- Filter Contacts ( `all` / `favorite` )
- Add Contact
  - First name
  - Last name
  - Avatar
  - Email
  - Favorite [boolean]
  - Phones
    - Number (e.g. 023 3923 3939)
    - Label (e.g. Work)

## Stack and Packages

**API**

- MySQL

- [Laravel 5](https://github.com/laravel/framework)

- [l5-repository](https://github.com/andersao/l5-repository)

- [Passport](https://github.com/laravel/passport)

**Frontend**

- [Vue.js](https://github.com/vuejs/vue)

- [Vuex](https://github.com/vuejs/vuex)

## Seeders

Run `php artisan db:seed`

- **PassportSeeder**
  - Creates default Passport client for testing purposes
    - Client id: 1,
    - Client secret: `config('auth.POSTMAN_CLIENT_SECRET', 'PostmanNDC6Of0XbmHHTcZIi34PFuwbyTQegfyd0')`
- **UsersSeeder**
  - Creates default user
    - First name: `config('users.test.USER_FIRST_NAME', 'John')`,
    - Last name: `config('users.test.USER_LAST_NAME', 'Smith')`,
    - Email: `config('users.test.USER_EMAIL', 'test@phonebook.marionovak.online')`,
    - Password: `config('users.test.USER_PASSWORD', 'secret')`

## Tests

Run `./vendor/bin/phpunit`

## Envoy

**Run Tests**

`envoy run test --server=local`

**Deploy**

`envoy run deploy --server=['local', 'staging'] [--install] [--reset]`

### .env

`LOCAL_SERVER=vagrant@phonebook.test`

`STAGING_SERVER=vagrant@staging.test`

## Todo

- Project-wide
  - Docker as a submodule
- Frontend
  - Refactor
  - Responsive fixes
  - Vuex

## License

MIT
