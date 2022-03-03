SET search_path TO proj;

INSERT INTO proj.uprawnienia VALUES(DEFAULT,'kierownik','Zatrudnia pracowników, nadaje im uprawnienia do zarządzaniem konkretnym magazynem.');
INSERT INTO proj.uprawnienia VALUES(DEFAULT,'magazynier','Obsługuje przydzielony magazyn - dodaje nowe produkty, dodaje nowych klientów i realizuje ich zamowienia.');

SELECT proj.dodajMagazyn(true,'Wojska Polskiego 8','30-149','Szczecin');
SELECT proj.dodajMagazyn(false,'Krakowska 7','32-050','Kolobrzeg');
SELECT proj.dodajMagazyn(false,'Mickiewicza 78','33-300','Zakopane');
SELECT proj.dodajMagazyn(false,'Arkońska 3','33-300','Wrocław');

SELECT proj.dodajPracownika('kierownik','2021-08-04',null,'Bartosz','Nowak','bnowak@gmail.com','111112440','Mieszka 1','71-141','Warszwa','nowak','nowak');
SELECT proj.dodajPracownika('magazynier','2020-12-12',1,'Krzysiu','Wierzbicki','kwierzbicki@o2.pl','927474758','Dobrego Pasterza 98','30-180','Kraków','krzysiutegiepaly','krrs34s');
SELECT proj.dodajPracownika('magazynier','2018-10-20',2,'Karol','Klops','kklops@gmail.com','115978234','Jamnicka 67','32-987','Kraków','klopsini','tylkomielone');
SELECT proj.dodajPracownika('magazynier','2016-04-03',3,'Barbara','Babacka','babacka@wp.com','547823674','Grubsona 112','21-443','Nowy Targ','babbbba','cka'); 

SELECT proj.dodajproducenta('JABLKOSAD','jablkosad@wp.com', '325612345');
SELECT proj.dodajproducenta('GRUSZKOPOL','grupol@gmail.pl', '342342195');
SELECT proj.dodajproducenta('WARZYWEX','warzyw@sad.pl', '55555500');
SELECT proj.dodajproducenta('POMARANCZIL','pom@pom.com', '507507935');
SELECT proj.dodajproducenta('RABAR','rabar@ragaba.eu', '601998698');
SELECT proj.dodajproducenta('POMIDORO','elpomidoro@pomidoro.pl', '783783700');
SELECT proj.dodajproducenta('SADEX','kontakt@sadex.pl', '783783700');
SELECT proj.dodajproducenta('SMACZNA SZAMKA','ss@szamka.de', '642221092');

SELECT proj.dodajkategorie('OWOCE KRAJOWE', 'Owoce dostępne na ternie kraju.');
SELECT proj.dodajkategorie('OWOCE TROPIKALNE', 'Owoce z różnych zakątków świata.');
SELECT proj.dodajkategorie('MIĘSO', 'Wyrony mięsne - wieprzowina, drób, wołowina.');
SELECT proj.dodajkategorie('WARZYWA', 'Świeże, soczyste warzywa, zielone i nie tylko.');

SELECT proj.dodajprodukt(1,1,'Jabłko', 2.39);
SELECT proj.dodajprodukt(4,3,'Pomidor', 6.77);
SELECT proj.dodajprodukt(2,6,'Banan', 4.55);
SELECT proj.dodajprodukt(3,2,'Pierś z kurczaka', 18.89);
SELECT proj.dodajprodukt(3,8,'Wołowina - gulasz', 15.43);
SELECT proj.dodajprodukt(1,2,'Gruszka', 4.34);
SELECT proj.dodajprodukt(2,5,'Kiwi', 6.91);
SELECT proj.dodajprodukt(1,4,'Pomarancze', 3.45);
SELECT proj.dodajprodukt(1,7,'Śliwki', 3.22);
SELECT proj.dodajprodukt(2,3,'Gruszka', 4.55);

SELECT proj.dodajAsortyment(1,1,80);
SELECT proj.dodajAsortyment(1,2,75);
SELECT proj.dodajAsortyment(1,3,40);
SELECT proj.dodajAsortyment(1,4,28);
SELECT proj.dodajAsortyment(1,5,56);
SELECT proj.dodajAsortyment(1,7,42);

SELECT proj.noweZamowienie('Agata','Kowalska', 'kowalag@wp.pl', '7644323476', 'Lubicz 912', '31-203', 'Kraków');
SELECT proj.dodajDoZamowienia(3,1,1,12);
SELECT proj.dodajDoZamowienia(3,3,1,4);
SELECT proj.noweZamowienie('Karol','Klocek', 'klocek@onet.pl', '5394285823',  'Lechicka 44', '80-400', 'Gdańska');
SELECT proj.dodajDoZamowienia(4,2,1,6);
SELECT proj.dodajDoZamowienia(4,4,1,1);
SELECT proj.dodajDoZamowienia(4,5,1,1);
SELECT proj.noweZamowienie('Marek', 'Walkowiak', 'meslk@gmail.pl', '2953750138',  'Wielkopolska 8', '67-222', 'Poznań');
SELECT proj.dodajDoZamowienia(5,3,1,2);
SELECT proj.dodajDoZamowienia(5,7,1,1);

