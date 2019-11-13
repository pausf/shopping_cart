# Shopping Cart


#### Requerimientos:
- php ^7.1
- composer


[Esquema de la aplicación](doc/esquemas.md)


##### Primeros pasos antes de empezar:

Ejecutar el comando de composer para instalar todos los paquetes : 

```
composer install
```

## Utilización de la aplicación

Se ha creado un comando para poder utilizar la aplicación.


Para iniciar la ejecución es necesario tirar el siguiente comando :

`bin/console cart:interactive`


Después te aparecerá que pongas unas opciones necesarias para poder continuar:
```
Añadir una Id del Carrito (Recuerde que sea una UUID valida)

Añadir una Id del User (Recuerde que sea una UUID valida)
```

**IMPORTANTE** : Añadir unas Uuid validas para eso podemos utilizar la siguiente web: [Online UUID Generator](https://www.uuidgenerator.net/)


Ahora te aparecera las diferentes opciones que puedes utilizar:


```
 'Elige una opción?',
 '=============================================',
    '[1] Mostrar productos.',
    '[2] Añadir producto al carrito.',
    '[3] Mostrar el total del carrito.',
    '[4] Mostrar el total con el cambio de divisa.',
    '[5] Eliminar producto.',
    '[6] SALIR IMPORTANTE.',
```

En la opción número 4 te da muy pocas divisas USD, RUB, GBP, PLN.... pero existen muchas más.
Puedes ver el listado de divisas desde [aquí](https://api.exchangeratesapi.io/latest?base=EUR).

**IMPORTANTE**: Para cerrar la aplicación se tiene que elegir la opción número 6.

## Test

Para ejecutar los test tiene que tirar el siguiente comando:

`
./bin/phpunit
`

#### Estructura de carpetas

```scala
tests
├── Cart
│   ├── Application
│   │   ├── AddCartItem
│   │   │   └── AddCartItemUnitTest.php
│   │   ├── CurrencyCartTotal
│   │   │   └── CurrencyCartTotalUnitTest.php
│   │   ├── RemoveCartItem
│   │   │   └── RemoveCartItemUnitTest.php
│   │   └── TotalCart
│   │       └── TotalCartItemUnitTest.php
│   ├── CartUnitTest.php
│   └── Domain
│       ├── CartTest.php
│       └── Factory
│           └── CartIdFactory.php
├── CartItem
│   └── Domain
│       ├── CartItemTest.php
│       └── Factory
│           └── CartItemFactory.php
├── Currency
│   ├── Domain
│   │   ├── Factory
│   │   │   └── CurrencyCodeFactory.php
│   │   └── ValueObject
│   │       └── CurrencyCodeTest.php
│   └── Infrastructure
│       └── ExchangeRatesApiRepositoryTest.php
├── Discount
│   └── Domain
│       └── Factory
│           ├── DiscountFactory.php
│           ├── DiscountPriceFactory.php
│           └── DiscountUnitFactory.php
├── Product
│   ├── Application
│   │   ├── Create
│   │   │   └── CreateProductUnitTest.php
│   │   └── Find
│   │       └── FindProductUnitTest.php
│   ├── Domain
│   │   ├── Factory
│   │   │   ├── ProductFactory.php
│   │   │   ├── ProductIdFactory.php
│   │   │   └── ProductPriceFactory.php
│   │   └── ProductTest.php
│   ├── Infrastructure
│   │   └── InMemory
│   │       └── InMemoryProductRepositoryTest.php
│   └── ProductUnitTest.php
└── Shared
    ├── Application
    │   ├── CommandBusHandlerInterface.php
    │   └── QueryBusHandlerInterface.php
    └── Domain
        ├── Factory
        │   └── UserIdFactory.php
        └── ValueObject
            ├── FloatNumberTest.php
            ├── IntNumberTest.php
            └── UuidTest.php

```
