# Pruebas de software al proyecto CSS con PHP Unit

Proyecto Semestral de la materia Mantenimiento y Pruebas de Software.

## Instalación
Después de haber clonado este repositorio seguir los siguientes pasos:
```bash
docker ps -a
```
(Para fijarse que el puerto no se este utilizando por otro contenedor)
```bash
docker-compose up -d --build
```
```bash
docker ps
```
(deberían salir los dos contenedores corriendo [MySQL y PHP])
* En este punto ya la pagina web debería correrles en el localhost:(puerto-que-usaron)
```bash
* docker exec -it mysql mysql -uroot -p

clave123
```
* Si hacemos un “show databases;” debería salir nuestra base de datos “gestion_citas” listada.
* use gestion_citas;
* Pegar todo lo que está dentro del archivo app/Db/gestion_citas.sql y enter.
* Con “show tables;” deberían salir las 8 tablas que tenemos creadas.

