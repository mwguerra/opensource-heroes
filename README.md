# OpenSource Heroes

OpenSource Heroes is a Laravel-based project designed to connect users with prominent open-source developers. Leveraging the GitHub GraphQL API and a personal API key, it enables users to search for developers based on specific criteria, including follower count, repository count, location, and programming language. Ideal for discovering influential open-source contributors, this tool features an advanced search option and a Developer Analysis Dashboard for tracking selected developers.

## Features

- **Search Functionality**: Utilize the GitHub GraphQL API to find developers based on followers (>50 by default), repositories (>10 by default), location, and programming language.
- **Advanced Search**: Tailor search parameters to refine results.
- **Developer Analysis Dashboard**: Monitor and analyze selected open-source developers.
- **User Registration**: Open registration for demo access, allowing anyone to explore the application's capabilities.

## Getting Started

These instructions will guide you through setting up and running OpenSource Heroes on your local machine for development and testing purposes.

### Prerequisites

- PHP >= 8.2
- Composer
- MySQL
- A GitHub account and a [GitHub personal access token](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens) for accessing the GitHub GraphQL API.

### Installation

1. **Clone the repository**

```bash
git clone https://github.com/mwguerra/opensource-heroes.git
cd opensource-heroes
```

2. **Install dependencies**

```bash
composer install
```

3. **Setup environment variables**

Copy `.env.example` to `.env` and configure your application and database settings. Don't forget to set your [GitHub personal access token](https://docs.github.com/en/authentication/keeping-your-account-and-data-secure/managing-your-personal-access-tokens) here (the key in the `.env` file is `GITHUB_TOKEN`).

```bash
cp .env.example .env
```

4. **Generate an application key**

```bash
php artisan key:generate
```

5. **Run migrations**

```bash
php artisan migrate
```

6. **Start the server**

```bash
php artisan serve
```

Your application should now be running on `http://localhost:8000`.

## Usage

After logging in or registering, navigate to the search window to begin finding developers. Use the advanced options to refine your search criteria. Selected developers can be viewed and analyzed in the Developer Analysis Dashboard.

## Contributing

We welcome contributions! If you would like to help make OpenSource Heroes better, feel free to fork the repository and submit a pull request.

1. Fork the Project
2. Create your Feature Branch (`git checkout -b feature/AmazingFeature`)
3. Commit your Changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the Branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

Distributed under the MIT License. See `LICENSE` for more information.

## Acknowledgments

- GitHub GraphQL API
- Laravel
- All open-source developers who inspire this project
