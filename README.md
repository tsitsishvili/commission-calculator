# Commission Calculator

A PHP-based application that calculates commission fees based on transaction data provided in CSV format.

## Requirements

- PHP >= 8.3
- Composer

## Installation

1. Clone the repository
   ```bash
   git clone https://github.com/tsitsishvili/commission-calculator.git
   cd commission-calculator
   ```

2. Install dependencies
   ```bash
   composer install
   ```

3. Set up environment
   ```bash
   cp .env.example .env
   ```

4. Configure your environment variables in the `.env` file with appropriate credentials

## Usage

### Processing Transaction Data

The application processes transaction data from CSV files to calculate commission fees.

To run the application:

```bash
php script.php path/to/your/file.csv
```
Example CSV format:
- `2014-12-31,4,private,withdraw,1200.00,EUR`

### Configuration Options
The application can be configured in two ways:
1. **Using Static Exchange Rates**:
   ```
   $config = [
     'static_rates' => [
       'EUR' => 1.0,
       'USD' => 1.1,
       // Other currency rates
     ]
   ]; 
   ```
2. **Using Dynamic Exchange Rates via API**:
   ```
   $config = [
      'base_currency' => 'EUR',
    ]; 
   ```

You can pass these configurations when calling the application:
```\src\App::run('path/to/your/file.csv', $config);```

## Testing
### Manual Testing
Create a test CSV file (e.g., `test.csv`) 
in the root directory with transaction data:
```
2014-12-31,4,private,withdraw,1200.00,EUR
2015-01-01,4,private,withdraw,1000.00,EUR
...
```
Then run:
```bash
php script.php test.csv
```

### Automated Tests
To run the application's test suite:
```bash
composer run test
```

## Project Structure
- `src/` - Contains the application's source code
- `tests/` - Contains test files
- - Main entry point for the application `script.php`

