# ChartMogul PHP SDK

PHP SDK wrapping the ChartMogul API. Requires PHP >= 7.1 (8.0+ recommended). Uses PSR-18 HTTP client with automatic retry and exponential backoff.

## Commands

```bash
# Docker-based (matches CI - PHP 8.x + PHPUnit 9.6):
make test                                            # run tests with coverage
make phpunit tests/Unit/CustomerTest.php             # run single test file
make phpunit -- --filter testCreateCustomer          # run single test method
make lint                                            # check coding style (php-cs-fixer)
make analyse                                         # static analysis (phpstan level 4)
make build                                           # rebuild Docker images

# Local (if PHP + tools are installed globally):
composer install                                     # install dependencies
phpunit                                              # run full test suite
phpunit tests/Unit/CustomerTest.php                  # run single test file
```

Docker commands are preferred - they match CI's PHP version and PHPUnit version exactly.

Version is in `src/Http/Client.php` (`API_VERSION` constant).

## Architecture

### Class hierarchy

```
ChartMogul\Resource\AbstractModel       # base: constructor from array, toArray, magic __get
  └── ChartMogul\Resource\AbstractResource  # adds HTTP client, fromArray, collection parsing
        └── Concrete resources              # Customer, Plan, Invoice, etc.
```

### Resource class constants

Defined at the top of each resource class:

- `RESOURCE_NAME` - human-readable name for error messages (e.g. `'Customer'`)
- `RESOURCE_PATH` - API endpoint, supports `:param` placeholders (e.g. `'/v1/customers'`)
- `ROOT_KEY` - JSON key wrapping collection results (e.g. `'entries'`)
- `RESOURCE_ID` - identifier param name (e.g. `'customer_uuid'`)
- `ENTRY_KEY` - envelope key for param-based operations (e.g. `'subscription_event'`)

### Properties

- Declared as `protected` on each resource class
- `@property-read` PHPDoc annotations for public read access via magic `__get`
- API response keys with dashes are auto-converted to underscores (`billing-system-type` -> `billing_system_type`)
- `#[\AllowDynamicProperties]` on AbstractModel for flexibility

### Service traits (`src/Service/`)

Include to compose CRUD behavior on resource classes:

- `CreateTrait` - `create(array $data, ?ClientInterface $client)` (POST)
- `GetTrait` - `retrieve($uuid, ?ClientInterface $client)` / `get()` alias (GET by UUID)
- `AllTrait` - `all(array $data, ?ClientInterface $client)` (GET collection)
- `UpdateTrait` - `update(array $data, ?ClientInterface $client)` (PATCH)
- `DestroyTrait` - `destroy(?ClientInterface $client)` (DELETE)
- `ShowTrait` - `retrieve(?ClientInterface $client)` (GET singleton, no UUID)
- `UpdateWithParamsTrait` - update via envelope params (data_source_uuid + external_id)
- `DestroyWithParamsTrait` - delete via envelope params
- `FromArrayTrait` - custom fromArray deserialization

All traits delegate to `RequestService` which handles path interpolation, HTTP calls, and response parsing.

### RequestService (`src/Service/RequestService.php`)

Central service that all traits delegate to. Handles:
- Path parameter interpolation (`:param` placeholders replaced from data array)
- HTTP method dispatch via the client
- Response deserialization back into resource objects
- External-ID-based operations (getByExternalId, updateByExternalId, destroyByExternalId)

### Collection handling

- `Resource\Collection` - extends Doctrine `ArrayCollection`, adds pagination metadata (`current_page`, `total_pages`, `has_more`, `cursor`, `per_page`)
- `Resource\CollectionWithCursor` - cursor-based pagination variant
- `Resource\SubscriptionEventCollection` - special handling for subscription events with meta data

### Configuration

```php
ChartMogul\Configuration::getDefaultConfiguration()
    ->setApiKey('YOUR_API_KEY')
    ->setRetries(20);  // default: 20, retries on 429 and 5xx
```

Static singleton pattern - set once, used by all resources unless a custom `ClientInterface` is passed.

### HTTP client (`src/Http/Client.php`)

- Implements `ChartMogul\Http\ClientInterface`
- Uses PSR-18 client discovery (`php-http/discovery`)
- Basic Auth: `Authorization: Basic base64(apiKey:)`
- User-Agent: `chartmogul-php/{version}/PHP-{phpVersion}`
- GET requests convert data to query string; POST/PATCH send JSON body
- Retry with exponential backoff via `stechstudio/backoff`

### Error mapping

All errors extend `ChartMogulException` with HTTP status and response body:

| HTTP status | Exception class |
|-------------|-----------------|
| 400 | `SchemaInvalidException` |
| 401/403 | `ForbiddenException` |
| 404 | `NotFoundException` |
| 405 | `NotAllowedException` |
| 422 | `SchemaInvalidException` |
| Other | `ChartMogulException` |

## Testing

**Stack:** PHPUnit + php-http/mock-client

### Conventions

- Tests live in `tests/Unit/`, one test class per resource, extending `ChartMogul\Tests\TestCase`
- JSON fixtures are declared as class constants (e.g. `const CREATE_CUSTOMER_JSON = '...'`)
- Mock HTTP client via `getMockClient($retries, $statuses, $stream)` from the base `TestCase`
- Response mocking uses `GuzzleHttp\Psr7\Response` with JSON stream bodies
- Test both static factory methods (`create`, `retrieve`, `all`) and instance methods
- Assertions check property values on the returned resource objects

### CI

GitHub Actions on push/PR to main. Matrix: PHP 8.0, 8.1, 8.4. Three jobs: tests (phpunit), coding guidelines (php-cs-fixer --dry-run), static analysis (phpstan).

## Code style

- PSR-12 coding standard, enforced by php-cs-fixer
- PHPStan level 4 for static analysis
- `snake_case` for properties and variables, `camelCase` for methods, `PascalCase` for classes
- Trait names end with `Trait` suffix (e.g. `CreateTrait`)
- Resource properties are `protected`, accessed via `@property-read` PHPDoc + magic `__get`
- Class constants are `UPPER_SNAKE_CASE`
- File layout: namespace -> use statements -> class constants -> properties -> constructor -> public methods -> private methods
- One resource class per file, PSR-4 autoloading under `src/`
