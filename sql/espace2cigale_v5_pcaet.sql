/**

espace2cigale_v5_pcaet.sql
Air PACA - 2018 - RS

Création d'une table des données de l'inventaire des émissions à partir des scripts de CIGALE
mais au format PCAET et non SECTEN 1

Suppression du secret statistique du calcul
Ce script doit-être lancé après espace2cigale_vX.sql

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

*/



/**
Création de la table des émissions par PCEAT et catégorie d'énergie
- Récupération des données emi et élec de la table bilan
- Récupération des données GES séparées dans vue ges bilan
- On a des valeurs nulles dans les tables bilans de chaque schéma de calcul
  et on ne récupère donc pas ces valeurs.
- On ne récupère pas les émissions affectées à l'objet mer
*/




-- Création de la table finale vide
drop table if exists total.bilan_comm_v5_pcaet;
create table total.bilan_comm_v5_pcaet (
	id_polluant smallint NOT NULL,
	an smallint NOT NULL,
	id_comm integer NOT NULL,
	id_secteur_pcaet text NOT NULL,
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
from total.bilan_comm_v5 as a
left join total.corresp_energie_synapse as b on a.id_energie = b.espace_id_energie
left join transversal.tpk_energie as c on b.synapse_id_energie = c.id_energie
left join (select * from src_ind.def_corresp_sources where id_version_corresp = 6 and actif is true) as d using (id_corresp)
where 
	id_polluant in (131,38,65,108,16,48,36,11)
	and val is not null -- NOTE: Certaines valeurs nulles dans les tables bilan de chaque secteur
	and a.id_comm <> 99999 -- Sans les émissions associées à l'objet mer
	and a.id_comm <> 99138 -- Sans MC
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
	id_polluant, 
	an, a.id_comm, 
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
where 
	id_polluant in (15, 123, 124, 128)
	and val is not null -- NOTE: Certaines valeurs nulles dans les tables bilan de chaque secteur
	and a.id_comm <> 99999 -- Sans les émissions associées à l'objet mer
	and a.id_comm <> 99138 -- Sans MC	
group by 
	id_secteur,
	id_polluant,
	an, a.id_comm, 
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
insert into total.bilan_comm_v5_pcaet
select 
		id_polluant,
		an,
		id_comm,
		id_secteur_pcaet,
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
		c.id_secteur_pcaet as id_secteur_pcaet, -- c.id_secten1,
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
		c.id_secteur_pcaet,
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
		end as id_secteur_pcaet,
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
		end as id_secteur_pcaet,
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
		end as id_secteur_pcaet,
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
		end as id_secteur_pcaet,
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
		'2'::text as id_secteur_pcaet, -- Affectation manuelle d'un SECTEN
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
		c.id_secteur_pcaet,
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
		id_secteur_pcaet,
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




/**
Mise à jour tardive d'une fusion de commune 4198 -> 4033
avant calcul SS
*/
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

delete from total.bilan_comm_v5_pcaet where id_comm in (4198, 4033);

insert into total.bilan_comm_v5_pcaet 
select * from public.tmp_fusion;


/**
Suppression des données hors années d'inventaire
*/
delete from total.bilan_comm_v5_pcaet where an in (2009,2009,2011);






/**
Maintenance de la table
*/
alter table total.bilan_comm_v5_pcaet add constraint "pk.total.bilan_comm_v5_pcaet" 
	primary key (id_polluant, an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab);

CREATE INDEX "idx.bilan_comm_v5_pcaet.an" ON total.bilan_comm_v5_pcaet (an);
CREATE INDEX "idx.bilan_comm_v5_pcaet.id_polluant" ON total.bilan_comm_v5_pcaet (id_polluant);

vacuum ANALYZE total.bilan_comm_v5_pcaet;
vacuum FREEZE total.bilan_comm_v5_pcaet;


/**
Cluster de la table pour accélérer les requêtes
*/
CREATE INDEX "idx.bilan_comm_v5_pcaet.id_polluant.an.ss.pcaet.code_cat_energie"
ON total.bilan_comm_v5_pcaet
USING btree
(id_polluant, an, ss, id_secteur_pcaet, code_cat_energie);

ALTER TABLE total.bilan_comm_v5_pcaet CLUSTER ON "idx.bilan_comm_v5_pcaet.id_polluant.an.ss.id_secteur_pcaet.code_cat_energie";

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
		group by id_polluant, an 

		union all

		select id_polluant, an, sum(val) as val, 'total.bilan_comm_v5_ges' as src
		from total.bilan_comm_v5_ges
		where 
			id_polluant in (38,65,108,16,48,36,11,123,124,128)
			and id_comm <> 99999
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





