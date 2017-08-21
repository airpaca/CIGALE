/** ***************************************************************************
Création de la table des émissions par SECTEN1, catégorie d'énergie et prise 
en compte du secret statistique

2017-07-17: La dernière version de la note sur la confidentialité des données
demande de repsecter le principe de précaution tant que l'on ne sait pas si
le traitement des données BDREP est une mission de service public. Il 
ne faut donc diffuser que des émissions IREP!

NOTE: Uniquement pour les polluants nécessaires à l'interface de visualisation
- consommations 		131
- émissions de NOx		38
- émissions de PM10		65
- émissions de PM2.5	108
- émissions de COVNM	16
- émissions de SO2		48
- émissions de NH3		36
- CO2 tot				15 
- CH4 eq_co2			123 
- N2O eq_co2			124 
- PRG1003GES 			128
NOTE: Pas de secret stat sur usages et branches
NOTE: Pour calculer le secret stat sur le nombre d'établissements, utilise les tables 
	  total_ind.bilan_comm et total_ter.bilan_comm. Pour le tertiaire, à part les 
	  établissements bdrep, on ne conserve pas l'id_etablissement du SIRENE mais 
	  on regroupe tt au NAF. 
	  TODO: Comment fait HC pour ses calculs SS? 
	  TODO: Peut-on conserver facilement cet id_etab en modifiant les scripts de calcul?
	  
TODO: FAIRE DES TESTS D'EXTRACTION PAR EPCI POUR SAVOIR QUELS EPCI SONT TRONQUES ?!
MAYBE: SI POSSIBLE FAUDRAIT PASSER SI UNE COMM EN SS ET FAIRE UNE EXTRACTION JUSTE A EPCI


TODO: Verif on ne doit pas avoir un etab en SS dont une émission n'est pas en SS.

TODO:
-- Quels sont les EPCI qui ont un secret stat dans une seule comm.
-- Idem, activite et energie 
-- On fait ce calcul au niveau de détail le plus fin
-- Si oui, on passe une donnée d'une autre commune en secret stat pou avoir deux communes en SS
**************************************************************************** */

/** 
Récupération du nombre d'établissements 
- Secteur industriel
- Secteur tertiaire
- Avec affectation d'un SESCTEN1 et catégorie d'énergie
- Les arrondissements de marseille sont transformés en 13055

NOTE: Pour certains etablissement on a un id_etablissement = -999. L'ensemble de ces 
	  établissements ne représentera qu'un seul étab.
FIXME: On ne ferme aucun établissement dans le film. Du coup, on en a de plus en plus?
*/
drop table if exists public.cigale_nb_etab;
create table public.cigale_nb_etab as 
select an, case when id_comm between 13201 and 13216 then 13055 else id_comm end as id_comm, id_secten1, code_cat_energie, count(*) as nb_etab
from (

	-- Sélection des établissements industriels dont on connait l'id (!= -999)
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

-- 	-- Sélection des établissements tertiaire dont on connait l'id (!= -999)
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

/* Validations
select an, sum(nb_etab) 
from public.cigale_nb_etab
group by an;

select an, id_secten1, code_cat_energie, sum(nb_etab) 
from public.cigale_nb_etab
group by an, id_secten1, code_cat_energie
order by an, id_secten1, code_cat_energie;
*/

/**
Création de la table des émissions par secten 1 et catégorie d'énergie
- Récupération des données élec séparées de la table bilan
- Récupération des données GES séparées dans autre table bilan
*/
-- Création de la table finale vide
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
	-- Premier regroupement des données conso et emi 
	-- avec catégories d'energie mais en regroupant les SNAPs
	-- pour traitements spécifiques
	-- 
	-- ATTENTION On update certains SNAP non énergétiques, qui ont quand même des id_energie dans la table totale
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
		d.code_gerep as code_etab, -- Pour calculer le SS à l'établissement
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
		d.code_gerep as code_etab, -- Pour calculer le SS à l'établissement
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
		d.code_gerep as code_etab, -- Pour calculer le SS à l'établissement
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
	-- On ne prends pas les correspondances complexes qui doivent-être traitées séparément pour ne pas avoir de doubles comptes.
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
	from emi as a
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
	group by an, id_polluant
) as a
full join (
	select an, id_polluant, sum(val) as val_secten1
	from total.bilan_comm_v4_secten1
	where 
		code_cat_energie not in (8) -- On a séparé l'élec dans la table d'origine
		and id_polluant in (38, 65, 108, 16, 48, 36, 131)
	group by an, id_polluant
) as b using (an, id_polluant)
order by an, id_polluant
*/

/**
Calcul du pourcentage de la consommation.
La consommation totale est stockée dans ss_85_conso_tot
*/
alter table total.bilan_comm_v4_secten1 drop column if exists ss_85_conso;
alter table total.bilan_comm_v4_secten1 add column ss_85_conso double precision;
alter table total.bilan_comm_v4_secten1 drop column if exists ss_85_conso_tot;
alter table total.bilan_comm_v4_secten1 add column ss_85_conso_tot text;

update total.bilan_comm_v4_secten1 as a set
	ss_85_conso = b.pct,
	ss_85_conso_tot = 'Conso etab =' || conso_etab || ' Conso tot = ' || b.conso_tot
from (
	-- % de consommation de l'établissement, secten1, energie / conso tot secten1, energie, commune
	select an, id_comm, code_etab, id_secten1, code_cat_energie, conso_etab, conso_tot, round((conso_etab / conso_tot * 100.)::numeric, 1) as pct
	from (
		-- Calcul de la consommation par établissement, secten1, code_cat énergie pour se séparer des usages et branches
		select an, id_comm, code_etab, id_secten1, code_cat_energie, sum(val) as conso_etab
		from total.bilan_comm_v4_secten1
		where 
			id_polluant = 131 
			and bdrep is true
		group by an, id_comm, code_etab, id_secten1, code_cat_energie 
	) as a
	left join (
		-- Calcul de la conso tot par commune, an, groupe d'énergie et secten 1
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
Jointure du nombre d'établissements
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
		bdrep is true -- Uniquement pour les établissements bdrep
		and code_cat_energie <> 8 -- Sans les consommations d'élec qui ne sont pas déclarées dans bdrep
		and not ( -- Sans les consommations de GN déclarées dans l'open data (Comprends GN, GNL, GNV)
			code_cat_energie = 1 
			and bdrep is false
			and (an, code_etab) not in (
				-- Sélection des années et établissement pour lesquels on a affecté du GRT GAZ non déclaré dans BDREP
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
Suppression des émissions de l'objet Mer
*/
delete from total.bilan_comm_v4_secten1 where id_comm = 99999;

/**
Calcul d'un champ val_conso pour pouvoir relier plus facilement 
la consommation aux émissions lors de l'extraction
FIXME: EN l'état l'inventaire ne permet pas de faire de lien juste émissions / consommations! Cf. #30

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