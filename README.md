# Contabilizzami

**Contabilizzami** is a personal finance management tool built with Laravel, designed to help you track your accounts, categorize your transactions, and monitor your financial status in a clean, efficient interface.

---

## 🚀 Features

- 💼 **Accounts management**  
  Create and manage accounts of different types: Spendable, Savings, Investment, Debt, Credit.

- 🔁 **Transfers between accounts**  
  Record transfers and automatically link the two involved transactions via a pivot table.

- 📊 **Dynamic balance**  
  Balances are automatically calculated based on transactions — no need to store static values.

- 🗃️ **Account archiving**  
  Instead of deleting accounts, archive them to preserve historical data.

- 🧭 **Unified interface**  
  All account types are managed from a single view with filterable types and status.

- 🧩 **Modular structure**  
  Clean architecture with service layer and dependency injection for easy extension and maintenance.

---

## ⚙️ Installation with Laravel Sail

Ensure you have Docker installed, then follow these steps:

```bash
git clone https://github.com/federicopf/contabilizzami.git
cd contabilizzami

cp .env.example .env

composer install
./vendor/bin/sail up -d

./vendor/bin/sail artisan key:generate
./vendor/bin/sail artisan migrate

./vendor/bin/sail npm install
./vendor/bin/sail npm run dev

## 🏗️ Architecture

The application follows a clean architecture pattern with:

- **Service Layer**: Business logic is separated into dedicated services
- **Dependency Injection**: Services are injected into controllers via interfaces
- **Repository Pattern**: Data access is abstracted through service interfaces
- **Single Responsibility**: Each component has a clear, focused purpose

This architecture ensures:
- Easy testing and maintenance
- Code reusability across different contexts
- Clear separation of concerns
- Scalable and extensible codebase