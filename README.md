# Pruebas de software al proyecto CSS con PHP Unit

Proyecto Semestral de la materia Mantenimiento y Pruebas de Software.

## Instalación
Después de haber clonado este repositorio seguir los siguientes pasos:
* Para fijarse que el puerto no se este utilizando por otro contenedor:
```bash
docker ps -a
```

```bash
docker-compose up -d --build
```
* Deberían salir los dos contenedores corriendo [MySQL y PHP]:
```bash
docker ps
```

* En este punto ya la pagina web debería correrles en el localhost:(puerto-que-usaron)

* Configuración de la base de datos:
```bash
docker exec -it mysql mysql -uroot -p

clave123
```
* Si hacemos un “show databases;” debería salir nuestra base de datos “gestion_citas” listada.
```bash
use gestion_citas;
```
* Pegar todo lo que está dentro del archivo app/Db/gestion_citas.sql y enter.
* Con “show tables;” deberían salir las 8 tablas que tenemos creadas.
* Para salir de la vista de MySQL:
```bash
\q
```
-------------------------------------------------
* Luego abren el proyecto en el contenedor (Attach to a running container.)
* Dentro del contenedor ponen "open folder"
* Escriben la ruta var/www/html (pueden buscarla dentro del explorador)
* Instalación del composer php unit:
```bash
composer require --dev phpunit/phpunit
```
