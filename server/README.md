
# Queue App Server

## 1. Installation
### 1.1 Requirements


- Mysql or Mariadb

- PHP 7.4 >

- Composer

### 1.2  Configuring database
- DATABASE: queue
- USERNAME: root
- PASSWORD: root 

Or edit the .env file

### 1.3. Instaling dependencies

- Use composer to install the the dependencies
```bash
  composer install
  php artisan migrate:fresh --seed

```

    
## 2. Quick and fast way to start (Recomended)
On the root of the project run:
```bash
  ./start.sh
```

## 3. Starting without using bash file
Open terminal window and start the websocket server:
```bash
php artisan websocket:serve
```
Open new terminal window and start the api:

```bash
php artisan serve
```

