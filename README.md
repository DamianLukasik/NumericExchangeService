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

1. **Testuj do API - podmiana liczb**

Otwórz aplikację Postman na swoim komputerze. Utwórz nowy request klikając na przycisk "New" w lewym górnym rogu. Wybierz "HTTP". Nadaj requestowi odpowiednią nazwę, np. "Exchange Request", potem zaznacz metodę na "POST", a w polu "Enter URL or paste text" wpisz http://127.0.0.1:8000/exchange/values

Przejdź do zakładki "Body". Wybierz opcję "raw" i ustaw typ na "JSON". Wprowadź dane JSON z dwoma parametrami, na przykład:

```json
{
    "first": 4,
    "second": 7
}
```

Gdzie:
first - liczba całkowita
second - liczba całkowita

W odpowiedzi powinnieneś dostać:

```json
{
    "status": "success",
    "message": "request completed correctly",
    "data": {
        "first": 7,
        "second": 4
    }
}
```

Wartości first oraz second powinny zostać podmienione, a te z kolei zapisane w bazie danych.

2. **Testuj do API - wyświetl rekordy**

Otwórz aplikację Postman na swoim komputerze. Utwórz nowy request klikając na przycisk "New" w lewym górnym rogu. Wybierz "HTTP". Nadaj requestowi odpowiednią nazwę, np. "All Records", potem zaznacz metodę na "POST" (lub "GET"), a w polu "Enter URL or paste text" wpisz http://127.0.0.1:8000/exchange/history/showAllRecords

Przejdź do zakładki "Body". Wybierz opcję "raw" i ustaw typ na "JSON". Wprowadź dane JSON z czterema wybranymi parametrami (można nic nie wybrać), na przykład:

```json
{
    "page": 1,
    "limit": 10,
    "sort": "firstIn",
    "order": "desc"
}
```

Gdzie:
page - strona
limit - liczba rekordów (wierszy) na stronie
sort - sortowanie po danej kolumnie (dostępne kolumny to: id, firstIn, secondIn, firstOut, secondOut, createdAt, updatedAt)
order - sortowanie malejęce (desc) lub rosnące (asc)

Przy czym w przypadku ustawienia strony (page) na liczbę większą od liczby dostępnych stron, ustawiana jest ostatnia strona (page)

W odpowiedzi powinnieneś dostać:

```json
{
    "status": "success",
    "message": "request completed correctly",
    "data": [
        {
            "first_in": 4,
            "second_in": 7,
            "first_out": 7,
            "second_out": 4,
            "created_at": "2023-12-06 19:04:48",
            "updated_at": "2023-12-06 19:04:48"
        },
        (...)
    ],
    "pagination": {
        "total_items": 33,
        "items_per_page": 10,
        "current_page": 1,
        "total_pages": 4
    }
}
```
