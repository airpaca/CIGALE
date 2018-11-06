/**
Création de la table des émissions détaillées par secteurs et grands secteurs d'activités

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

*/

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
	id_polluant, nom_abrege_polluant,
	id_secteur, nom_secteur, secteur_color, grand_secteur, grand_secteur_color,
	secteur_order, grand_secteur_order,
	id_comm_2017 as id_comm, siren_epci_2017, nom_epci_2017,
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
	id_comm_2017, siren_epci_2017, nom_epci_2017,
	an, scope	
order by 
	id_polluant,
	id_secteur, 
	id_comm, siren_epci_2017, 
	an, scope;


