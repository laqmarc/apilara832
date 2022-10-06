php artisan migrate:fresh --force
php artisan passport:install --force
php artisan db:seed

Data Model 

Cumple con los requisitos de la aplicación. Me chirría por eso que mezcles camelCase con snake_case en el caso de dice_a,dice_b, por ejemplo. Lo conviertes en antiintiutivo si es otra persona la que va a desarrollar en tu aplicación o en antiintiutivo para ti si regresas a ese  código fuente tras una larga pausa sin transitarlo. De hecho, como devuelves los modelos a pelo(cosa que NO TE RECOMIENDO PARA NADA), esta nomenclatura antiintiutiva se traslada incluso al JSON de respuesta de la API.
->Nomenclaturas cambiadas
Features

- No es un error en sí pero, porqué no hay seeders?

- Es controvertida, e insegura, la decisión de dejar crear usuarios admin libremente. Okey que es un contexto educativo y es práctico que lo dejes hacer así,pero espero que a la vez seas consciente del peligro que entraña esto.

-Entradas incorrectas de datos muestran errores de SQL en la respuesta. Demasiadas pistas. Mucho más limpio un JSON con un mensaje de error genérico. Por ejemplo al crear un usuario con un rol que no existe.

->He creado un admin en seeder y todos los usuarios nuevos siempre seran user.
error api

* post api/players/{id}/games.

Claramente funciona, pero el output no está muy pensado para frontend. Me explico. Imagínate que quieres en front mostrar los dos dados. Lo pondrías difícil con tu formato de respuesta para sacar esa info. Mucho más fácil si devuelves los campos por separado en el JSON.Además, si pongo un id de jugar que no existe,de nuevo se muestra una gran parrafada de error SQL.
->Comprobado que la id exista

- Hay un error grave de seguridad y es que, accediendo como un jugador normal, puedo ejercer y acceder a información de otros jugadores si pongo la id de esos jugadores en la URL. Esto es, con un jugador puedo suplantar a otros jugadores.

-> arreglat
- ojo con poner camelCases en JSONs:

{   "All_players_win_rate": [

        {

            "result": "4.54545450"

        }

    ]

}
->cambiado

- El output que devuelve tu API en los errores 404 es demasiado verboso e informativo. 


crear 404
-> eing??? 


Testing

Aunque los que hay son casos interesantes, hay pocos tests. Se ha contemplado todo desde la perspectiva de cuando todo está bien, pero no veo tests para cuando las cosas no se explican correctamente o se hacen accesos indebidos. Sería interesante incluirlos también.

-> testing

Code Style

No veo que tengas problemas para comprender como montar una API en Laravel, pero sigue viendo un reparto algo laxo de responsabilidades en tu código fuente. En concreto, demasiada lógica en algunos métodos de tus controladores. Recuerda, por ejemplo, que existen Modelos para encapsular operaciones de bases de datos, librando de esa responsabilidad a los controladores.

En resumen, está bastante bien, Marc, pero necesito que le des una última o penúltima vuelta de tuerca.
Saludos!

diceapi
Kgnx0000#