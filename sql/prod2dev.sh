#!/bin/bash
#
# CIGALE: Export de la bdd locale vers la bdd dev
# Script à lancer sur le serveur de la bdd locale
# Les schémas doivent être crées et vides sur la base prod
# La table des émissions détaillées par secteurs est également exportée
# 
# Pour ne pas avoir à répéter le password PostgreSQL à chaque connexion, 
# faire un fichier .pgpass dans le home de l'utilisateur du serveur prod 
# et insérer une ligne au format suivant: hostname:port:database:username:password
# Pour que le fichier soit pris en compte ill lui faut les permissions suivantes:
# > chmod 0600 ~/.pgpass
#
# Pour ne pas avoir à rentrer le mot de pass à chaque connexion ssh, la solution est 
# d'utiliser une clef RSA

# Configuration
dev_user="***"
dev_db="***"
prod_host="***"
prod_user="***"
prod_user_db="***"
prod_db="***"
version_inv="***"

# Dump des tables nécessaires de la bdd dev
echo "Dumping dev database into /tmp/cigale.dump"
pg_dump -U $dev_user \
-t total.bilan_comm_v${version_inv}_secten1 \
-t commun.tpk_commune_2015_2016 \
-t commun.tpk_depts \
-t commun.tpk_communes \
-t commun.tpk_polluants \
-t commun.unite_id_unite_seq \
-t commun.tpk_unite \
-t transversal.tpk_secten1 \
-t total.tpk_secten1_color \
-t transversal.tpk_energie \
-t total.tpk_cat_energie_color \
-t cigale.comm_poll \
-t cigale.epci \
-t cigale.epci_2154 \
-t cigale.epci_poll \
-t cigale.liste_entites_admin \
-t total.bilan_comm_v${version_inv}_prod \
-t src_prod_energie.tpk_grande_filiere_cigale \
-t src_prod_energie.tpk_detail_filiere_cigale \
-t total.bilan_comm_v${version_inv}_secteurs \
-Fc $dev_db > /tmp/cigale.dump

# Export du dump sur le serveur dev
echo "Exporting dump on dev server"
scp /tmp/cigale.dump $prod_user@$prod_host:/tmp/

# Restauration du dump sur le serveur dev
echo "Updating database on dev server"
ssh $prod_user@$prod_host "pg_restore --clean -U $prod_user_db -d $prod_db -h localhost /tmp/cigale.dump"

# Maintenance des tables sur le serveur dev pour plus de rapidité
echo "Maintaining database on dev server"
ssh $prod_user@$prod_host "psql -U $prod_user_db -d $prod_db -h localhost << EOF
vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1;
vacuum FREEZE total.bilan_comm_v${version_inv}_secten1;
ALTER TABLE total.bilan_comm_v${version_inv}_secten1 CLUSTER ON \"idx.bilan_comm_v${version_inv}_secten1.id_polluant.an.ss.id_secten1.code_cat_energie\";

vacuum analyze cigale.epci_poll;
vacuum freeze cigale.epci_poll;

vacuum analyse cigale.comm_poll;
vacuum freeze cigale.comm_poll;
ALTER TABLE cigale.epci_poll CLUSTER ON \"idx.cigale.epci_poll.id_polluant\";
EOF
" 

# Découpage des tables par polluant pour une plus grande rapidité d'affichage des graphiques
echo "Spliting emi table per pollutant"
ssh $prod_user@$prod_host "psql -U $prod_user_db -d $prod_db -h localhost << EOF
do \\$\\$ 
declare

	_id_polluant integer;
	_nom_abrege_polluant text;

begin 

	for _id_polluant in (SELECT DISTINCT id_polluant from total.bilan_comm_v${version_inv}_secten1) loop

		select replace(nom_abrege_polluant, '.', '') into _nom_abrege_polluant from commun.tpk_polluants where id_polluant = _id_polluant;

		raise notice 'Création de la table total.bilan_comm_v${version_inv}_secten1_%', _nom_abrege_polluant;

		execute '
			drop table if exists total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || ';
		
			create table total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || ' as 
			select * 
			from total.bilan_comm_v${version_inv}_secten1
			where id_polluant = ' || _id_polluant || ';
		
			ALTER TABLE total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '
			  ADD CONSTRAINT \"pk.total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '\" PRIMARY KEY(id_polluant, an, id_comm, id_secten1, code_cat_energie, id_usage, id_branche, code_etab);
			CREATE INDEX \"idx.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '.an\"
			  ON total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '
			  USING btree
			  (an);
			CREATE INDEX \"idx.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '.id_polluant\"
			  ON total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '
			  USING btree
			  (id_polluant);
			CREATE INDEX \"idx.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '.id_polluant.an.ss.id_secten1.code_cat\"
			  ON total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '
			  USING btree
			  (id_polluant, an, ss, id_secten1 COLLATE pg_catalog.\"default\", code_cat_energie);
			ALTER TABLE total.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || ' CLUSTER ON \"idx.bilan_comm_v${version_inv}_secten1_' || _nom_abrege_polluant || '.id_polluant.an.ss.id_secten1.code_cat_energie\";
			';

end loop;

end \\$\\$ language plpgsql;
EOF
"

# Opérations de maintenance
ssh $prod_user@$prod_host "psql -U $prod_user_db -d $prod_db -h localhost << EOF
    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_conso; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_conso; 			

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_ch4co2e; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_ch4co2e; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_co; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_co; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_co2; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_co2; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_covnm; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_covnm; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_n2oco2e; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_n2oco2e; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_nh3; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_nh3; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_nox; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_nox; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_pm10; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_pm10; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_pm25; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_pm25; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_prg1003ges; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_prg1003ges; 

    vacuum ANALYZE total.bilan_comm_v${version_inv}_secten1_so2; 			
    vacuum FREEZE total.bilan_comm_v${version_inv}_secten1_so2; 
EOF
" 

# Maintenance de la table des émissions détaillées
ssh $prod_user@$prod_host "psql -U $prod_user_db -d $prod_db -h localhost << EOF
    vacuum analyse total.bilan_comm_v5_secteurs;
    vacuum freeze total.bilan_comm_v5_secteurs;

    ALTER TABLE total.bilan_comm_v5_secteurs CLUSTER ON \"pk.total.bilan_comm_v5_secteurs\";
EOF
" 
