# 🔧 FIX FOR DATABASE CONNECTION ERROR

## ❌ Problemet du fikk:
```
Warning: mysqli::__construct(): php_network_getaddresses: 
getaddrinfo for mysql.usn.no failed: Name does not resolve
```

## ✅ Løsningen:

Databasen eksisterer på **`b-studentsql-1.usn.no`** (MariaDB 10.11.14).

Dette er den korrekte hostnamet fra phpMyAdmin-informasjonen din.

### Rask Fix (Allerede gjort!)

Jeg har allerede oppdatert `db.php` med riktig hostname. Endringene er:
- ✅ Pushet til GitHub
- ✅ Klar for auto-deploy til Dokploy

### Verifiser at det fungerer:

**Steg 1: Test forbindelsen**
```
Gå til: https://dokploy.usn.no/app/stpet1155-prg120v/test_db.php
```
Du skal nå se ✅ for alle tester.

**Steg 2: Hvis det fortsatt ikke fungerer**
```
Gå til: https://dokploy.usn.no/app/stpet1155-prg120v/troubleshoot_db.php
```
Dette skriptet tester automatisk alle mulige hostnames og viser deg hvilken som fungerer.

### Alternative Hostnames (hvis b-studentsql-1.usn.no ikke fungerer)

Prøv disse i rekkefølge:

1. **`b-studentsql-1.usn.no`** - Korrekt hostname fra phpMyAdmin (allerede satt)
2. **`localhost`** - Hvis MySQL kjører på samme server
3. **`mysql`** - Hvis i Docker container
4. **`127.0.0.1`** - IP loopback

### Hvordan endre hostname manuelt:

Hvis du trenger å endre til en annen hostname:

1. Åpne filen `db.php`
2. Finn linjen:
   ```php
   define('DB_HOST', 'mysql-ait.usn.no');
   ```
3. Endre til ønsket hostname:
   ```php
   define('DB_HOST', 'localhost'); // Eller annen hostname
   ```
4. Lagre og test igjen

### Hva er endret?

**Før:**
```php
define('DB_HOST', 'mysql.usn.no'); // ❌ Fungerte ikke
```

**Etter:**
```php
define('DB_HOST', 'b-studentsql-1.usn.no'); // ✅ Korrekt hostname!
```

**Server info fra phpMyAdmin:**
- Server: b-studentsql-1.usn.no via TCP/IP
- Server type: MariaDB 10.11.14
- Connection: TCP/IP (SSL optional)

### Ekstra ressurser:

- **Database Test:** `test_db.php` - Tester tilkobling
- **Auto-Troubleshooter:** `troubleshoot_db.php` - Finner riktig hostname automatisk
- **Dokumentasjon:** README.md inneholder all info

### Hvis ingenting fungerer:

1. Kontakt USN IT-support for å få bekreftet:
   - Riktig MySQL hostname for Dokploy
   - At bruker `stpet1155` har remote access
   - At databasen `stpet1155` eksisterer

2. Sjekk at tabellene eksisterer:
   - Logg inn på phpMyAdmin
   - Kjør `database_setup.sql`

3. Test lokalt først:
   - Verifiser at credentials fungerer i phpMyAdmin
   - Deretter test i applikasjonen

---

## 🎉 Neste steg:

1. Vent noen minutter på at endringene deployes til Dokploy
2. Gå til: https://dokploy.usn.no/app/stpet1155-prg120v/test_db.php
3. Verifiser at alle tester er grønne ✅
4. Gå til: https://dokploy.usn.no/app/stpet1155-prg120v/
5. Test applikasjonen!

**Endringene er allerede pushet til GitHub og vil auto-deployes til Dokploy!** 🚀
