# 📚 PRG120V - Student- og Klassebehandling

En komplett PHP-webapplikasjon for administrasjon av studenter og klasser med CRUD-funksjonalitet (Create, Read, Delete).

## 🎯 Oppgavebeskrivelse

Dette prosjektet er en løsning på PRG120V-oppgaven som innebærer å lage et student- og klassebehandlingssystem med følgende funksjonalitet:

### Funksjoner
- ✅ **Klassebehandling**: Registrere, vise og slette klasser
- ✅ **Studentbehandling**: Registrere, vise og slette studenter
- ✅ Brukervennlig grensesnitt med moderne design
- ✅ Validering av inndata
- ✅ Feilhåndtering og tilbakemeldinger
- ✅ Responsiv design for mobil og desktop

## 🗄️ Database

### Database-informasjon
- **Server**: mysql-ait.usn.no (se troubleshooting hvis dette ikke fungerer)
- **Database**: stpet1155
- **Bruker**: stpet1155
- **Passord**: d991stpet1155
- **Administrasjon**: Tilgjengelig via phpMyAdmin

**⚠️ Viktig:** Hvis du får "Name does not resolve" feil, kjør `troubleshoot_db.php` for å finne riktig hostname.

**Alternative hostnames:**
- `localhost` - Hvis MySQL kjører på samme server
- `mysql` - For Docker container setup
- `127.0.0.1` - IP loopback adresse

### Database-skjema

#### Tabell: `klasse`
```sql
CREATE TABLE klasse (
  klassekode CHAR(5) NOT NULL,
  klassenavn VARCHAR(50) NOT NULL,
  studiumkode VARCHAR(50) NOT NULL,
  PRIMARY KEY (klassekode)
);
```

#### Tabell: `student`
```sql
CREATE TABLE student (
  brukernavn CHAR(7) NOT NULL,
  fornavn VARCHAR(50) NOT NULL,
  etternavn VARCHAR(50) NOT NULL,
  klassekode CHAR(5) NOT NULL,
  PRIMARY KEY (brukernavn),
  FOREIGN KEY (klassekode) REFERENCES klasse(klassekode)
);
```

### Relasjoner
- En student tilhører én klasse (FOREIGN KEY)
- En klasse kan ikke slettes hvis den har studenter
- Klassekode må være registrert før studenter kan legges til

## 📁 Prosjektstruktur

```
PRG120V/
├── index.php           # Hovedside med oversikt og meny
├── db.php             # Database-tilkobling og konfigurasjon
├── klasse.php         # CRUD for klassebehandling
├── student.php        # CRUD for studentbehandling
├── style.css          # Stylesheet for hele applikasjonen
└── README.md          # Denne filen
```

## 🚀 Installasjon og deployment

### Lokal utvikling
1. Klon repository:
   ```bash
   git clone https://github.com/Snakkaz/PRG120V.git
   cd PRG120V
   ```

2. Sørg for at du har PHP og MySQL installert lokalt.

3. Oppdater database-innstillinger i `db.php` hvis nødvendig.

4. Start lokal PHP-server:
   ```bash
   php -S localhost:8000
   ```

5. Åpne nettleseren på `http://localhost:8000`

### Deployment til USN Dokploy

Applikasjonen er konfigurert for automatisk deployment til USN Dokploy-server.

#### Deployment-URL
🔗 **Live applikasjon**: https://dokploy.usn.no/app/stpet1155-prg120v/

#### Automatisk deployment
Applikasjonen deployes automatisk til USN Dokploy-serveren via SSH deploy key når endringer pushes til `main`-branchen.

#### Manuell deployment via SSH
```bash
# SSH inn til serveren
ssh stpet1155@dokploy.usn.no

# Gå til applikasjonsmappen
cd /path/to/app/stpet1155-prg120v

# Pull siste endringer
git pull origin main

# Restart hvis nødvendig
```

## 💻 Bruk av applikasjonen

### 1. Startside (`index.php`)
- Viser statistikk over antall klasser og studenter
- Navigasjon til alle funksjoner
- Oversikt over systemets funksjoner

### 2. Klassebehandling (`klasse.php`)
**Registrere ny klasse:**
- Klassekode: Nøyaktig 5 tegn (f.eks. IT101)
- Klassenavn: Beskrivende navn (f.eks. Informasjonsteknologi 1. klasse)
- Studiumkode: Studiekode (f.eks. ITE)

**Vise klasser:**
- Alle klasser vises i en oversiktlig tabell
- Viser antall studenter per klasse

**Slette klasse:**
- Klasse kan kun slettes hvis den ikke har studenter
- Bekreftelsesdialog for sikkerhet

### 3. Studentbehandling (`student.php`)
**Registrere ny student:**
- Brukernavn: Nøyaktig 7 tegn (f.eks. stpet11)
- Fornavn og etternavn
- Velg klasse fra dropdown-liste

**Vise studenter:**
- Alle studenter vises med klasseinformasjon
- Sortert etter etternavn og fornavn

**Slette student:**
- Bekreftelsesdialog for sikkerhet

## 🎨 Design og brukeropplevelse

Applikasjonen har et moderne og brukervennlig design med:
- Responsive layout (mobil og desktop)
- Intuitive navigasjon
- Tydelige tilbakemeldinger (suksess/feil)
- Validering av inndata
- Bekreftelses-dialoger ved sletting
- Emoji-ikoner for bedre visuell kommunikasjon

## 🔐 Sikkerhet

- **Prepared statements**: Beskytter mot SQL injection
- **Input validering**: Validerer alle inndata på server-siden
- **Escape functions**: Sikrer trygg håndtering av brukerinndata
- **UTF-8 encoding**: Støtter norske tegn korrekt

## 🛠️ Teknologi

- **Backend**: PHP 8.x
- **Database**: MySQL 8.x
- **Frontend**: HTML5, CSS3
- **Server**: USN Dokploy
- **Versjonskontroll**: Git/GitHub

## 📝 Viktige merknader

1. **Klassekode** må være nøyaktig 5 tegn
2. **Brukernavn** må være nøyaktig 7 tegn
3. Klasser må registreres før studenter kan legges til
4. Klasser med studenter kan ikke slettes
5. Alle felt er obligatoriske

## 🐛 Feilsøking

### Databasetilkobling feiler
- Sjekk at database-credentials i `db.php` er korrekte
- Verifiser at databaseserveren er tilgjengelig
- Kontroller at brukeren har nødvendige rettigheter

### Kan ikke legge til student
- Sørg for at klassen eksisterer først
- Sjekk at brukernavnet ikke allerede er i bruk
- Verifiser at alle felt er fylt ut

### Kan ikke slette klasse
- Klasser med studenter kan ikke slettes
- Slett studentene først, deretter klassen

## 📚 Utviklingsnotater

### Database-tilkobling (`db.php`)
- Håndterer MySQL-tilkobling
- Bruker mysqli for sikker database-interaksjon
- UTF-8 support for norske tegn

### CRUD-operasjoner
- **Create**: INSERT med prepared statements
- **Read**: SELECT med LEFT JOIN for relasjoner
- **Delete**: DELETE med validering

### Validering
- Server-side validering av all inndata
- Client-side validering via HTML5 attributes
- Bekreftelsesdialog via JavaScript

## 🔗 Lenker

- **GitHub Repository**: https://github.com/Snakkaz/PRG120V
- **Live applikasjon**: https://dokploy.usn.no/app/stpet1155-prg120v/
- **phpMyAdmin**: Via USN database-portal

## 👨‍💻 Forfatter

- **Student**: stpet1155
- **Kurs**: PRG120V
- **Institusjon**: Universitetet i Sørøst-Norge (USN)

## 📄 Lisens

Dette prosjektet er laget som en del av PRG120V-kurset ved USN.

---

**Sist oppdatert**: Oktober 2025
