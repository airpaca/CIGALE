#!/bin/bash
#
# Export des émissions détaillées 
# depuis la bdd inventaire 
# vers la bdd dev

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
-t total.bilan_comm_v${version_inv}_secteurs \
-Fc $dev_db > /tmp/emissions.detaillees.dump

# Export du dump sur le serveur prod
echo "Exporting dump on prod server"
scp /tmp/emissions.detaillees.dump $prod_user@$prod_host:/tmp/

# Restauration du dump sur le serveur prod
echo "Updating database on prod server"
ssh $prod_user@$prod_host "pg_restore --clean -U $prod_user_db -d $prod_db -h localhost /tmp/emissions.detaillees.dump"

# Maintenance des tables sur le serveur prod pour plus de rapidité
echo "Maintaining database on prod server"
ssh $prod_user@$prod_host "psql -U $prod_user_db -d $prod_db -h localhost << EOF
vacuum ANALYZE total.bilan_comm_v${version_inv}_secteurs;
vacuum FREEZE total.bilan_comm_v${version_inv}_secteurs;
EOF
" 