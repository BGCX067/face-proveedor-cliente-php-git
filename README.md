## Descripción del proyecto

Este proyecto tiene como finalidad mostrar como conectar con el sistema de FACe. FACe es un buzón de facturas para toda la administración y organismos del gobierno de España, para mas informacion acerca de FACe recomendamos visitar la web https://face.gob.es.

El codigo esta bajo licencia BSD http://www.opensource.org/licenses/bsd-license.php

## Instalación de librerias externas

El proyecto esta montado sobre el uso de composer. Una vez descargado el proyecto se debe realizar composer update para la completa descarga de las librerias de terceros.

## Configuración

En el directorio config, se dispone de un fichero app.yml donde se pueden incluir parametros de configuración. Los parametros que inicialmente dispone el
proyecto son:

  * wsdl: Dirección o path donde se ubica el wsdl de FACe. Está configurado el wsdl del entorno de test. Ver la documentación de FACe al respecto para saber los distintos entornos que tienen.
  * privateKey: Es el path del certificado con el que se firmarán las peticiones SOAP. El certificado debe estar en formato PEM
  * password: Es el password para abrir el certificado


NOTA: Para poder comunicarse con FACe deben dar de alta el certificado como
proveedor en la web de FACe https://face.gob.es

## Librerias de terceros

La comunicación con FACe requiere de firma digital, para cumplir este requisito se ha utilizado las librerias de firma soap de Robert Richards. Estas librerias se pueden encontrar en google. Tambien se han realizado ligeras modificaciones para que dichas librerias sean cargadas con namespace.
Podemos encontrar las librerias a las que hacemos referencia en el directorio
 XMLDsig.









