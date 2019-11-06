# OptMedia

> In construction...

## Contributing

### Set Up

**Installation requirements:**
  - [Docker](https://docs.docker.com/install/)
  - [Docker Compose](https://docs.docker.com/compose/install/)
  - [Node.js v12.13.0](https://nodejs.org/en/) (or just use [nvm](https://github.com/nvm-sh/nvm))
  - [PHP v7.2+](https://www.php.net/manual/en/install.php)
  - [Composer](https://getcomposer.org/download/)
  - [Yarn](https://yarnpkg.com/en/docs/install) (Optional)

**Installation**

  To start the plugin development you need to install its dependencies, then build the project for development and initialize the Docker containers. To do that you can run the following command on your terminal in the project's root folder:

  `npm i && composer install -o && npm run build:dev && npm run docker:up`

  or, if you have Yarn:

  `yarn && composer install -o && yarn build:dev && yarn docker:up`

  It will take some time when you first run it, because you need to download and build a lot of things. When the Docker containers are running you can access WordPress on [http://localhost:8000](http://localhost:8000) and phpMyAdmin on [http://localhost:8080](http://localhost:8080), the usernames and passwords are `wordpress` on both sites. A folder `tmp` will be created for your containers bind mounts, the database will persist in `tmp/db`, the WordPress/PHP debug log files will be in `tmp/logs`, and the WordPress upload files will be in `tmp/upload`, you can see more details in the `docker` files.

**Npm Scripts**

  There are some npm scripts available for the development process.

  | Script           | Description                                                                                                   |
  |------------------|---------------------------------------------------------------------------------------------------------------|
  | docker:up        | Initializes the Docker containers.                                                                            |
  | docker:down      | Stops the Docker containers and removes them.                                                                 |
  | docker:stop      | Stops the Docker containers.                                                                                  |
  | build            | Builds the project for release.                                                                               |
  | build:dev        | Builds the project in development mode.                                                                       |
  | build:prod       | Builds the project in production mode.                                                                        |
  | build:analyse    | Use [Jarvis](https://github.com/zouhir/jarvis) to analyse the production build.                               |
  | watch            | Builds the project in development mode on every file change.                                                  | 
  | lint:js          | Runs the JavaScript lint.                                                                                     |
  | lint:scss        | Runs the Sass lint.                                                                                           |
  | lint:php         | Runs the PHP lint.                                                                                            |
  | format:js        | Fixes the fixable JavaScript lint errors.                                                                     |
  | format:scss      | Fixes the fixable Sass lint errors.                                                                           |
  | format:php       | Fixes the fixable PHP lint errors.                                                                            |
  | test:js          | Runs the JavaScript tests.                                                                                    |
  | test:js:watch    | Runs the JavaScript tests on every .js file change.                                                           |
  | test:js:coverage | Runs the JavaScript tests and generates a coverage report (Available on `coverage` folder).                   |
  | test:php         | Runs the PHP tests inside the container and generates a coverage report (Available on `dist/tests/_reports`). |
  | test:php:all     | Runs the PHP tests inside the container (used by lint-staged).                                                |
  | i18n:makepot     | Generates the plugin's POT file in `src/wordpress/languages`.                                                 |

## Special Thanks

A special thanks to [natterstefan](https://github.com/natterstefan) and his [react-wordpress-plugin](https://github.com/natterstefan/react-wordpress-plugin) boilerplate, as this project structure is based on it.

## License

[MIT](https://github.com/renanbatel/optmedia/blob/master/LICENCE) © Renan Batel Rodrigues
