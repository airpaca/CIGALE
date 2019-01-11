#!/bin/bash
#
# Export de CIGALE depuis le serveur dev externalisé vers le serveur prod.
# Nécessite clef RSA et pgpass vers le serveur ext dev
# Pour la restauration, demandera le pwd de l'utilisateur CIGALE. 
# FIXME: Ajouter la connexion à la bdd prod dans pgpass

# Credentials 
dev_host="***"
dev_user="***"
dev_user_db="***" # dev db user
prod_user_db="***" # prod db user

# Export bdd dev
echo "- Dumping ..."
ssh $dev_user@$dev_host "pg_dump -h localhost -U $dev_user_db -n cigale -n commun -n src_prod_energie -n total -n transversal -Fc cigale > /tmp/cigale.dump"

# Déplacement dans le filer
echo "- Copying ..."
ssh $dev_user@$dev_host "cp /tmp/cigale.dump /filer/transfertsatmosud/"

# Restauration
echo "- Restauring ..."
ssh $dev_user@$dev_host "pg_restore -U $prod_user_db -d cigale -h 10.0.1.2 --clean /filer/transfertsatmosud/cigale.dump"