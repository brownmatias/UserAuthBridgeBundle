# UserAuthBridgeBundle

Bundle que funciona como puente entre las aplicaciones Symfony 1.x y Symfony 2 del Ministerio de Industria, utilizando Memcache


## Installation

### Using composer

Add following lines to your `composer.json` file:

#### Symfony >= 2.7

    "require": {
      ...
      "ministerio/user-auth-bridge-bundle": "dev-master"
    },
    "repositories": [
        {
            "type": "vcs",
            "url": "https://github.com/brownmatias/UserAuthBridgeBundle"
        }
    ]

Execute:

    php composer.phar update

Add it to the `AppKernel.php` class:

            new Ministerio\Bundle\UserAuthBridgeBundle\MinisterioUserAuthBridgeBundle(),
            new Theodo\Evolution\Bundle\SessionBundle\TheodoEvolutionSessionBundle(),

Add it to your `app/config/config.yml`

#Configuración del almacenamiento de sesión en Memcache

#Configuración del puente

ministerio_user_auth_bridge:
    roles: %roles%
    logout_url: %logout_url%
    login_url: %login_url%

Add to your `app/config/parameters.yml`

#Configuración de los parámetros del puente
    roles: USER_ROLES,SEPARADOS_POR,COMA #Vienen de la aplicación Symfony 1
    logout_url: http://url_de_logout_de_la_app_sf1.com
    login_url: http://url_de_login_de_la_app_sf1.com

#Configuración de los parámetros para almacenar la sesión en memcache
    memcache_host: localhost
    memcache_port: 11211
    memcache_expire: 86400
    session_prefix: 'nombre_aplicacion:'
    session_name: nombre_aplicacion

**This bundle works on Symfony >= 2.7 version.**


## Dependencies

This bundle extends [TheodoEvolutionSessionBundle](https://github.com/theodo/TheodoEvolutionSessionBundle).

## Usage

Use following command from console:

    app/console jordillonch:generate:crud

As you will see there is no config file. You will generate a CRUD code with all fields from your entity. But after code generation you
are free to modify the code because there is no magic just a simple code that is very easy to understand.

You have to know that if you reuse the command to recreate same entity, first you must delete controller and form files
from previous generation.

## Author

Matías Brown - matibrown at gmail dot com

## License

UserAuthBridgeBundle is licensed under the MIT License. See the LICENSE file for full details.