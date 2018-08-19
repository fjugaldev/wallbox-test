# wallbox-test
Prueba de código para Wallbox

Para ejecutar el test se debe:

1. Ejecutar servidor embebido de PHP:
```bash
php -S localhost:8080
```

2. Generar un token de seguridad simple, para esto necesitamos acceder a [http://sandbox.onlinephpfunctions.com/](http://sandbox.onlinephpfunctions.com/) y colocar el siguiente código:
```php
<?php
echo base64_encode(time());
```
Y hacer click en el botón "**Execute code**".

3. Con el postman, vía cURL, desde la terminal usando httpie, o cualquier método que se desee, hay que generar una petición **GET** con los siguientes parámetros:

- URL: http://localhost:8080
- QueryString:
  * activation_length
  * countries
- Headers:
  * Autorization
  
 Ejemplo de la URL con parámetros: 
 - http://localhost:8080/?activation_length=27&countries[]=PT&countries[]=CN
 
 En los headers, hay que añadir "Autorization" y cuyo valor será el token generado en el paso #2.
 
 Para probar con un token no vigente utilizar:
 - **MTUzNDYxOTU2OA==** 
 
 El token solo es válido siempre y cuando no sea mayor a 60 min (1 Hora) de su generación.
 
 En la raiz del proyecto, dejo una colección de Postman  para hacer el test de la petición. También lo puedes descargar haciendo [click aquí](https://raw.githubusercontent.com/fjugaldev/wallbox-test/master/Wallbox.postman_collection.json) 
 


