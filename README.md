# Team-report project

### Project setup:

1. Run `composer install` to install dependencies.
2. Create a database and set up the `.env` file.
3. Run the migrations using the `php artisan migrate` command.
4. Then start the server with the command `php artisan serve`

### Database seeder:

1. Run `php artisan db:seed` command to run the seeder.
2. You can control the number of entries added by changing the config variables in the `.env` file.
   `FAKE_TEAMS_COUNT` to change the number of added teams

   `FAKE_ACCOUNTS_COUNT` to change the number of added accounts
