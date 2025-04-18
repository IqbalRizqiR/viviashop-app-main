# Vivia App

## Overview
Vivia App is a Laravel-based web application that integrates with Instagram to allow users to post content and retrieve data from their Instagram accounts. This application provides a user-friendly interface for managing Instagram posts directly from the web.

## Features
- **Homepage**: Displays products and reports.
- **Instagram Integration**: 
  - Post content to Instagram.
  - Retrieve and display Instagram feed.
  
## Installation

1. **Clone the repository**:
   ```
   git clone https://github.com/yourusername/vivia-app.git
   cd vivia-app
   ```

2. **Install dependencies**:
   ```
   composer install
   ```

3. **Set up environment**:
   - Copy the `.env.example` to `.env`:
     ```
     cp .env.example .env
     ```
   - Update the `.env` file with your database and Instagram API credentials.

4. **Generate application key**:
   ```
   php artisan key:generate
   ```

5. **Run migrations**:
   ```
   php artisan migrate
   ```

6. **Start the server**:
   ```
   php artisan serve
   ```

## Configuration
- **Instagram API Credentials**: 
  - Update the `config/instagram.php` file with your Instagram API credentials including `client_id`, `client_secret`, and `access_token`.

## Usage
- Navigate to the homepage to view products and reports.
- Use the Instagram section to create new posts or view your Instagram feed.

## Contributing
Contributions are welcome! Please open an issue or submit a pull request for any enhancements or bug fixes.

## License
This project is licensed under the MIT License. See the LICENSE file for details.