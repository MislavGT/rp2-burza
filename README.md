# rp2-burza

Virtualna burza vrijednosnih papira

Opis zadatka:

1. :heavy_check_mark: Svaki korisnik tokom registracije dobije fiksan početni kapital.
2. :x: Korisnik ima mogućnost prodaje i kupnje dionica.
3. :x: Klikom na dionicu dobiju se dodatne informacije te povijest cijena dionica.
4. :x: Na glavnoj stranici korisnik može vidjet cijeli svoj portfolio sa informacijama o:
    1. :x: neto vrijednosti korisnika
    2. :x: ukupna zarada
    3. :x: dnevna zarada
    4. :x: dividendu
    5. :x: povijest transakcija itd.
5. :x: Također postoji lista najbogatijih korisnika po neto vrijednosti te korisnik može vidjeti svoj rank.
6. :x: Administrator stranice može postavit početni kapital, komisiju, dividendu, kamatnu stopu itd.

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

### `burza_transakcije`

1. id int
2. id_dionice int
3. kolicina int
4. cijena int
5. prodao int
6. kupio int
7. datum date

### `burza_kapital`

1. id_user int
2. kapital int

### `burza_imovina`

1. id_user int
2. id_dionica int
3. kolicina int
