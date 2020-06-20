## Pedidos

### Paso 1
```
composer install
````

### Paso 2
```
npm run prod
````

### Paso 3
```
npm run dev
````

### Problemas conocidos 

##### Problema
En Hostgator al actualizar el correo del cliente no arroja el error
```
idn_to_ascii(): INTL_IDNA_VARIANT_2003 is deprecated
```

##### Solución
En el cPanel  en la sección "Administrador MultiPHP" seleccionar la versión PHP 7.1 (ea-php71)

