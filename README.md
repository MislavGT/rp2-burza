# rp2-burza

Virtualna burza vrijednosnih papira

## Postavljanje baze

1. Otvori terminal pa `sudo mysql`
2. `CREATE DATABASE 'burza';`
3. `CREATE USER 'burza'@'localhost' IDENTIFIED BY 'burza';`
4. `GRANT ALL PRIVILEGES ON burza.* TO 'burza'@'localhost';`

## Tablice u bazi

### `burza_users`

1. id int
2. username varchar(50)
3. password_hash varchar(255)
4. email varchar(50)
5. registration_sequence varchar(20)
6. has_registered int

### `burza_privileges`

1. id_user int
2. admin bool

### `burza_dionice`

1. id int
2. ime varchar(50)
3. ticker varchar(4)
4. izdano int
5. zadnja_cijena int
6. dividenda int

### `burza_transakcije`

1. id int
2. id_dionice int
3. kolicina int
4. cijena int
5. prodao int
6. kupio int
7. datum datetime

### `burza_kapital`

1. id_user int
2. kapital int

### `burza_imovina`

1. id_user int
2. id_dionica int
3. kolicina int

### `burza_orderbook`

1. id_user int
2. id_dionica int
3. kolicina int
4. cijena int
5. tip enum('buy', 'sell')
6. datum datetime

### `burza_postavke`

1. pocetni_kapital int
2. kamata int
3. datum int
4. komisija int

## Početak

Pratimo MVC arhitekturalni uzorak.
Korisnici se mogu registrirati. Svaki korisnik dobiva početni kapital. `loginservice.class.php` `register_index.php`
Kreiraju se tablice u bazi podataka i napune pripadnim podacima. `db.class.php` `create_tables.php` `seed_tables.php`
Napočetku postoji 5 korisnika koji unaprijed posjeduju dionice. Prvi, mirko, je ujedno i administrator.
On može upravljati početnim kapitalom i dividendama pojedinih dionica. `adminservise.class.php` i `adminController.php`

## Dashboard

Korisnik može vidjeti svoj portfelj, dionice i neto vrijednost. `dashboard_index.php` `portfolio_index.php`
Postoji i rang lista korisnika. Gdje je ulogirani korisnik označen crvenom bojom. `rang_index.php`

## Dionice

Svaki korisnik ima mogućnost kupnje i prodaje dionica. `dioniceservice.class.php` `jedna_dionica_index.php` `dioniceController.php`
Ponuđeno je 10 popularnih dionica i prilikom generiranja baze podataka koristeći `Yahoo Finance API` pribave se stvarni podaci.
Kada korisnik želi kupiti ili prodati dionice, odabire broj dionica kojima želi trgovati i najgoru cijenu koju je spreman prihvatiti.
Tada se provjeri ispunjava li uvjete. Zabranjeno je "ići u minus".
Pretragom tablice `burza_orderbook` rekurzivno se ispunjava njegova narudžba najboljim cijenama (osim toga, preferiraju se starije narudžbe).
Ukoliko se u tom trenutku narudžba ne može do kraja ispuniti, ostatak odlazi u `burza_orderbook` na čekanje.
Također, ostale tablice se prikladno ažuriraju.
Stranica za svaku prodaju uzima proporcionalnu komisiju.

## Graf

Za svaku dionicu pamtimo njenu `zadnju_cijenu`. Isto tako, iz `burze_transakcije` možemo isčitati povijest cijena.
Jedini klijentski dio našeg projekta je iscrtavanje grafa povijesti cijena. 
U PHP-u imamo `query.php` dio koji pribavlja tražene podatke SQL naredbama. 
Odgovara na `Ajax` upit `(GET)` koji `JavaScript` šalje iz `jedna_dionica_index.php`.
Zatim koristi dobiveni `JSON` objekt kako bi alatom `CanvasJS` prikazao graf.
