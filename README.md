# rp2-burza

Virtualna burza vrijednosnih papira

Opis zadatka:

> Svaki korisnik tokom registracije dobije fiksan početni kapital. Korisnik ima mogućnost prodaje i kupnje dionica. Klikom na dionicu dobiju se dodatne informacije te povijest cijena dionica. Na glavnoj stranici korisnik može vidjet cijeli svoj portfolio sa informacijama o neto vrijednosti korisnika, ukupna zarada, dnevna zarada, dividendu, povijest transakcija itd. Također postoji lista najbogatijih korisnika po neto vrijednosti te korisnik može vidjeti svoj rank. Administrator stranice može postavit početni kapital, komisiju, dividendu, kamatnu stopu itd. 

## Postavljanje baze

1. Otvori terminal pa `sudo mysql`
2. `CREATE DATABASE 'burza';`
3. `CREATE USER 'burza'@'localhost' IDENTIFIED BY 'burza';`
4. `GRANT ALL PRIVILEGES ON burza.* TO 'burza'@'localhost';`

## Overview baze

`burza_users`

1. id int
2. username varchar(50)
3. password_hash varchar(255)
4. email varchar(50)
5. registration_sequence varchar(20)
6. has_registered int

`burza_privileges`

1. id_user int
2. admin bool

`burza_dionice`

1. id int
2. ime varchar(50)
3. ticker varchar(4)
4. izdano int

`burza_transakcije`

1. id int
2. id_dionica int
3. kolicina int
4. cijena int
5. prodao int
6. kupio int

`burza_kapital`

1. id_user int
2. kapital int

`burza_imovina`

1. id_user int
2. id_dionica int
3. kolicina int
