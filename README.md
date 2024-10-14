# AGI

## Introducción 

En el presente documento se encuentra consignado el proceso y la metodologia de solución al problema planteado para esta fase de la asignatura Seminario de Voz IP. Este documento sirve tanto para mostrar nuestra reconstrucción logica de los pasos que seguimos para solucionar el reto como para instruirse en la secuencia de operaciones necesarias si se quiere configurar en un servidor esta solución si es eso lo que se desea. 

## Objetivos 
### Objetivo General 
Afianzar y demostrar conocimientos en la primera parte practica de la materia Seminario de Voz IP. 

### Objetivos Especificos 
- Configurar un servidor de telefonia ip que permita conectividad al interior de su red.
- Configurar una base de datos adecuada lógicamente al problema. 
- Programar un AGI que permita a los usuarios interactuar con ella mediante DTMF.

## Instalación.
Se debe configurar una maquina virtual con issabel, se le deben asignar como minimo 2 GB de memoria RAM y un disco duro virtual con al menos 20GB.
la maquina virtual debe estar configurada con tipo de red _bridge adapter_ para que la máquina virtual pueda acceder a la red local y se le asigne una IP de la misma red que la computadora anfitriona.

## Uso
1. una vez configurada nuestra maquina virtual se debe acceder a ella por medio de ssh ssh **root@IP**
donde IP es la ip asignada a nuestra maquina virtual.

2. una vez conectados por medio de ssh a nuestra maquina virtual
- vamos a la siguiente carpeta `cd /var/www/html`
- creamos una carpeta para nuestro proyecto `mkdir universidad`
3. clonamos nuestro proyecto en una ubicación local de nuestro equipo.
  - `git clone https://github.com/estebandurangov/AGI.git`
  - entramos al directorio del proyecto `cd AGI`
  - enviamos los archivos definiciones, carga y permisos a la carpteta que creamos en el paso 2.
    - `scp carga.sql root@IP:/var/www/html/universidad`
    - `scp permisos.sql root@IP:/var/www/html/universidad`
    - `scp definiciones.inc root@IP:/var/www/html/universidad`
4. nuevamente desde la terminal donde conectamos por ssh nuestro proyecto:
  - Ingresamos a la carpeta que creamos `cd universidad`
  - creamos la base de datos `mysqladmin -u root -p create universidad`
  - cargamos los datos iniciales `mysql -u root -p universidad < carga.sql`
   
