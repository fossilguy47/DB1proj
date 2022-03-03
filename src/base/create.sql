
CREATE SEQUENCE proj.zatrudnienie_id_zatrudnienie_seq;

CREATE TABLE proj.zatrudnienie (
                id_zatrudnienie INTEGER NOT NULL DEFAULT nextval('proj.zatrudnienie_id_zatrudnienie_seq'),
                poczatek_zatrudnienia DATE NOT NULL,
                koniec_zatrudnienia DATE,
                CONSTRAINT zatrudnienie_pk PRIMARY KEY (id_zatrudnienie)
);


ALTER SEQUENCE proj.zatrudnienie_id_zatrudnienie_seq OWNED BY proj.zatrudnienie.id_zatrudnienie;

CREATE SEQUENCE proj.uprawnienia_id_uprawnienia_seq;

CREATE TABLE proj.uprawnienia (
                id_uprawnienia INTEGER NOT NULL DEFAULT nextval('proj.uprawnienia_id_uprawnienia_seq'),
                nazwa_stanowiska VARCHAR(124) NOT NULL,
                opis VARCHAR(124) NOT NULL,
                CONSTRAINT uprawnienia_pk PRIMARY KEY (id_uprawnienia)
);


ALTER SEQUENCE proj.uprawnienia_id_uprawnienia_seq OWNED BY proj.uprawnienia.id_uprawnienia;

CREATE SEQUENCE proj.producent_id_producent_seq;

CREATE TABLE proj.producent (
                id_producent INTEGER NOT NULL DEFAULT nextval('proj.producent_id_producent_seq'),
                nazwa VARCHAR(100) NOT NULL,
                emial VARCHAR(124) NOT NULL,
                telefon VARCHAR(9) NOT NULL,
                CONSTRAINT producent_pk PRIMARY KEY (id_producent)
);


ALTER SEQUENCE proj.producent_id_producent_seq OWNED BY proj.producent.id_producent;

CREATE SEQUENCE proj.dzial_id_dzial_seq;

CREATE TABLE proj.dzial (
                id_dzial INTEGER NOT NULL DEFAULT nextval('proj.dzial_id_dzial_seq'),
                opis VARCHAR(100),
                nazwa VARCHAR(50) NOT NULL,
                CONSTRAINT dzial_pk PRIMARY KEY (id_dzial)
);


ALTER SEQUENCE proj.dzial_id_dzial_seq OWNED BY proj.dzial.id_dzial;

CREATE SEQUENCE proj.produkt_id_produkt_seq;

CREATE TABLE proj.produkt (
                id_produkt INTEGER NOT NULL DEFAULT nextval('proj.produkt_id_produkt_seq'),
                id_dzial INTEGER NOT NULL,
                nazwa VARCHAR(50),
                opis VARCHAR(100),
                cena DOUBLE PRECISION NOT NULL,
                id_producent INTEGER NOT NULL,
                CONSTRAINT produkt_pk PRIMARY KEY (id_produkt)
);


ALTER SEQUENCE proj.produkt_id_produkt_seq OWNED BY proj.produkt.id_produkt;

CREATE SEQUENCE proj.adres_id_adres_seq;

CREATE TABLE proj.adres (
                id_adres INTEGER NOT NULL DEFAULT nextval('proj.adres_id_adres_seq'),
                kraj VARCHAR(50),
                miasto VARCHAR(50),
                ulica_i_numer VARCHAR(50),
                kod_pocztowy VARCHAR(6) NOT NULL,
                CONSTRAINT adres_pk PRIMARY KEY (id_adres)
);


ALTER SEQUENCE proj.adres_id_adres_seq OWNED BY proj.adres.id_adres;

CREATE SEQUENCE proj.magazyn_id_magazyn_seq;

CREATE TABLE proj.magazyn (
                id_magazyn INTEGER NOT NULL DEFAULT nextval('proj.magazyn_id_magazyn_seq'),
                id_adres INTEGER NOT NULL,
                glowny BOOLEAN NOT NULL,
                CONSTRAINT magazyn_pk PRIMARY KEY (id_magazyn)
);


ALTER SEQUENCE proj.magazyn_id_magazyn_seq OWNED BY proj.magazyn.id_magazyn;

CREATE SEQUENCE proj.sprzedawca_id_sprzedawca_seq;

CREATE TABLE proj.sprzedawca (
                id_sprzedawca INTEGER NOT NULL DEFAULT nextval('proj.sprzedawca_id_sprzedawca_seq'),
                id_magazyn INTEGER ,
                id_uprawnienia INTEGER NOT NULL,
                id_adres INTEGER NOT NULL,
                nazwisko VARCHAR(50),
                imie VARCHAR(50),
                email VARCHAR(150),
                telefon VARCHAR(20),
                id_zatrudnienie INTEGER NOT NULL,
                CONSTRAINT sprzedawca_pk PRIMARY KEY (id_sprzedawca)
);


ALTER SEQUENCE proj.sprzedawca_id_sprzedawca_seq OWNED BY proj.sprzedawca.id_sprzedawca;

CREATE TABLE proj.logowanie (
                id_sprzedawca INTEGER NOT NULL,
                login VARCHAR(124) NOT NULL,
                haslo VARCHAR(124) NOT NULL,
                CONSTRAINT logowanie_pk PRIMARY KEY (id_sprzedawca)
);


CREATE TABLE proj.magazyn_produkt (
                id_produkt INTEGER NOT NULL,
                id_magazyn INTEGER NOT NULL,
                ilosc INTEGER NOT NULL,
                CONSTRAINT magazyn_produkt_pk PRIMARY KEY (id_produkt, id_magazyn)
);


CREATE SEQUENCE proj.klient_id_klient_seq;

CREATE TABLE proj.klient (
                id_klient INTEGER NOT NULL DEFAULT nextval('proj.klient_id_klient_seq'),
                id_adres INTEGER NOT NULL,
                nazwisko VARCHAR(50),
                imie VARCHAR(50),
                email VARCHAR(150),
                nip VARCHAR(10),
                CONSTRAINT klient_pk PRIMARY KEY (id_klient)
);


ALTER SEQUENCE proj.klient_id_klient_seq OWNED BY proj.klient.id_klient;

CREATE SEQUENCE proj.zamowienie_id_zamowienie_seq;

CREATE TABLE proj.zamowienie (
                id_zamowienie INTEGER NOT NULL DEFAULT nextval('proj.zamowienie_id_zamowienie_seq'),
                id_klient INTEGER NOT NULL,
                status VARCHAR(50),
                data DATE NOT NULL,
                kwota DOUBLE PRECISION NOT NULL,
                CONSTRAINT zamowienie_pk PRIMARY KEY (id_zamowienie)
);


ALTER SEQUENCE proj.zamowienie_id_zamowienie_seq OWNED BY proj.zamowienie.id_zamowienie;

CREATE TABLE proj.zamowienie_szczegoly (
                id_produkt INTEGER NOT NULL,
                id_magazyn INTEGER NOT NULL,
                id_zamowienie INTEGER NOT NULL,
                ilosc NUMERIC(100) NOT NULL,
                CONSTRAINT zamowienie_szczegoly_pk PRIMARY KEY (id_produkt, id_magazyn, id_zamowienie)
);


CREATE SEQUENCE proj.metoda_platnosci_id_metoda_platnosci_seq;

CREATE TABLE proj.metoda_platnosci (
                id_metoda_platnosci INTEGER NOT NULL DEFAULT nextval('proj.metoda_platnosci_id_metoda_platnosci_seq'),
                id_zamowienie INTEGER NOT NULL,
                metoda_platnosci VARCHAR(100) NOT NULL,
                data_platnosci TIMESTAMP,
                CONSTRAINT metoda_platnosci_pk PRIMARY KEY (id_metoda_platnosci)
);


ALTER SEQUENCE proj.metoda_platnosci_id_metoda_platnosci_seq OWNED BY proj.metoda_platnosci.id_metoda_platnosci;

ALTER TABLE proj.sprzedawca ADD CONSTRAINT zatrudnienie_sprzedawca_fk
FOREIGN KEY (id_zatrudnienie)
REFERENCES proj.zatrudnienie (id_zatrudnienie)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.sprzedawca ADD CONSTRAINT uprawnienia_sprzedawca_fk
FOREIGN KEY (id_uprawnienia)
REFERENCES proj.uprawnienia (id_uprawnienia)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.produkt ADD CONSTRAINT producent_produkt_fk
FOREIGN KEY (id_producent)
REFERENCES proj.producent (id_producent)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.produkt ADD CONSTRAINT dzial_produkt_fk
FOREIGN KEY (id_dzial)
REFERENCES proj.dzial (id_dzial)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.magazyn_produkt ADD CONSTRAINT produkt_magazyn_produkt_fk
FOREIGN KEY (id_produkt)
REFERENCES proj.produkt (id_produkt)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.klient ADD CONSTRAINT adres_klient_fk
FOREIGN KEY (id_adres)
REFERENCES proj.adres (id_adres)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.magazyn ADD CONSTRAINT adres_magazyn_fk
FOREIGN KEY (id_adres)
REFERENCES proj.adres (id_adres)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.sprzedawca ADD CONSTRAINT adres_sprzedawca_fk
FOREIGN KEY (id_adres)
REFERENCES proj.adres (id_adres)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.magazyn_produkt ADD CONSTRAINT magazyn_magazyn_produkt_fk
FOREIGN KEY (id_magazyn)
REFERENCES proj.magazyn (id_magazyn)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.sprzedawca ADD CONSTRAINT magazyn_sprzedawca_fk
FOREIGN KEY (id_magazyn)
REFERENCES proj.magazyn (id_magazyn)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.logowanie ADD CONSTRAINT sprzedawca_logowanie_fk
FOREIGN KEY (id_sprzedawca)
REFERENCES proj.sprzedawca (id_sprzedawca)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.zamowienie_szczegoly ADD CONSTRAINT magazyn_produkt_zamowienie_szczegoly_fk
FOREIGN KEY (id_produkt, id_magazyn)
REFERENCES proj.magazyn_produkt (id_produkt, id_magazyn)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.zamowienie ADD CONSTRAINT klient_zamowienie_fk
FOREIGN KEY (id_klient)
REFERENCES proj.klient (id_klient)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.metoda_platnosci ADD CONSTRAINT zamowienie_metoda_platnosci_fk
FOREIGN KEY (id_zamowienie)
REFERENCES proj.zamowienie (id_zamowienie)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;

ALTER TABLE proj.zamowienie_szczegoly ADD CONSTRAINT zamowienie_zamowienie_szczegoly_fk
FOREIGN KEY (id_zamowienie)
REFERENCES proj.zamowienie (id_zamowienie)
ON DELETE NO ACTION
ON UPDATE NO ACTION
NOT DEFERRABLE;