# SpendTrack - Simple Expense Tracker

A simple expense tracker built with HTML, CSS, JavaScript, Bootstrap, MySQL and PHP.

## Features
- Add an expense with title, category, amount and date
- See total spent and total by category
- Filter expenses by category
- Search expenses by title
- Delete expenses
- Clean Bootstrap 5 interface with custom CSS and JavaScript

## Tech stack
- HTML and CSS
- JavaScript
- Bootstrap 5
- PHP with PDO
- MySQL

## Setup
1. Create the database and table using `database.sql`:
```sql
mysql -u root -p < database.sql
```
2. Update the database details in `config.php` if needed.
3. Copy the project folder to your web server, for example `htdocs` for XAMPP.
4. Open `http://localhost/expense-tracker-php-mysql/` in your browser.

## Database
Database name: `expense_tracker`
Table: `expenses`
- id: INT, auto increment, primary key
- title: VARCHAR(255)
- category: ENUM food, transport, shopping, bills, entertainment, other
- amount: DECIMAL(10,2)
- expense_date: DATE
- created_at: TIMESTAMP, default current timestamp

## Project structure
- `index.php` - main page, totals and expense list
- `config.php` - MySQL connection with PDO
- `add_expense.php` - handles new expenses
- `edit_expense.php` - handles editing an expense
- `delete_expense.php` - deletes an expense
- `database.sql` - database and table setup
- `assets/css/style.css` - custom styles
- `assets/js/script.js` - filters, search and confirm delete

## Author
Built by Hammad
