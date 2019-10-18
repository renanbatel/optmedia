# OptMedia

> In construction...

## Contributing

### Set Up

**Installation requirements:**
  - [Docker](https://docs.docker.com/install/)
  - [Docker Compose](https://docs.docker.com/compose/install/)
  - [Node.js](https://nodejs.org/en/) (Version 10.16.3 or later is recommended)
  - [PHP](https://www.php.net/manual/en/install.php) (Version 7.2 or later is recommended)
  - [Composer](https://getcomposer.org/download/)
  - [Yarn](https://yarnpkg.com/en/docs/install) (Optional)

**Installation**

  To start the plugin development first you need to install its dependencies, then build the project for development and initialize the docker containers. To do that you can run the following command on your terminal in the project root folder:

  `npm i && composer install -o && npm run build:dev && npm run docker:serve`

  or, if you have Yarn:

  `yarn && composer install -o && yarn build:dev && yarn docker:serve`

  It will take some time when you first run it, because you need to download and build a lot of things.
