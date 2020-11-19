# Load Data:
 php ./bin/console doctrine:fixtures:load
for test:
 php ./bin/console -e test doctrine:fixtures:load 
 
 create test database: 
  ./bin/console -e test  doctrine:database:create