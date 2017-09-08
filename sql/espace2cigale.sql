/**

espace2cigale.sql
Air PACA - 2017 - GL/RS

Insertion des donn�es de l'inventaire des �missions dans CIGALE
et traitement du secret statistique.

Polluants pris en compte:
- consommations 		131
- �missions de NOx		38
- �missions de PM10		65
- �missions de PM2.5	108
- �missions de COVNM	16
- �missions de SO2		48
- �missions de NH3		36
- CO2 tot				15 
- CH4 eq_co2			123 
- N2O eq_co2			124 
- PRG1003GES 			128

*/


/** ***************************************************************************
Cr�ation de la table des �missions par SECTEN1, cat�gorie d'�nergie et prise 
en compte du secret statistique

2017-07-17: La derni�re version de la note sur la confidentialit� des donn�es
demande de repsecter le principe de pr�caution tant que l'on ne sait pas si
le traitement des donn�es BDREP est une mission de service public. Il 
ne faut donc diffuser que des �missions IREP!

NOTE: Uniquement pour les polluants n�cessaires � l'interface de visualisation
- consommations 		131
- �missions de NOx		38
- �missions de PM10		65
- �missions de PM2.5	108
- �missions de COVNM	16
- �missions de SO2		48
- �missions de NH3		36
- CO2 tot				15 
- CH4 eq_co2			123 
- N2O eq_co2			124 
- PRG1003GES 			128
NOTE: Pas de secret stat sur usages et branches
NOTE: Pour calculer le secret stat sur le nombre d'�tablissements, utilise les tables 
	  total_ind.bilan_comm et total_ter.bilan_comm. Pour le tertiaire, � part les 
	  �tablissements bdrep, on ne conserve pas l'id_etablissement du SIRENE mais 
	  on regroupe tt au NAF. 
	  TODO: Comment fait HC pour ses calculs SS? 
	  TODO: Peut-on conserver facilement cet id_etab en modifiant les scripts de calcul?
	  
TODO: FAIRE DES TESTS D'EXTRACTION PAR EPCI POUR SAVOIR QUELS EPCI SONT TRONQUES ?!
MAYBE: SI POSSIBLE FAUDRAIT PASSER SI UNE COMM EN SS ET FAIRE UNE EXTRACTION JUSTE A EPCI


TODO: Verif on ne doit pas avoir un etab en SS dont une �mission n'est pas en SS.

TODO:
-- Quels sont les EPCI qui ont un secret stat dans une seule comm.
-- Idem, activite et energie 
-- On fait ce calcul au niveau de d�tail le plus fin
-- Si oui, on passe une donn�e d'une autre commune en secret stat pou avoir deux communes en SS
**************************************************************************** */

/** 
R�cup�ration du nombre d'�tablissements 
- Secteur industriel
- Secteur tertiaire
- Avec affectation d'un SESCTEN1 et cat�gorie d'�nergie
- Les arrondissements de marseille sont transform�s en 13055

NOTE: Pour certains etablissement on a un id_etablissement = -999. L'ensemble de ces 
	  �tablissements ne repr�sentera qu'un seul �tab.
FIXME: On ne ferme aucun �tablissement dans le film. Du coup, on en a de plus en plus?
*/
drop table if exists public.cigale_nb_etab;
create table public.cigale_nb_etab as 
select an, case when id_comm between 13201 and 13216 then 13055 else id_comm end as id_comm, id_secten1, code_cat_energie, count(*) as nb_etab
from (

	-- S�lection des �tablissements industriels dont on connait l'id (!= -999)
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v5_2015 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2015 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v5_2014 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2014 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v5_2013 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2013 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v5_2012 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2012 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v5_2010 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2010 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v5_2007 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2007 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)

-- 	union all

-- 	-- S�lection des �tablissements tertiaire dont on connait l'id (!= -999)
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2015 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2015 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2014 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2014 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2013 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2013 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2012 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2012 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2010 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2010 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2007 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2007 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 5 or b.id_version_corresp is null)

) as a
-- Lien avec les cat�gories d'�nergie
left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
left join total.corresp_snap_synapse as d on a.id_snap3 = d.espace_id_snap3
left join transversal.tpk_snap3 as e on d.synapse_id_snap3 = e.id_snap3
where 
	-- Corresp multiples uniquement pour 60405 qu'on ne retrouve que dans ind et pas ter. 
	-- On conserve uniquement la correspondance ind.
	d.specificite is null or d.synapse_id_snap3 = '06040502'
group by an, case when id_comm between 13201 and 13216 then 13055 else id_comm end, id_secten1, code_cat_energie;

/* Validations
select an, sum(nb_etab) 
from public.cigale_nb_etab
group by an
order by an;

select an, id_secten1, code_cat_energie, sum(nb_etab) 
from public.cigale_nb_etab
group by an, id_secten1, code_cat_energie
order by an, id_secten1, code_cat_energie;
*/

/**
Cr�ation de la table des �missions par secten 1 et cat�gorie d'�nergie
- R�cup�ration des donn�es �lec s�par�es de la table bilan
- R�cup�ration des donn�es GES s�par�es dans autre table bilan
*/
-- Cr�ation de la table finale vide
drop table if exists total.bilan_comm_v4_secten1;
create table total.bilan_comm_v4_secten1 (
	id_polluant smallint NOT NULL,
	an smallint NOT NULL,
	id_comm integer NOT NULL,
	id_secten1 text NOT NULL,
	code_cat_energie smallint NOT NULL,
	id_usage smallint NOT NULL,
	id_branche integer NOT NULL DEFAULT 0,	
	val double precision,
	id_unite integer NOT NULL,
	bdrep boolean,
	code_etab text,
	memo text
);

with emi as (
	-- Premier regroupement des donn�es conso et emi 
	-- avec cat�gories d'energie mais en regroupant les SNAPs
	-- pour traitements sp�cifiques
	-- 
	-- ATTENTION On update certains SNAP non �nerg�tiques, qui ont quand m�me des id_energie dans la table totale
	select 
		id_secteur,
		id_polluant, an, a.id_comm, 
		id_snap3,
		case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end as code_cat_energie, -- code_cat_energie,
		id_usage, id_branche,
		id_unite, 
		sum(val) as val,
		id_corresp, 
		case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end as bdrep,
		d.code_gerep as code_etab, -- Pour calculer le SS � l'�tablissement
		null::text as memo
	from total.bilan_comm_v4 as a
	left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
	left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
	left join (select * from src_ind.def_corresp_sources where id_version_corresp = 5 and actif is true) as d using (id_corresp)
	where id_polluant in (131,38,65,108,16,48,36)
	group by
		id_secteur,
		id_polluant, an, a.id_comm, 
		id_snap3,
		case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end,
		id_usage, id_branche,
		id_unite, 
		id_corresp,		
		case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end,
		d.code_gerep

	union all

	select 
		id_secteur,
		id_polluant, an, a.id_comm, 
		id_snap3,
		case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end as code_cat_energie, -- code_cat_energie,
		id_usage, id_branche,
		id_unite, 
		sum(val) as val,
		id_corresp,		
		case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end as bdrep,
		d.code_gerep as code_etab, -- Pour calculer le SS � l'�tablissement
		null::text as memo
	from total.bilan_comm_v4_elec as a
	left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
	left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
	left join (select * from src_ind.def_corresp_sources where id_version_corresp = 5 and actif is true) as d using (id_corresp)
	where id_polluant in (131,38,65,108,16,48,36)
	group by 
		id_secteur,
		id_polluant, an, a.id_comm, 
		id_snap3,
		case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end,
		id_usage, id_branche,
		id_unite, 
		id_corresp,		
		case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end,
		d.code_gerep

	union all

	select 
		id_secteur,
		id_polluant, an, a.id_comm, 
		id_snap3,
		case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end as code_cat_energie, -- code_cat_energie,
		id_usage, id_branche,
		id_unite, 
		sum(val) as val,
		id_corresp,		
		case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end as bdrep,
		d.code_gerep as code_etab, -- Pour calculer le SS � l'�tablissement
		null::text as memo
	from total.bilan_comm_v4_ges as a
	left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
	left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
	left join (select * from src_ind.def_corresp_sources where id_version_corresp = 5 and actif is true) as d using (id_corresp)
	where id_polluant in (15, 123, 124, 128)
	group by 
		id_secteur,
		id_polluant, an, a.id_comm, 
		id_snap3,
		case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end,
		id_usage, id_branche,
		id_unite, 
		id_corresp,		
		case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end,
		d.code_gerep

)

-- Affectation d'un SECTEN1 selon le cas de figure et insertion dans la table
insert into total.bilan_comm_v4_secten1
select 
		id_polluant,
		an,
		id_comm,
		id_secten1,
		code_cat_energie, 
		id_usage, 
		id_branche, 
		sum(a.val) as val,
		id_unite, 
		bdrep, 
		code_etab,
		memo
from (
	-- Passage au SECTEN 1
	-- On ne prends pas les correspondances complexes qui doivent-�tre trait�es s�par�ment pour ne pas avoir de doubles comptes.
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		c.id_secten1,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		sum(a.val) as val,
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999') as code_etab,
		a.memo
	from emi as a
	left join total.corresp_snap_synapse as b on a.id_snap3 = b.espace_id_snap3
	left join transversal.tpk_snap3 as c on b.synapse_id_snap3 = c.id_snap3
	where
		a.id_snap3 not in (select distinct espace_id_snap3 from total.corresp_snap_synapse where specificite is not null)
	group by 
		a.id_polluant,
		a.an,
		a.id_comm,
		c.id_secten1,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999'),
		a.memo

	union all 

	-- Application de colles et adh�sifs selon secteur
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			-- Application de colles et adh�sifs selon secteur
			when a.id_snap3 = 60405 and a.id_secteur = 1 then '2' -- 06040502 SECTEN 1 = 2
			when a.id_snap3 = 60405 and a.id_secteur = 3 then '3' -- 06040501 SECTEN 1 = 3
		end as id_secten1,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		sum(a.val) as val,
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999') as code_etab,
		a.memo
	from emi as a
	where
		a.id_snap3 = 60405
	group by 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			-- Application de colles et adh�sifs selon secteur
			when a.id_snap3 = 60405 and a.id_secteur = 1 then '2' -- 06040502 SECTEN 1 = 2
			when a.id_snap3 = 60405 and a.id_secteur = 3 then '3' -- 06040501 SECTEN 1 = 3
		end,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999'),
		a.memo

	union all

	-- Distinction du CH4 pour les rizi�res
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			when a.id_snap3 = 100103 and a.id_polluant = 7 then '5' -- 10010302 SECTEN 1 = 5
			when a.id_snap3 = 100103 and a.id_polluant <> 7 then '5' -- 10010301 SECTEN 1 = 5
		end as id_secten1,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		sum(a.val) as val,
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999') as code_etab,
		a.memo
	from emi as a
	where
		a.id_snap3 = 100103
	group by 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			when a.id_snap3 = 100103 and a.id_polluant = 7 then '5' -- 10010302 SECTEN 1 = 5
			when a.id_snap3 = 100103 and a.id_polluant <> 7 then '5' -- 10010301 SECTEN 1 = 5
		end,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999'),
		a.memo

	union all

	-- Distinction de NOx pour l'�levage
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			when a.id_snap3 in (100901,100902,100903,100904,100905,100906,100907,100908,100909,100910,100912) and a.id_polluant = 38 then '8'
			when a.id_snap3 in (100901,100902,100903,100904,100905,100906,100907,100908,100909,100910,100912) and a.id_polluant <> 38 then '5'
		end as id_secten1,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		sum(a.val) as val,
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999') as code_etab,
		a.memo
	from emi as a
	where
		a.id_snap3 in (
			100901,100902,100903,100904,100905,
			100906,100907,100908,100909,100910,100912	
		)	
	group by 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			when a.id_snap3 in (100901,100902,100903,100904,100905,100906,100907,100908,100909,100910,100912) and a.id_polluant = 38 then '8'
			when a.id_snap3 in (100901,100902,100903,100904,100905,100906,100907,100908,100909,100910,100912) and a.id_polluant <> 38 then '5'
		end,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999'),
		a.memo

	union all

	-- Distinction de NOx et COVNM pour les cultures
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			when a.id_snap3 in (100100,100101,100100,100101,100102,100102,100104,100104,100105,100105) and a.id_polluant in (38, 16) then '8'
			when a.id_snap3 in (100100,100101,100100,100101,100102,100102,100104,100104,100105,100105) and a.id_polluant not in (38, 16) then '5'
		end as id_secten1,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		sum(a.val) as val,
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999') as code_etab,
		a.memo
	from emi as a
	where
		a.id_snap3 in (100100,100101,100100,100101,100102,100102,100104,100104,100105,100105)	
	group by 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			when a.id_snap3 in (100100,100101,100100,100101,100102,100102,100104,100104,100105,100105) and a.id_polluant in (38, 16) then '8'
			when a.id_snap3 in (100100,100101,100100,100101,100102,100102,100104,100104,100105,100105) and a.id_polluant not in (38, 16) then '5'
		end,
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999'),
		a.memo
) as a
group by 
		id_polluant,
		an,
		id_comm,
		id_secten1,
		code_cat_energie, 
		id_usage, 
		id_branche, 
		id_unite, 
		bdrep, 
		code_etab,
		memo
;

/*
Validation en grandes masses sur les polluants de l'interface d'extraction uniquement 
NOx 38, PM10 65, PM2.5 108, COV 129, SO2 48, NH3 36, consommations 131

select an, id_polluant, val_orig, val_secten1
from (
	select an, id_polluant, sum(val) as val_orig
	from total.bilan_comm_v4
	where id_polluant in (38, 65, 108, 16, 48, 36, 131)
	and id_comm <> 99999
	group by an, id_polluant
) as a
full join (
	select an, id_polluant, sum(val) as val_secten1
	from total.bilan_comm_v4_secten1
	where 
		code_cat_energie not in (8) -- On a s�par� l'�lec dans la table d'origine
		and id_polluant in (38, 65, 108, 16, 48, 36, 131)
	group by an, id_polluant
) as b using (an, id_polluant)
order by an, id_polluant
*/

/**
Calcul du pourcentage de la consommation.
La consommation totale est stock�e dans ss_85_conso_tot
*/
alter table total.bilan_comm_v4_secten1 drop column if exists ss_85_conso;
alter table total.bilan_comm_v4_secten1 add column ss_85_conso double precision;
alter table total.bilan_comm_v4_secten1 drop column if exists ss_85_conso_tot;
alter table total.bilan_comm_v4_secten1 add column ss_85_conso_tot text;

update total.bilan_comm_v4_secten1 as a set
	ss_85_conso = b.pct,
	ss_85_conso_tot = 'Conso etab =' || conso_etab || ' Conso tot = ' || b.conso_tot
from (
	-- % de consommation de l'�tablissement, secten1, energie / conso tot secten1, energie, commune
	select an, id_comm, code_etab, id_secten1, code_cat_energie, conso_etab, conso_tot, round((conso_etab / conso_tot * 100.)::numeric, 1) as pct
	from (
		-- Calcul de la consommation par �tablissement, secten1, code_cat �nergie pour se s�parer des usages et branches
		select an, id_comm, code_etab, id_secten1, code_cat_energie, sum(val) as conso_etab
		from total.bilan_comm_v4_secten1
		where 
			id_polluant = 131 
			and bdrep is true
		group by an, id_comm, code_etab, id_secten1, code_cat_energie 
	) as a
	left join (
		-- Calcul de la conso tot par commune, an, groupe d'�nergie et secten 1
		select an, id_comm, id_secten1, code_cat_energie, sum(val) as conso_tot
		from total.bilan_comm_v4_secten1
		where id_polluant = 131
		group by an, id_comm, id_secten1, code_cat_energie
	) as b using (an, id_comm, id_secten1, code_cat_energie)
) as b 
where
	(a.an, a.id_comm, a.id_secten1, a.code_cat_energie, a.code_etab) = (b.an, b.id_comm, b.id_secten1, b.code_cat_energie, b.code_etab)
	and a.bdrep is true -- Uniquement les consos bdrep
;
	
/**
Jointure du nombre d'�tablissements
*/
alter table total.bilan_comm_v4_secten1 drop column if exists ss_nb_etab;
alter table total.bilan_comm_v4_secten1 add column ss_nb_etab double precision;

update total.bilan_comm_v4_secten1 as a
set ss_nb_etab = b.nb_etab
from public.cigale_nb_etab as b
where 
	(a.an, a.id_comm, a.id_secten1, a.code_cat_energie) = (b.an, b.id_comm, b.id_secten1, b.code_cat_energie)
	and a.bdrep is true -- Uniquement les consos bdrep
;

/**
Calcul final du secret stat
*/
alter table total.bilan_comm_v4_secten1 drop column if exists ss;
alter table total.bilan_comm_v4_secten1 add column ss boolean;
alter table total.bilan_comm_v4_secten1 drop column if exists ss_commentaire;
alter table total.bilan_comm_v4_secten1 add column ss_commentaire text;

update total.bilan_comm_v4_secten1 as a set
	ss = coalesce(b.ss, FALSE),
	ss_commentaire = b.ss_commentaire
from (
	select 
		id_polluant, an, id_comm, id_secten1, code_cat_energie, code_etab, 
		case 
			when ss_85_conso >= 58 or ss_nb_etab <= 3 then true
			else false
		end as ss,
		case 
			when ss_85_conso >= 58  then '85%'
			when ss_nb_etab < 3 then 'nb_etab'
			else null
		end as ss_commentaire
	from total.bilan_comm_v4_secten1
	where 
		bdrep is true -- Uniquement pour les �tablissements bdrep
		and code_cat_energie <> 8 -- Sans les consommations d'�lec qui ne sont pas d�clar�es dans bdrep
		and not ( -- Sans les consommations de GN d�clar�es dans l'open data (Comprends GN, GNL, GNV)
			code_cat_energie = 1 
			and bdrep is false
			and (an, code_etab) not in (
				-- S�lection des ann�es et �tablissement pour lesquels on a affect� du GRT GAZ non d�clar� dans BDREP
				select distinct an, code_gerep
				from src_ind.src_conso_source as a
				left join src_ind.def_corresp_sources as b using (id_version_corresp, id_corresp)
				where 
					id_version_corresp = 5
					and actif is true
					and id_energie = 301 
					and a.commentaire = 'GRT GAZ'			
			)
		) 
	order by id_polluant, an, id_comm, code_etab, id_secten1, code_cat_energie
) as b
where 
	(a.id_polluant, a.an, a.id_comm, a.code_etab, a.id_secten1, a.code_cat_energie)
	=
	(b.id_polluant, b.an, b.id_comm, b.code_etab, b.id_secten1, b.code_cat_energie)
;

update total.bilan_comm_v4_secten1 set ss = FALSE where ss is null;

/**
Suppression des �missions de l'objet Mer
*/
delete from total.bilan_comm_v4_secten1 where id_comm = 99999;

/**
Calcul d'un champ val_conso pour pouvoir relier plus facilement 
la consommation aux �missions lors de l'extraction
FIXME: EN l'�tat l'inventaire ne permet pas de faire de lien juste �missions / consommations! Cf. #30

alter table total.bilan_comm_v4_secten1 drop column if exists val_conso;
alter table total.bilan_comm_v4_secten1 add column val_conso double precision;

update total.bilan_comm_v4_secten1 as a
set val_conso = b.val_conso
from (
	select an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab, sum(val) as val_conso
	from total.bilan_comm_v4_secten1
	where id_polluant = 131 
	group by an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab
) as b
where 
	(a.an, a.id_comm, a.id_secten1, a.code_cat_energie, a.id_usage, a.id_branche, a.code_etab) = 
	(b.an, b.id_comm, b.id_secten1, b.code_cat_energie, b.id_usage, b.id_branche, b.code_etab)
	and a.id_polluant <> 131 and a.id_secten1 <> '8'
;
*/

/**
Maintenance de la table
*/
alter table total.bilan_comm_v4_secten1 add constraint "pk.total.bilan_comm_v4_secten1" 
	primary key (id_polluant, an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab);

CREATE INDEX "idx.bilan_comm_v4_secten1.an" ON total.bilan_comm_v4_secten1 (an);
CREATE INDEX "idx.bilan_comm_v4_secten1.id_polluant" ON total.bilan_comm_v4_secten1 (id_polluant);

vacuum ANALYZE total.bilan_comm_v4_secten1;
vacuum FREEZE total.bilan_comm_v4_secten1;


/**
Cluster de la table pour acc�l�rer les requ�tes
*/
CREATE INDEX "idx.bilan_comm_v4_secten1.id_polluant.an.ss.id_secten1.code_cat_energie"
ON total.bilan_comm_v4_secten1
USING btree
(id_polluant, an, ss, id_secten1, code_cat_energie);

ALTER TABLE total.bilan_comm_v4_secten1 CLUSTER ON "idx.bilan_comm_v4_secten1.id_polluant.an.ss.id_secten1.code_cat_energie";


/*
Validation rapide

select an, id_polluant, val_orig, val_secten1
from (
	select an, id_polluant, sum(val) as val_orig
	from total.bilan_comm_v4
	where id_polluant in (38, 65, 108, 16, 48, 36, 131)
	and id_comm <> 99999
	group by an, id_polluant
) as a
full join (
	select an, id_polluant, sum(val) as val_secten1
	from total.bilan_comm_v4_secten1
	where 
		code_cat_energie not in (8) -- On a s�par� l'�lec dans la table d'origine
		and id_polluant in (38, 65, 108, 16, 48, 36, 131)
	group by an, id_polluant
) as b using (an, id_polluant)
order by an, id_polluant
*/


















































































/*
PARTIE DE VALIDATION FINALE DES EMISSIONS

###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
*/





/* 
Validation des consommations et �missions
*/

with emi_espace as (
	-- Emissions de l'inventaire par polluant et ann�e
	select id_polluant, an, sum(val) as val
	from (
		select id_polluant, an, sum(val) as val, 'total.bilan_comm_v4' as src
		from total.bilan_comm_v4
		where 
			id_polluant in (38,65,108,16,48,36,15,123,124,128)
			and id_comm <> 99999
		group by id_polluant, an 

		union all

		select id_polluant, an, sum(val) as val, 'total.bilan_comm_v4_elec' as src
		from total.bilan_comm_v4_elec
		where 
			id_polluant in (38,65,108,16,48,36,123,124,128)
			and id_comm <> 99999
		group by id_polluant, an 

		union all

		select id_polluant, an, sum(val) as val, 'total.bilan_comm_v4_ges' as src
		from total.bilan_comm_v4_ges
		where 
			id_polluant in (38,65,108,16,48,36,123,124,128)
			and id_comm <> 99999
		group by id_polluant, an 

-- 		select distinct id_polluant from total.bilan_comm_v4_ges
-- 		select distinct id_polluant from total.bilan_comm_v4 order by id_polluant
		
	) as a
	group by id_polluant, an
	order by id_polluant, an
),

emi_cigale as (
	-- Emissions CIGALE par �nergie et ann�e
	select id_polluant, an, sum(val) as val
	from total.bilan_comm_v4_secten1 
	where 
		id_polluant in (38,65,108,16,48,36,15,123,124,128)
	group by id_polluant, an
	order by id_polluant, an
) 

select id_polluant, an, a.val as val_espace, b.val as val_cigale
from emi_espace as a
full join emi_cigale as b using (id_polluant, an)

order by id_polluant, an









































/*
PARTIE DE COPIE DES TABLES APPLICATION ET DE MISE EN FORME DES DONNEES POUR RAPIDITE D'EXEC

###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
###############################################################################################
*/




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
EPCI cr�e � partir des communes GEOFLA 2017
Le champ superficie est en hectares
Code SQL de cr�ation de la table:
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
	and not (id_polluant in (38,65,108,16,48,36) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastr�e: Pord d'�nergie mais pas d'�lec ni chaleur
	and not (id_polluant not in (38,65,108,16,48,36) and id_secten1 = '1') -- GES et Ener = Finale
	and ss is false -- Sans aucune donn�e soumise au SS	
group by an, nom_abrege_polluant, siren_epci_2017, nom_epci_2017, superficie
order by an, nom_abrege_polluant, siren_epci_2017, nom_epci_2017, superficie;

SELECT AddGeometryColumn ('cigale','epci_poll','geom',4326,'MULTIPOLYGON',2, false);

ALTER TABLE cigale.epci_poll drop CONSTRAINT enforce_geotype_geom;

update cigale.epci_poll as a
set geom = ST_SimplifyPreserveTopology(b.geom,0.0004) -- Simplification de la g�om�trie pour rapidit� d'affichage
from cigale.epci as b
where a.siren_epci = b.siren_epci;

alter table cigale.epci_poll add constraint "pk.cigale.epci_poll" primary key (nom_abrege_polluant, siren_epci);
CREATE INDEX "gidx.cigale.epci_poll.geom.gist" ON cigale.epci_poll USING GIST (geom);
CREATE INDEX "idx.cigale.epci_poll.id_polluant" ON cigale.epci_poll (nom_abrege_polluant);

comment on table cigale.epci_poll is '
NOTE: Pour la conso �nergie primaire c''est � dire sans l''�lec
NOTE: Pour les GES, �missions directes c''est � dire sans l''�lec
';

vacuum analyze cigale.epci_poll;
vacuum freeze cigale.epci_poll;





drop table if exists cigale.comm_poll;
create table cigale.comm_poll WITH OIDS as  
select 
	row_number() over () as gid, 
	nom_abrege_polluant, nom_comm, siren_epci_2017 as siren_epci, 
	val / (d.superficie / 100.) as val, -- Superficie en hectares dans les donn�es geofla
	st_transform(geom, 4326) as geomtmp
from (
	select id_polluant, an, id_comm, sum(val) as val
	from total.bilan_comm_v4_secten1
	where 
		an = 2015
		and not (id_polluant in (38,65,108,16,48,36,16) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastr�e: Pord d'�nergie mais pas d'�lec ni chaleur
		and not (id_polluant not in (38,65,108,16,48,36,16) and id_secten1 = '1') -- GES et Ener = Finale
		and ss is false -- Sans aucune donn�e en SS
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
set geom = ST_Multi(ST_SimplifyPreserveTopology(geomtmp,0.001)); -- Simplification de la g�om�trie pour rapidit� d'affichage

alter table cigale.comm_poll add constraint "pk.cigale.comm_poll" primary key (gid);
CREATE INDEX "gidx.cigale.comm_poll.geom.gist" ON cigale.comm_poll USING GIST (geom);

comment on table cigale.comm_poll is '
NOTE: Pour la conso �nergie primaire c''est � dire sans l''�lec
NOTE: Pour les GES, �missions directes c''est � dire sans l''�lec
';

vacuum analyse cigale.comm_poll;
vacuum freeze cigale.comm_poll;


-- Clusterisation des tables g�ographiques pour am�liorer les temps d'affichage
CREATE INDEX "cigale.comm_poll.nom_abrege_polluant.siren_epci"
  ON cigale.comm_poll
  USING btree
  (nom_abrege_polluant, siren_epci);
ALTER TABLE cigale.comm_poll CLUSTER ON "cigale.comm_poll.nom_abrege_polluant.siren_epci";

ALTER TABLE cigale.epci_poll CLUSTER ON "idx.cigale.epci_poll.id_polluant";




-- Cr�ation d'une table de liste des entit�s administratives pour une r�cup�ration rapide de l'info
-- et des temps d'affichage am�lior�s.
drop table if exists cigale.liste_entites_admin;
create table cigale.liste_entites_admin as
    -- R�gion PACA
    select 1 as order_field, 93 as valeur, 'R�gion PACA' as texte
    union all
    -- D�partements
    select 2 as order_field, id_dep as valeur, joli_nom_dep as texte 
    from commun.tpk_depts
    where id_reg = 93
    union all
    -- EPCI
    select distinct 3 as order_field, siren_epci_2017, nom_epci_2017
    from commun.tpk_commune_2015_2016
    where siren_epci_2017 is not null
    union all
    -- Communes
    select order_field, id_comm, b.nom_comm || ' (' || lpad((id_comm / 1000)::text, 2, '0') || ')' as nom_comm
    from (
        select distinct 4 as order_field, a.id_comm
        from total.bilan_comm_v4_secten1 as a
    ) as a
    left join commun.tpk_communes as b using (id_comm)
order by order_field, valeur;





















