# Recipes

# setup
    -php 7.3 or >
    - composer
    -  ` composer install `
    - /bin/console -e test  doctrine:database:create
    
    check `./bin/console` for migrations
    
Load Data.
  - ./bin/console doctrine:fixtures:load
  - ./bin/console -e test doctrine:fixtures:load
  - Magic

# run server!
  -  ` composer install `
  - symfony server:start  

### Run tests
    - ./bin/phpunit 
