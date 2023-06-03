# money tracker

## Development

### Setup
- `npm install`
- `composer install`
- `cp .env.example .env`
- `php artisan key:generate`
- `php artisan migrate:fresh --seed`

### Running it
- `npm run dev` in one terminal
- `php artisan serve` in another terminal
- Visit `http://localhost:8000` in your browser, login with `tadhgsmboyle@gmail.com` and `password`
- W

### Code locations
- Frontend: `resources/`
- Backend: `app/`

### Todo
- Edit account
- Reorder accounts
- Edit credit card
- Reorder credit cards
- Delete credit card
- Better graph aggregation
- Reminder emails
- Handle no cards/accounts in net worth table
- Date picker returns 1 day behind the selected date
