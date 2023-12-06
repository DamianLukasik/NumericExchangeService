# NumericExchangeService

## Instalacja

1. **Pobierz projekt**

Sklonuj repozytorium na swój lokalny komputer:

```bash
git clone https://github.com/DamianLukasik/NumericExchangeService.git
```

2. **Przejdź do katalogu projektu**

```bash
cd NumericExchangeService
```

3. **Zainstaluj zależności**

Użyj Composer do zainstalowania niezbędnych zależności:

```bash
composer install
```

4. **Uruchom lokalny serwer Symfony**

```bash
symfony server:start
```

Upewnij się, że masz zainstalowany Symfony CLI. Jeśli nie, możesz go zainstalować przy użyciu Composer:

```bash
composer require symfony/cli --dev
```

5. **Otwórz przeglądarkę**

Otwórz przeglądarkę i wejdź na stronę: http://127.0.0.1:8000

## Użycie

1. **Testuj do API**

Otwórz aplikację Postman na swoim komputerze. Utwórz nowy request klikając na przycisk "New" w lewym górnym rogu. Wybierz "HTTP". Nadaj requestowi odpowiednią nazwę, np. "Exchange Request", potem zaznacz metodę na "POST", a w polu "Enter URL or paste text" wpisz http://127.0.0.1:8000/api/exchange.

W odpowiedzi powinnieneś dostać:

```json
{
    "status": "success",
    "message": "request completed correctly",
    "data": null
}
```

