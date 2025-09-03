# Refactorizzazione Backend con Dependency Injection

## Panoramica

Questo documento descrive la refactorizzazione del backend dell'applicazione Contability, implementando un forte approccio di Dependency Injection (DI) per separare la logica di business dai controller.

## Problemi Identificati

I controller originali erano "cicciotti" e presentavano i seguenti problemi:

1. **Logica di business mescolata alla logica di presentazione**
2. **Duplicazione di codice** tra controller web e API
3. **Difficoltà di testing** dovuta alla mancanza di separazione delle responsabilità
4. **Scarsa manutenibilità** e riutilizzabilità del codice

## Soluzione Implementata

### 1. Architettura dei Servizi

Sono stati creati i seguenti servizi con le relative interfacce:

#### TransactionService
- **Responsabilità**: Gestione delle transazioni e trasferimenti
- **Metodi principali**:
  - `createTransaction()` - Crea una nuova transazione
  - `createTransfer()` - Crea un trasferimento tra conti
  - `deleteTransaction()` - Elimina una transazione
  - `getDescriptionSuggestions()` - Ottiene suggerimenti per le descrizioni

#### AccountService
- **Responsabilità**: Gestione degli account
- **Metodi principali**:
  - `getUserAccountsByType()` - Ottiene account filtrati per tipo
  - `createAccount()` - Crea un nuovo account
  - `getAccountWithProcessedTransactions()` - Ottiene account con transazioni processate
  - `deleteAccount()` - Elimina un account (soft delete)
  - `restoreAccount()` - Ripristina un account eliminato

#### UserService
- **Responsabilità**: Gestione degli utenti (superadmin)
- **Metodi principali**:
  - `getUsersBySuperadminType()` - Ottiene utenti filtrati per tipo
  - `createUser()` - Crea un nuovo utente con password temporanea
  - `updateUser()` - Aggiorna un utente
  - `resetUserPassword()` - Resetta la password di un utente

#### StatsService
- **Responsabilità**: Calcolo delle statistiche
- **Metodi principali**:
  - `getMonthlyInOutStats()` - Statistiche mensili entrate/uscite
  - `getYearlyInOutStats()` - Statistiche annuali entrate/uscite
  - `getMonthlyTotalStats()` - Statistiche mensili totali cumulativi
  - `getYearlyTotalStats()` - Statistiche annuali totali cumulativi

#### ProfileService
- **Responsabilità**: Gestione del profilo utente
- **Metodi principali**:
  - `updatePassword()` - Aggiorna la password dell'utente

#### DashboardService
- **Responsabilità**: Gestione della dashboard home
- **Metodi principali**:
  - `getDashboardData()` - Ottiene tutti i dati per la dashboard

#### AuthService
- **Responsabilità**: Gestione dell'autenticazione
- **Metodi principali**:
  - `validateRegistrationData()` - Valida i dati di registrazione
  - `createUser()` - Crea un nuovo utente

### 2. Interfacce e Dependency Injection

Ogni servizio implementa un'interfaccia specifica:

```php
interface TransactionServiceInterface
interface AccountServiceInterface
interface UserServiceInterface
interface StatsServiceInterface
interface ProfileServiceInterface
interface DashboardServiceInterface
interface AuthServiceInterface
```

### 3. Registrazione nel Container DI

I servizi sono registrati nell'`AppServiceProvider`:

```php
$this->app->bind(TransactionServiceInterface::class, TransactionService::class);
$this->app->bind(AccountServiceInterface::class, AccountService::class);
$this->app->bind(UserServiceInterface::class, UserService::class);
$this->app->bind(StatsServiceInterface::class, StatsService::class);
$this->app->bind(ProfileServiceInterface::class, ProfileService::class);
$this->app->bind(DashboardServiceInterface::class, DashboardService::class);
$this->app->bind(AuthServiceInterface::class, AuthService::class);
```

### 4. Refactorizzazione dei Controller

I controller sono stati refactorizzati per:

- **Iniettare le dipendenze** tramite constructor injection
- **Delegare la logica di business** ai servizi
- **Gestire solo la logica di presentazione** (validazione, risposta HTTP)
- **Rimuovere la duplicazione** di codice

#### Esempio di Controller Refactorizzato

**Prima**:
```php
public function store(Request $request)
{
    $data = $request->validate([...]);
    $account = Account::findOrFail($data['account_id']);
    if ($account->user_id !== auth()->id()) {
        abort(403, 'Accesso negato');
    }
    Transaction::create($data);
    return redirect()->route('conti.show', ['account' => $account->id])
                    ->with('success', 'Transazione creata con successo!');
}
```

**Dopo**:
```php
public function store(Request $request)
{
    $data = $request->validate([...]);
    $transaction = $this->transactionService->createTransaction($data, Auth::id() ?? 0);
    $account = Account::findOrFail($data['account_id']);
    return redirect()->route('conti.show', ['account' => $account->id])
                    ->with('success', 'Transazione creata con successo!');
}
```

## Benefici Ottenuti

### 1. Separazione delle Responsabilità
- **Controller**: Gestiscono solo la logica HTTP (validazione, risposta)
- **Servizi**: Contengono tutta la logica di business
- **Modelli**: Gestiscono solo le relazioni e le query base

### 2. Testabilità Migliorata
- I servizi possono essere testati in isolamento
- I controller possono essere testati con mock dei servizi
- Facile implementazione di unit test

### 3. Riutilizzabilità
- I servizi possono essere utilizzati da controller web e API
- Logica di business centralizzata e riutilizzabile
- Riduzione della duplicazione di codice

### 4. Manutenibilità
- Modifiche alla logica di business in un solo punto
- Codice più leggibile e organizzato
- Facile aggiunta di nuove funzionalità

### 5. Flessibilità
- Facile sostituzione delle implementazioni tramite interfacce
- Possibilità di implementare pattern come Repository, CQRS, ecc.
- Supporto per cache, logging, e altri cross-cutting concerns

## Struttura delle Directory

```
app/
├── Contracts/
│   └── Services/
│       ├── TransactionServiceInterface.php
│       ├── AccountServiceInterface.php
│       ├── UserServiceInterface.php
│       ├── StatsServiceInterface.php
│       ├── ProfileServiceInterface.php
│       ├── DashboardServiceInterface.php
│       └── AuthServiceInterface.php
├── Services/
│   ├── TransactionService.php
│   ├── AccountService.php
│   ├── UserService.php
│   ├── StatsService.php
│   ├── ProfileService.php
│   ├── DashboardService.php
│   └── AuthService.php
├── Http/
│   └── Controllers/
│       ├── TransactionController.php (refactorizzato)
│       ├── AccountController.php (refactorizzato)
│       ├── SuperadminUserController.php (refactorizzato)
│       ├── ProfileController.php (refactorizzato)
│       ├── HomeController.php (refactorizzato)
│       ├── SuperadminController.php (semplice, non necessita refactorizzazione)
│       ├── StatsController.php (semplice, non necessita refactorizzazione)
│       └── Auth/
│           └── RegisterController.php (refactorizzato)
│       └── Api/
│           └── ApiStatsController.php (refactorizzato)
└── Providers/
    └── AppServiceProvider.php (aggiornato con registrazione servizi)
```

## Prossimi Passi

1. **Implementazione di Repository Pattern** per l'accesso ai dati
2. **Aggiunta di DTOs** per il trasferimento dei dati
3. **Implementazione di Eventi** per operazioni asincrone
4. **Aggiunta di Cache** per migliorare le performance
5. **Implementazione di Logging** per debugging e monitoring

## Testing

I servizi possono essere testati facilmente:

```php
class TransactionServiceTest extends TestCase
{
    public function test_create_transaction()
    {
        $service = new TransactionService();
        $data = ['account_id' => 1, 'description' => 'Test', 'amount' => 100];
        
        $transaction = $service->createTransaction($data, 1);
        
        $this->assertInstanceOf(Transaction::class, $transaction);
        $this->assertEquals('Test', $transaction->description);
    }
}
```

## Conclusioni

La refactorizzazione ha trasformato un'architettura monolitica in una struttura modulare e testabile, seguendo i principi SOLID e implementando un forte approccio di Dependency Injection. Questo rende il codice più manutenibile, testabile e scalabile.
