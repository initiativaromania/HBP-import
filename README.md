# HBP-import
Scripturi pentru importarea datelor de pe data.gov.ro
Scriptul importa date ca atare. Dupa import unele informatii pot lipsi:
* Companiile si institutiile sunt generate dinamic, asadar nu contin date de geolocatie sau adresa. 
* Valoarea in EURO a licitatiilor poate lipsi in anumite luni si trebuie populata pe baza valorii RON si a unui curs de schimb din luna respectiva.


~~~ bash
$ php  command.php --strategy="ContractXlsxV1Strategy" --file=/var/www/test.xlsx --help
Asked for help page...
       --help        Shows this help message
  -v,  --verbose     Verbosity
  -s,  --strategy    the name of the import strategy
  -f,  --file        The file name for the csv file containing the data
~~~

## Defined strategies
~~~ bash
ContractXlsxV1Strategy
ContractCsvV1Strategy
TenderCsvV1Strategy
TenderXlsxV1Strategy
~~~
