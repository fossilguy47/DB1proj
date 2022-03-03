CREATE VIEW proj.pracownicy_lista AS 
  SELECT p.id_sprzedawca,
    w.login,
    p.imie,
    p.nazwisko,
    u.nazwa_stanowiska,
    p.id_magazyn,
    p.email,
    p.telefon,
    a.ulica_i_numer,
	a.kod_pocztowy,
    a.miasto,
    z.poczatek_zatrudnienia,
    z.koniec_zatrudnienia
    FROM proj.sprzedawca p JOIN proj.adres a USING(id_adres) JOIN proj.zatrudnienie z USING(id_zatrudnienie) JOIN proj.logowanie w USING(id_sprzedawca) JOIN proj.uprawnienia u USING(id_uprawnienia);


	CREATE VIEW proj.magazyny_lista AS
 SELECT DISTINCT 
 	m.id_magazyn,
    m.glowny,
    a.ulica_i_numer,
	a.kod_pocztowy,
    a.miasto,
	p.imie,
	p.nazwisko
   FROM proj.magazyn m
	LEFT JOIN proj.adres a USING(id_adres) LEFT JOIN proj.sprzedawca p USING(id_magazyn)
	GROUP BY m.id_magazyn, m.glowny, a.ulica_i_numer, a.kod_pocztowy, a.miasto, p.imie, p.nazwisko
	 ORDER BY id_magazyn;

	

	
CREATE VIEW proj.pracownicy_zatrudnienie_okres AS
 SELECT p.id_sprzedawca,
    w.login,
    z.poczatek_zatrudnienia,
    z.koniec_zatrudnienia 
   FROM proj.sprzedawca p,
    proj.zatrudnienie z,
    proj.logowanie w
   WHERE w.id_sprzedawca=p.id_sprzedawca AND p.id_zatrudnienie=z.id_zatrudnienie;


	
CREATE VIEW proj.produkty_lista AS
 SELECT prkt.id_produkt,
    k.nazwa as dzial,
    prkt.nazwa,
    prnt.nazwa as producent, 
    prkt.cena 
  FROM 
    proj.produkt prkt,
    proj.producent prnt, 
	proj.dzial k 
  WHERE 
    k.id_dzial=prkt.id_dzial AND 
	prkt.id_producent=prnt.id_producent
	GROUP BY id_produkt, dzial, prkt.nazwa, producent, prkt.cena;



	CREATE VIEW proj.pelny_stan_magazynow AS 
  SELECT DISTINCT
    prkt.id_produkt,
    k.nazwa AS dzial,
    prkt.nazwa AS nazwa_produktu,
    prkt.cena,
    prkt.id_producent,
    prnt.nazwa AS producent,
	ms.id_magazyn,
	ms.ilosc
  FROM 
	proj.magazyn_produkt ms, 
	proj.produkt prkt, 
	proj.dzial k,
	proj.producent prnt

  WHERE 

	prkt.id_produkt=ms.id_produkt AND
	k.id_dzial=prkt.id_dzial AND
	prkt.id_producent=prnt.id_producent;
	
	

CREATE VIEW proj.pelny_widok_szczegoly_zamowienia AS
  SELECT zs.id_magazyn, 
    zs.id_zamowienie, 
	zs.id_produkt, 
	psm.dzial, 
	psm.nazwa_produktu, 
	psm.id_producent, 
	psm.producent, 
	psm.cena, 
	zs.ilosc 
  FROM 
    proj.pelny_stan_magazynow psm,
	proj.zamowienie_szczegoly zs
  WHERE 
    zs.id_produkt=psm.id_produkt AND 
	zs.id_magazyn=psm.id_magazyn 
  ORDER BY 
    id_magazyn, 
	id_zamowienie;			


  CREATE VIEW proj.zamowienia_lista AS
  SELECT 
	DISTINCT z.id_zamowienie,
	zs.id_magazyn,
	z.data,
	z.status,
	k.nazwisko,
	k.imie,
	k.NIP,
	k.email,
	a.id_adres,
	a.ulica_i_numer,
	a.miasto,
	z.kwota
  FROM proj.zamowienie_szczegoly zs JOIN 
	proj.zamowienie z USING(id_zamowienie) JOIN 
	proj.klient k USING(id_klient) 
	JOIN proj.adres a USING(id_adres)
	WHERE z.kwota >0
  ORDER BY z.status DESC;
  
 
 CREATE VIEW proj.platnosci_lista AS
  SELECT 
	DISTINCT p.id_metoda_platnosci,
	p.metoda_platnosci as status_platnosci,
	p.data_platnosci,
	z.id_zamowienie,
	zs.id_magazyn,
	z.status as status_zamowienia,
	z.id_klient,
	k.imie,
	k.nazwisko,
	k.NIP,
	k.email,
	a.id_adres,
	a.ulica_i_numer,
	a.miasto,
	z.data,
	z.kwota
  FROM proj.zamowienie_szczegoly zs JOIN 
	proj.metoda_platnosci p USING(id_zamowienie) JOIN 
	proj.zamowienie z USING(id_zamowienie) JOIN 
	proj.klient k USING(id_klient) JOIN 
	proj.adres a USING(id_adres) 
	WHERE z.kwota > 0
  ORDER BY p.data_platnosci, z.status;
 

  CREATE OR REPLACE FUNCTION proj.dodajAdres (ul_i_nr VARCHAR ,kp VARCHAR(6), m VARCHAR) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (SELECT * FROM proj.adres a WHERE a.ulica_i_numer=$1 AND a.kod_pocztowy=$2 AND a.miasto=$3)
	THEN
		INSERT INTO proj.adres VALUES(DEFAULT, $3, $1 , $2);
		RETURN TRUE;
	ELSE
		raise notice 'dlaczego ale w adresie';
		RETURN false;
	END IF;
END;

$$
  LANGUAGE 'plpgsql';



CREATE OR REPLACE FUNCTION proj.dodajMagazyn (main BOOLEAN, ul_i_nr VARCHAR, kp VARCHAR(6), m VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE adresId integer;
DECLARE magId integer;
BEGIN
	adresId := (SELECT id_adres FROM proj.adres a WHERE a.ulica_i_numer=$2 AND a.kod_pocztowy=$3 AND a.miasto=$4);
	IF adresId IS NOT NULL THEN
		magId := (SELECT id_magazyn FROM proj.magazyn m WHERE m.id_adres=adresId);
	ELSE
		PERFORM dodajAdres($2,$3,$4);
		adresId := (SELECT id_adres FROM proj.adres a WHERE a.ulica_i_numer=$2 AND a.kod_pocztowy=$3 AND a.miasto=$4);
		magId := (SELECT id_magazyn FROM proj.magazyn m WHERE m.id_adres=adresId);
	END IF;
	IF magId IS NOT NULL THEN
		raise notice 'value';
		RETURN FALSE;
	ELSE
		INSERT INTO proj.magazyn VALUES(DEFAULT,adresId, $1);
		RETURN TRUE;
	END IF;	
END;
$$ 
  LANGUAGE 'plpgsql';


CREATE OR REPLACE FUNCTION proj.dodajPracownika(stanowisko VARCHAR, dataStartu DATE, id_magazynu INTEGER, imie VARCHAR, nazwisko VARCHAR, email VARCHAR, telefon VARCHAR, ulica_i_numer VARCHAR,  kod_pocztowy VARCHAR(6), miasto VARCHAR, login VARCHAR, haslo VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE adresId integer;
DECLARE zatrudId integer;
DECLARE uprawId integer;
DECLARE pracowIst integer;
DECLARE id_pracow integer;
BEGIN
	pracowIst := (SELECT p.id_sprzedawca FROM proj.sprzedawca  p WHERE p.imie=$4 AND p.nazwisko=$5 AND p.email=$6 AND p.telefon=$7);
	zatrudId := (SELECT z.id_zatrudnienie FROM proj.zatrudnienie z WHERE z.poczatek_zatrudnienia=$2);
	adresId := (SELECT a.id_adres FROM proj.adres a WHERE a.ulica_i_numer=$8  AND a.kod_pocztowy=$9 AND a.miasto=$10);
	uprawId := (SELECT u.id_uprawnienia FROM proj.uprawnienia u WHERE u.nazwa_stanowiska=$1);
	IF pracowIst IS NOT NULL THEN
		raise notice 'value';
		RETURN false;
	END IF;
	IF zatrudId IS NULL THEN
		INSERT INTO proj.zatrudnienie VALUES(DEFAULT,$2,NULL);
		zatrudId := (SELECT z.id_zatrudnienie FROM proj.zatrudnienie z WHERE z.poczatek_zatrudnienia=$2);
	END IF;
	
	IF adresId IS NULL THEN
		PERFORM proj.dodajAdres($8,$9,$10);
		adresId := (SELECT a.id_adres FROM proj.adres a WHERE a.ulica_i_numer=$8 AND a.kod_pocztowy=$9 AND a.miasto=$10);
	END IF;
		
	IF zatrudId IS NOT NULL AND adresId IS NOT NULL THEN
		INSERT INTO proj.sprzedawca VALUES(DEFAULT, $3,uprawId,adresId,$5,$4,$6,$7,zatrudId);
		id_pracow := (SELECT p.id_sprzedawca FROM proj.sprzedawca p WHERE p.id_zatrudnienie=zatrudId AND p.id_uprawnienia=uprawId AND p.imie=$4 AND p.nazwisko=$5 AND p.email=$6 AND p.telefon=$7 AND p.id_adres=adresId);
		INSERT INTO proj.logowanie VALUES(id_pracow, $11,md5($12));
		RETURN true;
	END IF;	
END;
$$ 
  LANGUAGE 'plpgsql';


  CREATE OR REPLACE FUNCTION proj.zwolnijMagazyniera(id_prac integer) RETURNS BOOLEAN AS
$$
DECLARE id_zatr integer;
DECLARE id_upraw integer;
DECLARE num integer;
DECLARE start_date DATE;
DECLARE end_date DATE;
BEGIN
    id_zatr := (SELECT z.id_zatrudnienie FROM proj.zatrudnienie z, proj.sprzedawca p WHERE p.id_sprzedawca=$1 AND p.id_zatrudnienie=z.id_zatrudnienie);
    id_upraw := (SELECT p.id_uprawnienia FROM proj.sprzedawca p WHERE p.id_sprzedawca=$1);
    start_date := (SELECT z.poczatek_zatrudnienia FROM proj.zatrudnienie z, proj.sprzedawca p WHERE p.id_sprzedawca=$1 AND p.id_zatrudnienie=z.id_zatrudnienie);
    num := (SELECT COUNT(*) FROM proj.sprzedawca p WHERE p.id_zatrudnienie=id_zatr);
	end_date := (SELECT z.koniec_zatrudnienia FROM proj.zatrudnienie z WHERE z.id_zatrudnienie=id_zatr);
	IF end_date IS NOT NULL THEN
		RETURN FALSE;
	END IF;
    IF (num > 1) THEN
		end_date := (SELECT CURRENT_DATE);
        INSERT into proj.zatrudnienie VALUES(DEFAULT,start_date,end_date);
        id_zatr := (SELECT z.id_zatrudnienie FROM proj.zatrudnienie z WHERE z.poczatek_zatrudnienia=start_date AND z.koniec_zatrudnienia=end_date); 
        UPDATE proj.sprzedawca s SET s.id_zatrudnienie=id_zatr WHERE s.id_sprzedawca=$1;
        UPDATE proj.sprzedawca s SET s.id_magazyn=null WHERE s.id_sprzedawca=$1;
        RETURN TRUE;
    ELSIF ( num = 1 ) THEN
		end_date := (SELECT CURRENT_DATE);
		UPDATE proj.zatrudnienie z SET z.koniec_zatrudnienia=end_date WHERE z.poczatek_zatrudnienia=start_date AND z.id_zatrudnienie=id_zatr;
        UPDATE proj.sprzedawca s SET s.id_magazyn=null WHERE s.id_sprzedawca=$1;
		RETURN TRUE;
    ELSE
		RETURN FALSE;   
    END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
  

  
CREATE OR REPLACE FUNCTION proj.edytujDanePracownika(id_prac INTEGER, im VARCHAR, nazw VARCHAR, mail VARCHAR,telefon VARCHAR, login VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE old_log VARCHAR;
DECLARE num INTEGER;
BEGIN
	old_log := (SELECT w.login FROM proj.logowanie w WHERE w.id_sprzedawca=$1);
    num := (SELECT COUNT(*) FROM proj.logowanie w WHERE  w.login=old_log);
    IF ( num = 1 ) THEN 
        UPDATE proj.sprzedawca SET imie=$2, nazwisko=$3, email=$4, telefon=$5 WHERE id_sprzedawca=$1;
        UPDATE proj.logowanie SET login=$5 WHERE id_sprzedawca=$1;
        RETURN TRUE;
    ELSE
		RETURN FALSE;   
    END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
  


  CREATE OR REPLACE FUNCTION proj.dodajprodukt(id_kateg INTEGER, id_produc INTEGER, nazwa VARCHAR, cena FLOAT ) RETURNS BOOLEAN AS
$$
BEGIN
	IF NOT EXISTS (SELECT * FROM proj.produkt p WHERE p.nazwa=UPPER($3))
	THEN
		INSERT INTO proj.produkt VALUES(DEFAULT, $1, UPPER($3), $4, $2);
		RETURN TRUE;
	ELSE
		RETURN false;
	END IF;
END;
$$
  LANGUAGE 'plpgsql';
  


  CREATE OR REPLACE FUNCTION proj.dodajAsortyment(id_mag INTEGER, id_produk INTEGER, szt INTEGER ) RETURNS BOOLEAN AS
$$
DECLARE ilosc_s INTEGER;
DECLARE ilosc_n INTEGER;
BEGIN
	IF NOT EXISTS (SELECT * FROM proj.magazyn_produkt WHERE id_magazyn=$1 AND id_produkt=$2)
	THEN
		RETURN FALSE;
	ELSE
		ilosc_s := (SELECT ilosc FROM proj.magazyn_produkt WHERE id_magazyn=$1 AND id_produkt=$2);
		ilosc_n := ilosc_s + $3;
		UPDATE proj.magazyn_produkt SET ilosc=ilosc_n WHERE id_produkt=$2 AND id_magazyn=$1;
		RETURN TRUE;
	END IF;
END;
$$
  LANGUAGE 'plpgsql';
  
  
 
CREATE OR REPLACE FUNCTION proj.inicjacja_produktu() 
 RETURNS trigger AS
$$
DECLARE id_pr INTEGER;
DECLARE temprow RECORD;
BEGIN
	id_pr := new.id_produkt;
	FOR temprow IN 
		SELECT m.id_magazyn FROM proj.magazyn m ORDER BY m.id_magazyn
	LOOP
		IF NOT EXISTS (SELECT * FROM proj.magazyn_produkt ms WHERE ms.id_magazyn=temprow.id_magazyn AND ms.id_produkt=id_pr)
		THEN	
			INSERT INTO proj.magazyn_produkt VALUES(id_pr, temprow.id_magazyn,0);
		END IF;
	END LOOP;
	RETURN new;
END;
$$
  LANGUAGE 'plpgsql';
  --//dodac opis TREIGGERA do dokumentacji 

CREATE TRIGGER proj.inicjuj_produkt_magazyn_stan AFTER INSERT ON proj.produkt FOR EACH ROW EXECUTE PROCEDURE inicjacja_produktu();
  

  

CREATE OR REPLACE FUNCTION proj.dodajKlienta (imie VARCHAR, nazwisko VARCHAR,email VARCHAR, nip VARCHAR(10),  ulica_i_numer VARCHAR, kod VARCHAR, miasto VARCHAR) RETURNS BOOLEAN AS
$$
DECLARE adresId integer;
DECLARE kliId integer;
BEGIN
	PERFORM proj.dodajAdres($5,$6,$7);
	adresId := (SELECT a.id_adres FROM proj.adres a WHERE a.ulica_i_numer=$5  AND a.kod_pocztowy=$6 AND a.miasto=$7);
	kliId := (SELECT k.id_klient FROM proj.klient  k WHERE id_adres=adresId);
	IF kliId IS NULL THEN
		INSERT INTO proj.klient VALUES(DEFAULT,adresId,$2,$1,$3,$4);
	END IF;	
	RETURN TRUE;
END;
$$ 
  LANGUAGE 'plpgsql';
  

  
CREATE OR REPLACE FUNCTION proj.noweZamowienie (imie VARCHAR, nazwisko VARCHAR, email VARCHAR, nip VARCHAR(10), ulica_i_numer VARCHAR, kod VARCHAR, miasto VARCHAR) RETURNS INTEGER AS
$$
DECLARE adresId INTEGER;
DECLARE kliId INTEGER;
DECLARE today DATE;
DECLARE id_now_zam INTEGER;
BEGIN 
	
	today := CURRENT_DATE;
	adresId := (SELECT a.id_adres FROM proj.adres a WHERE a.ulica_i_numer=$5 AND a.kod_pocztowy=$6 AND a.miasto=$7);
	kliId := (SELECT k.id_klient FROM proj.klient k WHERE k.nazwisko=$2 AND k.imie=$1 AND k.NIP=$4 AND k.email=$3);
	
	IF kliId IS NULL THEN
		PERFORM proj.dodajKlienta($1,$2,$3,$4,$5,$6,$7);
		kliId := (SELECT k.id_klient FROM proj.klient k WHERE k.nazwisko=$2 AND k.imie=$1 AND k.NIP=$4 AND k.email=$3);
	END IF;	
	IF adresId IS NULL THEN 
		PERFORM proj.dodajAdres($5,$6,$7);
		adresId := (SELECT a.id_adres FROM proj.adres a WHERE a.ulica_i_numer=$5 AND a.kod_pocztowy=$6 AND a.miasto=$7);
	END IF;
	IF adresId IS NOT NULL AND kliId IS NOT NULL THEN
		id_now_zam := (SELECT nextval('proj.zamowienie_id_zamowienie_seq'));
		INSERT INTO proj.zamowienie VALUES(id_now_zam,kliId,'oczekujace',today,0.00);
		INSERT INTO proj.metoda_platnosci VALUES(DEFAULT,id_now_zam,'oczekujaca',null);
		RETURN id_now_zam;
	ELSE
		
		
		RETURN NULL;
	
	END IF;
END;
$$ 
  LANGUAGE 'plpgsql';
 

CREATE OR REPLACE FUNCTION proj.dodajDoZamowienia (id_zam INTEGER, id_prod INTEGER, id_mag INTEGER, il INTEGER) RETURNS BOOLEAN AS
$$
DECLARE il_s INTEGER;
DECLARE il_n INTEGER;
DECLARE cena_p NUMERIC;
DECLARE kwota_z NUMERIC;
DECLARE suma NUMERIC;
BEGIN
	IF ($4=0) THEN
		RETURN FALSE;
	END IF;
	il_s := (SELECT ms.ilosc FROM proj.magazyn_produkt ms WHERE ms.id_magazyn=$3 AND ms.id_produkt=$2);
	cena_p := (SELECT p.cena FROM proj.produkt p WHERE p.id_produkt=$2);
	suma := (cena_p * $4);
	kwota_z := (SELECT kwota FROM proj.zamowienie WHERE id_zamowienie=$1);
	kwota_z := kwota_z + suma;
	il_n := (il_s - $4);
	IF(il_n > 0) THEN
		UPDATE proj.magazyn_produkt SET ilosc=il_n WHERE id_magazyn=$3 AND id_produkt=$2;
		INSERT INTO proj.zamowienie_szczegoly VALUES($2,$3,$1,$4);
		UPDATE proj.zamowienie SET kwota=kwota_z WHERE id_zamowienie=$1;
		RETURN TRUE;
	ELSE
		RETURN FALSE;
		raise notice 'value';
	END IF;
END;
$$ 
  LANGUAGE 'plpgsql';


  CREATE OR REPLACE FUNCTION proj.zaktualizujPlatnosc (id_plat INTEGER, id_zam INTEGER, id_kli INTEGER, nowy_status VARCHAR(12)) RETURNS BOOLEAN AS
$$
DECLARE obecny_status VARCHAR;
DECLARE temprow RECORD;
DECLARE ilosc_s INTEGER;
BEGIN
	obecny_status := (SELECT data_platnosci FROM proj.metoda_platnosci WHERE id_zamowienie=$2 AND id_metoda_platnosci=$1);
	
		IF (nowy_status='zakonczona') THEN
			UPDATE proj.metoda_platnosci SET metoda_platnosci='zakonczona', data_platnosci=NOW() WHERE id_metoda_platnosci=$1 AND id_zamowienie=$2;
			UPDATE proj.zamowienie SET  status='zakonczona' WHERE id_zamowienie=$2;
			RETURN TRUE;
		ELSIF (nowy_status='oczekujaca') THEN
			UPDATE proj.metoda_platnosci SET metoda_platnosci='oczekujaca', tdata_platnosci=NULL WHERE id_metoda_platnosci=$1 AND id_zamowienie=$2;
			UPDATE proj.zamowienie SET  status='oczekujaca' WHERE id_zamowienie=$2;
				FOR temprow IN 
					SELECT zs.id_magazyn, zs.id_produkt, zs.ilosc FROM proj.zamowienie_szczegoly zs WHERE zs.id_zamowienie=$2
				LOOP
					ilosc_s := (SELECT ms.ilosc FROM magazyn_produkt ms WHERE ms.id_magazyn=temprow.id_magazyn AND ms.id_produkt=temprow.id_produkt);
					UPDATE proj.magazyn_produkt SET ilosc=(ilosc_s+temprow.ilosc) WHERE id_magazyn=temprow.id_magazyn AND id_produkt=temprow.id_produkt;
				END LOOP;
			RETURN TRUE;
		ELSE
			RETURN FALSE;
		END IF;
	
	
END;
$$ 
  LANGUAGE 'plpgsql';
  