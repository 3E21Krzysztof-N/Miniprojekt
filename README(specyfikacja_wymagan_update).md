# Specyfikacja wymagań dla bazy danych „Playlista” 
 
1. Wprowadzenie 
 
1.1 Cel systemu 
 
 System „Playlista” ma na celu przechowywanie, organizację i zarządzanie informacjami dotyczącymi utworów muzycznych, playlist, albumów, wykonawców oraz wytwórni muzycznych. Pozwala na efektywne zarządzanie relacjami między tymi obiektami i zapewnia wygodny dostęp do danych. 
 
1.2 Zakres systemu 
 
 System obejmuje dane dotyczące: 
 	
    •	Playlist, ich nazw, ID, dat utworzenia oraz liczby utworów. 
 	
    •	Piosenek, ich ID, tytułów, czasów trwania, albumów oraz wykonawców. 
 	
    •	Albumów, ich ID, tytułów, wykonawców oraz liczby piosenek. 
 	
    •	Wykonawców, ich ID, nazw oraz przypisania do wytwórni. 
 	
    •	Wytwórni muzycznych, ich ID oraz nazw. 

Zawiera 4 typy danych takie jak: date, time, int, varchar.
 
2. Wymagania funkcjonalne 
 
2.1 Zarządzanie playlistami 
 	
    •	Możliwość tworzenia nowych playlist. 
 	
    •	Możliwość dodawania i usuwania piosenek z playlist. 
 	
    •	Możliwość edycji nazw oraz przeglądania zawartości playlist. 
 
2.2 Zarządzanie piosenkami 
 	
    •	Możliwość dodawania nowych piosenek. 
 	
    •	Możliwość przypisywania piosenek do albumów i wykonawców. 
 	
    •	Możliwość edycji oraz usuwania piosenek. 
 
2.3 Zarządzanie albumami 
 	•	Możliwość dodawania nowych albumów. 
 	
    •	Możliwość przypisywania piosenek do albumów. 
 	
    •	Możliwość edycji oraz usuwania albumów. 
 
2.4 Zarządzanie wykonawcami 
 	
    •	Możliwość dodawania wykonawców do bazy danych. 
 	
    •	Możliwość przypisywania wykonawców do albumów i piosenek. 
 	
    •	Możliwość edycji oraz usuwania wykonawców. 
 
2.5 Zarządzanie wytwórniami muzycznymi 
 	
    •	Możliwość dodawania wytwórni do bazy danych. 
 	
    •	Możliwość przypisywania wytwórni do wykonawców. 
 	
    •	Możliwość edycji oraz usuwania wytwórni. 
 
2.6 Relacje między encjami 
 	
    •	Playlista może zawierać wiele piosenek. 
 	
    •	Piosenka należy do jednego albumu, ale może znajdować się na wielu playlistach. 
 	
    •	Album może zawierać wiele piosenek i być przypisany do jednego wykonawcy. 
 	
    •	Wykonawca może mieć wiele albumów i być przypisany do jednej wytwórni. 
 
 
3. Wymagania niefunkcjonalne 
 
3.1 Wydajność 
 	
    •	System powinien obsługiwać zapytania do bazy danych w czasie poniżej 1 sekundy. 
 
3.2 Bezpieczeństwo 
 	
    •	Dostęp do edycji i usuwania danych powinien być ograniczony do uprawnionych użytkowników. 
 
3.3 Skalowalność 
 	
    •	System powinien umożliwiać dodawanie nowych utworów, albumów, wykonawców i playlist bez ograniczeń co do liczby rekordów. 
 
 
4. Model danych 
 
 Baza danych składa się z następujących tabel: 
 
4.1 Tabela: Playlista 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator playlisty 
 
 Nazwa	VARCHAR(255)	Nazwa playlisty 
 
 DataUtworzenia	DATE	Data utworzenia playlisty 
 
 LiczbaUtworów	INT	Liczba piosenek w playliście 
 
 <img width="1108" alt="Screenshot 2025-05-22 at 10 15 01" src="https://github.com/user-attachments/assets/0cb7b97e-6721-411c-a397-c783238b9999" />

4.2 Tabela: Piosenka 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator piosenki 
 
 Tytuł	VARCHAR(255)	Tytuł piosenki 
 
 CzasTrwania	TIME	Długość piosenki 
 
 AlbumID	INT (FK)	ID albumu, do którego należy 
 
 WykonawcaID	INT (FK)	ID wykonawcy piosenki 

 <img width="1094" alt="Screenshot 2025-05-22 at 10 13 58" src="https://github.com/user-attachments/assets/6ec7071b-d106-479c-9482-1af3fe383582" />

 
4.3 Tabela: Album 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator albumu 
 
 Tytuł	VARCHAR(255)	Tytuł albumu 
 
 WykonawcaID	INT (FK)	ID wykonawcy albumu 
 
 IlośćPiosenek	INT	Liczba piosenek na albumie 
 
 <img width="1107" alt="Screenshot 2025-05-22 at 10 12 22" src="https://github.com/user-attachments/assets/63598b0b-413a-466d-8fb8-f0341dd39a68" />

4.4 Tabela: Wykonawca 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator wykonawcy 
 
 Nazwa	VARCHAR(255)	Nazwa wykonawcy 
 
 WytwórniaID	INT (FK)	ID wytwórni, do której należy 

 
<img width="1100" alt="Screenshot 2025-05-22 at 10 17 36" src="https://github.com/user-attachments/assets/9e5ecf47-384d-4250-8d8c-b8e8f7b6d0a4" />

 
 
4.5 Tabela: Wytwórnia 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator wytwórni 
 
 Nazwa	VARCHAR(255)	Nazwa wytwórni 

 <img width="1114" alt="Screenshot 2025-05-22 at 10 16 42" src="https://github.com/user-attachments/assets/b2dbd60c-53b9-44e9-a434-419ad47dd2f5" />

 
4.6 Tabela: Playlista_Piosenka (relacja wiele-do-wielu między playlistą a piosenką) 
 
Atrybut	Typ danych	Opis 
 
 ID_Playlisty	INT (FK)	ID playlisty 
 
 ID_Piosenki	INT (FK)	ID piosenki 
 
 <img width="1106" alt="Screenshot 2025-05-22 at 10 15 52" src="https://github.com/user-attachments/assets/4b473c9b-bec1-4ab1-91f7-0da2ecd0b6b7" />

 
5. Relacje między tabelami 
 	
    •	Playlista_Piosenka → łączy Playlista i Piosenka (wiele-do-wielu). 
 	
    •	Piosenka → należy do jednego Albumu (wiele-do-jednego). 
 	
    •	Piosenka → jest przypisana do jednego Wykonawcy (wiele-do-jednego). 
 	
    •	Album → może zawierać wiele Piosenek (jeden-do-wiele). 
 	
    •	Album → jest przypisany do jednego Wykonawcy (wiele-do-jednego). 
 	
    •	Wykonawca → jest przypisany do jednej Wytwórni (wiele-do-jednego). 
 
 

6. Podsumowanie 
 
 Baza danych Playlista jest zaprojektowana do przechowywania danych muzycznych w sposób logiczny i uporządkowany. Umożliwia ona: 
 	
    •	Przechowywanie informacji o playlistach, utworach, albumach, wykonawcach i wytwórniach. 
 	
    •	Organizację danych w hierarchicznej strukturze (Playlista → Piosenki → Albumy → Wykonawcy → Wytwórnie). 
 	
    •	Zarządzanie relacjami między encjami. 
    
7. Zrzut z designera
   <img width="1271" alt="Screenshot 2025-05-22 at 09 54 42" src="https://github.com/user-attachments/assets/0112eced-40b9-4155-8d69-007a464f32a2" />

8. Widok relacyjny bazy danych
   <img width="1040" alt="Screenshot 2025-05-22 at 09 58 25" src="https://github.com/user-attachments/assets/d5a5c0f7-b10e-4842-96e6-14edd0f0508e" />

9. Layout strony głównej
   <img width="1512" alt="Screenshot 2025-05-22 at 09 53 13" src="https://github.com/user-attachments/assets/7d129419-fd9b-4b4f-973e-d95befc78f2e" />


10. Layout strony z artystami
   <img width="1342" alt="Screenshot 2025-05-22 at 10 18 58" src="https://github.com/user-attachments/assets/baf1997b-ca33-4936-baf0-fd280f1f825e" />

11. Layout strony z albumami
    <img width="1339" alt="Screenshot 2025-05-22 at 10 20 19" src="https://github.com/user-attachments/assets/c4f50314-732a-4c4e-843e-88d87b725c16" />

12. Layout strony z playlistami
    <img width="1341" alt="Screenshot 2025-05-22 at 10 21 44" src="https://github.com/user-attachments/assets/cb3a5a11-a3ae-47d2-b02a-f48e88556311" />



---

# PlaylistHub: Twój Cyfrowy Notatnik Muzyczny

Wyobraź sobie **PlaylistHub** jako specjalny **cyfrowy notatnik muzyczny** działający online. Przechowuje on informacje o piosenkach, artystach i Twoich osobistych playlistach.

---

## Co możesz robić w tym notatniku?

* **Przeglądać spisy**
  Przeglądaj listy artystów, albumów i piosenek – jak w katalogu muzycznym.

* **Zakładać nowe "zakładki" (playlisty)**
  Twórz własne kategorie, np. *"Muzyka do biegania"* czy *"Relaks wieczorem"*.

* **Zapisywać piosenki w zakładkach**
  Dodawaj utwory do wybranych playlist.

* **Wykreślać piosenki z zakładek**
  Usuń utwory, które już Ci się znudziły.

* **Zmieniać nazwy zakładek**
  Zmieniaj nazwy swoich playlist, gdy np. *"Muzyka do biegania"* zamienia się w *"Turbo Trening"*.

**Uwaga:** PlaylistHub **nie odtwarza muzyki** i **nie wyświetla obrazków** – służy tylko do organizowania informacji o muzyce.

---

## Jak to działa, gdy coś klikasz?

### Centralny Magazyn Danych (Wielka Księga)

Wszystkie dane (artyści, utwory, playlisty) są przechowywane w jednym miejscu – w tzw. **Wielkiej Księdze**.

### System Obsługi Zapytań (Inteligentny Asystent)

Kiedy klikniesz coś na stronie, komputer wysyła **zapytanie do serwera**. Odpowiada na nie nasz "Inteligentny Asystent" (np. aplikacja napisana w PHP).

### Strona Internetowa (Wygenerowany Raport)

Asystent przetwarza zapytanie, przegląda dane i generuje stronę (HTML + CSS), którą widzisz w przeglądarce.

### Twoja Przeglądarka (Prezenter Raportu)

Otrzymuje gotowy raport i pokazuje go na ekranie w czytelnej, estetycznej formie.

---

## Case Study: Tworzysz nową playlistę "Wieczorne Nuty"

### Krok 1: Tworzenie playlisty

* **Ty:** Klikasz „Utwórz Nową Playlistę”.
* **Przeglądarka:** Wysyła zapytanie: „Użytkownik chce utworzyć nową playlistę”.

---

### Krok 2: Nadanie nazwy

* **Ty:** Wpisujesz „Wieczorne Nuty” i klikasz „Utwórz”.
* **Serwer:**

  * Zapisuje nową playlistę w Wielkiej Księdze:

    ```
    Nazwa: Wieczorne Nuty
    Data utworzenia: [dzisiejsza data]
    Liczba piosenek: 0
    Czas: 00:00:00
    ```
  * Wysyła instrukcję do przeglądarki, by przejść na stronę edycji playlisty.

---

### Krok 3: Wyświetlenie pustej playlisty

* **Przeglądarka:** Przechodzi do `edit_playlist.php?id=123`.
* **Serwer:** Pobiera:

  * Szczegóły playlisty o ID 123.
  * Listę wszystkich dostępnych piosenek w systemie.
* **Wygenerowana strona:** Wyświetla:

  * Tytuł: „Wieczorne Nuty”
  * Informację: brak piosenek na liście
  * Listę dostępnych utworów do dodania

---

### Krok 4: Dodanie piosenki "Moonlight Sonata"

* **Ty:** Klikasz „Dodaj” przy utworze „Moonlight Sonata”.
* **Przeglądarka:** Wysyła zapytanie: „Dodaj piosenkę o ID 789 do playlisty o ID 123”.
* **Serwer:**

  * Aktualizuje playlistę: dodaje utwór, zwiększa liczbę piosenek i sumaryczny czas trwania.
  * Generuje ponownie stronę edycji:

    * Sekcja „Piosenki w tej playliście” zawiera teraz „Moonlight Sonata”.
    * Jeśli utwór występował tylko raz, znika z listy dostępnych.

---
