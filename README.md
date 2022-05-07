<div>
    <h3 align="center">To-do App</h3>
        <p align="center">
            A basic website to list your tasks.
            <br />
        </p>
</div>

### Built With

- [Vue.js](https://vuejs.org/)
- [Axios](https://github.com/axios/axios)
- [Bootstrap](https://getbootstrap.com)

### Installation

1. Clone the repo

   ```sh
   git clone -b <branch> <remote_repo>

   example: git clone -b dev https://github.com/username/project.git
   ```

2. Install NPM packages
   ```sh
   npm install
   ```
3. Setup your PHP backend or use Apache
   ```sh
   php -S localhost:portNumber (e.g. 3000)
   ```
4. Enter your PHP server URL in `HomePage.vue`
   ```js
   const PHP_SERVER_URL = "ENTER YOUR URL";
   ```
5. Configure database configuration in `db_tasks.php`
   ```php
   $db = new MysqliDb("localhost", "username", "password", "database");
   $table = "table_name";
   ```
6. Run Node server with vue-cli.
   ```sh
   npm run serve
   ```
