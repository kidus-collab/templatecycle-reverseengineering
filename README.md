# Template Cycle - Project Portfolio Manager

Template Cycle is a Laravel + Alpine.js application designed to streamline project tracking by utilizing reusable Stage Templates. It allows project managers to create templates with predefined tracks, milestones, and sub-milestones, which can then be instantly applied to new projects to track progress uniformly across the portfolio.

## Tech Stack
- **Backend:** Laravel 11 (PHP 8.2+)
- **Frontend:** Blade Templates, TailwindCSS, Alpine.js
- **Database:** SQLite
- **Icons:** Lucide Icons

## Environment Setup (Docker)

The absolute easiest way to run the application is using the provided Docker setup. The environment is pre-configured to automatically install all dependencies, build the frontend assets via Vite, and run the Laravel development server.

### Prerequisites
- [Docker Desktop](https://www.docker.com/products/docker-desktop) installed and running.

### Getting Started

1. **Clone the repository**
   ```bash
   git clone https://github.com/kidus-collab/templatecycle-reverseengineering.git
   cd templatecycle-reverseengineering
   ```

2. **Environment Variables**
   Copy the example environment file to `.env`:
   ```bash
   cp .env.example .env
   ```
   *(Ensure that `DB_CONNECTION=sqlite` and `DB_DATABASE=database/database.sqlite` are set in the `.env`)*

3. **Start the Application**
   Run the following command to build the image and start the container:
   ```bash
   docker-compose up --build -d
   ```

   This single command will:
   - Build the PHP/Node.js environment container.
   - Install all Composer and NPM dependencies automatically.
   - Serve the Laravel application on port `8000`.
   - Start the Vite development server on port `5173` for hot-module replacement.

4. **Initialize the Database & Seed Dummy Data**
   Once the container is running, execute the database migrations and seeders. The seeder (specifically `UltimateTemplateSeeder`) automatically sets up default templates, tracks, and factory dummy data for projects:
   ```bash
   docker-compose exec app php artisan migrate:fresh --seed
   ```
   *Note: If you only want to run a specific seeder, you can use `--class=UltimateTemplateSeeder`.*

### Accessing the Application

- **Web App:** Open your browser to [http://localhost:8000](http://localhost:8000)
- **Vite Assets:** Ensure port `5173` is also open and mapped properly if you are running on a remote VPS.

## Local Development (Without Docker)

If you prefer to run it locally without Docker:

```bash
# Install PHP Dependencies
composer install

# Install NPM Dependencies
npm install

# Run database migrations
touch database/database.sqlite
php artisan migrate:fresh --seed

# Start both Laravel and Vite using concurrently
composer run dev
```
