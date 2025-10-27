# PRG120V - Student- og Klasseadministrasjon

En webapplikasjon for å registrere, vise og slette studenter og klasser.

## Funksjonalitet

- Registrere nye klasser (klassekode, klassenavn, studiumkode)
- Vise alle klasser i tabell
- Slette klasser via listeboks
- Registrere nye studenter (brukernavn, fornavn, etternavn, klassekode)
- Vise alle studenter i tabell med tilhørende klasseinformasjon
- Slette studenter via listeboks

## Installasjon

1. Installer en lokal webserver med PHP og MySQL (f.eks. XAMPP, WAMP, eller MAMP)
2. Kopier alle filene til webserver-katalogen (f.eks. `htdocs` for XAMPP)
3. Start MySQL-serveren
4. Importer database-skjemaet:
   - Åpne phpMyAdmin eller MySQL command line
   - Kjør SQL-skriptet fra `database.sql`
5. Oppdater databasetilkoblingsinformasjon i `config.php` om nødvendig
6. Åpne `index.php` i nettleseren

## Teknologier

- PHP 7.4+
- MySQL 5.7+
- HTML5
- CSS3

## Filstruktur

- `index.php` - Hovedfil med all funksjonalitet og brukergrensesnitt
- `config.php` - Databasekonfigurasjon og tilkoblingsfunksjon
- `database.sql` - SQL-skript for å opprette database og tabeller
