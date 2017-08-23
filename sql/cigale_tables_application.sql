create table cigale.epci as 
select 
	siren_epci_2017 as siren_epci, 
	nom_epci_2017 as nom_epci,
	superficie
from sig.epci_geofla_4326;

SELECT AddGeometryColumn ('cigale','epci','geom',4326,'MULTIPOLYGON',2, false);

update cigale.epci as a
set geom = b.geom
from sig.epci_geofla_4326 as b
where a.siren_epci = b.siren_epci_2017;

alter table cigale.epci add constraint "pk.cigale.epci" primary key (siren_epci);
CREATE INDEX "gidx.cigale.epci.geom.gist" ON cigale.epci USING GIST (geom);

comment on table cigale.epci is '
EPCI crée à partir des communes GEOFLA 2017
Le champ superficie est en hectares


Code SQL de création de la table:
----------------------------------------
create table cigale.epci as 
select 
	siren_epci_2017 as siren_epci, 
	nom_epci_2017 as nom_epci,
	superficie
from sig.epci_geofla_4326;

SELECT AddGeometryColumn (''cigale'',''epci'',''geom'',4326,''MULTIPOLYGON'',2, false);

update cigale.epci as a
set geom = b.geom
from sig.epci_geofla_4326 as b
where a.siren_epci = b.siren_epci_2017;

alter table cigale.epci add constraint "pk.cigale.epci" primary key (siren_epci);
CREATE INDEX "gidx.cigale.epci.geom.gist" ON cigale.epci USING GIST (geom);
';




drop table cigale.epci_2154;
create table cigale.epci_2154 as 
select siren_epci, nom_epci
from cigale.epci;

SELECT AddGeometryColumn ('cigale','epci_2154','geom',2154,'MULTIPOLYGON',2, false);

update cigale.epci_2154 as a
set geom = st_transform(b.geom, 2154)
from cigale.epci as b
where a.siren_epci = b.siren_epci;

alter table cigale.epci_2154 add constraint "pk.cigale.epci_2154" primary key (siren_epci);
CREATE INDEX "gidx.cigale.epci_2154.geom.gist" ON cigale.epci_2154 USING GIST (geom);

comment on table cigale.epci_2154 is '
create table cigale.epci_2154 as 
select siren_epci, nom_epci
from cigale.epci;

SELECT AddGeometryColumn (''cigale'',''epci_2154'',''geom'',2154,''MULTIPOLYGON'',2, false);

update cigale.epci_2154 as a
set geom = st_transform(b.geom, 2154)
from cigale.epci as b
where a.siren_epci = b.siren_epci;

alter table cigale.epci_2154 add constraint "pk.cigale.epci_2154" primary key (siren_epci);
CREATE INDEX "gidx.cigale.epci_2154.geom.gist" ON cigale.epci_2154 USING GIST (geom);
';





drop table if exists cigale.epci_poll;
create table cigale.epci_poll WITH OIDS as 
select  
	row_number() over () as gid,
	an,
	nom_abrege_polluant, 
	siren_epci_2017 as siren_epci,
	nom_epci_2017 as nom_epci,
	sum(val)::numeric / (superficie / 100.)::numeric as val -- Superficie hectares -> km2
from total.bilan_comm_v4_secten1 as a
left join commun.tpk_commune_2015_2016 as b using (id_comm)
left join commun.tpk_polluants as c using (id_polluant)
left join cigale.epci as d on b.siren_epci_2017 = d.siren_epci
where 
	an = 2015
	and not (id_polluant in (38,65,108,16,48,36) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastrée: Pord d'énergie mais pas d'élec ni chaleur
	and not (id_polluant not in (38,65,108,16,48,36) and id_secten1 = '1') -- GES et Ener = Finale
	and ss is false -- Sans aucune donnée soumise au SS	
group by an, nom_abrege_polluant, siren_epci_2017, nom_epci_2017, superficie
order by an, nom_abrege_polluant, siren_epci_2017, nom_epci_2017, superficie;

SELECT AddGeometryColumn ('cigale','epci_poll','geom',4326,'MULTIPOLYGON',2, false);

ALTER TABLE cigale.epci_poll drop CONSTRAINT enforce_geotype_geom;

update cigale.epci_poll as a
set geom = ST_SimplifyPreserveTopology(b.geom,0.0004) -- Simplification de la géométrie pour rapidité d'affichage
from cigale.epci as b
where a.siren_epci = b.siren_epci;

alter table cigale.epci_poll add constraint "pk.cigale.epci_poll" primary key (nom_abrege_polluant, siren_epci);
CREATE INDEX "gidx.cigale.epci_poll.geom.gist" ON cigale.epci_poll USING GIST (geom);
CREATE INDEX "idx.cigale.epci_poll.id_polluant" ON cigale.epci_poll (nom_abrege_polluant);

comment on table cigale.epci_poll is '
NOTE: Pour la conso énergie primaire c''est à dire sans l''élec
NOTE: Pour les GES, émissions directes c''est à dire sans l''élec
';

vacuum analyze cigale.epci_poll;
vacuum freeze cigale.epci_poll;





drop table if exists cigale.comm_poll;
create table cigale.comm_poll WITH OIDS as  
select 
	row_number() over () as gid, 
	nom_abrege_polluant, nom_comm, siren_epci_2017 as siren_epci, 
	val / (d.superficie / 100.) as val, -- Superficie en hectares dans les données geofla
	st_transform(geom, 4326) as geomtmp
from (
	select id_polluant, an, id_comm, sum(val) as val
	from total.bilan_comm_v4_secten1
	where 
		an = 2015
		and not (id_polluant in (38,65,108,16,48,36,16) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastrée: Pord d'énergie mais pas d'élec ni chaleur
		and not (id_polluant not in (38,65,108,16,48,36,16) and id_secten1 = '1') -- GES et Ener = Finale
		and ss is false -- Sans aucune donnée en SS
	group by id_polluant, an, id_comm
) as a
left join commun.tpk_polluants as b using (id_polluant)
left join commun.tpk_commune_2015_2016 as c using (id_comm)
left join (
	select 
		case when insee_com::integer between 13201 and 13216 then 13055 else insee_com::integer end as id_comm,
		sum(superficie) as superficie,
		st_union(geom) as geom
	from sig.geofla2016_communes
	where
		code_reg = '93'
	group by 
		case when insee_com::integer between 13201 and 13216 then 13055 else insee_com::integer end
) as d using (id_comm)
where 
	nom_abrege_polluant in ('conso','so2','nox','pm10','pm2.5','covnm','nh3','co2','ch4.co2e','n2o.co2e','prg100.3ges')
;

SELECT AddGeometryColumn ('cigale','comm_poll','geom',4326,'MULTIPOLYGON',2, false);

update cigale.comm_poll
set geom = ST_Multi(geomtmp);

alter table cigale.comm_poll add constraint "pk.cigale.comm_poll" primary key (gid);
CREATE INDEX "gidx.cigale.comm_poll.geom.gist" ON cigale.comm_poll USING GIST (geom);

comment on table cigale.comm_poll is '
NOTE: Pour la conso énergie primaire c''est à dire sans l''élec
NOTE: Pour les GES, émissions directes c''est à dire sans l''élec
';

vacuum analyse cigale.comm_poll;
vacuum freeze cigale.comm_poll;



