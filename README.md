# Guide

1. Install Lando from here https://lando.dev/
2. Run `lando --channel none` (disables the upgrade notice, trust me it is annoying)
3. Run `lando start`  
   Should automatically open up Docker and build the containers.  
   If it doesn't, then open Docker manually and run the command again.
4. After Lando has finished building, run the following commands:

```
lando php bin/console lexik:jwt:generate-keypair
lando php bin/console doctrine:migrations:migrate
lando php bin/console doctrine:fixtures:load
lando php bin/console assets:install --symlink
```
