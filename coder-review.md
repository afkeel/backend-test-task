1. Иправления ошибок PHP.

- class ProductRepository, getByCategory(), используется стрелочная функция с ключевым словом static, в теле функции используется $this;
- class ProductRepository, getByCategory(), запрос "SELECT id FROM products WHERE is_active = 1 AND category = " . $category, возвращает id, тогда как нужны все поля для создания класса. Также передаются параметры в самом выражении.
- class ProductRepository, getByUuid(), fetchOne вернёт первый столбец, тогда как нужны все для создания класса.
- class ProductRepository, make(), $row['is_active'] = int, тогда как в class Product поле isActive bool (declare(strict_types = 1));
- class ProductRepository, нет use Exception;
- class CartManager, getCart(), return new Cart(session_id(), []) - не верные аргументы для создания класса.
- class CartManager, saveCart(), $this->connector->set($cart, session_id()), аргументы перепутаны местами;
- class Connector, get() string параметр
- class Connector, __construct() удалил return;
- class ConnectorFacade, __construct() исправления;

2. Архитектура, логика.

Вынес ответ на запрос из контроллеров в отдельный метод в класс Response.
Вместо классов View (которые должны отправлять данные на фронт приложения), сделал классы-Collection. В Doctrine они есть и отдельно писать их не нужно, можно подключить библиотеку.
В классах-Collection вынес из методов toArray(), запросы к репозиторию и логику подсчета суммы.
Архитектура приложения: слой Domain, в него входит слои Entity и Repository.

PS. На мой вгляд нужно реализовать связь Cart и Product многие-ко-многим. Тогда класс CartItem не нужен совсем.
Всю бизнес-логику также можно вынести в классы Service.