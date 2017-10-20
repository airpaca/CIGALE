#!/bin/bash
#
# CIGALE: Export de la bdd dev vers la bdd prod
# Script à lancer sur le serveur de la bdd dev
# Les passwords seront demandés lors des connexions au serveur prod

# Configuration
dev_user="***"
dev_db="***"
prod_host="***"
prod_user="***"
prod_db="***"

# Dump des tables nécessaires de la bdd dev
echo "Dumping dev database into /tmp/cigale.dump"
pg_dump -U $dev_user \
-t total.bilan_comm_v4_secten1 \
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
-t total.bilan_comm_v4_prod \
-Fc $dev_db > /tmp/cigale.dump

# Export du dump sur le serveur prod
echo "Exporting dump on prod server"
scp /tmp/cigale.dump $prod_user@$prod_host:/tmp/

# Restauration du dump sur le serveur prod
echo "Updating database on prod server"
ssh $prod_user@$prod_host "pg_restore --clean -U $prod_user -d $prod_db /tmp/cigale.dump"

# Maintenance des tables sur le serveur prod pour plus de rapidité
echo "Maintaining database on prod server"
ssh $prod_user@$prod_host "psql -U $prod_user -d $prod_db << EOF
vacuum ANALYZE total.bilan_comm_v4_secten1;
vacuum FREEZE total.bilan_comm_v4_secten1;
ALTER TABLE total.bilan_comm_v4_secten1 CLUSTER ON \"idx.bilan_comm_v4_secten1.id_polluant.an.ss.id_secten1.code_cat_energie\";

vacuum analyze cigale.epci_poll;
vacuum freeze cigale.epci_poll;

vacuum analyse cigale.comm_poll;
vacuum freeze cigale.comm_poll;
ALTER TABLE cigale.epci_poll CLUSTER ON \"idx.cigale.epci_poll.id_polluant\";
EOF
" 
