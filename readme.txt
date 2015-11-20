1. zainstalujcie xampp (przy instalacji istotne aby miec zaznaczone apache i mysql)
2. na win10 trzeba: services.msc -> zatrzymaæ usluge "usluga publikowania w sieci www"
	inaczej server apache nie ruszy (krzyczy ze zablokowany port 80)
3. odpalacie xampp control panel
4. apache i mysql -> start
5. localhost/security -> zmieniacie haslo do bazy danych dla uzytkownika 'root' na 'ziomeczek' (gdy haslo na root
	jest domyslne czyli '' to bedzie wam sypalo errory w phpmyadmin)
6. wbijacie w xampp control panel na mysql -> admin (jak wejdzie to gratki, jak nie 
	to sprobujcie config -> my.ini i tam zlikwidowac # przed password i zmienic je na ziomeczek )
7. tworzycie nowe db po lewo - 'interfejs'
8. W db tworzycie nowa tabele o kolumnach id typ int, di typ int, ai typ int o nazwie 'slavestany'
9. z tak skonfigurowana baza danych mozecie testowac stronke - wrzuccie wszystkie pliki oprocz tego do c:/xampp:/htdocs
	(wyrzucic domyslne index.php)
10. zapraszam na localhost/index.php do testowania. Wywolac localhost/retrieve.php aby dostac skladnie json z db
11. Mozecie sprobowac zaimplementowac w C# to co gosciu tu opisuje: 
	http://www.codeproject.com/Articles/609027/Displaying-JSON-from-PHP-into-a-DataGridView-using