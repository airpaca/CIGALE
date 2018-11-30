/**

espace2cigale.sql
Air PACA - 2018 - GL/RS

FIXME: Faire des validations automatiques sur le même modèle que celle de la table des émissions par secteur.


Insertion des données de l'inventaire des émissions dans CIGALE
et traitement du secret statistique.

* Utilisation:
Si utilisé avec PgAdmin, lancer tout le code avec PgScript (pour que les commandes soient passées une par une)

* Polluants pris en compte:
- consommations 		131
- émissions de NOx		38
- émissions de PM10		65
- émissions de PM2.5	108
- émissions de COVNM	16
- émissions de SO2		48
- émissions de NH3		36
- co					11
- CO2 tot				15 
- CH4 eq_co2			123 
- N2O eq_co2			124 
- PRG1003GES 			128

* Secret statistique:
Secret stat à la commune par SECTEN 1 et catégorie d'énergie mais pas sur usages et branches.
Secret stat à l'EPCI par SECTEN 1 et catégorie d'énergie en secrétisant une commune si besoin. 

-- FIXME: Il faut peut-être passer les emissions de GES du 805XX en secten 8?



* Mise à jour des communes avec les dernières fusions en date
- sig.admin_express_2018
- commun.tpk_commune_2015_2016 -> id_comm_2018, nom_comm_2018, siren_epci_2018, nom_epci_2018
! Le faire pour la visualisation mais également pour l'extraction!


Table des émissions détaillées pour accès restreint
_______________________________________________________

En fin de script, création de la table des émissions détaillées par secteur
total.bilan_comm_v5_secteurs

*/



/** 
Récupération du nombre d'établissements (Uniquement si nécessaire, long)
- Secteur industriel
- Secteur tertiaire
- Avec affectation d'un SESCTEN1 et catégorie d'énergie
- Les arrondissements de marseille sont transformés en 13055

NOTE: Pour certains etablissement on a un id_etablissement = -999. L'ensemble de ces 
	  établissements ne représentera qu'un seul étab.
FIXME: On ne ferme aucun établissement dans le film. Du coup, on en a de plus en plus?

-- si besoin de faire une backup de la version précédente: 
-- create table public.cigale_nb_etab_backup as select * from public.cigale_nb_etab;
drop table if exists public.cigale_nb_etab;
create table public.cigale_nb_etab as 
select an, case when id_comm between 13201 and 13216 then 13055 else id_comm end as id_comm, id_secten1, code_cat_energie, count(*) as nb_etab
from (

	-- Sélection des établissements industriels dont on connait l'id (!= -999)
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2016 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2016 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
	union all	
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2015 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2015 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2014 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2014 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2013 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2013 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2012 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2012 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2010 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2010 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
	union all
	select distinct an, a.id_comm, case when a.id_etablissement = -999 then code_gerep else a.id_etablissement::text end as id_etablissement, id_snap3, id_energie from total_ind.bilan_comm_v7_2007 as a left join src_ind.def_corresp_sources as b using (id_corresp) where (a.id_etablissement <> -999 or a.id_corresp is not null) and an = 2007 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)

-- 	union all

-- 	-- Sélection des établissements tertiaire dont on connait l'id (!= -999)
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2015 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2015 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2014 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2014 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2013 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2013 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2012 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2012 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2010 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2010 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)
-- 	union all
-- 	select distinct an, a.id_comm, b.code_gerep, id_snap3, id_energie from total_ter.bilan_comm_v5_2007 as a left join src_ind.def_corresp_sources as b using (id_corresp) where id_corresp <> -999 and an = 2007 and (b.actif is true or b.actif is null) and (b.id_version_corresp = 6 or b.id_version_corresp is null)

) as a
-- Lien avec les catégories d'énergie
left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
left join total.corresp_snap_synapse as d on a.id_snap3 = d.espace_id_snap3
left join transversal.tpk_snap3 as e on d.synapse_id_snap3 = e.id_snap3
where 
	-- Corresp multiples uniquement pour 60405 qu'on ne retrouve que dans ind et pas ter. 
	-- On conserve uniquement la correspondance ind.
	d.specificite is null or d.synapse_id_snap3 = '06040502'
group by an, case when id_comm between 13201 and 13216 then 13055 else id_comm end, id_secten1, code_cat_energie;
*/


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
Création de la table des émissions par secten 1 et catégorie d'énergie
- Récupération des données emi et élec de la table bilan
- Récupération des données GES séparées dans vue ges bilan
- On a des valeurs nulles dans les tables bilans de chaque schéma de calcul
  et on ne récupère donc pas ces valeurs.
- On ne récupère pas les émissions affectées à l'objet mer
*/



-- Création de la table finale vide
drop table if exists total.bilan_comm_v5_secten1;
create table total.bilan_comm_v5_secten1 (
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

drop table if exists public.emi;
create table public.emi as
-- Premier regroupement des données conso et emi 
-- avec catégories d'energie mais en regroupant les SNAPs
-- pour traitements spécifiques
-- 
-- ATTENTION On update certains SNAP non énergétiques, qui ont quand même des id_energie dans la table totale
-- Ajout v5 - Dans inv v5 on a différencié tout le 15 en 121 et 122. Etant donné que l'on a que du CO2 tot dans CIGALE on 
-- 			  regroupe le tout en 15
-- NOTE: On fusionne les données avec le dernier découpage communal disponible
select 
	id_secteur,
	id_polluant, an, 
	e.id_comm_2018 as id_comm, -- a.id_comm, 
	id_snap3,
	case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end as code_cat_energie, -- code_cat_energie,
	id_usage, id_branche,
	id_unite, 
	sum(val) as val,
	id_corresp, 
	case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end as bdrep,
	d.code_gerep as code_etab, -- Pour calculer le SS à l'établissement
	null::text as memo
from total.bilan_comm_v5 as a
left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
left join (select * from src_ind.def_corresp_sources where id_version_corresp = 6 and actif is true) as d using (id_corresp)
left join commun.tpk_commune_2015_2016 as e on a.id_comm = e.id_comm
where 
	id_polluant in (131,38,65,108,16,48,36,11)
	and val is not null -- NOTE: Certaines valeurs nulles dans les tables bilan de chaque secteur
	and a.id_comm <> 99999 -- Sans les émissions associées à l'objet mer
	and a.id_comm <> 99138 -- Sans les émissions de la commune de Monaco  
group by
	id_secteur,
	id_polluant, an, 
	e.id_comm_2018, -- a.id_comm, 
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
	id_polluant, 
	an, 
	e.id_comm_2018 as id_comm, -- a.id_comm, 
	id_snap3,
	case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end as code_cat_energie, -- code_cat_energie,
	id_usage, id_branche,
	id_unite, 
	sum(val) as val,
	id_corresp,		
	case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end as bdrep,
	d.code_gerep as code_etab, -- Pour calculer le SS à l'établissement
	null::text as memo
from total.bilan_comm_v5_ges as a
left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
left join (select * from src_ind.def_corresp_sources where id_version_corresp = 6 and actif is true) as d using (id_corresp)
left join commun.tpk_commune_2015_2016 as e on a.id_comm = e.id_comm
where 
	id_polluant in (15, 123, 124, 128)
	and val is not null -- NOTE: Certaines valeurs nulles dans les tables bilan de chaque secteur
	and a.id_comm <> 99999 -- Sans les émissions associées à l'objet mer
	and a.id_comm <> 99138 -- Sans les émissions associées à MC
group by 
	id_secteur,
	id_polluant,
	an, 
	e.id_comm_2018, -- a.id_comm, 
	id_snap3,
	case when id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end,
	id_usage, id_branche,
	id_unite, 
	id_corresp,		
	case when id_corresp not in (-999, -888) and id_secteur <> 4 and d.code_gerep is not null then true else false end,
	d.code_gerep
;

-- SELECT DISTINCT id_polluant from public.emi order by id_polluant


-- select distinct a.id_snap3
-- from public.emi as a
-- left join total.corresp_snap_synapse as b on a.id_snap3 = b.espace_id_snap3
-- left join transversal.tpk_snap3 as c on b.synapse_id_snap3 = c.id_snap3
-- where id_secten1 is null
-- 
-- select * from total.bilan_comm_v5 where id_snap3 = 60500

-- Affectation d'un SECTEN1 selon le cas de figure et insertion dans la table
insert into total.bilan_comm_v5_secten1
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
	-- On ne prends pas les correspondances complexes qui doivent-être traitées séparément pour ne pas avoir de doubles comptes.
	-- Ajout v5 :  On ne prend pas en compte le 060500 que l'on passera dans l'industrie (consos uniquement)
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
	from public.emi as a
	left join total.corresp_snap_synapse as b on a.id_snap3 = b.espace_id_snap3
	left join transversal.tpk_snap3 as c on b.synapse_id_snap3 = c.id_snap3
	where
		a.id_snap3 not in (select distinct espace_id_snap3 from total.corresp_snap_synapse where specificite is not null)
		and a.id_snap3 not in (60500)
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

	-- Application de colles et adhésifs selon secteur
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			-- Application de colles et adhésifs selon secteur
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
	from public.emi as a
	where
		a.id_snap3 = 60405
	group by 
		a.id_polluant,
		a.an,
		a.id_comm,
		case 
			-- Application de colles et adhésifs selon secteur
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

	-- Distinction du CH4 pour les rizières
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
	from public.emi as a
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

	-- Distinction de NOx pour l'élevage
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
	from public.emi as a
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
	from public.emi as a
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

	union all
	
	-- Ajout du 060500 qui sont des consos de froid remontées de la bdrep
	select 
		a.id_polluant,
		a.an,
		a.id_comm,
		'2'::text as id_secten1, -- Affectation manuelle d'un SECTEN
		a.code_cat_energie, 
		a.id_usage, 
		a.id_branche, 
		sum(a.val) as val,
		a.id_unite, 
		a.bdrep, 
		coalesce(a.code_etab, '-999') as code_etab,
		a.memo
	from public.emi as a
	left join total.corresp_snap_synapse as b on a.id_snap3 = b.espace_id_snap3
	left join transversal.tpk_snap3 as c on b.synapse_id_snap3 = c.id_snap3
	where
		a.id_snap3 not in (select distinct espace_id_snap3 from total.corresp_snap_synapse where specificite is not null)
		and a.id_snap3 in (60500)
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

-- FIXME: Il faut peut-être passer les emissions de GES du 805XX en secten 8?

/*
Validation en grandes masses sur les polluants de l'interface d'extraction uniquement 
NOx 38, PM10 65, PM2.5 108, COV 129, SO2 48, NH3 36,11, consommations 131

select an, id_polluant, val_orig, val_tmp, val_secten1
from (
	-- Table source
	select an, id_polluant, sum(val) as val_orig
	from total.bilan_comm_v5
	where id_polluant in (38, 65, 108, 16, 48, 36, 11, 131)
	and id_comm <> 99999
	and id_comm <> 99138
	group by an, id_polluant
) as a
full join (
	-- Table temporaire
	select an, id_polluant, sum(val) as val_tmp
	from public.emi
	where 
		id_polluant in (38, 65, 108, 16, 48, 36, 11, 131)
	group by an, id_polluant
) as b using (an, id_polluant)
full join (
	-- Table finale
	select an, id_polluant, sum(val) as val_secten1
	from total.bilan_comm_v5_secten1
	where 
		id_polluant in (38, 65, 108, 16, 48, 36, 11, 131)
	group by an, id_polluant
) as c using (an, id_polluant)
order by an, id_polluant
*/


/*
Si besoin, comparaison avec la version précédente de l'inventaire
select 
	src, 
	id_polluant, nom_abrege_polluant,
	id_secten1, nom_secten1,
	code_cat_energie, cat_energie,
	an, 
	val
from (
	select 'v5' as src, id_polluant, id_secten1, code_cat_energie, an, sum(val) as val
	from total.bilan_comm_v5_secten1
	where id_polluant in (131,38,65,108,16,48,36,11,15,123,124,128)
	group by id_polluant, id_secten1, code_cat_energie, an

	union all

	select 'v4' as src, id_polluant, id_secten1, code_cat_energie, an, sum(val) as val
	from total.bilan_comm_v4_secten1
	where id_polluant in (131,38,65,108,16,48,36,11,15,123,124,128)
	group by id_polluant, id_secten1, code_cat_energie, an
) as a
left join (select distinct code_cat_energie, cat_energie from transversal.tpk_energie) as b using (code_cat_energie)
left join transversal.tpk_secten1 as c using (id_secten1)
left join commun.tpk_polluants as d using (id_polluant)
order by src, id_polluant, id_secten1, code_cat_energie, an
*/


-- FAUT-IL ENCORE LANCER CETTE FONCTION DANS LA V5 ?
-- ********************************************************************************************
-- ********************************************************************************************
-- ********************************************************************************************
-- /**
-- Récupération tardive des consos elec routier dans CIGALE directement dans la table SECTEN1
-- */
-- insert into total.bilan_comm_v5_secten1
-- -- Passage en SECTEN1 et Code_cat_energie
-- select
-- 	id_polluant, 
-- 	an, 
-- 	case when id_comm >= 13201 and id_comm <= 13216 then 13055 else id_comm end as id_comm,  -- regroupement des arrondissements de Marseille.
-- 	e.id_secten1,
-- 	case when a.id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end as code_cat_energie,
-- 	id_usage,
-- 	id_branche, 
-- 	SUM(val) as val, 
-- 	id_unite, 
-- 	bdrep,
-- 	code_etab,
-- 	memo
-- from (
-- 	-- Récupération des données
-- 	select 
-- 		id_polluant, 
-- 		an, 
-- 		case when id_comm >= 13201 and id_comm <= 13216 then 13055 else id_comm end as id_comm,  -- regroupement des arrondissements de Marseille.
-- 		id_snap3, -- Passer au SECTEN 1
-- 		id_energie, -- Passer en Cat énergie
-- 		38::integer as id_usage,
-- 		0::integer as id_branche, 
-- 		SUM(__convertir(val, 1, 1)) as val, 
-- 		1::integer as id_unite, 
-- 		false as bdrep,
-- 		-999::integer as code_etab,
-- 		'Consos traf elec récupérées dans un second temps'::text as memo
-- 	from total_traf.bilan_comm_v37_2015
-- 	where 
-- 		id_polluant=71 -- Consos MOCAT
-- 		and id_energie = 401
-- 		and an in (2007, 2010, 2012, 2013, 2014, 2015)
-- 		and id_polluant not in (121, 122)  -- Dans traf, 15 = 121 + 122
-- 	group by 
-- 	id_polluant, 
-- 		an, 
-- 		case when id_comm >= 13201 and id_comm <= 13216 then 13055 else id_comm end,  -- regroupement des arrondissements de Marseille.
-- 		id_snap3, -- Passer au SECTEN 1
-- 		id_energie -- Passer en Cat énergie
-- ) as a
-- left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
-- left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
-- left join total.corresp_snap_synapse as d on a.id_snap3 = d.espace_id_snap3
-- left join transversal.tpk_snap3 as e on d.synapse_id_snap3 = e.id_snap3
-- group by 
-- 	id_polluant, 
-- 	an, 
-- 	case when id_comm >= 13201 and id_comm <= 13216 then 13055 else id_comm end,  -- regroupement des arrondissements de Marseille.
-- 	e.id_secten1,
-- 	case when a.id_snap3 in (70900,70900,70900,70900,110300) then 0 else code_cat_energie end,
-- 	id_usage,
-- 	id_branche, 
-- 	id_unite, 
-- 	bdrep,
-- 	code_etab,
-- 	memo
-- ;










/**
Mise à jour tardive d'une fusion de commune 4198 -> 4033
avant calcul SS

NOTE: Plus nécessaire, on prend la dernière version des fusions de communes lors de la création de la table

drop table if exists public.tmp_fusion;
create table public.tmp_fusion as 
select 
	id_polluant, an, 
	4033::integer as id_comm, 
	id_secten1, code_cat_energie, id_usage, id_branche, 
	sum(val) as val,
	id_unite, bdrep, code_etab, memo
from total.bilan_comm_v5_secten1
where id_comm in (4198, 4033)
group by 
	id_polluant, an, 
	4033::integer, 
	id_secten1, code_cat_energie, id_usage, id_branche, 
	id_unite, bdrep, code_etab, memo
order by 
	id_polluant, an, 
	4033::integer, 
	id_secten1, code_cat_energie, id_usage, id_branche, 
	id_unite, bdrep, code_etab, memo
;

delete from total.bilan_comm_v5_secten1 where id_comm in (4198, 4033);

insert into total.bilan_comm_v5_secten1 
select * from public.tmp_fusion;

*/








/**
Calcul du pourcentage de la consommation.
La consommation totale est stockée dans ss_85_conso_tot
*/
alter table total.bilan_comm_v5_secten1 drop column if exists ss_85_conso;
alter table total.bilan_comm_v5_secten1 add column ss_85_conso double precision;
alter table total.bilan_comm_v5_secten1 drop column if exists ss_85_conso_tot;
alter table total.bilan_comm_v5_secten1 add column ss_85_conso_tot text;

update total.bilan_comm_v5_secten1 as a set
	ss_85_conso = b.pct,
	ss_85_conso_tot = 'Conso etab =' || conso_etab || ' Conso tot = ' || b.conso_tot
from (
	-- % de consommation de l'établissement, secten1, energie / conso tot secten1, energie, commune
	select an, id_comm, code_etab, id_secten1, code_cat_energie, conso_etab, conso_tot, round((conso_etab / nullif(conso_tot, 0) * 100.)::numeric, 1) as pct
	from (
		-- Calcul de la consommation par établissement, secten1, code_cat énergie pour se séparer des usages et branches
		select an, id_comm, code_etab, id_secten1, code_cat_energie, sum(val) as conso_etab
		from total.bilan_comm_v5_secten1
		where 
			id_polluant = 131 
			and bdrep is true
		group by an, id_comm, code_etab, id_secten1, code_cat_energie 
	) as a
	left join (
		-- Calcul de la conso tot par commune, an, groupe d'énergie et secten 1
		select an, id_comm, id_secten1, code_cat_energie, sum(val) as conso_tot
		from total.bilan_comm_v5_secten1
		where id_polluant = 131
		group by an, id_comm, id_secten1, code_cat_energie
	) as b using (an, id_comm, id_secten1, code_cat_energie)
) as b 
where
	(a.an, a.id_comm, a.id_secten1, a.code_cat_energie, a.code_etab) = (b.an, b.id_comm, b.id_secten1, b.code_cat_energie, b.code_etab)
	and a.bdrep is true -- Uniquement les consos bdrep
;
	
/**
Jointure du nombre d'établissements
*/
alter table total.bilan_comm_v5_secten1 drop column if exists ss_nb_etab;
alter table total.bilan_comm_v5_secten1 add column ss_nb_etab double precision;

update total.bilan_comm_v5_secten1 as a
set ss_nb_etab = b.nb_etab
from public.cigale_nb_etab as b
where 
	(a.an, a.id_comm, a.id_secten1, a.code_cat_energie) = (b.an, b.id_comm, b.id_secten1, b.code_cat_energie)
	and a.bdrep is true -- Uniquement les consos bdrep
;


/**
Calcul final du secret stat à la commune
*/
alter table total.bilan_comm_v5_secten1 drop column if exists ss;
alter table total.bilan_comm_v5_secten1 add column ss boolean;
alter table total.bilan_comm_v5_secten1 drop column if exists ss_commentaire;
alter table total.bilan_comm_v5_secten1 add column ss_commentaire text;

update total.bilan_comm_v5_secten1 as a set
	ss = coalesce(b.ss, FALSE),
	ss_commentaire = b.ss_commentaire
from (
	select 
		id_polluant, an, id_comm, id_secten1, code_cat_energie, code_etab, 
		case 
			when ss_85_conso >= 85 or ss_nb_etab <= 3 then true
			else false
		end as ss,
		case 
			when ss_85_conso >= 85  then '85%'
			when ss_nb_etab < 3 then 'nb_etab'
			else null
		end as ss_commentaire
	from total.bilan_comm_v5_secten1
	where 
		bdrep is true -- Uniquement pour les établissements bdrep
		and code_cat_energie <> 8 -- Sans les consommations d'élec qui ne sont pas déclarées dans bdrep
		and not ( -- Sans les consommations de GN déclarées dans l'open data (Comprends GN, GNL, GNV)
			code_cat_energie = 1 
			and bdrep is false
			and (an, code_etab) not in (
				-- Sélection des années et établissement pour lesquels on a affecté du GRT GAZ non déclaré dans BDREP
				-- FIXME: Ne corresponds pas aux consos opendata mise à jour ou non par établissement dans bilan_comm! 
				select distinct an, code_gerep
				from src_ind.src_conso_source as a
				left join src_ind.def_corresp_sources as b using (id_version_corresp, id_corresp)
				where 
					id_version_corresp = 6
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

update total.bilan_comm_v5_secten1 set ss = FALSE where ss is null;

-- Si on a une valeur en ss pour une commune, une énergie, une activité, alors toutes les
-- valeurs de la commune doivent-apparaître en SS
update total.bilan_comm_v5_secten1 
set ss = true, ss_commentaire = 'ss communal'
where 
	(id_polluant, an, id_comm, id_secten1, code_cat_energie) in (
		-- Commune, an, activite, energie qui ont au moins une donnée en ss
		select distinct id_polluant, an, id_comm, id_secten1, code_cat_energie 
		from total.bilan_comm_v5_secten1  
		where ss is true
	);

/**
Calcul d'un champ val_conso pour pouvoir relier plus facilement 
la consommation aux émissions lors de l'extraction
FIXME: EN l'état l'inventaire ne permet pas de faire de lien juste émissions / consommations! Cf. #30

alter table total.bilan_comm_v5_secten1 drop column if exists val_conso;
alter table total.bilan_comm_v5_secten1 add column val_conso double precision;

update total.bilan_comm_v5_secten1 as a
set val_conso = b.val_conso
from (
	select an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab, sum(val) as val_conso
	from total.bilan_comm_v5_secten1
	where id_polluant = 131 
	group by an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab
) as b
where 
	(a.an, a.id_comm, a.id_secten1, a.code_cat_energie, a.id_usage, a.id_branche, a.code_etab) = 
	(b.an, b.id_comm, b.id_secten1, b.code_cat_energie, b.id_usage, b.id_branche, b.code_etab)
	and a.id_polluant <> 131 and a.id_secten1 <> '8'
;
*/








/*

Calcul d'un champ SS à l'EPCI / an / secten1 / catégorie d'énergie

1/ Calcul du SS théroque à l'EPCI (1 étab >= 85% conso ou < 3 etab)
2/ Si on a du secret stat théorique à l'EPCI alors pas de diffusion de la donnée ss_epci = TRUE
3/ Si on a pas de SS théorique à l'EPCI il faut vérifier que l'on ne puisse pas retrouver la valeur 
   d'une commune en SS à partir de la valeur totale de son EPCI.
4/ Si dans un EPCI sans SS théorique on a deux communes ou plus en SS alors diffusion de la donnée ss_epci = FALSE
5/ Si dans un EPCI sans SS théorique on a une seule commune qui a du SS alors il faut pouvoir en passer une autre en SS
6/ Si dans un EPCI sans SS théorique on a une seule commune qui a du SS mais au moins deux autres communes non SS 
   alors on passe la conso la plus basse en SS et on diffuse la donnée ss_epic = FALSE
7/ Si moins de deux autres communes sans SS alors pas de diffusion de la donnée ss_epci = TRUE
8/ Tous ces calculs réalisés sur les consommations doivent ensuite être appliqués à tous les autres polluants
9/ Dans CIGALE:
   Si extraction comm where ss is false
   Si extraction epci where ss_epci is false
*/

-- Uniquement pour tests
-- update total.bilan_comm_v5_secten1 set ss = TRUE where memo = 'Secrétisation manuelle';

-- Création du champ final de secret stat à l'EPCI
alter table total.bilan_comm_v5_secten1 drop column if exists ss_epci;
alter table total.bilan_comm_v5_secten1 add column ss_epci boolean;

-- Création d'une table temporaire comportant tous les champs nécessaires au calcul du SS à l'EPCI
drop table if exists public.tmp_ss_epci;
create table public.tmp_ss_epci as 
with ss_epci as (
	-- Calcul du SS à l'EPCI, an, secten1, cat_energie pour la consommation
	select 
		a.*, b.nb_etab, c.conso_epci,
		conso_etab / nullif(conso_epci, 0) * 100. as pct_conso, 
		case 
			when (conso_etab / nullif(conso_epci, 0) * 100. >= 85 or nb_etab < 3) and code_cat_energie <> 0 then true 
			else false 
		end as ss_epci
	from (
		-- Somme des consos à l'établissement
		select id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie, code_etab, sum(val) as conso_etab
		from total.bilan_comm_v5_secten1
		left join commun.tpk_commune_2015_2016 as b using (id_comm)
		where id_polluant = 131 -- and an = 2015
		group by id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie, code_etab
	) as a
	left join (
		-- Calcul du nb etab à l'EPCI secten et énergie
		select an, siren_epci_2018, id_secten1, code_cat_energie, sum(coalesce(nb_etab, 0)) as nb_etab
		from  public.cigale_nb_etab as a
		left join commun.tpk_commune_2015_2016 as b using (id_comm)
		group by an, siren_epci_2018, id_secten1, code_cat_energie 
	) as b using (an, siren_epci_2018, id_secten1, code_cat_energie)
	left join (
		-- Calcul conso à l'EPCI secten et énergie
		select an, siren_epci_2018, id_secten1, code_cat_energie, sum(val) as conso_epci
		from total.bilan_comm_v5_secten1 as a
		left join commun.tpk_commune_2015_2016 as b using (id_comm)
		where a.id_polluant = 131
		group by an, siren_epci_2018, id_secten1, code_cat_energie 
	) as c using (an, siren_epci_2018, id_secten1, code_cat_energie)
	where 
		code_etab <> '-999'
		and code_cat_energie <> 8
		-- FIXME: Et les clients GRT GAZ open data?
	order by a.id_polluant, a.an, a.siren_epci_2018, a.id_secten1, a.code_cat_energie, a.code_etab
),
nb_comm_ss as (
	-- nb comm avec secret stat par epci
	select id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie, count(id_comm) as nb_comm_ss
	from (
		select id_polluant, an, id_comm, id_secten1, code_cat_energie
		from total.bilan_comm_v5_secten1 
		where ss is true and id_polluant = 131
	) as a
	left join commun.tpk_commune_2015_2016 as b using (id_comm)
	group by id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie
), 
nb_comm_noss as (
	-- nb comm sans secret stat par epci
	select id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie, count(id_comm) as nb_comm_noss
	from (
		select id_polluant, an, id_comm, id_secten1, code_cat_energie
		from total.bilan_comm_v5_secten1 
		where ss is false and id_polluant  = 131
	) as a
	left join commun.tpk_commune_2015_2016 as b using (id_comm)
	group by id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie
),
conso_min_comm_epci as (
	-- Conso la plus faible des communes d'un EPCI par an, secten1, cat energie qui ne sont pas en SS comm
	select *
	from (
		select 
			an, siren_epci_2018, id_secten1, code_cat_energie, id_comm, val, rank()
			OVER (PARTITION BY an, siren_epci_2018, id_secten1, code_cat_energie ORDER BY val DESC) as tri
		from (
			select 
				an, siren_epci_2018, id_secten1, code_cat_energie, id_comm, sum(val) as val	
			from total.bilan_comm_v5_secten1 as a
			left join commun.tpk_commune_2015_2016 as b using (id_comm)
			where a.id_polluant = 131 and ss is false
			group by an, siren_epci_2018, id_secten1, code_cat_energie, id_comm
		) as a
	) as a
	where tri = 1
),
stats_ss_epci as (
select a.*, ss_epci_true, case when ss_epci_true is not null then true else false end as ss_epci
from (		
	select distinct a.id_polluant, a.an, a.siren_epci_2018, a.id_secten1, a.code_cat_energie, nb_comm_ss, nb_comm_noss 
	from ss_epci as a
	left join nb_comm_ss as b using (an, siren_epci_2018, id_secten1, code_cat_energie)
	left join nb_comm_noss as c using (an, siren_epci_2018, id_secten1, code_cat_energie)
-- 	where 
-- 		ss_epci is true
		-- and siren_epci_2018 = 200035319 and id_secten1 = '2' and code_cat_energie = 8 and an = 2013	
) as a
left join (
	select a.id_polluant, a.an, a.siren_epci_2018, a.id_secten1, a.code_cat_energie, nb_comm_ss, nb_comm_noss, true as ss_epci_true
	from ss_epci as a
	left join nb_comm_ss as b using (an, siren_epci_2018, id_secten1, code_cat_energie)
	left join nb_comm_noss as c using (an, siren_epci_2018, id_secten1, code_cat_energie)
	where 
		ss_epci is true
		-- and siren_epci_2018 = 200035319 and id_secten1 = '2' and code_cat_energie = 8 and an = 2013
) as b using (id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie)
-- On lie toutes les statistiques calculées
-- 	select a.*, b.nb_comm_ss, c.nb_comm_noss
-- 	from ss_epci as a
-- 	left join nb_comm_ss as b using (an, siren_epci_2018, id_secten1, code_cat_energie)
-- 	left join nb_comm_noss as c using (an, siren_epci_2018, id_secten1, code_cat_energie)
-- 	where siren_epci_2018 = 200035319 and id_secten1 = '2' and code_cat_energie = 8 and an = 2013
)
-- On crée la table temporaire en calculant un champ SS_epci temporaire en fonction du cas de figure
select 
	a.*,
	case 
		when ss_epci is true then 'true' -- SI SS à l'EPCI alors SS
		when ss_epci is FALSE and nb_comm_ss >= 2 then 'false' -- Si pas SS à l'EPCI et >2 communes SS alors noSS
		when ss_epci is FALSE and (nb_comm_ss < 2 or nb_comm_ss is null) and nb_comm_noss >= 2 then '?'
	end as ss_epci_tmp,
	b.id_comm as id_comm_to_ss
from stats_ss_epci as a
left join conso_min_comm_epci as b using (an, siren_epci_2018, id_secten1, code_cat_energie)
order by an, siren_epci_2018, id_secten1, code_cat_energie
;

/**
select *
from public.tmp_ss_epci
order by id_polluant, an, siren_epci_2018, id_secten1, code_cat_energie
limit 300
*/

-- Calcul du champ ss_epci dans la table officielle
-- Ce calcul est effectué pour tous les polluants
update total.bilan_comm_v5_secten1 as a
set ss_epci = case 
	when b.ss_epci_tmp = 'true' then true
	when b.ss_epci_tmp = 'false' then False
	when b.ss_epci_tmp = '?' and id_comm_to_ss is not null then False
end 
from commun.tpk_commune_2015_2016 as c, public.tmp_ss_epci as b
where 
	(a.an, a.id_secten1, a.code_cat_energie) 
	= (b.an, b.id_secten1, b.code_cat_energie)
	and a.id_comm = c.id_comm
	and c.siren_epci_2018 = b.siren_epci_2018
;

-- Secrétisation d'une commune quand nécessaire
update total.bilan_comm_v5_secten1 as a
set ss = true, memo = 'Secrétisation manuelle'
where (an, id_secten1, code_cat_energie, id_comm) in (
	select distinct an, id_secten1, code_cat_energie, id_comm_to_ss
	from public.tmp_ss_epci
	where ss_epci_tmp = '?' and id_comm_to_ss is not null
);

-- Si pas d'établissements bdrep pour une année / epci / secten / cat énergie 
-- alors ss_epci = false
update total.bilan_comm_v5_secten1 as a
set ss_epci = FALSE
where ss_epci is null;

/* 
VALIDATIONS
-- Vérifier qu'on ait une valeur SS_EPCI unique pour un EPCI, an, id_secten1, code_cat_energie
select an, siren_epci_2018, id_secten1, code_cat_energie, count(ss_epci) as validation
from (
	select distinct an, siren_epci_2018, id_secten1, code_cat_energie, ss_epci
	from total.bilan_comm_v5_secten1 as a
	left join commun.tpk_commune_2015_2016 as b using (id_comm)
) as a
group by an, siren_epci_2018, id_secten1, code_cat_energie
order by validation desc

-- On regarde quelles sont les données en SS EPCI ou non
select distinct an, id_secten1, code_cat_energie, b.siren_epci_2018, ss_epci
from total.bilan_comm_v5_secten1 as a
left join commun.tpk_commune_2015_2016 as b using (id_comm)
where id_polluant = 131 and ss_epci is false
-- order by an, id_secten1, code_cat_energie, b.siren_epci_2018, ss_epci
order by ss_epci, an, id_secten1, code_cat_energie, b.siren_epci_2018
*/












































/**
Maintenance de la table
*/
alter table total.bilan_comm_v5_secten1 add constraint "pk.total.bilan_comm_v5_secten1" 
	primary key (id_polluant, an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab);

CREATE INDEX "idx.bilan_comm_v5_secten1.an" ON total.bilan_comm_v5_secten1 (an);
CREATE INDEX "idx.bilan_comm_v5_secten1.id_polluant" ON total.bilan_comm_v5_secten1 (id_polluant);

vacuum ANALYZE total.bilan_comm_v5_secten1;
vacuum FREEZE total.bilan_comm_v5_secten1;


/**
Cluster de la table pour accélérer les requêtes
*/
CREATE INDEX "idx.bilan_comm_v5_secten1.id_polluant.an.ss.id_secten1.code_cat_energie"
ON total.bilan_comm_v5_secten1
USING btree
(id_polluant, an, ss, id_secten1, code_cat_energie);

ALTER TABLE total.bilan_comm_v5_secten1 CLUSTER ON "idx.bilan_comm_v5_secten1.id_polluant.an.ss.id_secten1.code_cat_energie";

/* 
Validation finale des consommations et émissions

select id_polluant, an, a.val as val_espace, b.val as val_cigale
from (
	-- Emissions de l'inventaire par polluant et année
	select id_polluant, an, sum(val) as val
	from (
		select id_polluant, an, sum(val) as val, 'total.bilan_comm_v5' as src
		from total.bilan_comm_v5
		where 
			id_polluant in (38,65,108,16,48,36,11,15,123,124,128)
			and id_comm <> 99999
			and id_comm <> 99138
		group by id_polluant, an 

		union all

		select id_polluant, an, sum(val) as val, 'total.bilan_comm_v5_ges' as src
		from total.bilan_comm_v5_ges
		where 
			id_polluant in (38,65,108,16,48,36,11,123,124,128)
			and id_comm <> 99999
			and id_comm <> 99138
		group by id_polluant, an 		
	) as a
	group by id_polluant, an
	order by id_polluant, an
) as a
full join (
	-- Emissions CIGALE par énergie et année
	select id_polluant, an, sum(val) as val
	from total.bilan_comm_v5_secten1 
	where 
		id_polluant in (38,65,108,16,48,36,11,15,123,124,128)
	group by id_polluant, an
	order by id_polluant, an
) as b using (id_polluant, an)
order by id_polluant, an;

*/









/**

Intégration prod ener CIGALE 
Dans une nouvelle table

src_prod_energie.tpk_grande_filiere_cigale - Grandes filières avec codes_couleur.
src_prod_energie.tpk_detail_filiere_cigale - Détail des filières avec code_couleur pour ENR.
src_prod_energie.tpk_filiere - Les id_filiere de la table bilan_prod et les liens vers les deux tables précédentes.

Mise en forme des valeurs
- Regroupement 
	* Par grande_filiere_cigale et detail_filiere_cigale
	* Type production
- Ajout des champs EPCI et dep dans la table pour éviter les requêtes 
  trop longues lors de l'extraction
*/

drop table if exists total.bilan_comm_v5_prod;
create table total.bilan_comm_v5_prod as
select 
	a.an, 
	e.id_comm_2018 as id_comm, -- a.id_comm, 
	a.id_type_prod, b.lib_type_prod,
	c.id_grande_filiere_cigale, d.grande_filiere_cigale, d.color_grande_filiere_cigale,
	c.id_detail_filiere_cigale, dd.detail_filiere_cigale, dd.color_detail_filiere_cigale,
	sum(a.production) as val, 
	a.id_unite,
	a.id_comm / 1000 as dep,
	e.siren_epci_2018,
	e.nom_epci_2018
from total_prod_energie.prod_comm_v2 as a
left join src_prod_energie.tpk_type_prod as b using (id_type_prod)
left join src_prod_energie.tpk_filiere as c using (id_filiere)
left join src_prod_energie.tpk_grande_filiere_cigale as d using (id_grande_filiere_cigale)
left join src_prod_energie.tpk_detail_filiere_cigale as dd using (id_detail_filiere_cigale)
left join commun.tpk_commune_2015_2016 as e using (id_comm)
group by
	a.an, 
	e.id_comm_2018, -- a.id_comm, 
	a.id_type_prod, b.lib_type_prod,
	c.id_grande_filiere_cigale, d.grande_filiere_cigale, d.color_grande_filiere_cigale,
	c.id_detail_filiere_cigale, dd.detail_filiere_cigale, dd.color_detail_filiere_cigale,
	a.id_unite,
	a.id_comm / 1000,
	e.siren_epci_2018,
	e.nom_epci_2018
order by
	an, 
	id_comm, 
	id_type_prod, lib_type_prod,
	grande_filiere_cigale,
	detail_filiere_cigale,
	id_unite;

































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



drop table if exists cigale.epci;
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
	row_number() over () as gid, *
from (
	select  	
		an,
		nom_abrege_polluant, 
		siren_epci_2018 as siren_epci,
		nom_epci_2018 as nom_epci,
		sum(val)::numeric / (superficie / 100.)::numeric as val -- Superficie hectares -> km2
	from total.bilan_comm_v5_secten1 as a
	left join commun.tpk_commune_2015_2016 as b using (id_comm)
	left join commun.tpk_polluants as c using (id_polluant)
	left join cigale.epci as d on b.siren_epci_2018 = d.siren_epci
	where 
		an = 2016
		and not (id_polluant in (38,65,108,16,48,36,11) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastrée: Pord d'énergie mais pas d'élec ni chaleur
		and not (id_polluant not in (38,65,108,16,48,36,11) and id_secten1 = '1') -- GES et Ener = Finale
		and ss_epci is false -- Sans aucune donnée soumise au SS à l'EPCI	
	group by an, nom_abrege_polluant, siren_epci_2018, nom_epci_2018, superficie

	union all

	-- Ajout des productions primaires
	select  	
		an,
		'prod'::text as nom_abrege_polluant, 
		a.siren_epci_2018 as siren_epci,
		nom_epci_2018 as nom_epci,
		sum(val)::numeric / (superficie / 100.)::numeric as val -- Superficie hectares -> km2
	from total.bilan_comm_v5_prod as a
	left join cigale.epci as d on a.siren_epci_2018 = d.siren_epci
	where 
		an = 2016
		and id_grande_filiere_cigale = 1 -- ENR
	group by an, siren_epci_2018, nom_epci_2018, superficie
) as a
order by an, nom_abrege_polluant, siren_epci, nom_epci;

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
NOTE: Pour les prod, énergie primaire c''est à dire ENR
';

vacuum analyze cigale.epci_poll;
vacuum freeze cigale.epci_poll;


/*
drop table if exists cigale.comm_poll;
create table cigale.comm_poll WITH OIDS as  
select row_number() over () as gid, *
from (
	select  
		nom_abrege_polluant, id_comm, nom_comm, siren_epci_2018 as siren_epci, 
		val / (d.superficie / 100.) as val, -- Superficie en hectares dans les données geofla
		st_transform(geom, 4326) as geomtmp
	from (
		select id_polluant, an, id_comm, sum(val) as val
		from total.bilan_comm_v5_secten1
		where 
			an = 2016
			and not (id_polluant in (38,65,108,16,48,36,11,16) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastrée: Pord d'énergie mais pas d'élec ni chaleur
			and not (id_polluant not in (38,65,108,16,48,36,11,16) and id_secten1 = '1') -- GES et Ener = Finale
			and ss is false -- Sans aucune donnée en SS
		group by id_polluant, an, id_comm
	) as a
	left join commun.tpk_polluants as b using (id_polluant)
	left join commun.tpk_commune_2015_2016 as c using (id_comm)
	left join (
		select 
			case 
				when insee_com::integer between 13201 and 13216 then 13055 
				when insee_com in ('04198', '04033') then 4033 -- Fusion 2017
				else insee_com::integer end as id_comm,
			sum(superficie) as superficie,
			st_union(geom) as geom
		from sig.geofla2016_communes
		where
			code_reg = '93'
		group by 
			case 
				when insee_com::integer between 13201 and 13216 then 13055 
				when insee_com in ('04198', '04033') then 4033 -- Fusion 2017
				else insee_com::integer end
	) as d using (id_comm)
	where 
		nom_abrege_polluant in ('conso','so2','nox','pm10','pm2.5','covnm','nh3','co','co2','ch4.co2e','n2o.co2e','prg100.3ges')

	union all

	-- Ajout des prod
	select 
		'prod'::text as nom_abrege_polluant, id_comm, nom_comm, siren_epci_2018 as siren_epci, 
		val / (d.superficie / 100.) as val, -- Superficie en hectares dans les données geofla
		st_transform(geom, 4326) as geomtmp
	from (
		select 999::integer as id_polluant, an, id_comm, sum(val) as val
		from total.bilan_comm_v5_prod
		where 
			an = 2015
			and id_grande_filiere_cigale = 1
		group by id_polluant, an, id_comm
	) as a
	left join commun.tpk_commune_2015_2016 as c using (id_comm)
	left join (
		select 
			case 
				when insee_com::integer between 13201 and 13216 then 13055 
				when insee_com in ('04198', '04033') then 4033 -- Fusion 2017
				else insee_com::integer end as id_comm,
			sum(superficie) as superficie,
			st_union(geom) as geom
		from sig.geofla2016_communes
		where
			code_reg = '93'
		group by 
			case 
				when insee_com::integer between 13201 and 13216 then 13055 
				when insee_com in ('04198', '04033') then 4033
				else insee_com::integer end
	) as d using (id_comm)
) as a;

*/

drop table if exists cigale.comm_poll;
create table cigale.comm_poll WITH OIDS as  
select row_number() over () as gid, *
from (
	select  
		nom_abrege_polluant, c.id_comm_2018 as id_comm, c.nom_comm_2018 as nom_comm, siren_epci_2018 as siren_epci, 
		sum(val) / (d.superficie / 1000000.) as val, -- Superficie retournée en m2 par st_area sur du rgf93
		-- st_transform(geom, 4326) as geomtmp
		st_transform(d.geom,4326) as geomtmp
	from (
		select id_polluant, an, id_comm, sum(val) as val
		from total.bilan_comm_v5_secten1
		where 
			an = 2016
			and not (id_polluant in (38,65,108,16,48,36,11,16) and code_cat_energie in ('8', '6')) -- Emissions: Approche cadastrée: Pord d'énergie mais pas d'élec ni chaleur
			and not (id_polluant not in (38,65,108,16,48,36,11,16) and id_secten1 = '1') -- GES et Ener = Finale
			and ss is false -- Sans aucune donnée en SS
		group by id_polluant, an, id_comm
	) as a
	left join commun.tpk_polluants as b using (id_polluant)
	left join commun.tpk_commune_2015_2016 as c using (id_comm)
	left join (
		-- On utilise maintenant admin express
		select 
			insee_com::integer as id_comm,
			sum(st_area(geom)) as superficie,
			st_union(geom) as geom
		from sig.admin_express_2018
		where 
			(insee_com::integer not between 13201 and 13216)
		group by insee_com::integer
	) as d on c.id_comm_2018 = d.id_comm -- using (id_comm)
	where 
		nom_abrege_polluant in ('conso','so2','nox','pm10','pm2.5','covnm','nh3','co','co2','ch4.co2e','n2o.co2e','prg100.3ges')
	group by nom_abrege_polluant, c.id_comm_2018, c.nom_comm_2018, siren_epci_2018, d.geom, d.superficie
	

	union all

	-- Ajout des prod
	select 
		'prod'::text as nom_abrege_polluant, c.id_comm_2018 as id_comm, c.nom_comm_2018 as nom_comm, siren_epci_2018 as siren_epci, 
		sum(val) / (d.superficie / 1000000.) as val, -- Superficie retournée en m2 par st_area sur du rgf93
		-- st_transform(geom, 4326) as geomtmp
		st_transform(d.geom,4326) as geomtmp
	from (
		select 999::integer as id_polluant, an, id_comm, sum(val) as val
		from total.bilan_comm_v5_prod
		where 
			an = 2016
			and id_grande_filiere_cigale = 1
		group by id_polluant, an, id_comm
	) as a
	left join commun.tpk_commune_2015_2016 as c using (id_comm)
	left join (
		-- On utilise maintenant admin express
		select 
			insee_com::integer as id_comm,
			sum(st_area(geom)) as superficie,
			st_union(geom) as geom
		from sig.admin_express_2018
		where 
			(insee_com::integer not between 13201 and 13216)
		group by insee_com::integer
	) as d on c.id_comm_2018 = d.id_comm -- using (id_comm)
	group by nom_abrege_polluant, c.id_comm_2018, c.nom_comm_2018, siren_epci_2018, d.geom, d.superficie
) as a;

SELECT AddGeometryColumn ('cigale','comm_poll','geom',4326,'MULTIPOLYGON',2, false);

update cigale.comm_poll
set geom = ST_Multi(ST_SimplifyPreserveTopology(geomtmp,0.001)); -- Simplification de la géométrie pour rapidité d'affichage

alter table cigale.comm_poll add constraint "pk.cigale.comm_poll" primary key (gid);
CREATE INDEX "gidx.cigale.comm_poll.geom.gist" ON cigale.comm_poll USING GIST (geom);

comment on table cigale.comm_poll is '
NOTE: Pour la conso énergie primaire c''est à dire sans l''élec
NOTE: Pour les GES, émissions directes c''est à dire sans l''élec
NOTE: Pour les prod, énergie primaire c''est à dire ENR
';

vacuum analyse cigale.comm_poll;
vacuum freeze cigale.comm_poll;

-- Clusterisation des tables géographiques pour améliorer les temps d'affichage
CREATE INDEX "cigale.comm_poll.nom_abrege_polluant.siren_epci"
  ON cigale.comm_poll
  USING btree
  (nom_abrege_polluant, siren_epci);
ALTER TABLE cigale.comm_poll CLUSTER ON "cigale.comm_poll.nom_abrege_polluant.siren_epci";

ALTER TABLE cigale.epci_poll CLUSTER ON "idx.cigale.epci_poll.id_polluant";


-- Création d'une table de liste des entités administratives pour une récupération rapide de l'info
-- et des temps d'affichage améliorés.
drop table if exists cigale.liste_entites_admin;
create table cigale.liste_entites_admin as
    -- Région PACA
    select 1 as order_field, 93 as valeur, 'Région PACA' as texte
    union all
    -- Départements
    select 2 as order_field, id_dep as valeur, joli_nom_dep as texte 
    from commun.tpk_depts
    where id_reg = 93
    union all
    -- EPCI
    select distinct 3 as order_field, siren_epci_2018, nom_epci_2018
    from commun.tpk_commune_2015_2016
    where siren_epci_2018 is not null
    union all
    -- Communes
    select distinct order_field, a.id_comm, b.nom_comm_2018 || ' (' || lpad((a.id_comm / 1000)::text, 2, '0') || ')' as nom_comm
    from (
        select distinct 4 as order_field, a.id_comm
        from total.bilan_comm_v5_secten1 as a
    ) as a
    -- left join commun.tpk_communes as b using (id_comm)
    left join commun.tpk_commune_2015_2016 as b on a.id_comm = b.id_comm_2018
order by order_field, valeur;














/******************************************************************************

Création de la table des émissions détaillées par secteurs et grands secteurs d'activités
Table utilisée en accès restreint car ne traite pas le secret statistique.

NOTE: PlPgSQL
NOTE: Temps d'éxéction ~ 2 min

- Récupération du secteur de la production d'énergie
	On a bien ce secteur dans tpk_secteur. 
	Mais l'affectation lors de la création de la table bilan comm
    ne le prend pas en compte!
	On a en revanche toujours l'id_snap3 dans bilan_comm
	On peut donc utiliser id_snap3 et la correspondance avec les SNAP du transversal
	pour retrouver quels SNAP partent dans le SECTEN 1 prod ener 
- On ajoute le nom secteur, grand secteur et leur couleurs respectives
- On ajoute les codes EPCI.
- Nécessité de distinguer élec (401) et chaleur (403) des autres énergies pour extractions 
  des consommations
- Polluants rajoutés non présentés dans CIGALE
  PCDDF (40), [1,3 Butadiène (54), Dichloroéthane-1,2 DCE (20) ne sont pas dans l'inventaire]
- Prise en compte des fusions de communes avec la table de correspondance
- On récupère les champs permettant de faire un order sur les secteurs et grands secteurs
- On ne prend pas la commune de Monaco 99138

Validations de la table des émissions:
-------------------------------------------------------------------------------
- Validations automatique après la création de la table

Validations des requêtes d'extraction:
-------------------------------------------------------------------------------
- Extraction des parts d'émissions par secteur ou grand secteur
  Emissions de tous les secteurs, sans l'élec et la chaleur [x][]
  Validation d'une somme d'émissions SQL vs Interface [x]
- Extraction des parts de GES par secteur ou grand secteur
  On ne prend pas en compte le secteur de la production d'énergie, mais elec et chaleur [x][]  
  Validation d'une somme d'émissions SQL vs Interface [x]
- Extraction de l'évolution des émissions de polluants
  Emissions de tous les secteurs, sans l'élec et la chaleur [x][]
  Validation d'une somme d'émissions SQL vs Interface [x]
- Extraction de l'évolution des GES
  On ne prend pas en compte le secteur de la production d'énergie, mais elec et chaleur [x][]  
  Validation d'une somme d'émissions SQL vs Interface [x]
- Extraction de l'évolution des consommations
  On veut des consos finales donc sans secteur de la prod énergie mais avec elec et chaleur [x][]  
  Validation d'une somme d'émissions SQL vs Interface [x]


Validation des affichages
-------------------------------------------------------------------------------
- La part des émissions / reg ou EPCI fait bien 100%


******************************************************************************/

drop table if exists total.bilan_comm_v5_secteurs;
create table total.bilan_comm_v5_secteurs as
with snap_prod_ener as (
	-- Table des SNAP ESPACE qui doivent passer en prod énergie
	select distinct b.espace_id_snap3 as id_snap3
	from transversal.tpk_snap3 as a
	left join total.corresp_snap_synapse as b on a.id_snap3 = b.synapse_id_snap3
	where 
		id_secten1 = '1'
		and b.espace_id_snap3 is not null
	order by id_snap3
), emi as (
	select 
		id_polluant, 
		case when id_snap3 in (select distinct id_snap3 from snap_prod_ener) then 12 else id_secteur end as id_secteur, 
		id_comm, an, 
		case when id_energie in (401,403) then 2 else 1 end as scope,
		sum(coalesce(val,0)) as val
	from total.bilan_comm_v5 as a
	where 
		(
			id_polluant in (131,38,65,108,16,48,36,11,2,3,4,5,37,39)
			OR
			id_polluant in (40,54,20)
		)
		and val is not null -- NOTE: Certaines valeurs nulles dans les tables bilan de chaque secteur
		and id_comm <> 99999 -- Sans les émissions associées à l'objet mer
		and id_comm <> 99138 -- Sans les émissions de la commune de Monaco	
		and an not in (2008,2009,2011) -- Uniquement les années d'inventaire
	group by 
		id_polluant, 
		case when id_snap3 in (select distinct id_snap3 from snap_prod_ener) then 12 else id_secteur end, 
		id_comm, an, 
		case when id_energie in (401,403) then 2 else 1 end

	union all

	select 
		id_polluant, 
		case when id_snap3 in (select distinct id_snap3 from snap_prod_ener) then 12 else id_secteur end as id_secteur,
		id_comm, an, 
		case when id_energie in (401,403) then 2 else 1 end as scope,
		sum(coalesce(val,0)) as val
	from total.bilan_comm_v5_ges as a
	where 
		(
			id_polluant in (15, 123, 124, 128)
		)
		and val is not null -- NOTE: Certaines valeurs nulles dans les tables bilan de chaque secteur
		and id_comm <> 99999 -- Sans les émissions associées à l'objet mer
		and id_comm <> 99138 -- Sans les émissions de la commune de Monaco	
		and an not in (2008,2009,2011) -- Uniquement les années d'inventaire
	group by 
		id_polluant, 
		case when id_snap3 in (select distinct id_snap3 from snap_prod_ener) then 12 else id_secteur end, 
		id_comm, an,
		case when id_energie in (401,403) then 2 else 1 end
) 

select
	case 
		when id_polluant = 131 then 0
		when id_polluant = 65 then 1
		when id_polluant = 108 then 2
		when id_polluant = 38 then 3
		when id_polluant = 48 then 4
		when id_polluant = 16 then 5
		when id_polluant = 3 then 6
		when id_polluant = 4 then 7
		when id_polluant = 36 then 8
		when id_polluant = 2 then 9
		when id_polluant = 5 then 10
		when id_polluant = 37 then 11
		when id_polluant = 39 then 12
		when id_polluant = 40 then 13
		when id_polluant = 11 then 14
		when id_polluant = 15 then 15
		when id_polluant = 123 then 16
		when id_polluant = 124 then 17   
		when id_polluant = 128 then 18
	end as poll_order,
	id_polluant, nom_abrege_polluant,
	id_secteur, nom_secteur, secteur_color, grand_secteur, grand_secteur_color,
	secteur_order, grand_secteur_order,
	id_comm_2018 as id_comm, nom_comm_2018 as nom_comm, siren_epci_2018, nom_epci_2018,
	an, scope,
	sum(val) as val
from emi as a
left join commun.tpk_commune_2015_2016 as b using (id_comm)
left join total.tpk_secteur as c using (id_secteur)
left join commun.tpk_polluants as d using (id_polluant)
group by
	id_polluant, nom_abrege_polluant,
	id_secteur, nom_secteur, secteur_color, grand_secteur, grand_secteur_color,
	secteur_order, grand_secteur_order,
	id_comm_2018, nom_comm_2018, siren_epci_2018, nom_epci_2018,
	an, scope	
order by 
	poll_order,
	-- id_polluant,
	id_secteur, 
	id_comm, siren_epci_2018, 
	an, scope;


-- Validation sur NOx et PM10
drop function if exists total.validation_bilan_secteur();
create function total.validation_bilan_secteur() returns void as $$ BEGIN

	if (
		select count(*)
		from (
			select (
				select sum(val)::integer as val
				from total.bilan_comm_v5
				where 
					an = 2016
					and id_polluant = 38
					and id_comm not in (99999,99138)
			) - (
				select sum(val)::integer as val
				from total.bilan_comm_v5_secteurs
				where 
					an = 2016
					and id_polluant = 38
					and id_comm not in (99999,99138)
			) as diff
			union all
			select (
				select sum(val)::integer as val
				from total.bilan_comm_v5
				where 
					an = 2016
					and id_polluant = 38
					and id_comm not in (99999,99138)
			) - (
				select sum(val)::integer as val
				from total.bilan_comm_v5_secteurs
				where 
					an = 2016
					and id_polluant = 38
					and id_comm not in (99999,99138)
			) as diff
		) as a
		where diff > 0
	) > 0 then
		raise WARNING 'ERREUR LORS DE LA VALIDATION DES EMISSIONS DETAILLEES';
	end if;

end $$ language plpgsql;
select total.validation_bilan_secteur();

-- Indexes et management
alter table total.bilan_comm_v5_secteurs add constraint "pk.total.bilan_comm_v5_secteurs"
	primary key (id_polluant, id_secteur, id_comm, an, scope);

vacuum analyse total.bilan_comm_v5_secteurs;
vacuum freeze total.bilan_comm_v5_secteurs;

ALTER TABLE total.bilan_comm_v5_secteurs CLUSTER ON "pk.total.bilan_comm_v5_secteurs";









/******************************************************************************

Extraction des émissions 
par snap2 et catégorie d'énergie
pour fichier excel référents

Différenciation du secteur de la production 
d'énergie avec prod_ener true ou false

-- Sélection des consommations
select count(*) from total.bilan_comm_v5_snap2_catener where id_polluant in (131)

-- Sélection des GES
select count(*) from total.bilan_comm_v5_snap2_catener where id_polluant in (15, 123, 124, 128)

-- Sélection des métaux
select count(*) from total.bilan_comm_v5_snap2_catener where id_polluant in (2,5,37,39)

-- Sélection des Particules
select count(*) from total.bilan_comm_v5_snap2_catener where id_polluant in (65,108)

-- Sélection des Dioxines et furanes
select count(*) from total.bilan_comm_v5_snap2_catener where id_polluant in (40)

-- Sélection des autres polluants
select count(*) from total.bilan_comm_v5_snap2_catener where id_polluant in (3,4,11,16,36,38,48)

******************************************************************************/

drop table if exists total.bilan_comm_v5_snap2_catener;
create table total.bilan_comm_v5_snap2_catener as 

with snap_prod_ener as (
	-- Table des SNAP ESPACE qui doivent passer en prod énergie
	select distinct b.espace_id_snap3 as id_snap3
	from transversal.tpk_snap3 as a
	left join total.corresp_snap_synapse as b on a.id_snap3 = b.synapse_id_snap3
	where 
		id_secten1 = '1'
		and b.espace_id_snap3 is not null
	order by id_snap3
)

select 
	id_polluant, 
	nom_polluant, nom_abrege_polluant,
	id_comm_2018 as id_comm, nom_comm_2018 as nom_comm, 
	an, 
	id_snap2, lib_snap2, 
	code_cat_energie, cat_energie, prod_ener,
	sum(val) as val
from (
	-- Sélection et regroupement des données émissions 35,200 ms
	select 
		id_polluant, id_comm, an, id_snap3 / 100 as id_snap2, id_energie, 
		case when b.id_snap3 is not null then true else false end as prod_ener,
		sum(val) as val
	from total.bilan_comm_v5 as a
	left join snap_prod_ener as b using (id_snap3)
	where 
		id_comm not in (99999, 99138)
		and an not in (2008,2009,2011)
		and id_polluant in (
			131,
			38,65,108,16,48,36,11,2,3,4,5,37,39,
			40,
			54,20 -- Pas disponibles dans le bilan
		)
	group by 
		id_polluant, id_comm, an, id_snap3 / 100, id_energie, 
		case when b.id_snap3 is not null then true else false end

	union all

	select 
		id_polluant, id_comm, an, id_snap3 / 100 as id_snap2, id_energie, 
		case when b.id_snap3 is not null then true else false end as prod_ener,
		sum(val) as val
	from total.bilan_comm_v5_ges as a
	left join snap_prod_ener as b using (id_snap3)
	where 
		id_comm not in (99999, 99138)
		and an not in (2008,2009,2011)
		and id_polluant in (
			15, 123, 124, 128
		)
	group by 
		id_polluant, id_comm, an, id_snap3 / 100, id_energie, 
		case when b.id_snap3 is not null then true else false end		
) as a
left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
-- left join commun.tpk_snap3 as d using (id_snap3)
left join commun.tpk_snap2 as d using (id_snap2)
left join commun.tpk_energie as e on a.id_energie = e.id_energie
left join commun.tpk_polluants as f using (id_polluant)
left join commun.tpk_commune_2015_2016 as g using (id_comm)
group by
	id_polluant, 
	nom_polluant, nom_abrege_polluant,
	id_comm_2018, nom_comm_2018, 
	an, 
	id_snap2, lib_snap2, 
	code_cat_energie, cat_energie, prod_ener
order by 
	id_polluant, 
	nom_polluant, nom_abrege_polluant,
	id_comm, 
	an, 
	id_snap2, lib_snap2, 
	code_cat_energie, cat_energie, prod_ener
;

-- Validation
drop function if exists total.bilan_snap2_catener();
create function total.bilan_snap2_catener() returns void as $$ BEGIN

if (
	select round((
		(
			select sum(val) as val
			from total.bilan_comm_v5
			where 
				id_comm not in (99999, 99138)
				and an in (2016)
				and id_polluant in (131)
		) - (
			select sum(val) as val
			from total.bilan_comm_v5_snap2_catener
			where 
				id_comm not in (99999, 99138)
				and an in (2016)
				and id_polluant in (131)
		)
	)::numeric, 2) as diff
) <> 0.00 then
		raise WARNING 'ERREUR LORS DE LA VALIDATION DES EMISSIONS SNAP2';
end if;
end $$ language plpgsql;
select total.bilan_snap2_catener();


















/** 

Création du compte utilisateur + acces pour grand public



-- Création de l'utilisateur plateforme J
CREATE USER *** WITH PASSWORD 'user=*** dbname=*** host=*** password=***';

-- Autorisation de la connexion (sudo vi pg_hba.conf)
GRANT ALL PRIVILEGES ON DATABASE "***" to ***;
SELECT pg_reload_conf();

-- Affectation des droits sur les tables
GRANT SELECT ON ALL TABLES IN SCHEMA public TO ***;
GRANT USAGE ON SCHEMA public TO ***;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA public TO ***;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA public TO ***;

GRANT SELECT ON ALL TABLES IN SCHEMA cigale TO ***;
GRANT USAGE ON SCHEMA cigale TO ***;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA cigale TO ***;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA cigale TO ***;

GRANT SELECT ON ALL TABLES IN SCHEMA total TO ***;
GRANT USAGE ON SCHEMA total TO ***;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA total TO ***;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA total TO ***;

GRANT SELECT ON ALL TABLES IN SCHEMA transversal TO ***;
GRANT USAGE ON SCHEMA transversal TO ***;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA transversal TO ***;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA transversal TO ***;

GRANT SELECT ON ALL TABLES IN SCHEMA commun TO ***;
GRANT USAGE ON SCHEMA commun TO ***;
GRANT USAGE ON ALL SEQUENCES IN SCHEMA commun TO ***;
GRANT EXECUTE ON ALL FUNCTIONS IN SCHEMA commun TO ***;
*/



