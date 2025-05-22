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
 
4.2 Tabela: Piosenka 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator piosenki 
 
 Tytuł	VARCHAR(255)	Tytuł piosenki 
 
 CzasTrwania	TIME	Długość piosenki 
 
 AlbumID	INT (FK)	ID albumu, do którego należy 
 
 WykonawcaID	INT (FK)	ID wykonawcy piosenki 
 
4.3 Tabela: Album 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator albumu 
 
 Tytuł	VARCHAR(255)	Tytuł albumu 
 
 WykonawcaID	INT (FK)	ID wykonawcy albumu 
 
 IlośćPiosenek	INT	Liczba piosenek na albumie 
 
4.4 Tabela: Wykonawca 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator wykonawcy 
 
 Nazwa	VARCHAR(255)	Nazwa wykonawcy 
 
 WytwórniaID	INT (FK)	ID wytwórni, do której należy 
 
4.5 Tabela: Wytwórnia 
 
Atrybut	Typ danych	Opis 
 
 ID	INT (PK)	Unikalny identyfikator wytwórni 
 
 Nazwa	VARCHAR(255)	Nazwa wytwórni 
 
4.6 Tabela: Playlista_Piosenka (relacja wiele-do-wielu między playlistą a piosenką) 
 
Atrybut	Typ danych	Opis 
 
 ID_Playlisty	INT (FK)	ID playlisty 
 
 ID_Piosenki	INT (FK)	ID piosenki 
 
 
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
